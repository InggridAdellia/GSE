<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permohonan_masuk extends MY_Controller {

    protected $public_methods = ['scan_gse'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Permohonan_masuk_model');
        $this->load->model('Gse_model');
        $this->load->model('Airline_model');
        $this->load->model('Permohonan_keluar_model');
        $this->load->model('Ids_model');
    }

    public function index()
    {
        if ($this->user_role === 'ground_handling') {
            $semua_masuk = $this->Permohonan_masuk_model->get_masuk_baru_by_airline($this->user_airline);
            $kontrak     = $this->Permohonan_masuk_model->get_perbaruan_kontrak_by_airline($this->user_airline);
        } else {
            $semua_masuk = $this->Permohonan_masuk_model->get_masuk_baru();
            $kontrak     = $this->Permohonan_masuk_model->get_perbaruan_kontrak();
        }

        foreach ($semua_masuk as $row) {
            $this->Permohonan_masuk_model->sinkron_status_induk($row->id_permohonan_masuk);
            $row_fresh = $this->Permohonan_masuk_model->get_by_id($row->id_permohonan_masuk);
            if ($row_fresh) {
                $row->status               = $row_fresh->status;
                $row->verifikasi_operasi   = $row_fresh->verifikasi_operasi;
                $row->verifikasi_equipment = $row_fresh->verifikasi_equipment;
                $row->verifikasi_sales     = $row_fresh->verifikasi_sales;
                $row->verifikasi_security  = $row_fresh->verifikasi_security;
            }
            $row->daftar_gse = $this->Permohonan_masuk_model->get_gse_list($row->id_permohonan_masuk);
        }
        foreach ($kontrak as $row) {
            $this->Permohonan_masuk_model->sinkron_status_induk($row->id_permohonan_masuk);
            $row_fresh = $this->Permohonan_masuk_model->get_by_id($row->id_permohonan_masuk);
            if ($row_fresh) {
                $row->status               = $row_fresh->status;
                $row->verifikasi_operasi   = $row_fresh->verifikasi_operasi;
                $row->verifikasi_equipment = $row_fresh->verifikasi_equipment;
                $row->verifikasi_sales     = $row_fresh->verifikasi_sales;
                $row->verifikasi_security  = $row_fresh->verifikasi_security;
            }
            $row->daftar_gse = $this->Permohonan_masuk_model->get_gse_list($row->id_permohonan_masuk);
        }

        $data['semua_masuk'] = $semua_masuk;
        $data['kontrak']     = $kontrak;

        $this->load->view('template/header');
        $this->load->view('permohonan_masuk/index', $data);
        $this->load->view('template/footer');
    }

    public function perbaikan()
    {
        redirect('permohonan_masuk');
    }

    public function tambah()
    {
        if ($this->user_role !== 'ground_handling' && $this->user_role !== 'admin') {
            $this->session->set_flashdata('error', 'Hanya Ground Handling / Admin yang dapat menambah permohonan.');
            redirect('permohonan_masuk');
        }

        $id_laporan = $this->input->get('id_laporan');
        $laporan_kerusakan = null;
        if (!empty($id_laporan)) {
            $this->load->model('Kerusakan_model');
            $laporan_kerusakan = $this->Kerusakan_model->get_by_id($id_laporan);
        }

        $airline_id = $this->user_role === 'ground_handling' ? $this->user_airline : ($laporan_kerusakan->id_airline ?? null);

        $data['daftar_gse']        = $this->Gse_model->get_aktif();
        $data['airline']           = $airline_id ? $this->Airline_model->get_by_id($airline_id) : null;
        $data['laporan_kerusakan'] = $laporan_kerusakan;
        $data['user_role']         = $this->user_role;

        $this->load->view('template/header');
        $this->load->view('permohonan_masuk/tambah', $data);
        $this->load->view('template/footer');
    }

    public function tambah_perbaikan()
    {
        redirect('permohonan_masuk/tambah');
    }

    // ─── Simpan Permohonan Masuk (GSE / Sparepart) ───────────────────────────
    public function simpan()
    {
        if ($this->user_role !== 'ground_handling' && $this->user_role !== 'admin') {
            redirect('permohonan_masuk');
        }

        $jenis_pengajuan      = $this->input->post('jenis_pengajuan') ?: 'Masuk Baru';
        $id_laporan_kerusakan = $this->input->post('id_laporan_kerusakan') ?: null;

        $jumlah_unit_gse = (int) $this->input->post('jumlah_unit_gse');
        if ($jumlah_unit_gse < 1) {
            $this->session->set_flashdata('error', 'Jumlah unit GSE / sparepart yang diajukan wajib diisi (minimal 1).');
            redirect('permohonan_masuk/tambah' . ($id_laporan_kerusakan ? '?id_laporan=' . $id_laporan_kerusakan : ''));
        }

        if (empty($_FILES['file_bukti_permohonan']['name'])) {
            $this->session->set_flashdata('error', 'Bukti Permohonan wajib dilampirkan.');
            redirect('permohonan_masuk/tambah' . ($id_laporan_kerusakan ? '?id_laporan=' . $id_laporan_kerusakan : ''));
        }

        $id_airline = $this->user_role === 'ground_handling' ? $this->user_airline : $this->input->post('id_airline');
        $airline    = $this->Airline_model->get_by_id($id_airline);
        $nomor      = $this->generate_nomor($jenis_pengajuan);

        $folder_nomor = str_replace(['/', '\\'], '_', $nomor);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500;

        $ext  = strtolower(pathinfo($_FILES['file_bukti_permohonan']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['file_bukti_permohonan']['size'];

        if (!in_array($ext, $allowed)) {
            $this->session->set_flashdata('error', 'Bukti Permohonan: format tidak didukung. Gunakan JPG, PNG, atau PDF.');
            redirect('permohonan_masuk/tambah' . ($id_laporan_kerusakan ? '?id_laporan=' . $id_laporan_kerusakan : ''));
        }
        if ($size > $max_size) {
            $this->session->set_flashdata('error', 'Bukti Permohonan: ukuran file melebihi 500KB.');
            redirect('permohonan_masuk/tambah' . ($id_laporan_kerusakan ? '?id_laporan=' . $id_laporan_kerusakan : ''));
        }

        $file_bukti_permohonan = null;
        $new_name = 'file_bukti_permohonan_' . time() . '_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['file_bukti_permohonan']['tmp_name'], $upload_path . $new_name)) {
            $file_bukti_permohonan = $folder_nomor . '/' . $new_name;
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload Bukti Permohonan. Pastikan folder uploads/lampiran/ dapat ditulis.');
            redirect('permohonan_masuk/tambah' . ($id_laporan_kerusakan ? '?id_laporan=' . $id_laporan_kerusakan : ''));
        }

        $data_permohonan = [
            'nomor_permohonan'       => $nomor,
            'nomor_surat'            => $this->input->post('nomor_surat'),
            'tanggal_masuk'          => $this->input->post('tanggal_masuk'),
            'id_airline'             => $id_airline,
            'asal_instansi'          => $airline ? $airline->nama_airline : null,
            'jenis_permohonan'       => $jenis_pengajuan,
            'status'                 => 'Menunggu Verifikasi Operasi',
            'jumlah_unit_gse'        => $jumlah_unit_gse,
            'file_bukti_permohonan'  => $file_bukti_permohonan,
            'created_by'             => $this->user_id,
        ];

        $inserted_id = $this->Permohonan_masuk_model->insert($data_permohonan);

        if (!$inserted_id) {
            $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'tambah_masuk', 'Gagal menyimpan permohonan nomor ' . $nomor, 'failed');
            $this->session->set_flashdata('error', 'Gagal menyimpan permohonan ke database.');
            redirect('permohonan_masuk/tambah');
        }

        if (!empty($id_laporan_kerusakan)) {
            $this->load->model('Kerusakan_model');
            $this->Kerusakan_model->tindak_laporan($id_laporan_kerusakan, [
                'id_permohonan_masuk' => $inserted_id,
                'status_sparepart'    => 'Sedang Diajukan',
            ]);
        }

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'tambah_masuk', 'Nomor: ' . $nomor, 'success');

        $this->session->set_flashdata('success', 'Permohonan berhasil disimpan dengan nomor ' . $nomor . '. Selanjutnya lengkapi data item lewat menu "Input Kelengkapan".');
        redirect('permohonan_masuk/kelengkapan/' . $inserted_id);
    }

    public function kelengkapan($id)
    {
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);
        $allowed_types = ['Masuk Baru', 'Perbaikan', 'Campuran', 'Masuk Perbaikan Sparepart', 'Sparepart'];
        if (!$permohonan || !in_array($permohonan->jenis_permohonan, $allowed_types)) {
            show_404();
        }
        if ($this->user_role === 'ground_handling' && $permohonan->id_airline != $this->user_airline) {
            show_404();
        }

        $daftar_gse_terisi = $this->Permohonan_masuk_model->get_gse_list($id);
        $sudah_terisi      = count($daftar_gse_terisi);
        $sisa              = max(0, (int) $permohonan->jumlah_unit_gse - $sudah_terisi);

        $this->load->model('Kerusakan_model');
        $laporan_kerusakan = null;
        if (!empty($permohonan->id_permohonan_masuk)) {
            $laporan_kerusakan = $this->db->where('id_permohonan_masuk', $permohonan->id_permohonan_masuk)->get('laporan_kerusakan')->row();
        }

        // Ambil daftar laporan kerusakan yang belum ditindak untuk pilihan unit GSE
        $this->db->select('lk.id, lk.id_gse, lk.nama_gse, lk.sticker_ap, lk.tingkat_kerusakan, lk.keterangan, lk.status, lk.kebutuhan_sparepart, lk.created_at, a.nama_airline')
                 ->from('laporan_kerusakan lk')
                 ->join('airlines a', 'a.id_airline = lk.id_airline', 'left');

        if (!empty($permohonan->id_airline)) {
            $this->db->group_start()
                     ->where('lk.id_airline', $permohonan->id_airline)
                     ->or_where('lk.id_permohonan_masuk', $permohonan->id_permohonan_masuk)
                     ->group_end();
        }

        // Filter hanya laporan kerusakan yang belum ditindak
        $this->db->group_start()
                 ->where('lk.status', 'Baru')
                 ->or_where('lk.status !=', 'Sudah Ditindak')
                 ->group_end();

        $this->db->order_by('lk.created_at', 'DESC');
        $daftar_laporan_kerusakan = $this->db->get()->result();

        $data['permohonan']               = $permohonan;
        $data['gse_tidak_aktif']          = $this->Gse_model->get_tidak_aktif_by_airline($permohonan->id_airline);
        $data['daftar_gse_terisi']        = $daftar_gse_terisi;
        $data['sudah_terisi']             = $sudah_terisi;
        $data['sisa']                     = $sisa;
        $data['laporan_kerusakan']        = $laporan_kerusakan;
        $data['daftar_laporan_kerusakan'] = $daftar_laporan_kerusakan;

        $data['mode'] = $this->session->userdata('mode_kelengkapan_' . $id) ?? 'normal';
        $this->session->unset_userdata('mode_kelengkapan_' . $id);

        $this->load->view('template/header');
        $this->load->view('permohonan_masuk/kelengkapan', $data);
        $this->load->view('template/footer');
    }

    public function simpan_kelengkapan($id)
    {
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);
        $allowed_types = ['Masuk Baru', 'Perbaikan', 'Campuran', 'Masuk Perbaikan Sparepart', 'Sparepart'];
        if (!$permohonan || !in_array($permohonan->jenis_permohonan, $allowed_types)) {
            show_404();
        }
        if ($this->user_role === 'ground_handling' && $permohonan->id_airline != $this->user_airline) {
            show_404();
        }

        $sudah_terisi = count($this->Permohonan_masuk_model->get_gse_list($id));
        $sisa         = max(0, (int) $permohonan->jumlah_unit_gse - $sudah_terisi);
        if ($sisa <= 0) {
            $this->session->set_flashdata('error', 'Kuota item untuk permohonan ini sudah terpenuhi.');
            redirect('permohonan_masuk/kelengkapan/' . $id);
        }

        $jenis_item = $this->input->post('jenis_item');
        if ($permohonan->jenis_permohonan === 'Masuk Perbaikan Sparepart') {
            $jenis_item = 'Sparepart';
        }

        if (!in_array($jenis_item, ['Masuk Baru', 'Perbaikan', 'Sparepart'])) {
            $this->session->set_flashdata('error', 'Pilih jenis item terlebih dahulu.');
            redirect('permohonan_masuk/kelengkapan/' . $id);
        }

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500;

        // ─── Penanganan Khusus Item Sparepart ──────────────────────────────────
        if ($jenis_item === 'Sparepart') {
            $nama_sparepart = trim((string) $this->input->post('nama_sparepart'));
            $gse_terkait    = trim((string) $this->input->post('gse_terkait'));
            $qty_sparepart  = trim((string) $this->input->post('qty_sparepart'));
            $keterangan_sp  = trim((string) $this->input->post('keterangan'));
            $id_lk_item     = (int) $this->input->post('id_laporan_kerusakan_item');

            if ($nama_sparepart === '') {
                $this->session->set_flashdata('error', 'Nama sparepart / komponen wajib diisi.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            if ($gse_terkait === '') {
                $this->session->set_flashdata('error', 'Unit GSE yang akan diperbaiki wajib diisi atau dipilih dari laporan kerusakan.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $file_foto_gse_unit = null;
            if (!empty($_FILES['file_foto_gse']['name'])) {
                $ext  = strtolower(pathinfo($_FILES['file_foto_gse']['name'], PATHINFO_EXTENSION));
                $size = $_FILES['file_foto_gse']['size'];

                if (!in_array($ext, $allowed)) {
                    $this->session->set_flashdata('error', 'Foto sparepart: format tidak didukung.');
                    redirect('permohonan_masuk/kelengkapan/' . $id);
                }
                if ($size > $max_size) {
                    $this->session->set_flashdata('error', 'Foto sparepart: ukuran melebihi 500KB.');
                    redirect('permohonan_masuk/kelengkapan/' . $id);
                }

                $new_name = 'foto_sparepart_' . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['file_foto_gse']['tmp_name'], $upload_path . $new_name)) {
                    $file_foto_gse_unit = $folder_nomor . '/' . $new_name;
                }
            }

            $ket_gabung = ($qty_sparepart !== '' ? 'Jumlah: ' . $qty_sparepart : '') .
                          ($gse_terkait !== '' ? ' | Untuk GSE: ' . $gse_terkait : '') .
                          ($keterangan_sp !== '' ? ' | ' . $keterangan_sp : '');

            $this->Permohonan_masuk_model->insert_gse([
                'id_permohonan_masuk'   => $id,
                'jenis_item'            => 'Sparepart',
                'nama_gse'              => $nama_sparepart,
                'manufacture_type'      => 'Sparepart',
                'no_asset'              => $gse_terkait ?: null,
                'keterangan'            => $ket_gabung,
                'file_foto_gse'         => $file_foto_gse_unit,
                'tahap_saat_ini'        => 'operasi',
                'status_item'           => null,
                'verifikasi_oleh'       => null,
                'verifikasi_at'         => null,
                'alasan_penolakan_item' => null,
            ]);

            // Hubungkan laporan kerusakan dengan permohonan masuk ini jika dipilih
            if ($id_lk_item > 0) {
                $this->load->model('Kerusakan_model');
                $this->Kerusakan_model->tindak_laporan($id_lk_item, [
                    'id_permohonan_masuk' => $id,
                    'status_sparepart'    => 'Sedang Diajukan',
                ]);
            }

            $this->Permohonan_masuk_model->recompute_jenis_permohonan($id);
            $this->session->set_flashdata('success', 'Item sparepart "' . $nama_sparepart . '" berhasil disimpan.');
            redirect('permohonan_masuk/kelengkapan/' . $id);
        }

        $sticker_ap_lama = null;

        if ($jenis_item === 'Perbaikan') {
            $id_gse_asal = (int) $this->input->post('id_gse_asal');
            if ($id_gse_asal < 1) {
                $this->session->set_flashdata('error', 'Pilih unit GSE (No. Asset) yang sedang diperbaiki.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $gse_asal = $this->Gse_model->get_by_id($id_gse_asal);
            if (!$gse_asal || $gse_asal->status !== 'Sedang Diperbaiki' || $gse_asal->id_airline != $permohonan->id_airline) {
                $this->session->set_flashdata('error', 'Unit GSE yang dipilih tidak valid atau bukan milik airline ini.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $nama_gse         = $gse_asal->nama_gse;
            $manufacture_type = $gse_asal->manufacture_type;
            $no_asset         = $gse_asal->no_asset;
            $nomor_rangka     = $gse_asal->nomor_rangka;
            $nomor_mesin      = $gse_asal->nomor_mesin;
            $sticker_ap_lama  = $gse_asal->sticker_ap;
            $dimensi_lama = $this->Permohonan_masuk_model->get_dimensi_terakhir_by_no_asset($no_asset);
        } else {
            $nama_gse         = trim((string) $this->input->post('nama_gse'));
            $manufacture_type = trim((string) $this->input->post('manufacture_type'));
            $no_asset_input   = trim((string) $this->input->post('no_asset'));
            if ($no_asset_input !== '') {
                $no_asset = $no_asset_input;
            } else {
                $no_asset = $this->Permohonan_masuk_model->generate_no_asset($manufacture_type);
            }
        }
        
        if ($no_asset !== '') {
            $daftar_gse_terisi = $this->Permohonan_masuk_model->get_gse_list($id);
            foreach ($daftar_gse_terisi as $g) {
                if (($g->no_asset ?? null) === $no_asset) {
                    $this->session->set_flashdata('error', 'No. Asset "' . $no_asset . '" sudah dipakai unit lain di permohonan ini.');
                    redirect('permohonan_masuk/kelengkapan/' . $id);
                }
            }

            if ($jenis_item === 'Masuk Baru' && $this->Gse_model->no_asset_exists($no_asset)) {
                $this->session->set_flashdata('error', 'No. Asset "' . $no_asset . '" sudah terdaftar di data GSE. Gunakan nomor lain.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }
        }

        if ($nama_gse === '' || $manufacture_type === '') {
            $this->session->set_flashdata('error', 'Nama GSE dan Manufacture Type wajib diisi.');
            redirect('permohonan_masuk/kelengkapan/' . $id);
        }

        $keterangan_unit = trim((string) $this->input->post('keterangan'));

        $is_motorized = strtolower(trim($manufacture_type)) === 'motorized';
        $is_foto_gse = in_array($jenis_item, ['Masuk Baru', 'Perbaikan']);

       if ($jenis_item === 'Masuk Baru') {
            $nomor_rangka = trim((string) $this->input->post('nomor_rangka'));
            $nomor_mesin  = trim((string) $this->input->post('nomor_mesin'));
        } else {
            $nomor_rangka_baru_input = trim((string) $this->input->post('nomor_rangka_baru'));
            $nomor_mesin_baru_input  = trim((string) $this->input->post('nomor_mesin_baru'));

            if ($nomor_rangka_baru_input !== '') {
                $nomor_rangka = $nomor_rangka_baru_input;
            }
            if ($nomor_mesin_baru_input !== '') {
                $nomor_mesin = $nomor_mesin_baru_input;
            }
        }

        $mode = $this->input->post('mode_kelengkapan');
        $wajib_dokumen = ($jenis_item === 'Masuk Baru' 
                          && $is_motorized 
                          && $mode !== 'ajukan_ulang');

        if ($wajib_dokumen) {
            foreach ([
                'file_ktp'                      => 'Fotocopy KTP',
                'file_tim'                      => 'Fotocopy TIM',
                'file_sim'                      => 'Fotocopy SIM (Driver)',
                'file_emisi'                    => 'Surat Keterangan Uji Emisi',
                'file_penugasan'                => 'Surat Keterangan Penugasan',
                'file_rekomendasi_bengkel_luar' => 'Surat Rekomendasi Laik Bengkel',
            ] as $field => $label) {
                if (empty($_FILES[$field]['name'])) {
                    $this->session->set_flashdata('error', $label . ' wajib dilampirkan karena unit ' . $nama_gse . ' berkategori Motorized.');
                    redirect('permohonan_masuk/kelengkapan/' . $id);
                }
            }
        }

        if ($jenis_item === 'Perbaikan' && empty($_FILES['file_bukti_perbaikan']['name'])) {
            $this->session->set_flashdata('error', 'Bukti Perbaikan wajib dilampirkan untuk unit ' . $nama_gse . '.');
            redirect('permohonan_masuk/kelengkapan/' . $id);
        }

        $nomor_rangka_baru = trim((string) $this->input->post('nomor_rangka_baru'));
        $nomor_mesin_baru  = trim((string) $this->input->post('nomor_mesin_baru'));
        $ada_perubahan_nomor = ($nomor_rangka_baru !== '' || $nomor_mesin_baru !== '');
        $file_perubahan_rangka = null;
        $file_perubahan_mesin = null;

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500;

        if ($is_foto_gse) {
            if (empty($_FILES['file_foto_gse']['name'])) {
                $this->session->set_flashdata('error', 'Bukti foto unit GSE wajib dilampirkan untuk unit ' . $nama_gse . '.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }
        }

        $file_foto_gse_unit = null;

        if ($is_foto_gse) {
            $ext  = strtolower(pathinfo($_FILES['file_foto_gse']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['file_foto_gse']['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Bukti foto unit GSE: format tidak didukung.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Bukti foto unit GSE: ukuran melebihi 500KB.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $new_name = 'bukti_unit_gse_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file_foto_gse']['tmp_name'], $upload_path . $new_name)) {
                $file_foto_gse_unit = $folder_nomor . '/' . $new_name;
            }
        }

        $file_ktp = $file_tim = $file_stnk = $file_sim = $file_emisi = $file_bukti_perbaikan_unit = $file_penugasan = $file_foto_rangka = $file_foto_mesin = $file_rekomendasi_bengkel_luar = $file_nomor_asset = null;

        if ($jenis_item === 'Masuk Baru' && !empty($no_asset_input)) {
            $file_key = !empty($_FILES['file_nomor_asset']['name']) ? 'file_nomor_asset' : (!empty($_FILES['file_no_asset']['name']) ? 'file_no_asset' : null);
            if (empty($file_key)) {
                $this->session->set_flashdata('error', 'Bukti No. Asset wajib dilampirkan karena No. Asset diisi secara manual.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $ext  = strtolower(pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION));
            $size = $_FILES[$file_key]['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Bukti No. Asset: format tidak didukung. Gunakan JPG, PNG, atau PDF.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Bukti No. Asset: ukuran file melebihi 500KB.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $new_name = 'bukti_no_asset_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_path . $new_name)) {
                $file_nomor_asset = $folder_nomor . '/' . $new_name;
            }
        }

        if ($wajib_dokumen) {
            foreach (['file_ktp', 'file_tim', 'file_stnk', 'file_sim', 'file_emisi', 'file_penugasan', 'file_foto_rangka', 'file_foto_mesin', 'file_rekomendasi_bengkel_luar'] as $field) {
                if (!empty($_FILES[$field]['name'])) {
                    $ext  = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                    $size = $_FILES[$field]['size'];

                    if (!in_array($ext, $allowed)) {
                        $this->session->set_flashdata('error', strtoupper($field) . ': format tidak didukung.');
                        redirect('permohonan_masuk/kelengkapan/' . $id);
                    }
                    if ($size > $max_size) {
                        $this->session->set_flashdata('error', strtoupper($field) . ': ukuran melebihi 500KB.');
                        redirect('permohonan_masuk/kelengkapan/' . $id);
                    }

                    $new_name = $field . '_' . time() . '_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_path . $new_name)) {
                        $$field = $folder_nomor . '/' . $new_name;
                    }
                }
            }
        }

        if ($jenis_item === 'Perbaikan' && !empty($_FILES['file_bukti_perbaikan']['name'])) {
            $ext  = strtolower(pathinfo($_FILES['file_bukti_perbaikan']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['file_bukti_perbaikan']['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Bukti Perbaikan: format tidak didukung.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Bukti Perbaikan: ukuran melebihi 500KB.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $new_name = 'bukti_perbaikan_unit_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file_bukti_perbaikan']['tmp_name'], $upload_path . $new_name)) {
                $file_bukti_perbaikan_unit = $folder_nomor . '/' . $new_name;
            }
        }

        if ($ada_perubahan_nomor && !empty($_FILES['file_perubahan_rangka']['name'])) {
            $ext  = strtolower(pathinfo($_FILES['file_perubahan_rangka']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['file_perubahan_rangka']['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Dokumentasi Perubahan Nomor Rangka: format tidak didukung.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Dokumentasi Perubahan Nomor Rangka: ukuran melebihi 500KB.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $new_name = 'dok_perubahan_rangka_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file_perubahan_rangka']['tmp_name'], $upload_path . $new_name)) {
                $file_perubahan_rangka = $folder_nomor . '/' . $new_name;
            }
        }

        if ($ada_perubahan_nomor && !empty($_FILES['file_perubahan_mesin']['name'])) {
            $ext  = strtolower(pathinfo($_FILES['file_perubahan_mesin']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['file_perubahan_mesin']['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Dokumentasi Perubahan Nomor Mesin: format tidak didukung.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Dokumentasi Perubahan Nomor Mesin: ukuran melebihi 500KB.');
                redirect('permohonan_masuk/kelengkapan/' . $id);
            }

            $new_name = 'dok_perubahan_mesin_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file_perubahan_mesin']['tmp_name'], $upload_path . $new_name)) {
                $file_perubahan_mesin = $folder_nomor . '/' . $new_name;
            }
        }

        $this->Permohonan_masuk_model->insert_gse([
        'id_permohonan_masuk'  => $id,
        'jenis_item'           => $jenis_item,
        'nama_gse'             => $nama_gse,
        'manufacture_type'     => $manufacture_type,
        'no_asset'             => $no_asset !== '' ? $no_asset : null,
        'dimensi_p'             => $jenis_item === 'Perbaikan' ? ($dimensi_lama->dimensi_p    ?? null) : null,
        'dimensi_l'             => $jenis_item === 'Perbaikan' ? ($dimensi_lama->dimensi_l    ?? null) : null,
        'dimensi_luas'          => $jenis_item === 'Perbaikan' ? ($dimensi_lama->dimensi_luas ?? null) : null,
        'masa_mulai'            => $jenis_item === 'Perbaikan' ? ($dimensi_lama->masa_mulai   ?? null) : null,
        'masa_selesai'          => $jenis_item === 'Perbaikan' ? ($dimensi_lama->masa_selesai ?? null) : null,
        'keterangan'            => $keterangan_unit,        
        'nomor_rangka'          => $is_motorized ? ($nomor_rangka ?: null) : null,
        'nomor_mesin'           => $is_motorized ? ($nomor_mesin  ?: null) : null,
        'sticker_ap'            => $jenis_item === 'Perbaikan' ? ($sticker_ap_lama ?: null) : null,
        'file_ktp'              => $file_ktp,
        'file_tim'              => $file_tim,
        'file_stnk'             => $file_stnk,
        'file_sim'              => $file_sim,
        'file_emisi'            => $file_emisi,
        'file_penugasan'        => $file_penugasan,
        'file_foto_rangka'      => $file_foto_rangka,
        'file_foto_mesin'       => $file_foto_mesin,
        'file_bukti_perbaikan'  => $file_bukti_perbaikan_unit,
        'file_rekomendasi_bengkel_luar' => $file_rekomendasi_bengkel_luar,
        'file_foto_gse'                 => $file_foto_gse_unit,
        'file_nomor_asset'              => $file_nomor_asset,
        'file_perubahan_rangka'         => $file_perubahan_rangka,
        'file_perubahan_mesin'          => $file_perubahan_mesin,
        'tahap_saat_ini'                => 'operasi',
        'status_item'                   => null,
        'verifikasi_oleh'               => null,
        'verifikasi_at'                 => null,
        'alasan_penolakan_item'         => null,
    ]);

    $this->Permohonan_masuk_model->recompute_jenis_permohonan($id);

    if ($jenis_item === 'Perbaikan' && $no_asset !== '') {
        $this->load->model('Gse_model');
        $this->Gse_model->sync_data_by_no_asset($no_asset, [
            'nomor_rangka' => $is_motorized ? ($nomor_rangka ?: null) : null,
            'nomor_mesin'  => $is_motorized ? ($nomor_mesin  ?: null) : null,
        ]);
    }


        $sudah_terisi_baru = $sudah_terisi + 1;

        if ($jenis_item === 'Perbaikan'
            && $permohonan->status === 'Menunggu Lengkapi Berkas'
            && $sudah_terisi_baru >= (int) $permohonan->jumlah_unit_gse) {
            $this->Permohonan_masuk_model->update($id, ['status' => 'Menunggu Verifikasi Operasi']);
        }

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'input_kelengkapan', 'Unit "' . $nama_gse . '" ditambahkan ke permohonan ID ' . $id . ' (' . $sudah_terisi_baru . '/' . (int) $permohonan->jumlah_unit_gse . ')', 'success');

        $this->session->set_flashdata('success', 'Unit GSE "' . $nama_gse . '" berhasil disimpan (' . $sudah_terisi_baru . ' dari ' . (int) $permohonan->jumlah_unit_gse . ' unit).');
        redirect('permohonan_masuk/kelengkapan/' . $id);
    }


    public function simpan_perbaikan()
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_masuk');
        }

        $jumlah = (int) $this->input->post('jumlah_unit_gse');
        if ($jumlah < 1) {
            $this->session->set_flashdata('error', 'Jumlah unit GSE minimal 1.');
            redirect('permohonan_masuk/tambah');
        }

        $airline = $this->Airline_model->get_by_id($this->user_airline);
        $nomor   = $this->generate_nomor('Perbaikan');

        $folder_nomor = str_replace(['/', '\\'], '_', $nomor);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500;

        $file_bukti_permohonan = null;

        if (!empty($_FILES['file_bukti_permohonan']['name'])) {

            if ($_FILES['file_bukti_permohonan']['error'] !== UPLOAD_ERR_OK) {
                $this->session->set_flashdata('error', 'Gagal upload Bukti Permohonan (kode error: ' . $_FILES['file_bukti_permohonan']['error'] . '). Coba lagi dengan file yang lebih kecil.');
                redirect('permohonan_masuk/tambah');
            }

            $ext  = strtolower(pathinfo($_FILES['file_bukti_permohonan']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['file_bukti_permohonan']['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Bukti permohonan: format tidak didukung. Gunakan JPG, PNG, atau PDF.');
                redirect('permohonan_masuk/tambah');
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Bukti permohonan: ukuran file melebihi 500KB.');
                redirect('permohonan_masuk/tambah');
            }

            $new_name = 'file_bukti_permohonan_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file_bukti_permohonan']['tmp_name'], $upload_path . $new_name)) {
                $file_bukti_permohonan = $folder_nomor . '/' . $new_name;
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan file Bukti Permohonan ke server. Pastikan folder uploads/lampiran/ dapat ditulis (writable).');
                redirect('permohonan_masuk/tambah');
            }
        } else {
            $this->session->set_flashdata('error', 'Bukti Permohonan wajib dilampirkan.');
            redirect('permohonan_masuk/tambah');
        }

        $data_permohonan = [
            'nomor_permohonan'     => $nomor,
            'nomor_surat'          => $this->input->post('nomor_surat'),
            'tanggal_masuk'        => $this->input->post('tanggal_masuk'),
            'id_airline'           => $this->user_airline,
            'asal_instansi'        => $airline ? $airline->nama_airline : null,
            'keterangan'           => $this->input->post('keterangan'),
            'jenis_permohonan'     => 'Perbaikan',
            'jumlah_unit_gse'      => $jumlah,
            'file_bukti_permohonan' => $file_bukti_permohonan,
            'status'               => 'Menunggu Lengkapi Berkas',
            'created_by'           => $this->user_id,
        ];

        $inserted = $this->Permohonan_masuk_model->insert($data_permohonan);

        if (!$inserted) {
            $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'tambah_perbaikan', 'Gagal menyimpan permohonan perbaikan nomor ' . $nomor, 'failed');
            $this->session->set_flashdata('error', 'Gagal menyimpan permohonan perbaikan ke database. Silakan coba lagi atau hubungi admin.');
            redirect('permohonan_masuk/tambah');
        }

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'tambah_perbaikan', 'Nomor: ' . $nomor, 'success');

        $this->session->set_flashdata('success', 'Permohonan perbaikan berhasil disimpan dengan nomor ' . $nomor . '. Silakan lengkapi unit GSE-nya.');
        redirect('permohonan_masuk');
    }

    public function simpan_dimensi($id_detail)
    {
        if ($this->user_role !== 'unit_sales') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengisi dimensi.');
            redirect('permohonan_masuk');
        }

        $row = $this->Permohonan_masuk_model->get_detail_item($id_detail);

        if (!$row) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('permohonan_masuk');
        }

        if (!empty($row->dimensi_diisi_pertama_at)) {
            if ((int)($row->dimensi_edit_count ?? 0) >= 2) {
                $this->session->set_flashdata('error', 'Batas edit dimensi sudah tercapai (maksimal 2 kali).');
                redirect('permohonan_masuk/detail/' . $row->id_permohonan_masuk);
            }

            if (time() > strtotime($row->dimensi_diisi_pertama_at . ' +30 days')) {
                $this->session->set_flashdata('error', 'Batas waktu edit dimensi sudah lewat (30 hari).');
                redirect('permohonan_masuk/detail/' . $row->id_permohonan_masuk);
            }
        }

        $p    = (float) $this->input->post('dimensi_p');
        $l    = (float) $this->input->post('dimensi_l');

        if ($p <= 0 || $l <= 0) {
            $this->session->set_flashdata('error', 'Panjang dan lebar wajib diisi dengan nilai lebih dari 0.');
            redirect('permohonan_masuk/detail/' . $row->id_permohonan_masuk);
        }

        $data = [
            'dimensi_p'    => $p,
            'dimensi_l'    => $l,
            'dimensi_luas' => round($p * $l, 2),
        ];

        $this->Permohonan_masuk_model->simpan_dimensi($id_detail, $data);
        $this->session->set_flashdata('success', 'Dimensi berhasil disimpan. Luas: ' . round($p * $l, 2) . ' m²');
        redirect('permohonan_masuk/detail/' . $row->id_permohonan_masuk);
    }

    public function detail($id)
    {
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);
        if (!$permohonan) {
            show_404();
        }

        $is_sparepart = in_array($permohonan->jenis_permohonan, ['Masuk Perbaikan Sparepart', 'Sparepart']);
        if ($is_sparepart) {
            $this->Permohonan_masuk_model->sinkron_status_induk($id);
            $permohonan = $this->Permohonan_masuk_model->get_by_id($id);
        }

        $data['permohonan']         = $permohonan;
        $data['daftar_gse']         = $this->Permohonan_masuk_model->get_gse_list($id);
        $data['riwayat_verifikasi'] = $this->Permohonan_masuk_model->get_riwayat_verifikasi($id);

        $this->load->view('template/header');
        $this->load->view('permohonan_masuk/detail', $data);
        $this->load->view('template/footer');
    }

    public function edit($id)
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_masuk');
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);

        if (!$permohonan
            || !in_array($permohonan->status, ['Ditolak', 'Sebagian Ditolak'])
            || $permohonan->id_airline != $this->user_airline) {
            $this->session->set_flashdata('error', 'Permohonan tidak dapat diedit.');
            redirect('permohonan_masuk');
        }

        $data['permohonan']   = $permohonan;
        $data['daftar_gse']   = $this->Gse_model->get_aktif();
        $data['gse_terpilih'] = $this->Permohonan_masuk_model->get_gse_list($id);

        $this->load->view('template/header');
        $this->load->view('permohonan_masuk/edit', $data);
        $this->load->view('template/footer');
    }

    public function update($id)
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_masuk');
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);

        if (!$permohonan
            || !in_array($permohonan->status, ['Ditolak', 'Sebagian Ditolak'])
            || $permohonan->id_airline != $this->user_airline) {
            redirect('permohonan_masuk');
        }

        $data_update = [
            'nomor_surat'   => $this->input->post('nomor_surat'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'keterangan'    => $this->input->post('keterangan'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $this->Permohonan_masuk_model->update($id, $data_update);
        $this->Permohonan_masuk_model->sinkron_status_induk($id);

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'ajukan_ulang', 'Permohonan ID ' . $id . ' diperbarui', 'success');

        $this->session->set_flashdata('success', 'Informasi permohonan berhasil diperbarui. Silakan ajukan ulang unit yang ditolak melalui tombol "Ajukan Ulang" di tabel unit.');
        redirect('permohonan_masuk/detail/' . $id);
    }

    public function cetak_ba($id)
    {
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);

        if (!$permohonan || $permohonan->status !== 'Disetujui') {
            $this->session->set_flashdata('error', 'Berita Acara hanya dapat dicetak untuk permohonan yang sudah Disetujui.');
            redirect('permohonan_masuk');
        }

        if (in_array($permohonan->jenis_permohonan, ['Masuk Perbaikan Sparepart', 'Sparepart'], true)) {
            $this->session->set_flashdata('error', 'Cetak Berita Acara tidak tersedia untuk permohonan masuk sparepart.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $daftar_gse = $this->Permohonan_masuk_model->get_gse_list($id);
        $data['daftar_gse'] = $daftar_gse;
        $data['permohonan'] = $permohonan;

        $this->load->model('User_model');

        $id_operasi = $id_equipment = $id_sales = $id_security = null;
        $waktu_operasi = $waktu_equipment = $waktu_sales = $waktu_security = null;

        foreach ($daftar_gse as $g) {
            if (!$id_operasi && !empty($g->operasi_oleh)) {
                $id_operasi    = $g->operasi_oleh;
                $waktu_operasi = $g->verifikasi_at;
            }
            if (!$id_equipment && !empty($g->equipment_oleh)) {
                $id_equipment    = $g->equipment_oleh;
                $waktu_equipment = $g->verifikasi_at;
            }
            if (!$id_sales && !empty($g->sales_oleh)) {
                $id_sales    = $g->sales_oleh;
                $waktu_sales = $g->verifikasi_at;
            }
            if (!$id_security && !empty($g->security_oleh)) {
                $id_security    = $g->security_oleh;
                $waktu_security = $g->verifikasi_at;
            }
        }

        $gh        = $this->User_model->get_by_id($permohonan->created_by);
        $operasi   = $this->User_model->get_by_id($id_operasi);
        $equipment = $this->User_model->get_by_id($id_equipment);
        $sales     = $this->User_model->get_by_id($id_sales);
        $security  = $this->User_model->get_by_id($id_security);

        $data['nama_gh']        = $gh        ? $gh->nama        : '-';
        $data['nama_operasi']   = $operasi   ? $operasi->nama   : '-';
        $data['nama_equipment'] = $equipment ? $equipment->nama : '-';
        $data['nama_sales']     = $sales     ? $sales->nama     : '-';
        $data['nama_security']  = $security  ? $security->nama  : '-';

        $data['waktu_operasi']   = $waktu_operasi   ?: $permohonan->updated_at;
        $data['waktu_equipment'] = $waktu_equipment ?: $permohonan->updated_at;
        $data['waktu_sales']     = $waktu_sales     ?: $permohonan->updated_at;
        $data['waktu_security']  = $waktu_security  ?: $permohonan->updated_at;

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'cetak_ba', 'Cetak BA untuk permohonan ID ' . $id, 'success');

        $this->load->view('permohonan_masuk/cetak_ba', $data);
    }

    public function cetak_stiker($id)
    {
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);

        if (!$permohonan) {
            $this->session->set_flashdata('error', 'Permohonan tidak ditemukan.');
            redirect('permohonan_masuk');
        }

        if (in_array($permohonan->jenis_permohonan, ['Masuk Perbaikan Sparepart', 'Sparepart'], true)) {
            $this->session->set_flashdata('error', 'Cetak Stiker tidak tersedia untuk permohonan masuk sparepart.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $daftar_gse = $this->Permohonan_masuk_model->get_gse_list($id);
        $urutan_tahap  = ['operasi', 'equipment', 'sales', 'security', 'selesai'];
        $idx_equipment = array_search('equipment', $urutan_tahap);

        $eq_lulus_semua = $permohonan->status === 'Disetujui';

        if (!$eq_lulus_semua) {
            $this->session->set_flashdata('error', 'Stiker Verifikasi hanya dapat dicetak setelah semua unit GSE lulus Verifikasi.');
            redirect('permohonan_masuk');
        }

        $kode_bandara = 'UPG';

        preg_match('/(\d+)$/', $permohonan->nomor_permohonan, $m);
        $nomor_seri = isset($m[1]) ? str_pad($m[1], 6, '0', STR_PAD_LEFT) : str_pad($id, 6, '0', STR_PAD_LEFT);

        foreach ($daftar_gse as $i => $g) {
            $sticker_ap = 'GE.' . $kode_bandara . '.' . $nomor_seri . '.' . ($i + 1);
            $this->Permohonan_masuk_model->update_sticker_ap($g->id, $sticker_ap);
        }

        $data['permohonan']   = $permohonan;
        $data['daftar_gse']   = $this->Permohonan_masuk_model->get_gse_list($id);
        $data['kode_bandara'] = $kode_bandara;

        // Tanggal validasi: pakai waktu verifikasi terakhir dari item (kalau ada),
        // fallback ke updated_at permohonan.
        $tgl_acuan = null;
        foreach ($daftar_gse as $g) {
            if (!empty($g->verifikasi_at) && (!$tgl_acuan || $g->verifikasi_at > $tgl_acuan)) {
                $tgl_acuan = $g->verifikasi_at;
            }
        }
        $tgl_disetujui          = $tgl_acuan ?? $permohonan->updated_at;
        $data['tahun_validasi'] = date('Y', strtotime($tgl_disetujui)); // fallback kalau unit tidak punya masa_mulai

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'cetak_stiker', 'Cetak stiker untuk permohonan ID ' . $id, 'success');

        $this->load->view('permohonan_masuk/cetak_stiker', $data);
    }

    public function cetak_ba_item($id, $id_detail)
    {
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);
        if (!$permohonan) {
            $this->session->set_flashdata('error', 'Permohonan tidak ditemukan.');
            redirect('permohonan_masuk');
        }

        $gse = $this->Permohonan_masuk_model->get_detail_by_id_permohonan($id_detail, $id);

        if (!$gse || $gse->tahap_saat_ini !== 'selesai') {
            $this->session->set_flashdata('error', 'BA hanya dapat dicetak untuk unit GSE yang sudah lolos semua tahap verifikasi.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $is_item_sparepart = (($gse->jenis_item ?? '') === 'Sparepart' || strtolower($gse->manufacture_type ?? '') === 'sparepart' || in_array($permohonan->jenis_permohonan, ['Masuk Perbaikan Sparepart', 'Sparepart'], true));
        if ($is_item_sparepart) {
            $this->session->set_flashdata('error', 'Cetak Berita Acara tidak tersedia untuk sparepart.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $this->load->model('User_model');
        $gh        = $this->User_model->get_by_id($permohonan->created_by);
        $operasi   = $this->User_model->get_by_id($gse->operasi_oleh);
        $equipment = $this->User_model->get_by_id($gse->equipment_oleh);
        $sales     = $this->User_model->get_by_id($gse->sales_oleh);
        $security  = $this->User_model->get_by_id($gse->security_oleh);

        $data['permohonan']     = $permohonan;
        $data['gse']            = $gse;
        $data['nama_gh']        = $gh       ? $gh->nama       : '-';
        $data['nama_operasi']   = $operasi  ? $operasi->nama  : '-';
        $data['nama_equipment'] = $equipment? $equipment->nama : '-';
        $data['nama_sales']     = $sales    ? $sales->nama     : '-';
        $data['nama_security']  = $security ? $security->nama  : '-';

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'cetak_ba_item',
            'Cetak BA item ID ' . $id_detail . ' permohonan ID ' . $id, 'success');

        $this->load->view('permohonan_masuk/cetak_ba_item', $data);
    }

    public function cetak_stiker_item($id, $id_detail)
    {
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);
        if (!$permohonan) {
            $this->session->set_flashdata('error', 'Permohonan tidak ditemukan.');
            redirect('permohonan_masuk');
        }

        $gse = $this->Permohonan_masuk_model->get_detail_by_id_permohonan($id_detail, $id);

        if (!$gse || $gse->tahap_saat_ini !== 'selesai') {
            $this->session->set_flashdata('error', 'Stiker hanya dapat dicetak untuk unit GSE yang sudah lolos semua tahap verifikasi.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $is_item_sparepart = (($gse->jenis_item ?? '') === 'Sparepart' || strtolower($gse->manufacture_type ?? '') === 'sparepart' || in_array($permohonan->jenis_permohonan, ['Masuk Perbaikan Sparepart', 'Sparepart'], true));
        if ($is_item_sparepart) {
            $this->session->set_flashdata('error', 'Cetak Stiker tidak tersedia untuk sparepart.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $kode_bandara = 'UPG';

        if ($permohonan->jenis_permohonan === 'Perbaikan') {
            if (empty($gse->sticker_ap)) {
                $this->session->set_flashdata('error', 'Unit ini belum memiliki nomor Sticker AP dari data sebelumnya.');
                redirect('permohonan_masuk/detail/' . $id);
            }
            $sticker_ap = $gse->sticker_ap;
        } else {
            preg_match('/(\d+)$/', $permohonan->nomor_permohonan, $m);
            $nomor_urut = isset($m[1]) ? str_pad($m[1], 6, '0', STR_PAD_LEFT) : str_pad($id, 6, '0', STR_PAD_LEFT);

            $sticker_ap = 'GE.' . $kode_bandara . '.' . ($permohonan->kode_airline ?? '') . '.' . $nomor_urut . '.' . $id_detail;

            $this->Permohonan_masuk_model->update_detail($id_detail, ['sticker_ap' => $sticker_ap]);

            // Sinkronkan juga ke tabel master gse kalau baris untuk unit ini sudah ada di sana
            $this->load->model('Gse_model');
            $this->Gse_model->sync_sticker_by_no_asset($gse->no_asset, $sticker_ap);
        }

        $tgl_validasi = $gse->masa_mulai ?? $gse->verifikasi_at ?? $gse->security_at ?? $permohonan->updated_at;

        $data['permohonan']    = $permohonan;
        $data['gse']           = $gse;
        $data['sticker_ap']    = $sticker_ap;
        $data['kode_bandara']  = $kode_bandara;
        $data['tahun_validasi']= date('Y', strtotime($tgl_validasi));

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'cetak_stiker_item',
            'Cetak stiker item ID ' . $id_detail . ' permohonan ID ' . $id, 'success');

        $this->load->view('permohonan_masuk/cetak_stiker_item', $data);
    }

    public function scan_gse($id_detail)
    {
        $gse = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);

        if (!$gse) {
            show_404();
        }

        $data['gse'] = $gse;
        $this->load->view('permohonan_masuk/scan_gse', $data);
    }

    public function hapus($id)
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_masuk');
        }

        $this->Permohonan_masuk_model->delete($id);
        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'hapus_permohonan', 'Permohonan ID ' . $id . ' dihapus', 'success');
        $this->session->set_flashdata('success', 'Permohonan berhasil dihapus.');
        redirect('permohonan_masuk');
    }

    private function generate_nomor($jenis = 'Masuk Baru')
    {
        if ($jenis === 'Perbaikan') {
            $prefix = 'PMP';
        } elseif ($jenis === 'Masuk Perbaikan Sparepart' || $jenis === 'Sparepart') {
            $prefix = 'PMS';
        } else {
            $prefix = 'PMB';
        }

        $last   = $this->Permohonan_masuk_model->get_last_nomor($prefix);
        $urutan = 1;
        if ($last && preg_match('/(\d+)$/', $last->nomor_permohonan, $m)) {
            $urutan = ((int) $m[1]) + 1;
        }

        do {
            $nomor  = $prefix . '/' . str_pad($urutan, 6, '0', STR_PAD_LEFT);
            $exists = $this->Permohonan_masuk_model->nomor_exists($nomor);
            $urutan++;
        } while ($exists);

        return $nomor;
    }

    public function update_item($id_detail)
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_masuk');
        }

        $detail = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);

        if (!$detail || ($detail->status_item ?? null) !== 'Ditolak') {
            $this->session->set_flashdata('error', 'Unit GSE ini tidak dapat diajukan ulang.');
            redirect('permohonan_masuk');
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($detail->id_permohonan_masuk);

        if (!$permohonan || $permohonan->id_airline != $this->user_airline) {
            show_404();
        }

        $nama_gse = trim($this->input->post('nama_gse'));
        if ($nama_gse === '') {
            $this->session->set_flashdata('error', 'Nama GSE wajib diisi.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500; // 500KB

        $data_update = [
        'nama_gse'         => $nama_gse,
        'manufacture_type' => $this->input->post('manufacture_type'),
        'no_asset'         => $this->input->post('no_asset'),
        'keterangan'       => $this->input->post('keterangan'),
        ];

        if ($this->input->post('nomor_rangka') !== null) {
            $data_update['nomor_rangka'] = $this->input->post('nomor_rangka');
        }
        if ($this->input->post('nomor_mesin') !== null) {
            $data_update['nomor_mesin'] = $this->input->post('nomor_mesin');
        }

        $sticker_ap_post = $this->input->post('sticker_ap');
        if (!empty($sticker_ap_post)) {
            $data_update['sticker_ap'] = $sticker_ap_post;
        }

        $file_fields = ['file_foto_gse', 'file_nomor_asset', 'file_bukti_perbaikan', 'file_ktp', 'file_tim', 'file_stnk', 'file_sim', 'file_emisi', 'file_foto_rangka', 'file_foto_mesin', 'file_perubahan_rangka', 'file_perubahan_mesin', 'file_ba_uji_laik', 'file_penugasan', 'file_rekomendasi_bengkel_luar'];

        foreach ($file_fields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                $ext  = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                $size = $_FILES[$field]['size'];

                if (!in_array($ext, $allowed)) {
                    $this->session->set_flashdata('error', strtoupper($field) . ': format tidak didukung. Gunakan JPG, PNG, atau PDF.');
                    redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
                }
                if ($size > $max_size) {
                    $this->session->set_flashdata('error', strtoupper($field) . ': ukuran file melebihi 500KB.');
                    redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
                }

                $new_name = $field . '_' . time() . '_' . uniqid() . '.' . $ext;

                if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_path . $new_name)) {
                    $data_update[$field] = $folder_nomor . '/' . $new_name;
                }
            }
        }

        if ($permohonan->jenis_permohonan === 'Perbaruan Kontrak') {
            $masa_mulai   = $this->input->post('masa_mulai');
            $masa_selesai = $this->input->post('masa_selesai');
            if (!empty($masa_mulai))   $data_update['masa_mulai']   = $masa_mulai;
            if (!empty($masa_selesai)) $data_update['masa_selesai'] = $masa_selesai;

            $dim_p = trim((string) $this->input->post('dimensi_p'));
            $dim_l = trim((string) $this->input->post('dimensi_l'));
            if ($dim_p !== '' && $dim_l !== '' && is_numeric($dim_p) && is_numeric($dim_l)) {
                $data_update['dimensi_p']    = $dim_p;
                $data_update['dimensi_l']    = $dim_l;
                $data_update['dimensi_luas'] = round(((float) $dim_p) * ((float) $dim_l), 2);
            }
         }


        $this->Permohonan_masuk_model->ajukan_ulang_item($id_detail, $data_update);

        $this->load->model('Ids_model');
        $this->Ids_model->catat_aktivitas(
            'permohonan_masuk',
            'ajukan_ulang_item',
            "Ajukan ulang unit GSE '{$data_update['nama_gse']}' (ID: {$id_detail}) pada permohonan {$permohonan->nomor_permohonan}"
        );

        $this->session->set_flashdata('success', 'Unit GSE berhasil diajukan ulang dan langsung masuk ke antrian verifikasi pada tahapan yang bersangkutan.');
        redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
    }

    public function upload_surat_rekomendasi_item($id_detail)
    {
        if ($this->user_role !== 'unit_operasi') {
            redirect('permohonan_masuk');
        }

        $g = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
        if (!$g) {
            show_404();
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($g->id_permohonan_masuk);

        if (empty($_FILES['file_surat_rekomendasi']['name'])) {
            $this->session->set_flashdata('error', 'File surat rekomendasi wajib diupload.');
            redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
        }

        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500; // 500KB
        $ext      = strtolower(pathinfo($_FILES['file_surat_rekomendasi']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $this->session->set_flashdata('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
            redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
        }
        if ($_FILES['file_surat_rekomendasi']['size'] > $max_size) {
            $this->session->set_flashdata('error', 'Ukuran file melebihi 500KB.');
            redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
        }

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $new_name = 'surat_rekomendasi_' . time() . '_' . uniqid() . '.' . $ext;

        if (move_uploaded_file($_FILES['file_surat_rekomendasi']['tmp_name'], $upload_path . $new_name)) {
            $this->Permohonan_masuk_model->update_gse($id_detail, [
                'file_surat_rekomendasi' => $folder_nomor . '/' . $new_name,
            ]);
            $this->session->set_flashdata('success', 'Surat rekomendasi berhasil diupload.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload file.');
        }

        redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
    }

    public function upload_file_pass_kendaraan_item($id_detail)
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_masuk');
        }

        $g = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
        if (!$g) {
            show_404();
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($g->id_permohonan_masuk);

        if (empty($_FILES['file_pass_kendaraan']['name'])) {
            $this->session->set_flashdata('error', 'File pass kendaraan wajib diupload.');
            redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
        }

        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500; // 500KB
        $ext      = strtolower(pathinfo($_FILES['file_pass_kendaraan']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $this->session->set_flashdata('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
            redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
        }
        if ($_FILES['file_pass_kendaraan']['size'] > $max_size) {
            $this->session->set_flashdata('error', 'Ukuran file melebihi 500KB.');
            redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
        }

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $new_name = 'pass_kendaraan_' . time() . '_' . uniqid() . '.' . $ext;

        if (move_uploaded_file($_FILES['file_pass_kendaraan']['tmp_name'], $upload_path . $new_name)) {
            $this->Permohonan_masuk_model->update_gse($id_detail, [
                'file_pass_kendaraan' => $folder_nomor . '/' . $new_name,
            ]);
            $this->session->set_flashdata('success', 'Pass kendaraan berhasil diupload.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload file.');
        }

        redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
    }

    public function edit_jumlah_unit($id)
    {
        if ($this->user_role !== 'admin') {
            $this->session->set_flashdata('error', 'Hanya Admin yang dapat mengubah jumlah unit.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);
        if (!$permohonan) {
            show_404();
        }

        if ($permohonan->status === 'Disetujui') {
            $this->session->set_flashdata('error', 'Jumlah unit tidak bisa diubah karena permohonan ini sudah selesai disetujui sepenuhnya.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $edit_count = (int) ($permohonan->jumlah_unit_edit_count ?? 0);
        if ($edit_count >= 2) {
            $this->session->set_flashdata('error', 'Batas maksimal 2x edit jumlah unit untuk permohonan ini sudah tercapai.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        if (!empty($permohonan->jumlah_unit_diedit_pertama_at)) {
            $batas_waktu = strtotime($permohonan->jumlah_unit_diedit_pertama_at . ' +30 days');
            if (time() > $batas_waktu) {
                $this->session->set_flashdata('error', 'Batas waktu 30 hari untuk mengedit jumlah unit permohonan ini sudah lewat.');
                redirect('permohonan_masuk/detail/' . $id);
            }
        }

        $jumlah_baru = (int) $this->input->post('jumlah_unit_gse');
        if ($jumlah_baru < 1) {
            $this->session->set_flashdata('error', 'Jumlah unit minimal 1.');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $sudah_terisi = count($this->Permohonan_masuk_model->get_gse_list($id));
        if ($jumlah_baru < $sudah_terisi) {
            $this->session->set_flashdata('error', 'Jumlah unit tidak boleh kurang dari jumlah unit yang sudah diisi (' . $sudah_terisi . ' unit).');
            redirect('permohonan_masuk/detail/' . $id);
        }

        $this->Permohonan_masuk_model->edit_jumlah_unit($id, $jumlah_baru);

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'edit_jumlah_unit', 'Jumlah unit permohonan ID ' . $id . ' diubah menjadi ' . $jumlah_baru, 'success');
        $this->session->set_flashdata('success', 'Jumlah unit berhasil diubah menjadi ' . $jumlah_baru . ' (' . ($edit_count + 1) . '/2 kali edit).');
        redirect('permohonan_masuk/detail/' . $id);
    }

}