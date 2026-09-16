<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gse_model extends CI_Model {

    protected $table = 'gse';

    public function get_all()
    {
        return $this->db
            ->select('g.*, a.nama_airline, a.kode_airline')
            ->from('gse g')
            ->join('airlines a', 'a.id_airline = g.id_airline', 'left')
            ->order_by('g.id_gse', 'DESC')
            ->get()->result();
    }

    public function get_aktif()
    {
        return $this->db
            ->select('g.*, a.nama_airline')
            ->from('gse g')
            ->join('airlines a', 'a.id_airline = g.id_airline', 'left')
            ->where('g.status', 'Aktif')
            ->order_by('g.nama_gse', 'ASC')
            ->get()->result();
    }

        public function get_all_by_airline($id_airline)
    {
        return $this->db
            ->select('g.*, a.nama_airline, a.kode_airline')
            ->from('gse g')
            ->join('airlines a', 'a.id_airline = g.id_airline', 'left')
            ->where('g.id_airline', $id_airline)
            ->order_by('g.id_gse', 'DESC')
            ->get()->result();
    }

    public function get_aktif_by_airline($id_airline)
    {
        return $this->db
            ->select('g.*, a.nama_airline')
            ->from('gse g')
            ->join('airlines a', 'a.id_airline = g.id_airline', 'left')
            ->where('g.status', 'Aktif')
            ->where('g.id_airline', $id_airline)
            ->order_by('g.nama_gse', 'ASC')
            ->get()->result();
    }

    public function get_by_id($id_gse)
    {
        return $this->db
            ->select('g.*, a.nama_airline')
            ->from('gse g')
            ->join('airlines a', 'a.id_airline = g.id_airline', 'left')
            ->where('g.id_gse', $id_gse)
            ->get()->row();
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update_status($id_gse, $status)
    {
        return $this->db->where('id_gse', $id_gse)->update($this->table, [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function update_sticker_ap($id_gse, $sticker_ap)
    {
        return $this->db->where('id_gse', $id_gse)->update($this->table, [
            'sticker_ap' => $sticker_ap,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function sync_sticker_by_no_asset($no_asset, $sticker_ap)
    {
        $no_asset = trim((string) $no_asset);
        if ($no_asset === '' || empty($sticker_ap)) {
            return false;
        }

        return $this->db->where('no_asset', $no_asset)->update($this->table, [
            'sticker_ap' => $sticker_ap,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function sync_data_by_no_asset($no_asset, array $data)
    {
        $no_asset = trim((string) $no_asset);
        if ($no_asset === '' || empty($data)) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->where('no_asset', $no_asset)->update($this->table, $data);
    }

    public function no_asset_exists($no_asset)
    {
        $no_asset = trim((string) $no_asset);
        if ($no_asset === '') {
            return false;
        }
        return $this->db->where('no_asset', $no_asset)->count_all_results($this->table) > 0;
    }

    public function generate_from_permohonan_masuk($id_permohonan_masuk)
    {
        $this->load->model('Permohonan_masuk_model');

        $result = ['created' => 0, 'skipped' => 0, 'detail' => []];

        $permohonan = $this->Permohonan_masuk_model->get_by_id($id_permohonan_masuk);

        if (!$permohonan
            || $permohonan->status !== 'Disetujui'
            || $permohonan->jenis_permohonan !== 'Masuk Baru') {
            return $result;
        }

        $daftar_unit = $this->Permohonan_masuk_model->get_gse_list($id_permohonan_masuk);

        foreach ($daftar_unit as $unit) {
            $no_asset = trim((string) $unit->no_asset);
            $nama_gse = $unit->nama_gse;

            if ($this->no_asset_exists($no_asset)) {
            if ($permohonan->jenis_permohonan === 'Perbaikan') {
                $this->db->where('no_asset', $no_asset)->update($this->table, [
                    'status'       => 'Aktif',
                    'nomor_rangka' => $unit->nomor_rangka,
                    'nomor_mesin'  => $unit->nomor_mesin,
                    'sticker_ap'   => $unit->sticker_ap,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                $result['created']++;
                $result['detail'][] = $nama_gse . ' (' . $no_asset . ') - status diaktifkan kembali setelah perbaikan';
            } else {
                $result['skipped']++;
                $result['detail'][] = $nama_gse . ' (' . $no_asset . ') - dilewati, No. Asset sudah terdaftar';
            }
            continue;
}

            $data = [
                'nama_gse'         => $nama_gse,
                'manufacture_type' => $unit->manufacture_type,
                'no_asset'         => $unit->no_asset,
                'nomor_rangka'     => $unit->nomor_rangka,
                'nomor_mesin'      => $unit->nomor_mesin,
                'sticker_ap'       => $unit->sticker_ap,
                'id_airline'       => $permohonan->id_airline,
                'status'           => 'Aktif',
            ];

            $this->insert($data);
            $result['created']++;
            $result['detail'][] = $nama_gse . ' (' . $no_asset . ') - berhasil dibuat';
        }

        return $result;
    }

    /**
     * Generate/sinkronkan 1 unit GSE ke tabel master `gse` segera setelah
     * unit itu SENDIRI selesai seluruh tahap verifikasi — tidak perlu
     * menunggu unit lain dalam permohonan yang sama.
     *
     * Dipanggil dari Permohonan_masuk_model::setujui_item() begitu
     * tahap_saat_ini unit tersebut mencapai 'selesai'.
     */
    public function generate_dari_item($id_detail)
    {
        $this->load->model('Permohonan_masuk_model');

        $result = ['status' => 'skipped', 'pesan' => ''];

        $unit = $this->Permohonan_masuk_model->get_detail_by_id($id_detail);
        if (!$unit) {
            return $result;
        }

        // Item yang ditolak tidak pernah masuk ke Data GSE
        if (($unit->status_item ?? null) === 'Ditolak') {
            return $result;
        }

        $permohonan = $this->Permohonan_masuk_model->get_by_id($unit->id_permohonan_masuk);
        if (!$permohonan) {
            return $result;
        }

        $no_asset = trim((string) $unit->no_asset);
        $nama_gse = $unit->nama_gse;

        switch ($permohonan->jenis_permohonan) {

            case 'Masuk Baru':
                if ($no_asset !== '' && $this->no_asset_exists($no_asset)) {
                    $result = ['status' => 'skipped', 'pesan' => $nama_gse . ' (' . $no_asset . ') - dilewati, No. Asset sudah terdaftar'];
                    break;
                }
                $data = [
                    'nama_gse'         => $nama_gse,
                    'manufacture_type' => $unit->manufacture_type,
                    'no_asset'         => $unit->no_asset,
                    'nomor_rangka'     => $unit->nomor_rangka,
                    'nomor_mesin'      => $unit->nomor_mesin,
                    'sticker_ap'       => $unit->sticker_ap,
                    'id_airline'       => $permohonan->id_airline,
                    'status'           => 'Aktif',
                    'masa_mulai'       => $unit->masa_mulai ?? null,
                    'masa_selesai'     => $unit->masa_selesai ?? null,
                ];
                $this->insert($data);
                $result = ['status' => 'created', 'pesan' => $nama_gse . ' (' . $no_asset . ') - berhasil dibuat'];
                break;

            case 'Perbaikan':
                if ($no_asset === '') {
                    break;
                }
                $this->db->where('no_asset', $no_asset)->update($this->table, [
                    'status'       => 'Aktif',
                    'nomor_rangka' => $unit->nomor_rangka,
                    'nomor_mesin'  => $unit->nomor_mesin,
                    'sticker_ap'   => $unit->sticker_ap,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                $result = ['status' => 'updated', 'pesan' => $nama_gse . ' (' . $no_asset . ') - status diaktifkan kembali setelah perbaikan'];
                break;

            case 'Perbaruan Kontrak':
                if ($no_asset === '') {
                    break;
                }
                $this->db->where('no_asset', $no_asset)->update($this->table, [
                    'status'       => 'Aktif',
                    'nomor_rangka' => $unit->nomor_rangka,
                    'nomor_mesin'  => $unit->nomor_mesin,
                    'sticker_ap'   => $unit->sticker_ap,
                    'masa_mulai'   => $unit->masa_mulai ?? null,
                    'masa_selesai' => $unit->masa_selesai ?? null,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
                $result = ['status' => 'updated', 'pesan' => $nama_gse . ' (' . $no_asset . ') - kontrak berhasil diperbarui'];
                break;
        }

        return $result;
    }

    public function nonaktifkan($no_asset)
    {
        if (empty($no_asset)) {
            return false;
        }
        return $this->db->where('no_asset', $no_asset)->update($this->table, ['status' => 'Tidak Aktif']);
    }

    public function get_tidak_aktif_by_airline($id_airline)
    {
        return $this->db
            ->select('g.*, a.nama_airline')
            ->from('gse g')
            ->join('airlines a', 'a.id_airline = g.id_airline', 'left')
            ->where('g.status', 'Tidak Aktif')
            ->where('g.id_airline', $id_airline)
            ->order_by('g.nama_gse', 'ASC')
            ->get()->result();
    }

    public function cek_status_kontrak($masa_selesai)
    {
        if (empty($masa_selesai)) return 'tidak_ada';

        $now     = time();
        $selesai = strtotime($masa_selesai);

        if ($selesai === false) return 'tidak_ada';

        if ($now > $selesai) return 'berakhir';

        $sisa_hari = (int) ceil(($selesai - $now) / 86400);

        if ($sisa_hari <= 30) return 'segera_berakhir';

        return 'aktif';
    }

    public function get_by_ids(array $ids)
    {
        if (empty($ids)) return [];
        return $this->db->where_in('id_gse', $ids)->get('gse')->result();
    }

    public function tandai_proses_perbaruan($id_gse)
    {
        if (is_array($id_gse)) {
            return $this->db->where_in('id_gse', $id_gse)->update('gse', [
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        return $this->db->where('id_gse', $id_gse)->update('gse', [
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
    
}