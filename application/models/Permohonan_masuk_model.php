<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permohonan_masuk_model extends CI_Model {

    protected $table = 'permohonan_masuk';

    public function get_masuk_baru()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a','a.id_airline=pm.id_airline','left')
            ->where_in('pm.jenis_permohonan', ['Masuk Baru', 'Perbaikan', 'Campuran', 'Masuk Perbaikan Sparepart', 'Sparepart'])
            ->order_by('pm.id_permohonan_masuk','DESC')
            ->get()->result();
    }

    public function get_masuk_baru_by_airline($id_airline)
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a','a.id_airline=pm.id_airline','left')
            ->where('pm.id_airline',$id_airline)
            ->where_in('pm.jenis_permohonan', ['Masuk Baru', 'Perbaikan', 'Campuran', 'Masuk Perbaikan Sparepart', 'Sparepart'])
            ->order_by('pm.id_permohonan_masuk','DESC')
            ->get()->result();
    }

    public function get_perbaikan()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a','a.id_airline=pm.id_airline','left')
            ->where_in('pm.jenis_permohonan', ['Perbaikan', 'Campuran'])
            ->order_by('pm.id_permohonan_masuk','DESC')
            ->get()->result();
    }

    public function get_perbaikan_by_airline($id_airline)
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a','a.id_airline=pm.id_airline','left')
            ->where('pm.id_airline',$id_airline)
            ->where_in('pm.jenis_permohonan', ['Perbaikan', 'Campuran'])
            ->order_by('pm.id_permohonan_masuk','DESC')
            ->get()->result();
    }

    public function recompute_jenis_permohonan($id_permohonan_masuk)
    {
        $items = $this->get_gse_list($id_permohonan_masuk);
        if (empty($items)) {
            return;
        }

        $jenis_unik = array_unique(array_map(function ($it) {
            if (($it->jenis_item ?? '') === 'Perbaikan') return 'Perbaikan';
            if (($it->jenis_item ?? '') === 'Sparepart') return 'Masuk Perbaikan Sparepart';
            return 'Masuk Baru';
        }, $items));

        $baru = (count($jenis_unik) > 1) ? 'Campuran' : $jenis_unik[0];

        $this->db->where('id_permohonan_masuk', $id_permohonan_masuk)
                 ->update('permohonan_masuk', ['jenis_permohonan' => $baru]);
    }

    public function get_perbaruan_kontrak()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a','a.id_airline=pm.id_airline','left')
            ->where('pm.jenis_permohonan','Perbaruan Kontrak')
            ->order_by('pm.id_permohonan_masuk','DESC')
            ->get()->result();
    }

    public function get_perbaruan_kontrak_by_airline($id_airline)
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a','a.id_airline=pm.id_airline','left')
            ->where('pm.id_airline',$id_airline)
            ->where('pm.jenis_permohonan','Perbaruan Kontrak')
            ->order_by('pm.id_permohonan_masuk','DESC')
            ->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.id_permohonan_masuk', $id)
            ->get()->row();
    }

    public function get_gse_list($id_permohonan_masuk)
    {
        return $this->db
            ->from('detail_permohonan_masuk')
            ->where('id_permohonan_masuk', $id_permohonan_masuk)
            ->order_by('id', 'ASC')
            ->get()->result();
    }

    public function get_detail_item($id_detail)
    {
        return $this->db
            ->where('id', $id_detail)
            ->get('detail_permohonan_masuk')
            ->row();
    }

    public function update_detail($id_detail, $data)
    {
        return $this->db->where('id', $id_detail)->update('detail_permohonan_masuk', $data);
    }

    public function update_gse($id_detail, array $data)
    {
        return $this->db->where('id', $id_detail)->update('detail_permohonan_masuk', $data);
    }

    public function upload_surat_rekomendasi_item($id_detail)
    {
        if (!in_array($this->user_role, ['admin', 'unit_operasi'])) {
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
        if (!in_array($this->user_role, ['admin', 'ground_handling'])) {
            redirect('permohonan_masuk');
        }

        $g = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
        if (!$g) {
            show_404();
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($g->id_permohonan_masuk);

        if (strtolower($g->manufacture_type ?? '') !== 'motorized') {
            $this->session->set_flashdata('error', 'Pass kendaraan hanya berlaku untuk unit GSE berkategori Motorized.');
            redirect('permohonan_masuk/detail/' . $g->id_permohonan_masuk);
        }

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


    public function update_sticker_ap($id_detail, $sticker_ap)
    {
        return $this->db->where('id', $id_detail)->update('detail_permohonan_masuk', [
            'sticker_ap' => $sticker_ap,
        ]);
    }

    public function get_pending_operasi()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.status', 'Menunggu Verifikasi Operasi')
            ->where(
                "(pm.jenis_permohonan NOT IN ('Masuk Baru','Campuran') OR pm.jumlah_unit_gse <= " .
                "(SELECT COUNT(*) FROM detail_permohonan_masuk dpm WHERE dpm.id_permohonan_masuk = pm.id_permohonan_masuk))",
                null, false
            )
            ->order_by('pm.id_permohonan_masuk', 'ASC')
            ->get()->result();
    }

    public function get_pending_equipment()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.status', 'Menunggu Verifikasi Equipment')
            ->order_by('pm.id_permohonan_masuk', 'ASC')
            ->get()->result();
    }

    public function get_pending_sales()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.status', 'Menunggu Verifikasi Sales')
            ->order_by('pm.id_permohonan_masuk', 'ASC')
            ->get()->result();
    }

    public function get_pending_security()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.status', 'Menunggu Verifikasi Security')
            ->order_by('pm.id_permohonan_masuk', 'ASC')
            ->get()->result();
    }

    public function get_pending()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where_in('pm.status', [
                'Menunggu Verifikasi Operasi',
                'Menunggu Verifikasi Equipment',
                'Menunggu Verifikasi Sales',
                'Menunggu Verifikasi Security',
            ])
            ->where(
                "(pm.jenis_permohonan NOT IN ('Masuk Baru','Campuran') OR pm.jumlah_unit_gse <= " .
                "(SELECT COUNT(*) FROM detail_permohonan_masuk dpm WHERE dpm.id_permohonan_masuk = pm.id_permohonan_masuk))",
                null, false
            )
            ->order_by('pm.id_permohonan_masuk', 'ASC')
            ->get()->result();
    }

    public function get_disetujui()
    {
        return $this->db
            ->where('status', 'Disetujui')
            ->order_by('id_permohonan_masuk', 'DESC')
            ->get($this->table)->result();
    }

    public function get_disetujui_by_airline($id_airline)
    {
        $rows = $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.id_airline', $id_airline)
            ->where('pm.status', 'Disetujui')
            ->order_by('pm.id_permohonan_masuk', 'DESC')
            ->get()->result();

        return $this->attach_gse($rows);
    }

    public function get_pending_by_airline($id_airline)
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_masuk pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.id_airline', $id_airline)
            ->where_in('pm.status', [
                'Menunggu Verifikasi Operasi',
                'Menunggu Verifikasi Equipment',
                'Menunggu Verifikasi Sales',
                'Menunggu Verifikasi Security',
            ])
            ->where(
                "(pm.jenis_permohonan NOT IN ('Masuk Baru','Campuran') OR pm.jumlah_unit_gse <= " .
                "(SELECT COUNT(*) FROM detail_permohonan_masuk dpm WHERE dpm.id_permohonan_masuk = pm.id_permohonan_masuk))",
                null, false
            )
            ->order_by('pm.id_permohonan_masuk', 'ASC')
            ->get()->result();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function count_by_airline($id_airline)
    {
        return $this->db->where('id_airline', $id_airline)->count_all_results($this->table);
    }

    public function get_last_nomor($prefix)
    {
        return $this->db
            ->select('nomor_permohonan')
            ->from($this->table)
            ->like('nomor_permohonan', $prefix . '/', 'after')
            ->order_by('nomor_permohonan', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function nomor_exists($nomor_permohonan)
    {
        return $this->db
            ->where('nomor_permohonan', $nomor_permohonan)
            ->count_all_results($this->table) > 0;
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        if ($this->db->affected_rows() == 0) {
            print_r($this->db->error());
            die();
        }
        return $this->db->insert_id();
    }

    public function insert_gse($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('detail_permohonan_masuk', $data);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id_permohonan_masuk', $id)->update($this->table, $data);
    }

    public function delete_gse($id_permohonan_masuk)
    {
        return $this->db->where('id_permohonan_masuk', $id_permohonan_masuk)
                        ->delete('detail_permohonan_masuk');
    }

    public function tandai_item_ditolak($id_permohonan_masuk, array $item_ids)
    {
        $item_ids = array_filter(array_map('intval', $item_ids));
        if (empty($item_ids)) {
            return false;
        }

        return $this->db
            ->where('id_permohonan_masuk', $id_permohonan_masuk)
            ->where_in('id', $item_ids)
            ->update('detail_permohonan_masuk', ['status_item' => 'Ditolak']);
    }

    const URUTAN_TAHAP = ['operasi', 'equipment', 'sales', 'security'];

    /** Ambil daftar unit GSE yang sedang menunggu di tahap tertentu, untuk role tertentu */
    public function get_item_pending_by_tahap($tahap)
    {
        return $this->db
            ->select('dpm.*, pm.nomor_permohonan, pm.jenis_permohonan, pm.asal_instansi, pm.created_at as created_permohonan, pm.id_airline')
            ->from('detail_permohonan_masuk dpm')
            ->join('permohonan_masuk pm', 'pm.id_permohonan_masuk = dpm.id_permohonan_masuk')
            ->where('dpm.tahap_saat_ini', $tahap)
            ->group_start()
                ->where('dpm.status_item !=', 'Ditolak')
                ->or_where('dpm.status_item IS NULL', null, false)
            ->group_end()
            ->order_by('dpm.id_permohonan_masuk', 'ASC')
            ->get()->result();
    }

    public function setujui_item($id_detail, $tahap_sekarang, $id_user, $data_tambahan = [])
    {
        $unit = $this->get_detail_by_id($id_detail);
        $is_sparepart = ($unit && (($unit->jenis_item ?? '') === 'Sparepart' || strpos(($unit->jenis_item ?? ''), 'Sparepart') !== false));

        if ($is_sparepart) {
            $urutan = ['operasi', 'security'];
        } else {
            $urutan = self::URUTAN_TAHAP;
        }

        $idx = array_search($tahap_sekarang, $urutan);
        if ($idx === false) return false;

        $tahap_berikut = $urutan[$idx + 1] ?? 'selesai';

        if ($tahap_sekarang === 'security' && !$is_sparepart) {
            if ($unit && strtolower($unit->manufacture_type ?? '') === 'motorized' && empty($unit->file_pass_kendaraan)) {
                if (!empty($data_tambahan)) {
                    $this->db->where('id', $id_detail)->update('detail_permohonan_masuk', $data_tambahan);
                }
                throw new Exception('Unit GSE ini berkategori Motorized dan belum melampirkan Pass Kendaraan. Pass Kendaraan wajib diupload terlebih dahulu oleh pihak GH sebelum unit dapat disetujui di tahap Security.');
            }
        }

        $status_baru   = ($tahap_berikut === 'selesai') ? 'Selesai' : null;

        $kolom_oleh = [
            'operasi'   => 'operasi_oleh',
            'equipment' => 'equipment_oleh',
            'sales'     => 'sales_oleh',
            'security'  => 'security_oleh',
        ];

        $data = array_merge($data_tambahan, [
            'tahap_saat_ini'        => $tahap_berikut,
            'status_item'           => $status_baru,
            'verifikasi_oleh'       => $id_user,
            'verifikasi_at'         => date('Y-m-d H:i:s'),
            'alasan_penolakan_item' => null,
        ]);

        if ($tahap_sekarang === 'operasi' && empty($data_tambahan['file_dispo']) && empty($unit->file_dispo)) {
            $parent_pm = $this->get_by_id($unit->id_permohonan_masuk);
            if ($parent_pm && !empty($parent_pm->file_dispo)) {
                $data['file_dispo'] = $parent_pm->file_dispo;
            }
        }

        if (isset($kolom_oleh[$tahap_sekarang])) {
            $data[$kolom_oleh[$tahap_sekarang]] = $id_user;
        }

        $updated = $this->db->where('id', $id_detail)->update('detail_permohonan_masuk', $data);

        if ($updated) {
            $this->catat_riwayat_item($id_detail, $tahap_sekarang, 'Disetujui', $id_user);

            if ($tahap_berikut === 'selesai' && !$is_sparepart) {
                $this->load->model('Gse_model');
                $this->Gse_model->generate_dari_item($id_detail);
            }

            $this->sinkron_status_induk($this->get_id_induk_dari_detail($id_detail));
        }

        return $updated;
    }


    public function tolak_item($id_detail, $id_user, $alasan)
    {
        $detail_sblm_tolak = $this->get_detail_by_id($id_detail);
        $tahap_ditolak = $detail_sblm_tolak->tahap_saat_ini ?? 'operasi';

        $updated = $this->db->where('id', $id_detail)->update('detail_permohonan_masuk', [
            'status_item'           => 'Ditolak',
            'verifikasi_oleh'       => $id_user,
            'verifikasi_at'         => date('Y-m-d H:i:s'),
            'alasan_penolakan_item' => $alasan,
        ]);

        if ($updated) {
            $this->catat_riwayat_item($id_detail, $tahap_ditolak, 'Ditolak', $id_user, $alasan);
            $this->sinkron_status_induk($this->get_id_induk_dari_detail($id_detail));
        }

        return $updated;
    }

    private function catat_riwayat($id_detail, $tahap, $aksi, $id_user, $catatan = null)
    {
        $this->db->insert('riwayat_verifikasi_gse', [
            'id_detail'  => $id_detail,
            'tahap'      => $tahap,
            'aksi'       => $aksi,
            'oleh'       => $id_user,
            'catatan'    => $catatan,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function get_riwayat_verifikasi($id_detail)
    {
        return $this->db
            ->select('r.*, u.nama AS nama_user')
            ->from('riwayat_verifikasi_gse r')
            ->join('users u', 'u.id_user = r.oleh', 'left')
            ->where('r.id_detail', $id_detail)
            ->where_in('r.aksi', ['Input Dimensi', 'Edit Dimensi'])
            ->order_by('r.created_at', 'DESC')
            ->get()->result();
    }

    public function catat_riwayat_item($id_detail, $tahap, $aksi, $id_user, $catatan = null)
    {
        return $this->db->insert('riwayat_verifikasi_gse', [
            'id_detail'  => $id_detail,
            'tahap'      => $tahap,
            'aksi'       => $aksi,
            'oleh'       => $id_user,
            'catatan'    => $catatan,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function simpan_dimensi($id_detail, $data)
    {
        $existing = $this->get_detail_item($id_detail);

        // Pertama kali diisi
        if (empty($existing->dimensi_diisi_pertama_at)) {
            $data['dimensi_diisi_pertama_at'] = date('Y-m-d H:i:s');
            $data['dimensi_edit_count']       = 1;
        } else {
            $data['dimensi_edit_count'] = (int)($existing->dimensi_edit_count ?? 0) + 1;
        }

        return $this->db
            ->where('id', $id_detail)
            ->update('detail_permohonan_masuk', $data);
    }

    public function edit_dimensi_item($id_detail, array $data, $id_user)
    {
        $lama = $this->get_detail_by_id($id_detail);

        $updated = $this->db->where('id', $id_detail)->update('detail_permohonan_masuk', $data);

        if ($updated && $lama) {
            $catatan = json_encode([
                'sebelum' => [
                    'dimensi_p'    => $lama->dimensi_p,
                    'dimensi_l'    => $lama->dimensi_l,
                    'dimensi_luas' => $lama->dimensi_luas,
                    'masa_mulai'   => $lama->masa_mulai,
                    'masa_selesai' => $lama->masa_selesai,
                ],
                'sesudah' => [
                    'dimensi_p'    => $data['dimensi_p']    ?? null,
                    'dimensi_l'    => $data['dimensi_l']    ?? null,
                    'dimensi_luas' => $data['dimensi_luas'] ?? null,
                    'masa_mulai'   => $data['masa_mulai']   ?? null,
                    'masa_selesai' => $data['masa_selesai'] ?? null,
                ],
            ]);
            $this->catat_riwayat_item($id_detail, 'sales', 'Edit Dimensi', $id_user, $catatan);
        }

        return $updated;
    }

    public function get_dimensi_terakhir_by_no_asset($no_asset)
    {
        return $this->db
            ->select('dimensi_p, dimensi_l, dimensi_luas, masa_mulai, masa_selesai')
            ->from('detail_permohonan_masuk')
            ->where('no_asset', $no_asset)
            ->where('dimensi_p IS NOT NULL', null, false)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row();
    }

    public function get_riwayat_dimensi_by_no_asset($no_asset)
    {
        return $this->db
            ->select('r.*, u.nama AS nama_user')
            ->from('riwayat_verifikasi_gse r')
            ->join('users u', 'u.id_user = r.oleh', 'left')
            ->join('detail_permohonan_masuk d', 'd.id = r.id_detail', 'inner')
            ->where('d.no_asset', $no_asset)
            ->where_in('r.aksi', ['Input Dimensi', 'Edit Dimensi'])
            ->order_by('r.created_at', 'ASC')
            ->get()->result();
    }

    public function ajukan_ulang_item($id_detail, $data_baru = [])
    {
        $item = $this->get_detail_by_id($id_detail);
        $tahap_tujuan = ($item && !empty($item->tahap_saat_ini)) ? $item->tahap_saat_ini : 'operasi';

        $reset = [
            'tahap_saat_ini'        => $tahap_tujuan,
            'status_item'           => null,
            'verifikasi_oleh'       => null,
            'verifikasi_at'         => null,
            'alasan_penolakan_item' => null,
        ];

        $data = array_merge($reset, $data_baru);

        $updated = $this->db
            ->where('id', $id_detail)
            ->update('detail_permohonan_masuk', $data);

        if ($updated) {
            $user_id = $this->session->userdata('id_user') ?: $this->session->userdata('user_id');
            $this->catat_riwayat_item($id_detail, $tahap_tujuan, 'Diajukan Ulang', $user_id, 'Unit diajukan ulang oleh Ground Handling');
            $this->sinkron_status_induk($this->get_id_induk_dari_detail($id_detail));
        }

        return $updated;
    }

    private function get_id_induk_dari_detail($id_detail)
    {
        $row = $this->db->select('id_permohonan_masuk')->where('id', $id_detail)->get('detail_permohonan_masuk')->row();
        return $row ? $row->id_permohonan_masuk : null;
    }

    public function sinkron_status_induk($id_permohonan_masuk)
    {
        if (!$id_permohonan_masuk) return;

        $items = $this->get_gse_list($id_permohonan_masuk);
        if (empty($items)) return;

        $permohonan = $this->get_by_id($id_permohonan_masuk);
        $is_sparepart = ($permohonan && in_array($permohonan->jenis_permohonan, ['Masuk Perbaikan Sparepart', 'Sparepart']));

        $label_tahap = [
            'operasi'   => 'Menunggu Verifikasi Operasi',
            'equipment' => 'Menunggu Verifikasi Equipment',
            'sales'     => 'Menunggu Verifikasi Sales',
            'security'  => 'Menunggu Verifikasi Security',
            'selesai'   => 'Disetujui',
        ];

        $ada_ditolak    = false;
        $semua_selesai  = true;
        $tahap_paling_awal = null;
        $urutan = $is_sparepart ? ['operasi', 'security'] : self::URUTAN_TAHAP;

        foreach ($items as $it) {
            if (($it->status_item ?? null) === 'Ditolak') {
                $ada_ditolak = true;
            }
            if (($it->tahap_saat_ini ?? '') !== 'selesai') {
                $semua_selesai = false;

                if (($it->status_item ?? null) !== 'Ditolak') {
                    $idx_saat_ini = array_search($it->tahap_saat_ini, $urutan);
                    $idx_paling_awal = $tahap_paling_awal !== null ? array_search($tahap_paling_awal, $urutan) : PHP_INT_MAX;
                    if ($idx_saat_ini !== false && $idx_saat_ini < $idx_paling_awal) {
                        $tahap_paling_awal = $it->tahap_saat_ini;
                    }
                }
            }
        }

        if ($semua_selesai) {
            $status_baru = 'Disetujui';
        } elseif ($ada_ditolak && $tahap_paling_awal === null) {
            $status_baru = 'Sebagian Ditolak';
        } elseif ($ada_ditolak) {
            $status_baru = 'Sebagian Ditolak';
        } else {
            $status_baru = $label_tahap[$tahap_paling_awal] ?? 'Menunggu Verifikasi Operasi';
        }

        $update_data = [
            'status'     => $status_baru,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Sinkronisasi status per tahapan verifikasi
        $semua_tahap = ['operasi', 'equipment', 'sales', 'security'];
        foreach ($semua_tahap as $tahap_kode) {
            $col = 'verifikasi_' . $tahap_kode;
            if (in_array($tahap_kode, $urutan)) {
                $st = status_tahap_gabungan($items, $tahap_kode);
                $update_data[$col] = $st['value']; // 'Disetujui', 'Ditolak', atau NULL
                if ($st['value'] === 'Disetujui' && empty($permohonan->{$col . '_at'})) {
                    $update_data[$col . '_at'] = date('Y-m-d H:i:s');
                }
            } else {
                $update_data[$col] = null;
            }
        }

        $this->db->where('id_permohonan_masuk', $id_permohonan_masuk)
                 ->update('permohonan_masuk', $update_data);
    }

    public function delete_gse_ditolak($id_permohonan_masuk)
    {
        return $this->db
            ->where('id_permohonan_masuk', $id_permohonan_masuk)
            ->where('status_item', 'Ditolak')
            ->delete('detail_permohonan_masuk');
    }

    public function delete($id)
    {
        return $this->db->where('id_permohonan_masuk', $id)->delete($this->table);
    }

    public function reset_pengajuan($id, $data)
    {
        $data['status']                    = 'Menunggu Verifikasi Operasi';
        $data['alasan_penolakan']          = NULL;
        $data['verifikasi_operasi']        = NULL;
        $data['verifikasi_operasi_at']     = NULL;
        $data['verifikasi_operasi_oleh']   = NULL;
        $data['verifikasi_equipment']      = NULL;
        $data['verifikasi_equipment_at']   = NULL;
        $data['verifikasi_equipment_oleh'] = NULL;
        $data['verifikasi_sales']          = NULL;
        $data['verifikasi_sales_at']       = NULL;
        $data['verifikasi_sales_oleh']     = NULL;
        $data['verifikasi_security']       = NULL;
        $data['verifikasi_security_at']    = NULL;
        $data['verifikasi_security_oleh']  = NULL;
        $data['file_ba_uji_laik']          = NULL;
        $data['status_kelayakan']          = 'Belum Diperiksa';
        $data['updated_at']                = date('Y-m-d H:i:s');

        return $this->db->where('id_permohonan_masuk', $id)->update($this->table, $data);
    }

    public function generate_no_asset($manufacture_type)
    {
        $prefix = strtoupper($manufacture_type) === 'MOTORIZED' ? 'MTR' : 'NMT';

        $last = $this->db
            ->select('no_asset')
            ->from('detail_permohonan_masuk')
            ->like('no_asset', $prefix . '-', 'after')
            ->order_by('no_asset', 'DESC')
            ->limit(1)
            ->get()->row();

        $urutan = 1;
        if ($last && preg_match('/(\d+)$/', $last->no_asset, $m)) {
            $urutan = ((int) $m[1]) + 1;
        }

        do {
            $no_asset = $prefix . '-' . date('Y') . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
            $exists   = $this->db->where('no_asset', $no_asset)->count_all_results('detail_permohonan_masuk') > 0;
            $urutan++;
        } while ($exists);

        return $no_asset;
    }

    public function get_detail_by_id($id_detail)
    {
        return $this->db->where('id', $id_detail)->get('detail_permohonan_masuk')->row();
    }

    public function get_detail_by_id_permohonan($id_detail, $id_permohonan_masuk)
    {
        return $this->db
            ->where('id', $id_detail)
            ->where('id_permohonan_masuk', $id_permohonan_masuk)
            ->get('detail_permohonan_masuk')->row();
    }

    public function upload_ba_item($id_detail, $file_ba, $id_user)
    {
        $updated = $this->db->where('id', $id_detail)->update('detail_permohonan_masuk', [
            'file_ba_uji_laik' => $file_ba,
            'status_kelayakan' => 'Layak',
        ]);

        if ($updated) {
            $this->setujui_item($id_detail, 'equipment', $id_user);
        }

        return $updated;
    }

    private function attach_gse($rows)
    {
        foreach ($rows as &$row) {
            $row->daftar_gse = $this->get_gse_list($row->id_permohonan_masuk);
        }
        return $rows;
    }

    public function edit_jumlah_unit($id, $jumlah_baru)
    {
        $existing = $this->get_by_id($id);
        if (!$existing) return false;

        $data = ['jumlah_unit_gse' => $jumlah_baru];

        if (empty($existing->jumlah_unit_diedit_pertama_at)) {
            $data['jumlah_unit_diedit_pertama_at'] = date('Y-m-d H:i:s');
            $data['jumlah_unit_edit_count']        = 1;
        } else {
            $data['jumlah_unit_edit_count'] = (int) ($existing->jumlah_unit_edit_count ?? 0) + 1;
        }

        return $this->db->where('id_permohonan_masuk', $id)->update($this->table, $data);
    }
}