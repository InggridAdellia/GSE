<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Permohonan_masuk_model');
        $this->load->model('Kerusakan_model');
        $this->load->model('Ids_model');
    }

    public function index()
    {
        $this->load->view('template/header');
        $this->load->view('scan/index');
        $this->load->view('template/footer');
    }

    public function get_gse_data($sticker_ap = null)
    {
        header('Content-Type: application/json');

        $sticker_ap = urldecode((string) $sticker_ap);

        if ($sticker_ap === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Nomor stiker tidak valid.']);
            return;
        }

        /** @var CI_DB_query_builder $db */
        $db = $this->db;
        
        $row = $db
            ->select('g.*, a.nama_airline')
            ->from('gse g')
            ->join('airlines a', 'a.id_airline = g.id_airline', 'left')
            ->where('g.sticker_ap', $sticker_ap)
            ->where('g.status', 'Aktif')
            ->order_by('g.id_gse', 'DESC')
            ->get()->row();

        if (!$row) {
            $row = $db
                ->select('g.*, a.nama_airline')
                ->from('gse g')
                ->join('airlines a', 'a.id_airline = g.id_airline', 'left')
                ->where('g.sticker_ap', $sticker_ap)
                ->order_by('g.id_gse', 'DESC')
                ->get()->row();
        }

        if (!$row) {
            http_response_code(404);
            echo json_encode(['error' => 'Data GSE dengan nomor stiker "' . $sticker_ap . '" tidak ditemukan.']);
            return;
        }

        if ($this->user_role === 'ground_handling' && $row->id_airline != $this->user_airline) {
            http_response_code(403);
            echo json_encode(['error' => 'Anda tidak memiliki akses untuk melihat data GSE ini.']);
            return;
        }

        $this->load->model('Gse_model');
        $status_kontrak = $this->Gse_model->cek_status_kontrak($row->masa_selesai ?? null);
        $sisa_hari      = $this->Gse_model->get_sisa_hari($row->masa_selesai ?? null);

        $tahun_kontrak = !empty($row->masa_selesai) ? date('Y', strtotime($row->masa_selesai)) : null;

        // Ambil riwayat laporan kerusakan aktif untuk stiker ini
        $active_warnings = $this->Kerusakan_model->get_active_warning_by_sticker($row->sticker_ap);

        echo json_encode([
            'id_gse'           => $row->id_gse,
            'nama_gse'         => $row->nama_gse,
            'manufacture_type' => $row->manufacture_type,
            'no_asset'         => $row->no_asset,
            'nomor_rangka'     => $row->nomor_rangka ?? null,
            'nomor_mesin'      => $row->nomor_mesin ?? null,
            'sticker_ap'       => $row->sticker_ap,
            'id_airline'       => $row->id_airline,
            'airline'          => $row->nama_airline,
            'status'           => $row->status,
            'tahun_kontrak'    => $tahun_kontrak,
            'masa_mulai'       => $row->masa_mulai ?? null,
            'masa_selesai'     => $row->masa_selesai ?? null,
            'status_kontrak'   => $status_kontrak,
            'sisa_hari'        => $sisa_hari,
            'active_warnings'  => $active_warnings,
        ]);
    }

    public function laporkan_kerusakan()
    {
        header('Content-Type: application/json');

        if ($this->input->method() !== 'post') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan.']);
            return;
        }

        $sticker_ap        = trim($this->input->post('sticker_ap', true));
        $nama_gse          = trim($this->input->post('nama_gse', true));
        $id_gse            = $this->input->post('id_gse', true) ?: null;
        $id_airline        = $this->input->post('id_airline', true) ?: null;
        $tingkat_kerusakan = trim($this->input->post('tingkat_kerusakan', true)) ?: 'Peringatan Sedang';
        $keterangan        = trim($this->input->post('keterangan', true));

        if (empty($sticker_ap) || empty($nama_gse) || empty($keterangan)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nomor stiker, nama GSE, dan rincian kerusakan wajib diisi.']);
            return;
        }

        // Handle upload foto jika ada
        $foto_kerusakan = null;
        if (!empty($_FILES['foto_kerusakan']['name']) && $_FILES['foto_kerusakan']['error'] === UPLOAD_ERR_OK) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['foto_kerusakan']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed_ext, true)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Format foto tidak valid. Gunakan format JPG, PNG, atau WEBP.']);
                return;
            }

            if ($_FILES['foto_kerusakan']['size'] > 5 * 1024 * 1024) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Ukuran file foto maksimal 5 MB.']);
                return;
            }

            $upload_path = FCPATH . 'uploads/kerusakan/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $clean_sticker = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $sticker_ap);
            $filename = 'kerusakan_' . $clean_sticker . '_' . time() . '.' . $ext;

            if (move_uploaded_file($_FILES['foto_kerusakan']['tmp_name'], $upload_path . $filename)) {
                $foto_kerusakan = 'uploads/kerusakan/' . $filename;
            }
        }

        $data_insert = [
            'id_gse'            => $id_gse,
            'sticker_ap'        => $sticker_ap,
            'nama_gse'          => $nama_gse,
            'id_airline'        => $id_airline,
            'tingkat_kerusakan' => $tingkat_kerusakan,
            'keterangan'        => $keterangan,
            'foto_kerusakan'    => $foto_kerusakan,
            'status'            => 'Baru',
            'dilaporkan_oleh'   => $this->user_id,
            'nama_pelapor'      => $this->user_nama,
            'role_pelapor'      => $this->user_role,
            'created_at'        => date('Y-m-d H:i:s'),
        ];

        $insert_id = $this->Kerusakan_model->insert_laporan($data_insert);

        if ($insert_id) {
            $this->Ids_model->catat_aktivitas(
                'Scan GSE',
                'Lapor Kerusakan',
                'Melaporkan kerusakan unit ' . $nama_gse . ' (' . $sticker_ap . ') - ' . $tingkat_kerusakan
            );

            echo json_encode([
                'status'  => 'success',
                'message' => 'Laporan kerusakan & peringatan berhasil dikirim!',
                'id'      => $insert_id,
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menyimpan laporan kerusakan ke database.',
            ]);
        }
    }
}