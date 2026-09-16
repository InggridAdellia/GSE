<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kerusakan_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->ensure_table_exists();
    }

    /**
     * Memastikan tabel laporan_kerusakan sudah ada di database.
     */
    private function ensure_table_exists()
    {
        /** @var CI_DB_query_builder $db */
        $db = $this->db;

        if (!$db->table_exists('laporan_kerusakan')) {
            $sql = "CREATE TABLE IF NOT EXISTS public.laporan_kerusakan (
                id SERIAL PRIMARY KEY,
                id_gse INTEGER NULL,
                sticker_ap VARCHAR(50) NOT NULL,
                nama_gse VARCHAR(100) NOT NULL,
                id_airline INTEGER NULL,
                tingkat_kerusakan VARCHAR(50) DEFAULT 'Peringatan Sedang' NOT NULL,
                keterangan TEXT NOT NULL,
                foto_kerusakan VARCHAR(255) NULL,
                status VARCHAR(30) DEFAULT 'Baru' NOT NULL,
                tindakan_perbaikan TEXT NULL,
                dilaporkan_oleh INTEGER NULL,
                nama_pelapor VARCHAR(100) NULL,
                role_pelapor VARCHAR(50) NULL,
                created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW() NOT NULL,
                ditindak_oleh INTEGER NULL,
                nama_penindak VARCHAR(100) NULL,
                ditindak_at TIMESTAMP WITHOUT TIME ZONE NULL
            )";
            $db->query($sql);
        } else {
            $columns_to_check = [
                'id_gse'              => 'INTEGER NULL',
                'sticker_ap'          => 'VARCHAR(50) NULL',
                'nama_gse'            => 'VARCHAR(100) NULL',
                'id_airline'          => 'INTEGER NULL',
                'tingkat_kerusakan'   => "VARCHAR(50) DEFAULT 'Peringatan Sedang'",
                'keterangan'          => 'TEXT NULL',
                'foto_kerusakan'      => 'VARCHAR(255) NULL',
                'status'              => "VARCHAR(30) DEFAULT 'Baru'",
                'tindakan_perbaikan'  => 'TEXT NULL',
                'kebutuhan_sparepart' => 'TEXT NULL',
                'id_permohonan_masuk' => 'INTEGER NULL',
                'status_sparepart'    => "VARCHAR(50) DEFAULT 'Belum Diajukan'",
                'dilaporkan_oleh'     => 'INTEGER NULL',
                'nama_pelapor'        => 'VARCHAR(100) NULL',
                'role_pelapor'        => 'VARCHAR(50) NULL',
                'ditindak_oleh'       => 'INTEGER NULL',
                'nama_penindak'       => 'VARCHAR(100) NULL',
                'ditindak_at'         => 'TIMESTAMP WITHOUT TIME ZONE NULL',
                'created_at'          => 'TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()',
            ];

            foreach ($columns_to_check as $col_name => $col_type) {
                if (!$db->field_exists($col_name, 'laporan_kerusakan')) {
                    $db->query("ALTER TABLE public.laporan_kerusakan ADD COLUMN IF NOT EXISTS {$col_name} {$col_type}");
                }
            }
        }
    }

    /**
     * Mengambil daftar laporan kerusakan dengan filter opsional.
     */
    public function get_all($filter = [], $limit = null, $offset = null)
    {
        /** @var CI_DB_query_builder $db */
        $db = $this->db;

        $db->select('lk.*, a.nama_airline, u.nama as user_pelapor, un.nama as user_penindak')
           ->from('laporan_kerusakan lk')
           ->join('airlines a', 'a.id_airline = lk.id_airline', 'left')
           ->join('users u', 'u.id_user = lk.dilaporkan_oleh', 'left')
           ->join('users un', 'un.id_user = lk.ditindak_oleh', 'left');

        if (!empty($filter['status'])) {
            $db->where('lk.status', $filter['status']);
        }

        if (!empty($filter['id_airline'])) {
            $db->where('lk.id_airline', $filter['id_airline']);
        }

        if (!empty($filter['tingkat_kerusakan'])) {
            $db->where('lk.tingkat_kerusakan', $filter['tingkat_kerusakan']);
        }

        if (!empty($filter['search'])) {
            $search = $filter['search'];
            $db->group_start();
            $db->like('lk.sticker_ap', $search);
            $db->or_like('lk.nama_gse', $search);
            $db->or_like('lk.keterangan', $search);
            $db->or_like('a.nama_airline', $search);
            $db->group_end();
        }

        $db->order_by('lk.created_at', 'DESC');

        if ($limit !== null) {
            $db->limit($limit, $offset);
        }

        return $db->get()->result();
    }

    /**
     * Mengambil detail laporan berdasarkan ID.
     */
    public function get_by_id($id)
    {
        /** @var CI_DB_query_builder $db */
        $db = $this->db;

        return $db->select('lk.*, a.nama_airline, u.nama as user_pelapor, un.nama as user_penindak')
                  ->from('laporan_kerusakan lk')
                  ->join('airlines a', 'a.id_airline = lk.id_airline', 'left')
                  ->join('users u', 'u.id_user = lk.dilaporkan_oleh', 'left')
                  ->join('users un', 'un.id_user = lk.ditindak_oleh', 'left')
                  ->where('lk.id', $id)
                  ->get()->row();
    }

    /**
     * Mengambil peringatan/laporan aktif untuk nomor stiker GSE tertentu.
     */
    public function get_active_warning_by_sticker($sticker_ap)
    {
        /** @var CI_DB_query_builder $db */
        $db = $this->db;

        return $db->select('lk.*, a.nama_airline')
                  ->from('laporan_kerusakan lk')
                  ->join('airlines a', 'a.id_airline = lk.id_airline', 'left')
                  ->where('lk.sticker_ap', $sticker_ap)
                  ->where('lk.status', 'Baru')
                  ->order_by('lk.created_at', 'DESC')
                  ->get()->result();
    }

    /**
     * Menambahkan laporan kerusakan baru.
     */
    public function insert_laporan($data)
    {
        /** @var CI_DB_query_builder $db */
        $db = $this->db;

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['status'])) {
            $data['status'] = 'Baru';
        }

        $db->insert('laporan_kerusakan', $data);
        return $db->insert_id();
    }

    /**
     * Menindaklanjuti laporan kerusakan.
     */
    public function tindak_laporan($id, $data)
    {
        /** @var CI_DB_query_builder $db */
        $db = $this->db;

        $data['ditindak_at'] = date('Y-m-d H:i:s');
        return $db->where('id', $id)->update('laporan_kerusakan', $data);
    }

    /**
     * Hapus laporan kerusakan.
     */
    public function delete_laporan($id)
    {
        /** @var CI_DB_query_builder $db */
        $db = $this->db;
        return $db->where('id', $id)->delete('laporan_kerusakan');
    }

    /**
     * Ringkasan statistik laporan kerusakan.
     */
    public function get_count_summary($id_airline = null)
    {
        /** @var CI_DB_query_builder $db */
        $db = $this->db;

        $db->select("
            COUNT(*) as total,
            COUNT(CASE WHEN status = 'Baru' THEN 1 END) as baru,
            COUNT(CASE WHEN status = 'Sudah Ditindak' THEN 1 END) as sudah_ditindak,
            COUNT(CASE WHEN tingkat_kerusakan = 'Bahaya / Stop Operasi' AND status = 'Baru' THEN 1 END) as kritis_aktif
        ")->from('laporan_kerusakan');

        if (!empty($id_airline)) {
            $db->where('id_airline', $id_airline);
        }

        return $db->get()->row();
    }
}
