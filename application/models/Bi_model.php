<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bi_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /** KPI 1: Rasio persetujuan per airline (berdasarkan permohonan_masuk) */
    public function rasio_persetujuan_per_airline()
    {
        return $this->db->query("
            SELECT 
                a.nama_airline,
                COUNT(*) AS total_permohonan,
                SUM(CASE WHEN pm.status = 'Disetujui' THEN 1 ELSE 0 END) AS disetujui,
                SUM(CASE WHEN pm.status = 'Ditolak' THEN 1 ELSE 0 END) AS ditolak,
                ROUND(
                    SUM(CASE WHEN pm.status = 'Disetujui' THEN 1 ELSE 0 END)::numeric 
                    / NULLIF(COUNT(*), 0) * 100, 2
                ) AS persentase_disetujui
            FROM permohonan_masuk pm
            JOIN airlines a ON pm.id_airline = a.id_airline
            GROUP BY a.nama_airline
            ORDER BY persentase_disetujui DESC
        ")->result();
    }

    /**
     * KPI 2: Waktu rata-rata proses persetujuan per jenis permohonan.
     *
     * CATATAN REVISI: Sejak alur approval dipindah ke level per-unit GSE
     * (detail_permohonan_masuk), kolom header permohonan_masuk.verifikasi_security_at
     * sudah tidak lagi diisi oleh proses approval per-item. KPI ini sekarang
     * dihitung dari waktu submit permohonan (pm.created_at) sampai unit GSE
     * selesai melewati tahap Security (dpm.security_at), per unit item.
     */
    public function waktu_rata_rata_proses()
    {
        return $this->db->query("
            SELECT 
                pm.jenis_permohonan,
                ROUND(AVG(EXTRACT(EPOCH FROM (dpm.security_at - pm.created_at)) / 3600)::numeric, 2) AS rata_rata_jam,
                ROUND(MIN(EXTRACT(EPOCH FROM (dpm.security_at - pm.created_at)) / 3600)::numeric, 2) AS tercepat_jam,
                ROUND(MAX(EXTRACT(EPOCH FROM (dpm.security_at - pm.created_at)) / 3600)::numeric, 2) AS terlama_jam
            FROM detail_permohonan_masuk dpm
            JOIN permohonan_masuk pm ON pm.id_permohonan_masuk = dpm.id_permohonan_masuk
            WHERE dpm.status_item = 'Selesai' AND dpm.security_at IS NOT NULL
            GROUP BY pm.jenis_permohonan
        ")->result();
    }

    /** KPI 3: Utilisasi GSE (frekuensi pemakaian per unit) */
    public function utilisasi_gse($limit = 20)
    {
        return $this->db->query("
            SELECT 
                g.nama_gse,
                g.manufacture_type,
                g.status,
                COUNT(dpm.id) AS total_pemakaian,
                MAX(dpm.created_at) AS terakhir_dipakai
            FROM gse g
            LEFT JOIN detail_permohonan_masuk dpm ON dpm.nama_gse = g.nama_gse
            GROUP BY g.nama_gse, g.manufacture_type, g.status
            ORDER BY total_pemakaian DESC
            LIMIT ?
        ", [$limit])->result();
    }

    /** KPI 4: Tren jumlah permohonan per bulan */
    public function tren_permohonan_bulanan()
    {
        return $this->db->query("
            SELECT 
                TO_CHAR(created_at, 'YYYY-MM') AS bulan,
                COUNT(*) AS total_permohonan,
                SUM(CASE WHEN jenis_permohonan = 'Masuk Baru' THEN 1 ELSE 0 END) AS masuk_baru,
                SUM(CASE WHEN jenis_permohonan = 'Perbaikan' THEN 1 ELSE 0 END) AS perbaikan
            FROM permohonan_masuk
            GROUP BY TO_CHAR(created_at, 'YYYY-MM')
            ORDER BY bulan ASC
        ")->result();
    }

    /**
     * KPI 5: Bottleneck waktu antar tahap verifikasi.
     *
     * CATATAN REVISI: dihitung dari detail_permohonan_masuk (per unit GSE),
     * karena timestamp per tahap (operasi_at/equipment_at/sales_at/security_at)
     * sekarang dicatat di level item, bukan lagi di header permohonan_masuk.
     */
    public function bottleneck_verifikasi()
    {
        return $this->db->query("
            SELECT 
                ROUND(AVG(EXTRACT(EPOCH FROM (equipment_at - operasi_at))/3600)::numeric, 2) AS operasi_ke_equipment,
                ROUND(AVG(EXTRACT(EPOCH FROM (sales_at - equipment_at))/3600)::numeric, 2) AS equipment_ke_sales,
                ROUND(AVG(EXTRACT(EPOCH FROM (security_at - sales_at))/3600)::numeric, 2) AS sales_ke_security
            FROM detail_permohonan_masuk
            WHERE security_at IS NOT NULL
        ")->row();
    }
}