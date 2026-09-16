<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class permohonan_keluar extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('permohonan_keluar_model');
        $this->load->model('Permohonan_masuk_model');
        $this->load->model('Gse_model');
        $this->load->model('Airline_model');
        $this->load->model('permohonan_keluar_model');
        $this->load->model('Ids_model');
    }

    public function index()
    {
        if ($this->user_role === 'ground_handling') {
            $semua = $this->permohonan_keluar_model->get_semua_by_airline($this->user_airline);
        } else {
            $semua = $this->permohonan_keluar_model->get_semua();
        }

        foreach ($semua as $row) {
            $this->permohonan_keluar_model->sinkron_status_induk($row->id_permohonan_keluar);
            $row_fresh = $this->permohonan_keluar_model->get_by_id($row->id_permohonan_keluar);
            if ($row_fresh) {
                $row->status              = $row_fresh->status;
                $row->verifikasi_sales    = $row_fresh->verifikasi_sales;
                $row->verifikasi_operasi  = $row_fresh->verifikasi_operasi;
                $row->verifikasi_security = $row_fresh->verifikasi_security;
            }
            $row->daftar_gse = $this->permohonan_keluar_model->get_gse_list($row->id_permohonan_keluar);
        }

        $data['semua'] = $semua;

        $this->load->view('template/header');
        $this->load->view('permohonan_keluar/index', $data);
        $this->load->view('template/footer');
    }

    public function perbaikan()
    {
        redirect('permohonan_keluar');
    }

    public function tambah()
    {
        if ($this->user_role !== 'ground_handling') {
            $this->session->set_flashdata('error', 'Hanya Ground Handling yang dapat menambah permohonan.');
            redirect('permohonan_keluar');
        }

        $data['daftar_gse'] = $this->Gse_model->get_aktif();
        $data['airline']    = $this->Airline_model->get_by_id($this->user_airline);

        $this->load->view('template/header');
        $this->load->view('permohonan_keluar/tambah', $data);
        $this->load->view('template/footer');
    }

    public function tambah_perbaikan()
    {
        redirect('permohonan_keluar/tambah');
    }

    public function simpan()
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_keluar');
        }

        $jumlah_unit_gse = (int) $this->input->post('jumlah_unit_gse');
        if ($jumlah_unit_gse < 1) {
            $this->session->set_flashdata('error', 'Jumlah unit GSE yang diajukan wajib diisi (minimal 1).');
            redirect('permohonan_keluar/tambah');
        }

        if (empty($_FILES['file_bukti_permohonan']['name'])) {
            $this->session->set_flashdata('error', 'Bukti Permohonan wajib dilampirkan.');
            redirect('permohonan_keluar/tambah');
        }

        $airline = $this->Airline_model->get_by_id($this->user_airline);
        $nomor   = $this->generate_nomor('Keluar Baru');

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
            redirect('permohonan_keluar/tambah');
        }
        if ($size > $max_size) {
            $this->session->set_flashdata('error', 'Bukti Permohonan: ukuran file melebihi 500KB.');
            redirect('permohonan_keluar/tambah');
        }

        $file_bukti_permohonan = null;
        $new_name = 'file_bukti_permohonan_' . time() . '_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['file_bukti_permohonan']['tmp_name'], $upload_path . $new_name)) {
            $file_bukti_permohonan = $folder_nomor . '/' . $new_name;
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload Bukti Permohonan. Pastikan folder uploads/lampiran/ dapat ditulis.');
            redirect('permohonan_keluar/tambah');
        }

        $data_permohonan = [
            'nomor_permohonan'       => $nomor,
            'nomor_surat'            => $this->input->post('nomor_surat'),
            'tanggal_keluar'          => $this->input->post('tanggal_keluar'),
            'id_airline'             => $this->user_airline,
            'asal_instansi'          => $airline ? $airline->nama_airline : null,
            'jenis_permohonan'       => 'Keluar Baru',
            'status'                 => 'Menunggu Verifikasi Sales',
            'jumlah_unit_gse'        => $jumlah_unit_gse,
            'file_bukti_permohonan'  => $file_bukti_permohonan,

            'created_by'             => $this->user_id,
        ];

        $inserted = $this->permohonan_keluar_model->insert($data_permohonan);

        if (!$inserted) {
            $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'tambah_keluar_baru', 'Gagal menyimpan permohonan nomor ' . $nomor, 'failed');
            $this->session->set_flashdata('error', 'Gagal menyimpan permohonan ke database. Silakan coba lagi atau hubungi admin.');
            redirect('permohonan_keluar/tambah');
        }

        $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'tambah_keluar_baru', 'Nomor: ' . $nomor, 'success');

        $this->session->set_flashdata('success', 'Permohonan berhasil disimpan dengan nomor ' . $nomor . '. Selanjutnya lengkapi data unit GSE lewat menu "Input Kelengkapan".');
        redirect('permohonan_keluar');
    }

    public function kelengkapan($id)
    {
        $permohonan = $this->permohonan_keluar_model->get_by_id($id);
        if (!$permohonan || !in_array($permohonan->jenis_permohonan, ['Keluar Baru', 'Perbaikan', 'Campuran'])) {
            show_404();
        }
        if ($this->user_role === 'ground_handling' && $permohonan->id_airline != $this->user_airline) {
            show_404();
        }

        $daftar_gse_terisi = $this->permohonan_keluar_model->get_gse_list($id);
        $sudah_terisi      = count($daftar_gse_terisi);
        $sisa              = max(0, (int) $permohonan->jumlah_unit_gse - $sudah_terisi);

        // Unit GSE yang bisa dipilih untuk Surat Keluar ini: unit aktif milik
        // airline yang sama (sudah terdaftar lewat Permohonan Masuk yang
        // Disetujui), dikurangi unit yang sudah dipilih di permohonan ini.
        $no_asset_terpakai = array_map(function ($g) { return $g->no_asset; }, $daftar_gse_terisi);
        $gse_aktif         = $this->Gse_model->get_aktif_by_airline($permohonan->id_airline);
        $gse_tersedia      = array_values(array_filter($gse_aktif, function ($g) use ($no_asset_terpakai) {
            return $g->no_asset === null || !in_array($g->no_asset, $no_asset_terpakai);
        }));

        $data['permohonan']        = $permohonan;
        $data['daftar_gse_terisi'] = $daftar_gse_terisi;
        $data['gse_tersedia']      = $gse_tersedia;
        $data['sudah_terisi']      = $sudah_terisi;
        $data['sisa']              = $sisa;

        $this->load->view('template/header');
        $this->load->view('permohonan_keluar/kelengkapan', $data);
        $this->load->view('template/footer');
    }

    public function simpan_kelengkapan($id)
    {
        $permohonan = $this->permohonan_keluar_model->get_by_id($id);
        if (!$permohonan || !in_array($permohonan->jenis_permohonan, ['Keluar Baru', 'Perbaikan', 'Campuran'])) {
            show_404();
        }
        if ($this->user_role === 'ground_handling' && $permohonan->id_airline != $this->user_airline) {
            show_404();
        }

        $sudah_terisi = count($this->permohonan_keluar_model->get_gse_list($id));
        $sisa         = max(0, (int) $permohonan->jumlah_unit_gse - $sudah_terisi);
        if ($sisa <= 0) {
            $this->session->set_flashdata('error', 'Kuota unit GSE untuk permohonan ini sudah terpenuhi.');
            redirect('permohonan_keluar/kelengkapan/' . $id);
        }

        $jenis_item = $this->input->post('jenis_item');
        if (!in_array($jenis_item, ['Keluar Baru', 'Perbaikan'])) {
            $this->session->set_flashdata('error', 'Pilih jenis unit (Keluar Baru / Keluar Untuk Perbaikan) terlebih dahulu.');
            redirect('permohonan_keluar/kelengkapan/' . $id);
        }

        $id_gse = (int) $this->input->post('id_gse');
        if ($id_gse < 1) {
            $this->session->set_flashdata('error', 'Pilih unit GSE yang akan diajukan keluar.');
            redirect('permohonan_keluar/kelengkapan/' . $id);
        }

        $keterangan_unit = trim((string) $this->input->post('keterangan'));

        $gse = $this->Gse_model->get_by_id($id_gse);
        if (!$gse || $gse->status !== 'Aktif' || $gse->id_airline != $permohonan->id_airline) {
            $this->session->set_flashdata('error', 'Unit GSE yang dipilih tidak valid atau bukan milik airline ini.');
            redirect('permohonan_keluar/kelengkapan/' . $id);
        }

        $sudah_dipakai = array_map(function ($g) { return $g->no_asset; }, $this->permohonan_keluar_model->get_gse_list($id));
        if ($gse->no_asset && in_array($gse->no_asset, $sudah_dipakai)) {
            $this->session->set_flashdata('error', 'Unit GSE ini sudah dipilih sebelumnya di permohonan ini.');
            redirect('permohonan_keluar/kelengkapan/' . $id);
        }

        $nama_gse         = $gse->nama_gse;
        $manufacture_type = $gse->manufacture_type;
        $no_asset         = $gse->no_asset;
        $sticker_ap       = $gse->sticker_ap;

        $is_foto_gse = in_array($jenis_item, ['Keluar Baru', 'Perbaikan']);
        $is_kerusakan = $jenis_item === 'Perbaikan';

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
        redirect('permohonan_keluar/kelengkapan/' . $id);
    }
}

        $file_foto_gse_unit = null;

        if ($is_foto_gse) {
            $ext  = strtolower(pathinfo($_FILES['file_foto_gse']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['file_foto_gse']['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Bukti foto unit GSE: format tidak didukung.');
                redirect('permohonan_keluar/kelengkapan/' . $id);
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Bukti foto unit GSE: ukuran melebihi 500KB.');
                redirect('permohonan_keluar/kelengkapan/' . $id);
            }

            $new_name = 'bukti_unit_gse_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file_foto_gse']['tmp_name'], $upload_path . $new_name)) {
                $file_foto_gse_unit = $folder_nomor . '/' . $new_name;
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan foto unit GSE ke server. Cek permission folder uploads/lampiran/.');
                redirect('permohonan_keluar/kelengkapan/' . $id);
            }
        }

        if ($is_kerusakan) {
            if (empty($_FILES['file_bukti_kerusakan']['name'])) {
                $this->session->set_flashdata('error', 'Bukti Kerusakan wajib dilampirkan untuk unit ' . $nama_gse . '.');
                redirect('permohonan_keluar/kelengkapan/' . $id);
            }
        }

        $file_bukti_kerusakan_unit = null;

        if ($is_kerusakan) {
            $ext  = strtolower(pathinfo($_FILES['file_bukti_kerusakan']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['file_bukti_kerusakan']['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Bukti Kerusakan: format tidak didukung.');
                redirect('permohonan_keluar/kelengkapan/' . $id);
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Bukti Kerusakan: ukuran melebihi 500KB.');
                redirect('permohonan_keluar/kelengkapan/' . $id);
            }

            $new_name = 'bukti_kerusakan_unit_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file_bukti_kerusakan']['tmp_name'], $upload_path . $new_name)) {
                $file_bukti_kerusakan_unit = $folder_nomor . '/' . $new_name;
            }
        }

        $db_debug_asli = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $this->db->trans_start();
        $this->permohonan_keluar_model->insert_gse([
            'id_permohonan_keluar' => $id,
            'jenis_item'           => $jenis_item,
            'nama_gse'             => $nama_gse,
            'manufacture_type'     => $manufacture_type,
            'no_asset'             => $no_asset,
            'sticker_ap'           => $sticker_ap,
            'tahap_saat_ini'       => 'sales',
            'file_foto_gse'        => $file_foto_gse_unit,
            'file_bukti_kerusakan' => $file_bukti_kerusakan_unit,
            'keterangan'           => $keterangan_unit,
        ]);
        $this->db->trans_complete();

        $this->db->db_debug = $db_debug_asli;

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            if (isset($error['code']) && (string) $error['code'] === '23505') {
                $this->session->set_flashdata('error', 'Unit GSE "' . $nama_gse . '" sudah tersimpan di permohonan ini (kemungkinan tombol Simpan terklik dua kali). Silakan cek daftar unit yang sudah diisi di bawah.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan unit GSE. Silakan coba lagi.');
            }
            redirect('permohonan_keluar/kelengkapan/' . $id);
        }

        $this->permohonan_keluar_model->recompute_jenis_permohonan($id);

        if ($is_kerusakan) {
            $this->Gse_model->set_status_perbaikan_by_no_asset($no_asset, 'Sedang Diperbaiki');
        }

        $sudah_terisi_baru = $sudah_terisi + 1;

        if ($is_kerusakan
            && $permohonan->status === 'Menunggu Lengkapi Berkas'
            && $sudah_terisi_baru >= (int) $permohonan->jumlah_unit_gse) {
            $this->permohonan_keluar_model->update($id, ['status' => 'Menunggu Verifikasi Sales']);
        }

        $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'input_kelengkapan', 'Unit "' . $nama_gse . '" ditambahkan ke permohonan ID ' . $id . ' (' . $sudah_terisi_baru . '/' . (int) $permohonan->jumlah_unit_gse . ')', 'success');

        $this->session->set_flashdata('success', 'Unit GSE "' . $nama_gse . '" berhasil disimpan (' . $sudah_terisi_baru . ' dari ' . (int) $permohonan->jumlah_unit_gse . ' unit).');
        redirect('permohonan_keluar/kelengkapan/' . $id);
    }

    public function simpan_perbaikan()
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_keluar');
        }

        $jumlah = (int) $this->input->post('jumlah_unit_gse');
        if ($jumlah < 1) {
            $this->session->set_flashdata('error', 'Jumlah unit GSE minimal 1.');
            redirect('permohonan_keluar/tambah');
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
                redirect('permohonan_keluar/tambah');
            }

            $ext  = strtolower(pathinfo($_FILES['file_bukti_permohonan']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['file_bukti_permohonan']['size'];

            if (!in_array($ext, $allowed)) {
                $this->session->set_flashdata('error', 'Bukti permohonan: format tidak didukung. Gunakan JPG, PNG, atau PDF.');
                redirect('permohonan_keluar/tambah');
            }
            if ($size > $max_size) {
                $this->session->set_flashdata('error', 'Bukti permohonan: ukuran file melebihi 500KB.');
                redirect('permohonan_keluar/tambah');
            }

            $new_name = 'file_bukti_permohonan_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file_bukti_permohonan']['tmp_name'], $upload_path . $new_name)) {
                $file_bukti_permohonan = $folder_nomor . '/' . $new_name;
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan file Bukti Permohonan ke server. Pastikan folder uploads/lampiran/ dapat ditulis (writable).');
                redirect('permohonan_keluar/tambah');
            }
        } else {
            $this->session->set_flashdata('error', 'Bukti Permohonan wajib dilampirkan.');
            redirect('permohonan_keluar/tambah');
        }

        $data_permohonan = [
            'nomor_permohonan'     => $nomor,
            'nomor_surat'          => $this->input->post('nomor_surat'),
            'tanggal_keluar'        => $this->input->post('tanggal_keluar'),
            'id_airline'           => $this->user_airline,
            'asal_instansi'        => $airline ? $airline->nama_airline : null,
            'keterangan'           => $this->input->post('keterangan'),
            'jenis_permohonan'     => 'Perbaikan',
            'jumlah_unit_gse'      => $jumlah,
            'file_bukti_permohonan' => $file_bukti_permohonan,
            'status'               => 'Menunggu Lengkapi Berkas',
            'created_by'           => $this->user_id,
        ];

        $inserted = $this->permohonan_keluar_model->insert($data_permohonan);

        if (!$inserted) {
            $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'tambah_perbaikan', 'Gagal menyimpan permohonan perbaikan nomor ' . $nomor, 'failed');
            $this->session->set_flashdata('error', 'Gagal menyimpan permohonan perbaikan ke database. Silakan coba lagi atau hubungi admin.');
            redirect('permohonan_keluar/tambah');
        }

        $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'tambah_perbaikan', 'Nomor: ' . $nomor, 'success');

        $this->session->set_flashdata('success', 'Permohonan perbaikan berhasil disimpan dengan nomor ' . $nomor . '. Silakan lengkapi unit GSE-nya.');
        redirect('permohonan_keluar');
    }

    public function detail($id)
    {
        $data['permohonan'] = $this->permohonan_keluar_model->get_by_id($id);

        if (!$data['permohonan']) {
            show_404();
        }

        $data['daftar_gse'] = $this->permohonan_keluar_model->get_gse_list($id);

        $this->load->view('template/header');
        $this->load->view('permohonan_keluar/detail', $data);
        $this->load->view('template/footer');
    }

    public function edit($id)
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_keluar');
        }

        $permohonan = $this->permohonan_keluar_model->get_by_id($id);

        if (!$permohonan
            || $permohonan->status !== 'Ditolak'
            || $permohonan->id_airline != $this->user_airline) {
            $this->session->set_flashdata('error', 'Permohonan tidak dapat diedit.');
            redirect('permohonan_keluar');
        }

        $data['permohonan']   = $permohonan;
        $data['daftar_gse']   = $this->Gse_model->get_aktif();
        $data['gse_terpilih'] = $this->permohonan_keluar_model->get_gse_list($id);

        $this->load->view('template/header');
        $this->load->view('permohonan_keluar/edit', $data);
        $this->load->view('template/footer');
    }

    public function update($id)
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_keluar');
        }

        $permohonan = $this->permohonan_keluar_model->get_by_id($id);

        if (!$permohonan
            || $permohonan->status !== 'Ditolak'
            || $permohonan->id_airline != $this->user_airline) {
            redirect('permohonan_keluar');
        }

        $id_gse_list = $this->input->post('id_gse');
        if (empty($id_gse_list)) {
            $this->session->set_flashdata('error', 'Pilih minimal 1 unit GSE.');
            redirect('permohonan_keluar/edit/' . $id);
        }

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500;

        $this->permohonan_keluar_model->delete_gse($id);

        $data_update = [
            'nomor_surat'   => $this->input->post('nomor_surat'),
            'tanggal_keluar' => $this->input->post('tanggal_keluar'),
            'keterangan'    => $this->input->post('keterangan'),
        ];

        $this->permohonan_keluar_model->reset_pengajuan($id, $data_update);

        $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'ajukan_ulang', 'Permohonan ID ' . $id . ' diajukan ulang', 'success');

        $this->session->set_flashdata('success', 'Permohonan berhasil diajukan kembali.');
        redirect('permohonan_keluar');
    }
    
    public function edit_unit($id_detail)
    {
        $unit = $this->permohonan_keluar_model->get_detail_by_id($id_detail);
        if (!$unit || $unit->status_item !== 'Ditolak') {
            show_404();
        }

        $permohonan = $this->permohonan_keluar_model->get_by_id($unit->id_permohonan_keluar);
        if (!$permohonan || ($this->user_role === 'ground_handling' && $permohonan->id_airline != $this->user_airline)) {
            show_404();
        }

        $data['unit']       = $unit;
        $data['permohonan'] = $permohonan;

        $this->load->view('template/header');
        $this->load->view('permohonan_keluar/edit', $data);
        $this->load->view('template/footer');
    }

    public function update_unit($id_detail)
    {
        $unit = $this->permohonan_keluar_model->get_detail_by_id($id_detail);
        if (!$unit || $unit->status_item !== 'Ditolak') {
            show_404();
        }

        $permohonan = $this->permohonan_keluar_model->get_by_id($unit->id_permohonan_keluar);
        if (!$permohonan || ($this->user_role === 'ground_handling' && $permohonan->id_airline != $this->user_airline)) {
            show_404();
        }

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500;

        $data = [
            'keterangan' => $this->input->post('keterangan'),
        ];

        $file_fields = ['file_foto_gse' => 'bukti_unit_gse_'];
        if (($unit->jenis_item ?? '') === 'Perbaikan') {
            $file_fields['file_bukti_kerusakan'] = 'bukti_perbaikan_unit_';
        }

        foreach ($file_fields as $field => $prefix) {
            if (!empty($_FILES[$field]['name'])) {
                $ext  = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                $size = $_FILES[$field]['size'];

                if (!in_array($ext, $allowed)) {
                    $this->session->set_flashdata('error', strtoupper($field) . ': format tidak didukung.');
                    redirect('permohonan_keluar/edit_unit/' . $id_detail);
                }
                if ($size > $max_size) {
                    $this->session->set_flashdata('error', strtoupper($field) . ': ukuran melebihi 500KB.');
                    redirect('permohonan_keluar/edit_unit/' . $id_detail);
                }

                $new_name = $prefix . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_path . $new_name)) {
                    $data[$field] = $folder_nomor . '/' . $new_name;
                }
            }
        }

        $this->permohonan_keluar_model->ajukan_ulang_item($id_detail, $data);

        $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'edit_unit', 'Unit "' . $unit->nama_gse . '" diperbaiki pada permohonan ID ' . $unit->id_permohonan_keluar, 'success');

        $this->session->set_flashdata('success', 'Unit GSE "' . $unit->nama_gse . '" berhasil diperbarui dan diajukan ulang.');
        redirect('permohonan_keluar/detail/' . $unit->id_permohonan_keluar);
    }

    public function cetak_ba($id)
    {
        $permohonan = $this->permohonan_keluar_model->get_by_id($id);

        if (!$permohonan || $permohonan->status !== 'Disetujui') {
            $this->session->set_flashdata('error', 'Berita Acara hanya dapat dicetak untuk permohonan yang sudah Disetujui.');
            redirect('permohonan_keluar');
        }

        $daftar_gse = $this->permohonan_keluar_model->get_gse_list($id);
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

        $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'cetak_ba', 'Cetak BA untuk permohonan ID ' . $id, 'success');

        $this->load->view('permohonan_keluar/cetak_ba', $data);
    }

    public function cetak_ba_item($id, $id_detail)
    {
        $permohonan = $this->permohonan_keluar_model->get_by_id($id);
        if (!$permohonan) {
            $this->session->set_flashdata('error', 'Permohonan tidak ditemukan.');
            redirect('permohonan_keluar');
        }

        $gse = $this->permohonan_keluar_model->get_detail_by_id($id_detail);
        if ($gse && (int)$gse->id_permohonan_keluar !== (int)$id) {
            $gse = null; 
        }

        if (!$gse) {
            $this->session->set_flashdata('error', 'Unit GSE tidak ditemukan.');
            redirect('permohonan_keluar/detail/' . $id);
        }

        $this->load->model('User_model');
        $gh       = $this->User_model->get_by_id($permohonan->created_by);
        $operasi   = $this->User_model->get_by_id($gse->operasi_oleh);
        $equipment = $this->User_model->get_by_id($gse->equipment_oleh);
        $sales     = $this->User_model->get_by_id($gse->sales_oleh);
        $security  = $this->User_model->get_by_id($gse->security_oleh);

        $data['permohonan']     = $permohonan;
        $data['gse']            = $gse;
        $data['nama_gh']        = $gh        ? $gh->nama        : '-';
        $data['nama_operasi']   = $operasi   ? $operasi->nama   : '-';
        $data['nama_equipment'] = $equipment ? $equipment->nama : '-';
        $data['nama_sales']     = $sales     ? $sales->nama     : '-';
        $data['nama_security']  = $security  ? $security->nama  : '-';

        $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'cetak_ba_item',
            'Cetak BA item ID ' . $id_detail . ' permohonan ID ' . $id, 'success');

        $this->load->view('permohonan_keluar/cetak_ba_item', $data);
    }

    public function hapus($id)
    {
        if ($this->user_role !== 'ground_handling') {
            redirect('permohonan_keluar');
        }

        $this->permohonan_keluar_model->delete($id);
        $this->Ids_model->catat_aktivitas('Permohonan Keluar', 'hapus_permohonan', 'Permohonan ID ' . $id . ' dihapus', 'success');
        $this->session->set_flashdata('success', 'Permohonan berhasil dihapus.');
        redirect('permohonan_keluar');
    }

    private function generate_nomor($jenis = 'Keluar Baru')
    {
        $prefix = ($jenis === 'Perbaikan') ? 'PKP' : 'PKB';

        $last   = $this->permohonan_keluar_model->get_last_nomor($prefix);
        $urutan = 1;
        if ($last && preg_match('/(\d+)$/', $last->nomor_permohonan, $m)) {
            $urutan = ((int) $m[1]) + 1;
        }

        do {
            $nomor  = $prefix . '/' . str_pad($urutan, 6, '0', STR_PAD_LEFT);
            $exists = $this->permohonan_keluar_model->nomor_exists($nomor);
            $urutan++;
        } while ($exists);

        return $nomor;
    }

    public function edit_jumlah_unit($id)
    {
        if ($this->user_role !== 'admin') {
            $this->session->set_flashdata('error', 'Hanya Admin yang dapat mengubah jumlah unit.');
            redirect('permohonan_keluar/detail/' . $id);
        }

        $permohonan = $this->permohonan_keluar_model->get_by_id($id);
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
            redirect('permohonan_keluar/detail/' . $id);
        }

        if (!empty($permohonan->jumlah_unit_diedit_pertama_at)) {
            $batas_waktu = strtotime($permohonan->jumlah_unit_diedit_pertama_at . ' +30 days');
            if (time() > $batas_waktu) {
                $this->session->set_flashdata('error', 'Batas waktu 30 hari untuk mengedit jumlah unit permohonan ini sudah lewat.');
                redirect('permohonan_keluar/detail/' . $id);
            }
        }

        $jumlah_baru = (int) $this->input->post('jumlah_unit_gse');
        if ($jumlah_baru < 1) {
            $this->session->set_flashdata('error', 'Jumlah unit minimal 1.');
            redirect('permohonan_keluar/detail/' . $id);
        }

        $sudah_terisi = count($this->permohonan_keluar_model->get_gse_list($id));
        if ($jumlah_baru < $sudah_terisi) {
            $this->session->set_flashdata('error', 'Jumlah unit tidak boleh kurang dari jumlah unit yang sudah diisi (' . $sudah_terisi . ' unit).');
            redirect('permohonan_keluar/detail/' . $id);
        }

        $this->permohonan_keluar_model->edit_jumlah_unit($id, $jumlah_baru);

        $this->Ids_model->catat_aktivitas('Permohonan Masuk', 'edit_jumlah_unit', 'Jumlah unit permohonan ID ' . $id . ' diubah menjadi ' . $jumlah_baru, 'success');
        $this->session->set_flashdata('success', 'Jumlah unit berhasil diubah menjadi ' . $jumlah_baru . ' (' . ($edit_count + 1) . '/2 kali edit).');
        redirect('permohonan_keluar/detail/' . $id);
    }
}