<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kerusakan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('text');
        $this->load->model('Kerusakan_model');
        $this->load->model('Ids_model');
    }

    public function index()
    {
        $status          = $this->input->get('status', true);
        $search          = $this->input->get('search', true);
        $tingkat         = $this->input->get('tingkat', true);
        $id_airline_filt = $this->input->get('id_airline', true);

        $filter = [];
        if (!empty($status)) {
            $filter['status'] = $status;
        }
        if (!empty($search)) {
            $filter['search'] = $search;
        }
        if (!empty($tingkat)) {
            $filter['tingkat_kerusakan'] = $tingkat;
        }

        // Jika ground handling, hanya lihat laporan untuk maskapainya
        if ($this->user_role === 'ground_handling') {
            $filter['id_airline'] = $this->user_airline;
            $airline_id_for_count = $this->user_airline;
        } else {
            if (!empty($id_airline_filt)) {
                $filter['id_airline'] = $id_airline_filt;
            }
            $airline_id_for_count = null;
        }

        $data['user_role'] = $this->user_role;
        $data['laporan']   = $this->Kerusakan_model->get_all($filter);
        $data['summary']   = $this->Kerusakan_model->get_count_summary($airline_id_for_count);
        $data['airlines']  = $this->db->get('airlines')->result();
        $data['filter']    = [
            'status'     => $status,
            'search'     => $search,
            'tingkat'    => $tingkat,
            'id_airline' => $id_airline_filt,
        ];

        $this->load->view('template/header');
        $this->load->view('kerusakan/index', $data);
        $this->load->view('template/footer');
    }

    public function detail_json($id)
    {
        header('Content-Type: application/json');

        $laporan = $this->Kerusakan_model->get_by_id($id);
        if (!$laporan) {
            http_response_code(404);
            echo json_encode(['error' => 'Data laporan kerusakan tidak ditemukan.']);
            return;
        }

        // Cek hak akses GH
        if ($this->user_role === 'ground_handling' && $laporan->id_airline != $this->user_airline) {
            http_response_code(403);
            echo json_encode(['error' => 'Anda tidak memiliki hak akses data ini.']);
            return;
        }

        echo json_encode($laporan);
    }

    public function tindak($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('kerusakan');
            return;
        }

        $laporan = $this->Kerusakan_model->get_by_id($id);
        if (!$laporan) {
            $this->session->set_flashdata('error', 'Laporan kerusakan tidak ditemukan.');
            redirect('kerusakan');
            return;
        }

        $tindakan_perbaikan = trim($this->input->post('tindakan_perbaikan', true));
        if (empty($tindakan_perbaikan)) {
            $this->session->set_flashdata('error', 'Keterangan tindakan perbaikan wajib diisi.');
            redirect('kerusakan');
            return;
        }

        $update_data = [
            'status'             => 'Sudah Ditindak',
            'tindakan_perbaikan' => $tindakan_perbaikan,
            'ditindak_oleh'      => $this->user_id,
            'nama_penindak'      => $this->user_nama,
        ];

        $res = $this->Kerusakan_model->tindak_laporan($id, $update_data);

        if ($res) {
            $this->Ids_model->catat_aktivitas(
                'Laporan Kerusakan',
                'Tindak Laporan',
                'Menandai laporan kerusakan ID #' . $id . ' (' . $laporan->nama_gse . ' - ' . $laporan->sticker_ap . ') telah ditindak.'
            );
            $this->session->set_flashdata('success', 'Laporan kerusakan #' . $id . ' berhasil ditandai sebagai Sudah Ditindak.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui status laporan kerusakan.');
        }

        redirect('kerusakan');
    }

    public function hapus($id)
    {
        if ($this->user_role !== 'admin') {
            $this->session->set_flashdata('error', 'Hanya Admin yang dapat menghapus laporan kerusakan.');
            redirect('kerusakan');
            return;
        }

        $laporan = $this->Kerusakan_model->get_by_id($id);
        if ($laporan) {
            // Hapus file foto fisik jika ada
            if (!empty($laporan->foto_kerusakan) && file_exists(FCPATH . $laporan->foto_kerusakan)) {
                @unlink(FCPATH . $laporan->foto_kerusakan);
            }

            $this->Kerusakan_model->delete_laporan($id);
            $this->Ids_model->catat_aktivitas(
                'Laporan Kerusakan',
                'Hapus Laporan',
                'Menghapus laporan kerusakan ID #' . $id . ' (' . $laporan->nama_gse . ' - ' . $laporan->sticker_ap . ')'
            );
            $this->session->set_flashdata('success', 'Laporan kerusakan #' . $id . ' berhasil dihapus.');
        }

        redirect('kerusakan');
    }

    public function simpan_sparepart($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('kerusakan');
            return;
        }

        $laporan = $this->Kerusakan_model->get_by_id($id);
        if (!$laporan) {
            $this->session->set_flashdata('error', 'Laporan kerusakan tidak ditemukan.');
            redirect('kerusakan');
            return;
        }

        $nama_sparepart = $this->input->post('nama_sparepart');
        $qty            = $this->input->post('qty');
        $keterangan     = $this->input->post('keterangan_sp');

        $list = [];
        if (is_array($nama_sparepart)) {
            foreach ($nama_sparepart as $idx => $nama) {
                $nama = trim((string)$nama);
                if ($nama !== '') {
                    $list[] = [
                        'nama_sparepart' => $nama,
                        'qty'            => trim((string)($qty[$idx] ?? '1')),
                        'keterangan'     => trim((string)($keterangan[$idx] ?? '')),
                    ];
                }
            }
        }

        $update_data = [
            'kebutuhan_sparepart' => !empty($list) ? json_encode($list) : null,
        ];

        $res = $this->Kerusakan_model->tindak_laporan($id, $update_data);
        if ($res) {
            $this->Ids_model->catat_aktivitas(
                'Laporan Kerusakan',
                'Input Kebutuhan Sparepart',
                'Memperbarui daftar kebutuhan sparepart untuk laporan ID #' . $id . ' (' . count($list) . ' item)'
            );
            $this->session->set_flashdata('success', 'Daftar kebutuhan sparepart berhasil disimpan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan kebutuhan sparepart.');
        }

        redirect('kerusakan');
    }
}
