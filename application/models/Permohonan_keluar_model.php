<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class permohonan_keluar_model extends CI_Model {

    protected $table = 'permohonan_keluar';

    public function get_semua()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a','a.id_airline=pm.id_airline','left')
            ->order_by('pm.id_permohonan_keluar','DESC')
            ->get()->result();
    }

    public function get_semua_by_airline($id_airline)
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a','a.id_airline=pm.id_airline','left')
            ->where('pm.id_airline',$id_airline)
            ->order_by('pm.id_permohonan_keluar','DESC')
            ->get()->result();
    }

    public function recompute_jenis_permohonan($id_permohonan_keluar)
    {
        $items = $this->get_gse_list($id_permohonan_keluar);
        if (empty($items)) {
            return;
        }

        $jenis_unik = array_unique(array_map(function ($it) {
            return ($it->jenis_item ?? '') === 'Perbaikan' ? 'Perbaikan' : 'Keluar Baru';
        }, $items));

        $baru = (count($jenis_unik) > 1) ? 'Campuran' : $jenis_unik[0];

        $this->db->where('id_permohonan_keluar', $id_permohonan_keluar)
                 ->update('permohonan_keluar', ['jenis_permohonan' => $baru]);
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.id_permohonan_keluar', $id)
            ->get()->row();
    }

    public function get_gse_list($id_permohonan_keluar)
    {
        return $this->db
            ->select('dpk.*, dpm_latest.nomor_rangka, dpm_latest.nomor_mesin')
            ->from('detail_permohonan_keluar dpk')
            ->join(
                '(SELECT DISTINCT ON (no_asset) no_asset, nomor_rangka, nomor_mesin
                  FROM detail_permohonan_masuk
                  WHERE no_asset IS NOT NULL
                  ORDER BY no_asset, created_at DESC) dpm_latest',
                'dpm_latest.no_asset = dpk.no_asset',
                'left'
            )
            ->where('dpk.id_permohonan_keluar', $id_permohonan_keluar)
            ->order_by('dpk.id', 'ASC')
            ->get()->result();
    }

    public function update_sticker_ap($id_detail, $sticker_ap)
    {
        return $this->db->where('id', $id_detail)->update('detail_permohonan_keluar', [
            'sticker_ap' => $sticker_ap,
        ]);
    }

    public function get_pending_operasi()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.status', 'Menunggu Verifikasi Operasi')
            ->where(
                "(pm.jenis_permohonan NOT IN ('Keluar Baru','Campuran') OR pm.jumlah_unit_gse <= " .
                "(SELECT COUNT(*) FROM detail_permohonan_keluar dpm WHERE dpm.id_permohonan_keluar = pm.id_permohonan_keluar))",
                null, false
            )
            ->order_by('pm.id_permohonan_keluar', 'ASC')
            ->get()->result();
    }

    public function get_pending_equipment()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.status', 'Menunggu Verifikasi Equipment')
            ->order_by('pm.id_permohonan_keluar', 'ASC')
            ->get()->result();
    }

    public function get_pending_sales()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.status', 'Menunggu Verifikasi Sales')
            ->order_by('pm.id_permohonan_keluar', 'ASC')
            ->get()->result();
    }

    public function get_pending_security()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.status', 'Menunggu Verifikasi Security')
            ->order_by('pm.id_permohonan_keluar', 'ASC')
            ->get()->result();
    }

    public function get_pending()
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where_in('pm.status', [
                'Menunggu Verifikasi Operasi',
                'Menunggu Verifikasi Equipment',
                'Menunggu Verifikasi Sales',
                'Menunggu Verifikasi Security',
            ])
            ->where(
                "(pm.jenis_permohonan NOT IN ('Keluar Baru','Campuran') OR pm.jumlah_unit_gse <= " .
                "(SELECT COUNT(*) FROM detail_permohonan_keluar dpm WHERE dpm.id_permohonan_keluar = pm.id_permohonan_keluar))",
                null, false
            )
            ->order_by('pm.id_permohonan_keluar', 'ASC')
            ->get()->result();
    }

    public function get_disetujui()
    {
        return $this->db
            ->where('status', 'Disetujui')
            ->order_by('id_permohonan_keluar', 'DESC')
            ->get($this->table)->result();
    }

    public function get_disetujui_by_airline($id_airline)
    {
        $rows = $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.id_airline', $id_airline)
            ->where('pm.status', 'Disetujui')
            ->order_by('pm.id_permohonan_keluar', 'DESC')
            ->get()->result();

        return $this->attach_gse($rows);
    }

    public function get_pending_by_airline($id_airline)
    {
        return $this->db
            ->select('pm.*, a.nama_airline, a.kode_airline')
            ->from('permohonan_keluar pm')
            ->join('airlines a', 'a.id_airline = pm.id_airline', 'left')
            ->where('pm.id_airline', $id_airline)
            ->where_in('pm.status', [
                'Menunggu Verifikasi Operasi',
                'Menunggu Verifikasi Equipment',
                'Menunggu Verifikasi Sales',
                'Menunggu Verifikasi Security',
            ])
            ->where(
                "(pm.jenis_permohonan NOT IN ('Keluar Baru','Campuran') OR pm.jumlah_unit_gse <= " .
                "(SELECT COUNT(*) FROM detail_permohonan_keluar dpm WHERE dpm.id_permohonan_keluar = pm.id_permohonan_keluar))",
                null, false
            )
            ->order_by('pm.id_permohonan_keluar', 'ASC')
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
        return $this->db->insert('detail_permohonan_keluar', $data);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id_permohonan_keluar', $id)->update($this->table, $data);
    }

    public function delete_gse($id_permohonan_keluar)
    {
        return $this->db->where('id_permohonan_keluar', $id_permohonan_keluar)
                        ->delete('detail_permohonan_keluar');
    }

    public function delete($id)
    {
        return $this->db->where('id_permohonan_keluar', $id)->delete($this->table);
    }

    public function tandai_item_ditolak($id_permohonan_keluar, array $item_ids)
    {
        $item_ids = array_filter(array_map('intval', $item_ids));
        if (empty($item_ids)) {
            return false;
        }

        return $this->db
            ->where('id_permohonan_keluar', $id_permohonan_keluar)
            ->where_in('id', $item_ids)
            ->update('detail_permohonan_keluar', ['status_item' => 'Ditolak']);
    }

    const URUTAN_TAHAP = ['sales', 'operasi', 'security'];

    public function get_item_pending_by_tahap($tahap)
    {
        return $this->db
            ->select('dpm.*, pm.nomor_permohonan, pm.jenis_permohonan, pm.asal_instansi, pm.created_at as created_permohonan, pm.id_airline')
            ->from('detail_permohonan_keluar dpm')
            ->join('permohonan_keluar pm', 'pm.id_permohonan_keluar = dpm.id_permohonan_keluar')
            ->where('dpm.tahap_saat_ini', $tahap)
            ->group_start()
                ->where('dpm.status_item !=', 'Ditolak')
                ->or_where('dpm.status_item IS NULL', null, false)
            ->group_end()
            ->order_by('dpm.id_permohonan_keluar', 'ASC')
            ->get()->result();
    }

    public function setujui_item($id_detail, $tahap_sekarang, $id_user, $data_tambahan = [])
    {
        $urutan = self::URUTAN_TAHAP;
        $idx    = array_search($tahap_sekarang, $urutan);

        if ($idx === false) return false;

        $tahap_berikut = $urutan[$idx + 1] ?? 'selesai';
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
            $parent_pk = $this->get_by_id($unit->id_permohonan_keluar);
            if ($parent_pk && !empty($parent_pk->file_dispo)) {
                $data['file_dispo'] = $parent_pk->file_dispo;
            }
        }

        if (isset($kolom_oleh[$tahap_sekarang])) {
            $data[$kolom_oleh[$tahap_sekarang]] = $id_user;
        }

        $updated = $this->db->where('id', $id_detail)->update('detail_permohonan_keluar', $data);

        if ($updated) {
            $this->catat_riwayat_item($id_detail, $tahap_sekarang, 'Disetujui', $id_user);

            if ($tahap_berikut === 'selesai') {
                $this->load->model('Gse_model');
                $this->Gse_model->generate_dari_item($id_detail);
            }
            
            $this->sinkron_status_induk($this->get_id_induk_dari_detail($id_detail));
        }

        return $updated;
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

    

    public function tolak_item($id_detail, $id_user, $alasan)
    {
        $updated = $this->db->where('id', $id_detail)->update('detail_permohonan_keluar', [
            'status_item'           => 'Ditolak',
            'verifikasi_oleh'       => $id_user,
            'verifikasi_at'         => date('Y-m-d H:i:s'),
            'alasan_penolakan_item' => $alasan,
        ]);

        if ($updated) {
            $this->sinkron_status_induk($this->get_id_induk_dari_detail($id_detail));
        }

        return $updated;
    }

    public function ajukan_ulang_item($id_detail, $data_baru = [])
    {
        $unit = $this->get_detail_by_id($id_detail);
        $tahap_tujuan = ($unit && !empty($unit->tahap_saat_ini)) ? $unit->tahap_saat_ini : 'sales';

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
            ->update('detail_permohonan_keluar', $data);

        if ($updated) {
            $this->sinkron_status_induk($this->get_id_induk_dari_detail($id_detail));
        }

        return $updated;
    }

    private function get_id_induk_dari_detail($id_detail)
    {
        $row = $this->db->select('id_permohonan_keluar')->where('id', $id_detail)->get('detail_permohonan_keluar')->row();
        return $row ? $row->id_permohonan_keluar : null;
    }

    public function sinkron_status_induk($id_permohonan_keluar)
    {
        if (!$id_permohonan_keluar) return;

        $items = $this->get_gse_list($id_permohonan_keluar);
        if (empty($items)) return;

        $label_tahap = [
            'sales'     => 'Menunggu Verifikasi Sales',
            'operasi'   => 'Menunggu Verifikasi Operasi',
            'security'  => 'Menunggu Verifikasi Security',
            'selesai'   => 'Disetujui',
        ];

        $ada_ditolak    = false;
        $semua_selesai  = true;
        $tahap_paling_awal = null;
        $urutan = self::URUTAN_TAHAP;

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
            $status_baru = $label_tahap[$tahap_paling_awal] ?? 'Menunggu Verifikasi Sales';
        }

        $update_data = [
            'status'     => $status_baru,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Sinkronisasi status per tahapan verifikasi keluar (sales, operasi, security)
        foreach ($urutan as $tahap_kode) {
            $st = status_tahap_gabungan($items, $tahap_kode);
            $col = 'verifikasi_' . $tahap_kode;
            $update_data[$col] = $st['value']; // 'Disetujui', 'Ditolak', atau NULL
            if ($st['value'] === 'Disetujui' && empty($permohonan->{$col . '_at'})) {
                $update_data[$col . '_at'] = date('Y-m-d H:i:s');
            }
        }

        $this->db->where('id_permohonan_keluar', $id_permohonan_keluar)
                 ->update('permohonan_keluar', $update_data);

        if ($status_baru === 'Disetujui') {
        $this->load->model('Gse_model');
        foreach ($items as $it) {
            if (empty($it->no_asset)) {
                continue;
            }
            if (($it->jenis_item ?? '') === 'Perbaikan') {
                continue;
            }

            $this->Gse_model->nonaktifkan($it->no_asset);
        }
    }
}

    public function delete_gse_ditolak($id_permohonan_keluar)
    {
        return $this->db
            ->where('id_permohonan_keluar', $id_permohonan_keluar)
            ->where('status_item', 'Ditolak')
            ->delete('detail_permohonan_keluar');
    }

    
    public function reset_pengajuan($id, $data)
    {
        $data['status']                    = 'Menunggu Verifikasi Sales';
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
        $data['updated_at']                = date('Y-m-d H:i:s');

        return $this->db->where('id_permohonan_keluar', $id)->update($this->table, $data);
    }

    public function get_detail_by_id($id_detail)
    {
        return $this->db->where('id', $id_detail)->get('detail_permohonan_keluar')->row();
    }

    public function update_keterangan_item($id_detail, $keterangan)
    {
        return $this->db->where('id', $id_detail)->update('detail_permohonan_keluar', [
            'keterangan' => $keterangan,
        ]);
    }

    private function attach_gse($rows)
    {
        foreach ($rows as &$row) {
            $row->daftar_gse = $this->get_gse_list($row->id_permohonan_keluar);
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

        return $this->db->where('id_permohonan_keluar', $id)->update($this->table, $data);
    }
}