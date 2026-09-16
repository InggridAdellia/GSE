<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ids_model
 * ---------
 * Model untuk fitur Intrusion Detection System (IDS) sederhana:
 * - Deteksi pola SQL Injection pada input login
 * - Pencatatan log login (berhasil/gagal) ke tabel login_log
 * - Pemblokiran IP otomatis/manual ke tabel blocked_ip
 * - Pencatatan & pembacaan ancaman terdeteksi ke tabel ids_ancaman
 * - Statistik & pencarian data untuk halaman monitoring IDS
 * - Pencatatan log aktivitas user ke tabel activity_log (dipakai di banyak controller)
 */
class Ids_model extends CI_Model {

    // Ambang batas percobaan login gagal dalam window waktu tertentu
    protected $window_menit       = 15; // rentang waktu analisis
    protected $ambang_sedang      = 3;  // >= sekian gagal -> level SEDANG (dicatat, belum diblokir)
    protected $ambang_tinggi      = 5;  // >= sekian gagal -> level TINGGI (auto-block)
    protected $durasi_block_menit = 30; // lama blokir otomatis (menit)

    // =====================================================
    // DETEKSI SQL INJECTION
    // =====================================================

    /**
     * Deteksi pola umum SQL Injection pada sebuah input string.
     * Kalau terdeteksi, otomatis dicatat ke tabel ids_ancaman.
     */
    public function deteksi_sql_injection($input)
    {
        if ($input === null || $input === '') {
            return false;
        }

        $pola = [
            '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i',
            '/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i',
            '/\bUNION\b.+\bSELECT\b/i',
            '/\bSELECT\b.+\bFROM\b/i',
            '/\bINSERT\s+INTO\b/i',
            '/\bDROP\s+TABLE\b/i',
            '/\bUPDATE\b.+\bSET\b/i',
            '/\bDELETE\s+FROM\b/i',
            '/\bOR\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i',
            '/\bAND\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i',
            '/\bxp_cmdshell\b/i',
            '/\bSLEEP\s*\(/i',
            '/\bBENCHMARK\s*\(/i',
        ];

        foreach ($pola as $regex) {
            if (preg_match($regex, $input)) {
                $this->catat_ancaman('sql_injection', $input);
                return true;
            }
        }

        return false;
    }

    /**
     * Simpan satu baris ancaman terdeteksi ke tabel ids_ancaman.
     */
    protected function catat_ancaman($tipe, $payload)
    {
        $this->db->insert('ids_ancaman', [
            'tipe'       => $tipe,
            'payload'    => substr((string) $payload, 0, 1000),
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // =====================================================
    // LOG LOGIN
    // =====================================================

    /**
     * Catat setiap percobaan login (berhasil/gagal) ke tabel login_log.
     */
    public function catat_log($username, $status, $keterangan = '')
    {
        return $this->db->insert('login_log', [
            'username'   => (string) $username,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'status'     => $status,
            'keterangan' => $keterangan,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // =====================================================
    // PEMBLOKIRAN IP
    // =====================================================

    /**
     * Cek apakah IP saat ini sedang diblokir (aktif & belum kedaluwarsa).
     * Return: row object kalau diblokir, atau FALSE kalau tidak.
     */
    public function ip_diblokir()
    {
        $ip = $this->input->ip_address();

        $row = $this->db
            ->where('ip_address', $ip)
            ->where('is_active', TRUE)
            ->group_start()
                ->where('blocked_until IS NULL', null, false)
                ->or_where('blocked_until >', date('Y-m-d H:i:s'))
            ->group_end()
            ->order_by('blocked_at', 'DESC')
            ->limit(1)
            ->get('blocked_ip')
            ->row();

        return $row ?: false;
    }

    /**
     * Blokir IP secara otomatis (dipanggil dari analisis_setelah_gagal).
     */
    protected function block_ip_otomatis($ip, $alasan)
    {
        $this->db->insert('blocked_ip', [
            'ip_address'    => $ip,
            'alasan'        => $alasan,
            'blocked_at'    => date('Y-m-d H:i:s'),
            'blocked_until' => date('Y-m-d H:i:s', strtotime('+' . $this->durasi_block_menit . ' minutes')),
            'is_active'     => TRUE,
        ]);
    }

    /**
     * Blokir IP secara manual & permanen oleh admin (dari halaman IDS).
     */
    public function block_ip_manual($ip, $alasan)
    {
        return $this->db->insert('blocked_ip', [
            'ip_address'    => $ip,
            'alasan'        => $alasan,
            'blocked_at'    => date('Y-m-d H:i:s'),
            'blocked_until' => null,
            'is_active'     => TRUE,
        ]);
    }

    /**
     * Cabut blokir sebuah IP (dari halaman IDS).
     */
    public function unblock_ip($id)
    {
        return $this->db->where('id', $id)->update('blocked_ip', ['is_active' => FALSE]);
    }

    // =====================================================
    // ANALISIS ANCAMAN SETELAH LOGIN GAGAL
    // =====================================================

    /**
     * Dipanggil setelah login gagal. Menghitung jumlah percobaan gagal
     * dari IP yang sama dalam beberapa menit terakhir, mencatat ancaman
     * credential_stuffing kalau perlu, dan auto-block IP kalau sudah
     * melewati ambang tinggi.
     *
     * Return: array daftar ancaman (masing-masing punya key 'level'),
     *         kosong kalau tidak ada ancaman terdeteksi.
     */
    public function analisis_setelah_gagal($username)
    {
        $ip    = $this->input->ip_address();
        $sejak = date('Y-m-d H:i:s', strtotime('-' . $this->window_menit . ' minutes'));

        $total_gagal = (int) $this->db
            ->where('ip_address', $ip)
            ->where('status', 'failed')
            ->where('created_at >=', $sejak)
            ->count_all_results('login_log');

        $hasil = [];

        if ($total_gagal >= $this->ambang_tinggi) {
            $alasan = "Auto-block: {$total_gagal}x percobaan login gagal dalam {$this->window_menit} menit terakhir";

            if (!$this->ip_diblokir()) {
                $this->block_ip_otomatis($ip, $alasan);
            }

            $this->catat_ancaman('credential_stuffing', "username: {$username}, {$total_gagal}x gagal dari IP {$ip}");

            $hasil[] = ['level' => 'TINGGI', 'pesan' => $alasan];

        } elseif ($total_gagal >= $this->ambang_sedang) {
            $this->catat_ancaman('credential_stuffing', "username: {$username}, {$total_gagal}x gagal dari IP {$ip}");

            $hasil[] = [
                'level' => 'SEDANG',
                'pesan' => "{$total_gagal}x percobaan login gagal dari IP {$ip} dalam {$this->window_menit} menit terakhir",
            ];
        }

        return $hasil;
    }

    // =====================================================
    // DATA UNTUK HALAMAN MONITORING IDS
    // =====================================================

    /**
     * Statistik login hari ini: jumlah berhasil, gagal, dan IP diblokir aktif.
     */
    public function statistik_hari_ini()
    {
        $hari_ini = date('Y-m-d');

        $success = (int) $this->db
            ->where('status', 'success')
            ->where('DATE(created_at) = ' . $this->db->escape($hari_ini), null, false)
            ->count_all_results('login_log');

        $failed = (int) $this->db
            ->where('status', 'failed')
            ->where('DATE(created_at) = ' . $this->db->escape($hari_ini), null, false)
            ->count_all_results('login_log');

        $blocked = (int) $this->db
            ->where('is_active', TRUE)
            ->group_start()
                ->where('blocked_until IS NULL', null, false)
                ->or_where('blocked_until >', date('Y-m-d H:i:s'))
            ->group_end()
            ->count_all_results('blocked_ip');

        return ['success' => $success, 'failed' => $failed, 'blocked' => $blocked];
    }

    /**
     * Log login terbaru (gabungan berhasil & gagal).
     */
    public function get_log_terbaru($limit = 50)
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('login_log')
            ->result();
    }

    /**
     * Daftar IP yang sedang aktif diblokir.
     */
    public function get_blocked_ip()
    {
        return $this->db
            ->where('is_active', TRUE)
            ->order_by('blocked_at', 'DESC')
            ->get('blocked_ip')
            ->result();
    }

    /**
     * IP dengan percobaan login gagal berulang hari ini, yang belum diblokir.
     */
    public function get_ip_mencurigakan()
    {
        $hari_ini = date('Y-m-d');

        $rows = $this->db
            ->select('ip_address, COUNT(*) as total_gagal, MAX(created_at) as terakhir', false)
            ->where('status', 'failed')
            ->where('DATE(created_at) = ' . $this->db->escape($hari_ini), null, false)
            ->group_by('ip_address')
            ->having('COUNT(*) >=', $this->ambang_sedang)
            ->order_by('total_gagal', 'DESC')
            ->get('login_log')
            ->result();

        if (empty($rows)) {
            return [];
        }

        $ip_terblokir = array_map(function ($b) {
            return $b->ip_address;
        }, $this->get_blocked_ip());

        return array_values(array_filter($rows, function ($r) use ($ip_terblokir) {
            return !in_array($r->ip_address, $ip_terblokir);
        }));
    }

    /**
     * Ancaman terbaru yang tercatat di ids_ancaman.
     */
    public function get_ancaman_terbaru($limit = 50)
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('ids_ancaman')
            ->result();
    }

    /**
     * Log aktivitas user (activity_log), dengan filter opsional:
     * username, tanggal_dari, tanggal_sampai.
     */
    public function get_log_aktivitas($limit = 50, $filter = [])
    {
        $this->db->from('activity_log');

        if (!empty($filter['username'])) {
            $this->db->like('username', $filter['username']);
        }
        if (!empty($filter['tanggal_dari'])) {
            $this->db->where('DATE(created_at) >= ' . $this->db->escape($filter['tanggal_dari']), null, false);
        }
        if (!empty($filter['tanggal_sampai'])) {
            $this->db->where('DATE(created_at) <= ' . $this->db->escape($filter['tanggal_sampai']), null, false);
        }

        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function catat_aktivitas($modul, $aksi, $keterangan, $status = 'success')
    {
        return $this->db->insert('activity_log', [
            'id_user'    => $this->session->userdata('id_user'),
            'username'   => $this->session->userdata('username'),
            'role'       => $this->session->userdata('role'),
            'ip_address' => $this->input->ip_address(),
            'modul'      => $modul,
            'aksi'       => $aksi,
            'keterangan' => $keterangan,
            'status'     => $status,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}