<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Approval_model');
        $this->load->model('Ids_model');

        $allowed = ['unit_operasi', 'unit_equipment', 'unit_sales', 'unit_security', 'admin'];
        if (!in_array($this->user_role, $allowed)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman Verifikasi.');
            redirect('dashboard');
        }
    }


    public function index()
    {
        $this->load->model('Gse_model');
        $result = $this->Approval_model->get_by_role($this->user_role);

        $masuk  = $result['masuk'];
        $keluar = $result['keluar'];

        // Hitung tahun_kontrak_baru per item — dipindahkan dari view ke controller
        foreach ($masuk as $permohonan) {
            if (!empty($permohonan->items)) {
                foreach ($permohonan->items as $it) {
                    $it->tahun_kontrak_baru = null;
                    if (($it->jenis_permohonan ?? '') === 'Perbaruan Kontrak'
                        && !empty($it->no_asset)) {
                        $masa_lama = $this->Gse_model->get_masa_selesai_lama($it->no_asset);
                        if (!empty($masa_lama)) {
                            $it->tahun_kontrak_baru = ((int) date('Y', strtotime($masa_lama))) + 1;
                        }
                    }
                }
            } else {
                // Struktur flat (item langsung di permohonan, bukan nested)
                $permohonan->tahun_kontrak_baru = null;
                if (($permohonan->jenis_permohonan ?? '') === 'Perbaruan Kontrak'
                    && !empty($permohonan->no_asset)) {
                    $masa_lama = $this->Gse_model->get_masa_selesai_lama($permohonan->no_asset);
                    if (!empty($masa_lama)) {
                        $permohonan->tahun_kontrak_baru = ((int) date('Y', strtotime($masa_lama))) + 1;
                    }
                }
            }
        }

        $data['item_masuk']  = $masuk;
        $data['item_keluar'] = $keluar;
        $data['user_role']   = $this->user_role;

        $this->load->view('template/header');
        $this->load->view('approval/index', $data);
        $this->load->view('template/footer');
    }

    public function setujui_item($id_detail)
    {
        $tahap_sekarang = $this->input->post('tahap_sekarang');

        $peta_tahap = [
            'unit_operasi'   => 'operasi',
            'unit_equipment' => 'equipment',
            'unit_sales'     => 'sales',
            'unit_security'  => 'security',
        ];

        if (($peta_tahap[$this->user_role] ?? null) !== $tahap_sekarang) {
            $this->session->set_flashdata('error', 'Anda tidak berwenang menyetujui unit di tahap ini.');
            redirect('approval');
        }

        if ($tahap_sekarang === 'security') {
            $this->load->model('Permohonan_masuk_model');
            $g = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
            $is_sparepart = ($g && (($g->jenis_item ?? '') === 'Sparepart' || strpos(($g->jenis_item ?? ''), 'Sparepart') !== false));
            if (!$is_sparepart && $g && strtolower($g->manufacture_type ?? '') === 'motorized' && empty($g->file_pass_kendaraan)) {
                $this->session->set_flashdata('error', 'Unit GSE ini berkategori Motorized dan belum melampirkan Pass Kendaraan. Pass Kendaraan wajib diupload terlebih dahulu oleh pihak GH sebelum unit dapat disetujui di tahap Security.');
                redirect('approval');
            }
        }

        try {
            $this->Approval_model->setujui_item($id_detail, $tahap_sekarang, $this->user_id);
            $this->Ids_model->catat_aktivitas('Approval', 'setujui_item', 'Unit GSE ID ' . $id_detail . ' disetujui di tahap ' . $tahap_sekarang, 'success');
            $this->session->set_flashdata('success', 'Unit GSE berhasil disetujui.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menyetujui unit: ' . $e->getMessage());
        }

        redirect('approval');
    }

    public function setujui_item_keluar($id_detail)
    {
        $tahap_sekarang = $this->input->post('tahap_sekarang');

        $this->load->model('permohonan_keluar_model');
        $unit = $this->permohonan_keluar_model->get_detail_by_id($id_detail);

        if (!$unit) {
            $this->session->set_flashdata('error', 'Unit GSE tidak ditemukan.');
            redirect('approval');
        }

        $id_permohonan_keluar = $unit->id_permohonan_keluar;

        $peta_tahap = [
            'unit_operasi'   => 'operasi',
            'unit_equipment' => 'equipment',
            'unit_sales'     => 'sales',
            'unit_security'  => 'security',
        ];
        if (($peta_tahap[$this->user_role] ?? null) !== $tahap_sekarang) {
            $this->session->set_flashdata('error', 'Anda tidak berwenang menyetujui unit di tahap ini.');
            redirect('permohonan_keluar/detail/' . $id_permohonan_keluar);
        }

        try {
            $this->Approval_model->setujui_item_keluar($id_detail, $tahap_sekarang, $this->user_id);
            $this->Ids_model->catat_aktivitas('Approval', 'setujui_item_keluar', 'Unit GSE keluar ID ' . $id_detail . ' disetujui', 'success');
            $this->session->set_flashdata('success', 'Unit GSE berhasil disetujui.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menyetujui unit: ' . $e->getMessage());
        }

        redirect('permohonan_keluar/detail/' . $id_permohonan_keluar);
    }

    public function tolak_item($id_detail)
    {
        if ($this->user_role === 'admin') {
            $this->session->set_flashdata('error', 'Admin tidak memiliki akses untuk melakukan aksi verifikasi.');
            redirect('approval');
        }

        $alasan = $this->input->post('alasan_penolakan');

        if (empty(trim($alasan))) {
            $this->session->set_flashdata('error', 'Alasan penolakan wajib diisi.');
            redirect('approval');
        }

        try {
            $this->Approval_model->tolak_item($id_detail, $this->user_id, $alasan);
            $this->Ids_model->catat_aktivitas('Approval', 'tolak_item', 'Unit GSE ID ' . $id_detail . ' ditolak: ' . $alasan, 'success');
            $this->session->set_flashdata('success', 'Unit GSE telah ditolak.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menolak unit: ' . $e->getMessage());
    }

    redirect('approval');

    }

    public function tolak_item_keluar($id_detail)
    {
        if ($this->user_role === 'admin') {
            $this->session->set_flashdata('error', 'Admin tidak memiliki akses untuk melakukan aksi verifikasi.');
            redirect('approval');
        }

        $this->load->model('permohonan_keluar_model');
        $unit = $this->permohonan_keluar_model->get_detail_by_id($id_detail);

        if (!$unit) {
            $this->session->set_flashdata('error', 'Unit GSE tidak ditemukan.');
            redirect('approval');
        }

        $id_permohonan_keluar = $unit->id_permohonan_keluar;
        $alasan                = $this->input->post('alasan_penolakan');

        if (empty(trim($alasan))) {
            $this->session->set_flashdata('error', 'Alasan penolakan wajib diisi.');
            redirect('permohonan_keluar/detail/' . $id_permohonan_keluar);
        }

        try {
            $this->Approval_model->tolak_item_keluar($id_detail, $this->user_id, $alasan);

            if (($unit->jenis_item ?? '') === 'Perbaikan' && !empty($unit->no_asset)) {
                $this->load->model('Gse_model');
                $this->Gse_model->set_status_perbaikan_by_no_asset($unit->no_asset, 'Aktif');
            }

            $this->Ids_model->catat_aktivitas('Approval', 'tolak_item_keluar', 'Unit GSE keluar ID ' . $id_detail . ' ditolak', 'success');
            $this->session->set_flashdata('success', 'Unit GSE telah ditolak.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menolak unit: ' . $e->getMessage());
        }

        redirect('permohonan_keluar/detail/' . $id_permohonan_keluar);
    }

    public function setujui($tipe, $id)
    {
        if ($this->user_role === 'admin') {
            $this->session->set_flashdata('error', 'Admin tidak memiliki akses untuk melakukan aksi verifikasi.');
            redirect('approval');
        }

        if ($tipe === 'masuk' && $this->user_role === 'unit_equipment') {
            $this->session->set_flashdata('error',
                'Unit Equipment harus mengupload BA Uji Laik terlebih dahulu melalui tombol <strong>Upload BA Uji Laik</strong>.');
            redirect('approval');
        }

        if ($tipe === 'masuk') {
            $this->load->model('Permohonan_masuk_model');
            $permohonan = $this->Permohonan_masuk_model->get_by_id($id);

            if ($permohonan && $permohonan->jenis_permohonan === 'Masuk Baru') {
                $total_unit    = (int) $permohonan->jumlah_unit_gse;
                $jumlah_terisi = count($this->Permohonan_masuk_model->get_gse_list($id));

                if ($total_unit > 0 && $jumlah_terisi < $total_unit) {
                    $this->session->set_flashdata('error',
                        'Permohonan belum bisa diverifikasi. Kelengkapan unit GSE baru terisi ' .
                        $jumlah_terisi . ' dari ' . $total_unit .
                        ' unit. Minta Ground Handling melengkapi data GSE terlebih dahulu melalui menu Input Kelengkapan.');
                    redirect('approval');
                }
            }
        }

        try {
            if ($tipe === 'keluar') {
                $this->Approval_model->setujui_keluar($id, $this->user_role, $this->user_id);
            } else {
                $this->Approval_model->setujui($id, $this->user_role, $this->user_id);
            }
            $this->session->set_flashdata('success', 'Permohonan berhasil diverifikasi (Disetujui).');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menyetujui permohonan: ' . $e->getMessage());
        }

        redirect('approval');
    }

    public function tolak($tipe = 'masuk', $id = null)
    {
        if ($this->user_role === 'admin') {
            $this->session->set_flashdata('error', 'Admin tidak memiliki akses untuk melakukan aksi verifikasi.');
            redirect('approval');
        }

        if ($id === null) {
            $id = $tipe;
            $tipe = 'masuk';
        }

        $alasan       = $this->input->post('alasan_penolakan');
        $item_ditolak = $this->input->post('item_ditolak') ?: [];

        if (empty(trim($alasan))) {
            $this->session->set_flashdata('error', 'Alasan penolakan wajib diisi.');
            redirect('approval');
        }

        if ($tipe === 'masuk') {
            $this->load->model('Permohonan_masuk_model');
            $daftar_gse = $this->Permohonan_masuk_model->get_gse_list($id);

            if (!empty($daftar_gse) && empty($item_ditolak)) {
                $this->session->set_flashdata('error', 'Pilih minimal satu unit GSE yang ditolak.');
                redirect('approval');
            }
        }

        try {
            if ($tipe === 'keluar') {
                $this->Approval_model->tolak_keluar($id, $this->user_role, $this->user_id, $alasan);
            } else {
                $this->Approval_model->tolak($id, $this->user_role, $this->user_id, $alasan, $item_ditolak);
            }
            $this->session->set_flashdata('success', 'Permohonan telah ditolak.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menolak permohonan: ' . $e->getMessage());
        }

        redirect('approval');
    }

    public function upload_ba_uji_laik($id_detail)
    {
        if ($this->user_role !== 'unit_equipment') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk upload BA Uji Laik.');
            redirect('approval');
        }

        $detail = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
        if (!$detail) {
            $this->session->set_flashdata('error', 'Unit GSE tidak ditemukan.');
            redirect('approval');
        }

        if ($detail->tahap_saat_ini !== 'equipment') {
            $this->session->set_flashdata('error', 'Unit GSE ini tidak sedang menunggu di tahap Equipment.');
            redirect('approval');
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($detail->id_permohonan_masuk);

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500; // 500KB

        if (empty($_FILES['file_ba_uji_laik']['name'])) {
            $this->session->set_flashdata('error', 'File BA Uji Laik wajib diupload.');
            redirect('approval');
        }

        $ext  = strtolower(pathinfo($_FILES['file_ba_uji_laik']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['file_ba_uji_laik']['size'];

        if (!in_array($ext, $allowed)) {
            $this->session->set_flashdata('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
            redirect('approval');
        }

        if ($size > $max_size) {
            $this->session->set_flashdata('error', 'Ukuran file melebihi 500KB.');
            redirect('approval');
        }

        $new_name = 'ba_uji_laik_item_' . time() . '_' . uniqid() . '.' . $ext;

        if (move_uploaded_file($_FILES['file_ba_uji_laik']['tmp_name'], $upload_path . $new_name)) {
            $file_ba_uji_laik = $folder_nomor . '/' . $new_name;
            $this->Permohonan_masuk_model->upload_ba_item($id_detail, $file_ba_uji_laik, $this->user_id);

            $this->Ids_model->catat_aktivitas('Approval', 'upload_ba_item', 'BA Uji Laik unit GSE ID ' . $id_detail . ' diupload', 'success');
            $this->session->set_flashdata('success', 'BA Uji Laik berhasil diupload. Unit GSE diteruskan ke tahap Sales.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload file BA Uji Laik ke server. Pastikan folder uploads/lampiran/ dapat ditulis.');
        }
        redirect('approval');
    }

    public function upload_dispo_item($id_detail)
    {
        if ($this->user_role !== 'unit_operasi') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk upload Dispo.');
            redirect('approval');
        }

        $detail = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
        if (!$detail) {
            $this->session->set_flashdata('error', 'Unit GSE tidak ditemukan.');
            redirect('approval');
        }

        if ($detail->tahap_saat_ini !== 'operasi') {
            $this->session->set_flashdata('error', 'Unit GSE ini tidak sedang menunggu di tahap Operasi.');
            redirect('approval');
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($detail->id_permohonan_masuk);

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500; // 500KB

        if (empty($_FILES['file_dispo']['name'])) {
            $this->session->set_flashdata('error', 'File Dispo wajib diupload.');
            redirect('approval');
        }

        $ext  = strtolower(pathinfo($_FILES['file_dispo']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['file_dispo']['size'];

        if (!in_array($ext, $allowed)) {
            $this->session->set_flashdata('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
            redirect('approval');
        }
        if ($size > $max_size) {
            $this->session->set_flashdata('error', 'Ukuran file melebihi 500KB.');
            redirect('approval');
        }

        $new_name = 'dispo_item_' . time() . '_' . uniqid() . '.' . $ext;

        if (move_uploaded_file($_FILES['file_dispo']['tmp_name'], $upload_path . $new_name)) {
            $file_dispo = $folder_nomor . '/' . $new_name;

            $this->Permohonan_masuk_model->update($detail->id_permohonan_masuk, ['file_dispo' => $file_dispo]);
            $this->db->where('id_permohonan_masuk', $detail->id_permohonan_masuk)->update('detail_permohonan_masuk', ['file_dispo' => $file_dispo]);

            $this->Approval_model->setujui_item($id_detail, 'operasi', $this->user_id, [
                'file_dispo' => $file_dispo,
            ]);

            $this->Ids_model->catat_aktivitas('Approval', 'upload_dispo_item', 'Dispo unit GSE ID ' . $id_detail . ' diupload dan disetujui', 'success');
            $this->session->set_flashdata('success', 'Dispo berhasil diupload. Unit GSE diteruskan ke tahap berikutnya.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload file Dispo ke server.');
        }

        $redirect_to = $this->input->post('redirect_to');
        if (!empty($redirect_to)) {
            redirect($redirect_to);
        }

        redirect('approval');
    }

    public function upload_dispo_item_keluar($id_detail)
    {
        if ($this->user_role !== 'unit_operasi') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk upload Dispo.');
            redirect('approval');
        }

        $this->load->model('permohonan_keluar_model');
        $detail = $this->permohonan_keluar_model->get_detail_by_id($id_detail);
        if (!$detail) {
            $this->session->set_flashdata('error', 'Unit GSE tidak ditemukan.');
            redirect('approval');
        }

        if ($detail->tahap_saat_ini !== 'operasi') {
            $this->session->set_flashdata('error', 'Unit GSE ini tidak sedang menunggu di tahap Operasi.');
            redirect('approval');
        }

        $permohonan = $this->permohonan_keluar_model->get_by_id($detail->id_permohonan_keluar);

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500;

        if (empty($_FILES['file_dispo']['name'])) {
            $this->session->set_flashdata('error', 'File Dispo wajib diupload.');
            redirect('approval');
        }

        $ext  = strtolower(pathinfo($_FILES['file_dispo']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['file_dispo']['size'];

        if (!in_array($ext, $allowed)) {
            $this->session->set_flashdata('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
            redirect('approval');
        }
        if ($size > $max_size) {
            $this->session->set_flashdata('error', 'Ukuran file melebihi 500KB.');
            redirect('approval');
        }

        $new_name = 'dispo_keluar_item_' . time() . '_' . uniqid() . '.' . $ext;

        if (move_uploaded_file($_FILES['file_dispo']['tmp_name'], $upload_path . $new_name)) {
            $file_dispo = $folder_nomor . '/' . $new_name;

            $this->permohonan_keluar_model->update($detail->id_permohonan_keluar, ['file_dispo' => $file_dispo]);
            $this->db->where('id_permohonan_keluar', $detail->id_permohonan_keluar)->update('detail_permohonan_keluar', ['file_dispo' => $file_dispo]);

            $this->Approval_model->setujui_item_keluar($id_detail, 'operasi', $this->user_id, [
                'file_dispo' => $file_dispo,
            ]);

            $this->Ids_model->catat_aktivitas('Approval', 'upload_dispo_item_keluar', 'Dispo unit GSE keluar ID ' . $id_detail . ' diupload dan disetujui', 'success');
            $this->session->set_flashdata('success', 'Dispo berhasil diupload. Unit GSE diteruskan ke tahap Security.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload file Dispo ke server.');
        }

        $redirect_to = $this->input->post('redirect_to');
        if (!empty($redirect_to)) {
            redirect($redirect_to);
        }

        redirect('approval');
    }

    public function simpan_dimensi_item($id_detail)
    {
        if ($this->user_role !== 'unit_sales') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengisi data ini.');
            redirect('permohonan_masuk');
        }

        $detail = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
        if (!$detail) {
            show_404();
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($detail->id_permohonan_masuk);

        $return_to = $this->input->post('return_to') === 'approval' ? 'approval' : 'detail';
        $tujuan    = $return_to === 'approval'
        ? 'approval'
        : 'permohonan_masuk/detail/' . $detail->id_permohonan_masuk;

        $p       = trim((string) $this->input->post('dimensi_p'));
        $l       = trim((string) $this->input->post('dimensi_l'));
        $mulai   = $this->input->post('masa_mulai');
        $selesai = $this->input->post('masa_selesai');

        if ($p === '' || $l === '' || !$mulai || !$selesai) {
            $this->session->set_flashdata('error', 'P (M), L (M), Tanggal Mulai, dan Tanggal Selesai wajib diisi.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }
        if (!is_numeric($p) || !is_numeric($l) || $p <= 0 || $l <= 0) {
            $this->session->set_flashdata('error', 'P (M) dan L (M) harus berupa angka lebih dari 0.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }
        if (strtotime($selesai) < strtotime($mulai)) {
            $this->session->set_flashdata('error', 'Tanggal Selesai tidak boleh sebelum Tanggal Mulai.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        if (empty($_FILES['file_ba_pengukuran']['name'])) {
            $this->session->set_flashdata('error', 'BA Pengukuran wajib diunggah.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_size = 1024 * 500;

        $ext  = strtolower(pathinfo($_FILES['file_ba_pengukuran']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['file_ba_pengukuran']['size'];

        if (!in_array($ext, $allowed)) {
            $this->session->set_flashdata('error', 'BA Pengukuran: format tidak didukung. Gunakan JPG, PNG, atau PDF.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }
        if ($size > $max_size) {
            $this->session->set_flashdata('error', 'BA Pengukuran: ukuran file melebihi 500KB.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $folder_nomor = str_replace(['/', '\\'], '_', $permohonan->nomor_permohonan);
        $upload_path  = FCPATH . 'uploads/lampiran/' . $folder_nomor . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $file_ba_pengukuran = null;
        $new_name = 'ba_pengukuran_' . time() . '_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['file_ba_pengukuran']['tmp_name'], $upload_path . $new_name)) {
            $file_ba_pengukuran = $folder_nomor . '/' . $new_name;
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload BA Pengukuran ke server.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $luas = round(((float) $p) * ((float) $l), 2);

        try {
            $this->Approval_model->setujui_item($id_detail, 'sales', $this->user_id, [
                'dimensi_p'                => $p,
                'dimensi_l'                => $l,
                'dimensi_luas'             => $luas,
                'masa_mulai'               => $mulai,
                'masa_selesai'             => $selesai,
                'file_ba_pengukuran'       => $file_ba_pengukuran,
                'dimensi_diisi_pertama_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $this->Permohonan_masuk_model->catat_riwayat_item($id_detail, 'sales', 'Input Dimensi', $this->user_id, json_encode([
            'dimensi_p'    => $p,
            'dimensi_l'    => $l,
            'dimensi_luas' => $luas,
            'masa_mulai'   => $mulai,
            'masa_selesai' => $selesai,
        ]));

        $this->session->set_flashdata('success', 'Data dimensi & masa berlaku berhasil disimpan, unit diteruskan ke tahap berikutnya.');
        redirect($tujuan);
    }

    public function edit_dimensi_item($id_detail)
    {
        if ($this->user_role !== 'unit_sales') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
            redirect('permohonan_masuk');
        }

        $detail = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
        if (!$detail) {
            show_404();
        }
        if (empty($detail->dimensi_diisi_pertama_at)) {
            $this->session->set_flashdata('error', 'Data dimensi belum pernah diisi untuk unit ini.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $edit_count = (int) ($detail->dimensi_edit_count ?? 0);
        if ($edit_count >= 2) {
            $this->session->set_flashdata('error', 'Batas maksimal 2x edit dimensi untuk unit ini sudah tercapai.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $batas_waktu = strtotime($detail->dimensi_diisi_pertama_at . ' +30 days');
        if (time() > $batas_waktu) {
            $this->session->set_flashdata('error', 'Batas waktu 30 hari untuk mengedit dimensi unit ini sudah lewat.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $p       = trim((string) $this->input->post('dimensi_p'));
        $l       = trim((string) $this->input->post('dimensi_l'));
        $mulai   = $this->input->post('masa_mulai');
        $selesai = $this->input->post('masa_selesai');

        if ($p === '' || $l === '' || !$mulai || !$selesai) {
            $this->session->set_flashdata('error', 'P (M), L (M), Tanggal Mulai, dan Tanggal Selesai wajib diisi.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }
        if (!is_numeric($p) || !is_numeric($l) || $p <= 0 || $l <= 0) {
            $this->session->set_flashdata('error', 'P (M) dan L (M) harus berupa angka lebih dari 0.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }
        if (strtotime($selesai) < strtotime($mulai)) {
            $this->session->set_flashdata('error', 'Tanggal Selesai tidak boleh sebelum Tanggal Mulai.');
            redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
        }

        $luas = round(((float) $p) * ((float) $l), 2);

        $this->Permohonan_masuk_model->edit_dimensi_item($id_detail, [
            'dimensi_p'          => $p,
            'dimensi_l'          => $l,
            'dimensi_luas'       => $luas,
            'masa_mulai'         => $mulai,
            'masa_selesai'       => $selesai,
            'dimensi_edit_count' => $edit_count + 1,
        ], $this->user_id);

        $this->session->set_flashdata('success', 'Data dimensi berhasil diperbarui (' . ($edit_count + 1) . '/2 kali edit).');
        redirect('permohonan_masuk/detail/' . $detail->id_permohonan_masuk);
    }
}