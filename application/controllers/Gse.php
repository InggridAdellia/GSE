<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gse extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gse_model');
        $this->load->model('Airline_model');
        $this->load->model('Ids_model');

        $allowed = ['admin', 'unit_operasi', 'unit_equipment', 'unit_sales', 'unit_security', 'ground_handling'];
        if (!in_array($this->user_role, $allowed)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman Data GSE.');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $jumlah_dinonaktifkan = $this->Gse_model->nonaktifkan_yang_kadaluarsa();
        if ($jumlah_dinonaktifkan) {
            $this->Ids_model->catat_aktivitas('GSE', 'auto_nonaktif',
                $jumlah_dinonaktifkan . ' unit GSE otomatis dinonaktifkan karena masa kontrak berakhir', 'success');
        }

        if ($this->user_role === 'ground_handling') {
            $data['gse'] = $this->Gse_model->get_all_by_airline($this->user_airline);
        } else {
            $data['gse'] = $this->Gse_model->get_all();
        }
        $data['user_role'] = $this->user_role;

        $jumlah_segera_berakhir = 0;
        $jumlah_berakhir        = 0;
        foreach ($data['gse'] as $row) {
            $row->status_kontrak = $this->Gse_model->cek_status_kontrak($row->masa_selesai ?? null);
            $row->sisa_hari      = $this->Gse_model->get_sisa_hari($row->masa_selesai ?? null);
            if ($row->status_kontrak === 'segera_berakhir') $jumlah_segera_berakhir++;
            if ($row->status_kontrak === 'berakhir') $jumlah_berakhir++;
        }
        $data['jumlah_segera_berakhir'] = $jumlah_segera_berakhir;
        $data['jumlah_berakhir']        = $jumlah_berakhir;

        $this->load->view('template/header');
        $this->load->view('gse/index', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {
        if ($this->user_role === 'ground_handling') {
            $this->session->set_flashdata('error', 'Ground Handling tidak dapat menambah master data GSE secara manual. Silakan gunakan permohonan masuk.');
            redirect('gse');
        }

        $data['daftar_airline'] = $this->Airline_model->get_all();

        $this->load->view('template/header');
        $this->load->view('gse/tambah', $data);
        $this->load->view('template/footer');
    }

    public function simpan()
    {
        $data = [
            'nama_gse'         => $this->input->post('nama_gse'),
            'manufacture_type' => $this->input->post('manufacture_type'),
            'no_asset'         => $this->input->post('no_asset'),
            'sticker_ap'       => $this->input->post('sticker_ap'),
            'id_airline'       => $this->input->post('id_airline') ?: NULL,
            'status'           => 'Aktif',
        ];

        try {
            $this->Gse_model->insert($data);
            $this->Ids_model->catat_aktivitas('GSE', 'tambah_gse', 'Menambahkan GSE: ' . $data['nama_gse'], 'success');
            $this->session->set_flashdata('success', 'Data GSE berhasil ditambahkan.');
        } catch (Exception $e) {
            $this->Ids_model->catat_aktivitas('GSE', 'tambah_gse', 'Gagal menambahkan GSE: ' . $data['nama_gse'], 'failed');
            $this->session->set_flashdata('error', 'Gagal menyimpan data GSE. Silakan coba lagi.');
        }

        redirect('gse');
    }

    public function generate($id_permohonan_masuk)
    {
        $this->load->model('Permohonan_masuk_model');
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id_permohonan_masuk);

        if (!$permohonan) {
            $this->session->set_flashdata('error', 'Permohonan tidak ditemukan.');
            redirect('permohonan_masuk');
        }

        if ($permohonan->jenis_permohonan !== 'Masuk Baru' || $permohonan->status !== 'Disetujui') {
            $this->session->set_flashdata('error',
                'Data GSE hanya bisa dibuat dari permohonan jenis "Masuk Baru" yang statusnya sudah Disetujui.');
            redirect('permohonan_masuk/detail/' . $id_permohonan_masuk);
        }

        $result = $this->Gse_model->generate_from_permohonan_masuk($id_permohonan_masuk);

        $this->Ids_model->catat_aktivitas('GSE', 'generate_gse',
            'Generate data GSE dari permohonan ' . $permohonan->nomor_permohonan .
            ' (dibuat: ' . $result['created'] . ', dilewati: ' . $result['skipped'] . ')', 'success');

        if ($result['created'] > 0) {
            $pesan = $result['created'] . ' unit GSE berhasil ditambahkan ke data master.';
            if ($result['skipped'] > 0) {
                $pesan .= ' ' . $result['skipped'] . ' unit dilewati karena No. Asset sudah terdaftar.';
            }
            $this->session->set_flashdata('success', $pesan);
        } else {
            $pesan = 'Tidak ada unit GSE baru yang ditambahkan.';
            if ($result['skipped'] > 0) {
                $pesan .= ' Semua (' . $result['skipped'] . ') unit sudah terdaftar di data master.';
            } else {
                $pesan .= ' Pastikan kelengkapan unit GSE sudah diisi pada permohonan ini.';
            }
            $this->session->set_flashdata('error', $pesan);
        }

        redirect('permohonan_masuk/detail/' . $id_permohonan_masuk);
    }

    public function ubah_status($id_gse)
    {
        $gse = $this->Gse_model->get_by_id($id_gse);

        if (!$gse) {
            $this->session->set_flashdata('error', 'Data GSE tidak ditemukan.');
            redirect('gse');
        }

        if ($this->user_role === 'ground_handling' && $gse->id_airline != $this->user_airline) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke data GSE airline lain.');
            redirect('gse');
        }

        $status = ($gse->status === 'Aktif') ? 'Tidak Aktif' : 'Aktif';

        try {
            $this->Gse_model->update_status($id_gse, $status);
            $this->Ids_model->catat_aktivitas('GSE', 'ubah_status_gse', 'GSE #' . $id_gse . ' (' . $gse->nama_gse . ') diubah ke status ' . $status, 'success');
            $this->session->set_flashdata('success', 'Status GSE berhasil diubah menjadi ' . $status . '.');
        } catch (Exception $e) {
            $this->Ids_model->catat_aktivitas('GSE', 'ubah_status_gse', 'Gagal mengubah status GSE #' . $id_gse, 'failed');
            $this->session->set_flashdata('error', 'Gagal mengubah status GSE. Silakan coba lagi.');
        }

        redirect('gse');
    }

    public function cetak_stiker_item($id_gse)
    {
        $g = $this->Gse_model->get_by_id((int) $id_gse);

        if (!$g || empty($g->sticker_ap)) {
            $this->session->set_flashdata('error', 'Data unit GSE tidak ditemukan atau belum memiliki Sticker AP.');
            redirect('gse');
        }

        if ($this->user_role === 'ground_handling' && $g->id_airline != $this->user_airline) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses untuk mencetak stiker unit GSE maskapai lain.');
            redirect('gse');
        }

        $data['daftar_gse'] = [$g];

        $this->Ids_model->catat_aktivitas('GSE', 'cetak_stiker_item',
            'Cetak stiker unit GSE ID ' . $g->id_gse . ' (' . $g->nama_gse . ' - ' . $g->sticker_ap . ')', 'success');

        $this->load->view('gse/cetak_semua_stiker', $data);
    }

    public function cetak_semua_stiker()
    {
        $id_gse_list = $this->input->post('id_gse');

        if (empty($id_gse_list) || !is_array($id_gse_list)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu unit GSE untuk dicetak stikernya.');
            redirect('gse');
        }

        $daftar_gse = [];
        foreach ($id_gse_list as $id_gse) {
            $g = $this->Gse_model->get_by_id((int) $id_gse);
            if ($g && !empty($g->sticker_ap)) {
                if ($this->user_role === 'ground_handling' && $g->id_airline != $this->user_airline) {
                    continue;
                }
                $daftar_gse[] = $g;
            }
        }

        if (empty($daftar_gse)) {
            $this->session->set_flashdata('error', 'Data unit GSE yang dipilih tidak ditemukan atau belum punya Sticker AP.');
            redirect('gse');
        }

        $data['daftar_gse'] = $daftar_gse;

        $this->Ids_model->catat_aktivitas('GSE', 'cetak_semua_stiker',
            'Cetak stiker massal untuk ' . count($daftar_gse) . ' unit GSE', 'success');

        $this->load->view('gse/cetak_semua_stiker', $data);
    }

    public function perbarui_kontrak()
    {
        if ($this->user_role !== 'ground_handling') {
            $this->session->set_flashdata('error', 'Hanya Ground Handling yang dapat memperbarui kontrak GSE.');
            redirect('gse');
        }

        $id_gse_list = $this->input->post('id_gse');

        if (empty($id_gse_list) || !is_array($id_gse_list)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu unit GSE untuk diperbarui kontraknya.');
            redirect('gse');
        }

        $id_gse_list = array_map('intval', $id_gse_list);
        $daftar_gse  = $this->Gse_model->get_by_ids($id_gse_list);

        if (empty($daftar_gse)) {
            $this->session->set_flashdata('error', 'Unit GSE yang dipilih tidak ditemukan.');
            redirect('gse');
        }

        foreach ($daftar_gse as $g) {
            if ($g->id_airline != $this->user_airline) {
                $this->session->set_flashdata('error', 'Anda hanya dapat memperbarui kontrak GSE milik airline Anda sendiri.');
                redirect('gse');
            }
            if ($g->status === 'Proses Perbaruan') {
                $this->session->set_flashdata('error', 'Unit "' . $g->nama_gse . '" sudah dalam proses perbaruan kontrak.');
                redirect('gse');
            }
        }

        $this->load->model('Permohonan_masuk_model');

        $airline = $this->Airline_model->get_by_id($this->user_airline);
        $nomor   = $this->_generate_nomor_perbaruan_kontrak();

        $data_permohonan = [
            'nomor_permohonan'  => $nomor,
            'tanggal_masuk'     => date('Y-m-d'),
            'id_airline'        => $this->user_airline,
            'asal_instansi'     => $airline ? $airline->nama_airline : null,
            'keterangan'        => 'Perbaruan kontrak untuk ' . count($daftar_gse) . ' unit GSE.',
            'jenis_permohonan'  => 'Perbaruan Kontrak',
            'status'            => 'Menunggu Verifikasi Operasi',
            'jumlah_unit_gse'   => count($daftar_gse),
            'created_by'        => $this->user_id,
        ];

        $id_permohonan = $this->Permohonan_masuk_model->insert($data_permohonan);

        if (!$id_permohonan) {
            $this->Ids_model->catat_aktivitas('GSE', 'perbarui_kontrak', 'Gagal membuat permohonan perbaruan kontrak', 'failed');
            $this->session->set_flashdata('error', 'Gagal membuat permohonan perbaruan kontrak. Silakan coba lagi.');
            redirect('gse');
        }

        $id_gse_berhasil = [];
        foreach ($daftar_gse as $g) {

            $dimensi_lama = $this->Permohonan_masuk_model->get_dimensi_terakhir_by_no_asset($g->no_asset);

            $detail = [
                'id_permohonan_masuk'   => $id_permohonan,
                'nama_gse'              => $g->nama_gse,
                'manufacture_type'      => $g->manufacture_type,
                'no_asset'              => $g->no_asset,
                'nomor_rangka'          => $g->nomor_rangka,
                'nomor_mesin'           => $g->nomor_mesin,
                'sticker_ap'            => null,
                'dimensi_p'             => $dimensi_lama->dimensi_p    ?? null,
                'dimensi_l'             => $dimensi_lama->dimensi_l    ?? null,
                'dimensi_luas'          => $dimensi_lama->dimensi_luas ?? null,
                'masa_mulai'            => $dimensi_lama->masa_mulai   ?? null,
                'masa_selesai'          => $dimensi_lama->masa_selesai ?? null,
                'tahap_saat_ini'        => 'operasi',
                'status_item'           => 'Ditolak',
                'alasan_penolakan_item' => 'Menunggu pembaruan dokumen & masa berlaku kontrak dari Ground Handling.',
            ];

            $this->Permohonan_masuk_model->insert_gse($detail);
            $id_gse_berhasil[] = $g->id_gse;
        }

        $this->Gse_model->tandai_proses_perbaruan($id_gse_berhasil);

        $this->Ids_model->catat_aktivitas('GSE', 'perbarui_kontrak',
            'Membuat permohonan perbaruan kontrak ' . $nomor . ' untuk ' . count($daftar_gse) . ' unit GSE', 'success');

        $this->session->set_flashdata('success',
            'Permohonan perbaruan kontrak ' . $nomor . ' berhasil dibuat. Klik "Ajukan Ulang" pada tiap unit untuk melengkapi dokumen & masa berlaku baru.');

        redirect('permohonan_masuk/detail/' . $id_permohonan);
    }

    private function _generate_nomor_perbaruan_kontrak()
    {
        $this->load->model('Permohonan_masuk_model');
        $prefix = 'PMK';
        $last   = $this->Permohonan_masuk_model->get_last_nomor($prefix);
        $urutan = 1;
        if ($last) {
            $bagian = explode('/', $last->nomor_permohonan);
            $urutan = (int) end($bagian) + 1;
        }
        return $prefix . '/' . str_pad($urutan, 6, '0', STR_PAD_LEFT);
    }

}