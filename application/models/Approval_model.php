<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Permohonan_masuk_model');
        $this->load->model('Permohonan_keluar_model');
    }
    

    public function get_by_role($role)
    {
        $masuk  = [];
        $keluar = [];

        $tahap_map = [
            'unit_operasi'     => 'operasi',
            'unit_equipment'   => 'equipment',
            'unit_sales'       => 'sales',
            'unit_security'    => 'security',
        ];

        if ($role === 'admin') {
            $masuk = $this->db
                ->select('d.*, pm.nomor_permohonan, pm.nomor_surat, pm.asal_instansi,
                        pm.keterangan, pm.jenis_permohonan, pm.tanggal_masuk,
                        pm.id_permohonan_masuk, pm.file_dispo as permohonan_file_dispo')
                ->from('detail_permohonan_masuk d')
                ->join('permohonan_masuk pm', 'pm.id_permohonan_masuk = d.id_permohonan_masuk')
                ->where('d.tahap_saat_ini IS NOT NULL')
                ->where('d.status_item IS NULL', null, false)
                ->order_by('d.id', 'ASC')
                ->get()->result();

            $keluar = $this->db
                ->select('d.*, pk.nomor_permohonan, pk.nomor_surat, pk.asal_instansi,
                        pk.keterangan, pk.jenis_permohonan, pk.tanggal_keluar,
                        pk.id_permohonan_keluar, pk.file_dispo as permohonan_file_dispo')
                ->from('detail_permohonan_keluar d')
                ->join('permohonan_keluar pk', 'pk.id_permohonan_keluar = d.id_permohonan_keluar')
                ->where('d.tahap_saat_ini IS NOT NULL')
                ->where("(d.status_item IS NULL OR (d.status_item NOT IN ('Disetujui', 'Selesai')))")
                ->order_by('d.id', 'ASC')
                ->get()->result();

        } elseif (isset($tahap_map[$role])) {
            $tahap = $tahap_map[$role];

            $masuk = $this->db
                ->select('d.*, pm.nomor_permohonan, pm.nomor_surat, pm.asal_instansi,
                        pm.keterangan, pm.jenis_permohonan, pm.tanggal_masuk,
                        pm.id_permohonan_masuk, pm.file_dispo as permohonan_file_dispo')
                ->from('detail_permohonan_masuk d')
                ->join('permohonan_masuk pm', 'pm.id_permohonan_masuk = d.id_permohonan_masuk')
                ->where('d.tahap_saat_ini', $tahap)
                ->where('d.status_item IS NULL', null, false)
                ->order_by('d.id', 'ASC')
                ->get()->result();

            $keluar = $this->db
                ->select('d.*, pk.nomor_permohonan, pk.nomor_surat, pk.asal_instansi,
                        pk.keterangan, pk.jenis_permohonan, pk.tanggal_keluar,
                        pk.id_permohonan_keluar, pk.file_dispo as permohonan_file_dispo')
                ->from('detail_permohonan_keluar d')
                ->join('permohonan_keluar pk', 'pk.id_permohonan_keluar = d.id_permohonan_keluar')
                ->where('d.tahap_saat_ini', $tahap)
                ->where("(d.status_item IS NULL OR (d.status_item NOT IN ('Disetujui', 'Selesai')))")
                ->order_by('d.id', 'ASC')
                ->get()->result();
        }

        return ['masuk' => $masuk, 'keluar' => $keluar];
    }

    public function setujui($id, $role, $id_user)
    {
        $now = date('Y-m-d H:i:s');
        $permohonan = $this->Permohonan_masuk_model->get_by_id($id);
        $is_sparepart = ($permohonan && in_array($permohonan->jenis_permohonan, ['Masuk Perbaikan Sparepart', 'Sparepart']));

        switch ($role) {
            case 'unit_operasi':
                $next_status = $is_sparepart ? 'Menunggu Verifikasi Security' : 'Menunggu Verifikasi Equipment';
                $data = [
                    'verifikasi_operasi'      => 'Disetujui',
                    'verifikasi_operasi_at'   => $now,
                    'verifikasi_operasi_oleh' => $id_user,
                    'status'                  => $next_status,
                ];
                break;
            case 'unit_equipment':
                $data = [
                    'verifikasi_equipment'      => 'Disetujui',
                    'verifikasi_equipment_at'   => $now,
                    'verifikasi_equipment_oleh' => $id_user,
                    'status'                    => 'Menunggu Verifikasi Sales',
                ];
                break;
            case 'unit_sales':
                $data = [
                    'verifikasi_sales'      => 'Disetujui',
                    'verifikasi_sales_at'   => $now,
                    'verifikasi_sales_oleh' => $id_user,
                    'status'               => 'Menunggu Verifikasi Security',
                ];
                break;
            case 'unit_security':
                $data = [
                    'verifikasi_security'      => 'Disetujui',
                    'verifikasi_security_at'   => $now,
                    'verifikasi_security_oleh' => $id_user,
                    'status'                   => 'Disetujui',
                ];
                break;
            case 'admin':
                if (!$permohonan) return false;
                if ($permohonan->status === 'Menunggu Verifikasi Operasi') {
                    $next_status = $is_sparepart ? 'Menunggu Verifikasi Security' : 'Menunggu Verifikasi Equipment';
                    $data = ['verifikasi_operasi'=>'Disetujui','verifikasi_operasi_at'=>$now,'verifikasi_operasi_oleh'=>$id_user,'status'=>$next_status];
                } elseif ($permohonan->status === 'Menunggu Verifikasi Equipment') {
                    $data = ['verifikasi_equipment'=>'Disetujui','verifikasi_equipment_at'=>$now,'verifikasi_equipment_oleh'=>$id_user,'status'=>'Menunggu Verifikasi Sales'];
                } elseif ($permohonan->status === 'Menunggu Verifikasi Sales') {
                    $data = ['verifikasi_sales'=>'Disetujui','verifikasi_sales_at'=>$now,'verifikasi_sales_oleh'=>$id_user,'status'=>'Menunggu Verifikasi Security'];
                } elseif ($permohonan->status === 'Menunggu Verifikasi Security') {
                    $data = ['verifikasi_security'=>'Disetujui','verifikasi_security_at'=>$now,'verifikasi_security_oleh'=>$id_user,'status'=>'Disetujui'];
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }

        $updated = $this->Permohonan_masuk_model->update($id, $data);
        if (isset($data['status']) && $data['status'] === 'Disetujui') {
            $this->load->model('Gse_model');
            $this->Gse_model->generate_from_permohonan_masuk($id);
        }

        return $updated;
    }

    public function setujui_item($id_detail, $tahap_sekarang, $id_user, $data_tambahan = [])
    {
        return $this->Permohonan_masuk_model->setujui_item($id_detail, $tahap_sekarang, $id_user, $data_tambahan);
    }

    public function setujui_item_keluar($id_detail, $tahap_sekarang, $id_user, $data_tambahan = [])
    {
        return $this->Permohonan_keluar_model->setujui_item($id_detail, $tahap_sekarang, $id_user, $data_tambahan);
    }

    public function tolak_item($id_detail, $id_user, $alasan)
    {
        return $this->Permohonan_masuk_model->tolak_item($id_detail, $id_user, $alasan);
    }

    public function tolak_item_keluar($id_detail, $id_user, $alasan)
    {
        return $this->Permohonan_keluar_model->tolak_item($id_detail, $id_user, $alasan);
    }

    public function setujui_keluar($id, $role, $id_user)
    {
        $now = date('Y-m-d H:i:s');

        switch ($role) {
            case 'unit_sales':
                $data = ['verifikasi_sales'=>'Disetujui','verifikasi_sales_at'=>$now,'verifikasi_sales_oleh'=>$id_user,'status'=>'Menunggu Verifikasi Operasi'];
                break;
            case 'unit_operasi':
                $data = ['verifikasi_operasi'=>'Disetujui','verifikasi_operasi_at'=>$now,'verifikasi_operasi_oleh'=>$id_user,'status'=>'Menunggu Verifikasi Security'];
                break;
            case 'unit_security':
                $data = ['verifikasi_security'=>'Disetujui','verifikasi_security_at'=>$now,'verifikasi_security_oleh'=>$id_user,'status'=>'Disetujui'];
                break;
            case 'admin':
                $permohonan = $this->Permohonan_keluar_model->get_by_id($id);
                if ($permohonan->status === 'Menunggu Verifikasi Sales') {
                    $data = ['verifikasi_sales'=>'Disetujui','verifikasi_sales_at'=>$now,'verifikasi_sales_oleh'=>$id_user,'status'=>'Menunggu Verifikasi Operasi'];
                } elseif ($permohonan->status === 'Menunggu Verifikasi Operasi') {
                    $data = ['verifikasi_operasi'=>'Disetujui','verifikasi_operasi_at'=>$now,'verifikasi_operasi_oleh'=>$id_user,'status'=>'Menunggu Verifikasi Security'];
                } elseif ($permohonan->status === 'Menunggu Verifikasi Security') {
                    $data = ['verifikasi_security'=>'Disetujui','verifikasi_security_at'=>$now,'verifikasi_security_oleh'=>$id_user,'status'=>'Disetujui'];
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }

        return $this->Permohonan_keluar_model->update($id, $data);
    }

    public function tolak($id, $role, $id_user, $alasan, $item_ditolak = [])
    {
        if (!empty($item_ditolak)) {
            foreach ($item_ditolak as $id_item) {
                $this->Permohonan_masuk_model->tolak_item((int)$id_item, $id_user, $alasan);
            }
        } else {
            $items = $this->Permohonan_masuk_model->get_gse_list($id);
            foreach ($items as $it) {
                $this->Permohonan_masuk_model->tolak_item((int)$it->id, $id_user, $alasan);
            }
        }

        $this->Permohonan_masuk_model->sinkron_status_induk($id);
        return true;
    }

    public function tolak_keluar($id, $role, $id_user, $alasan)
    {
        $items = $this->Permohonan_keluar_model->get_gse_list($id);
        foreach ($items as $it) {
            $this->Permohonan_keluar_model->tolak_item((int)$it->id, $id_user, $alasan);
        }

        $this->Permohonan_keluar_model->sinkron_status_induk($id);
        return true;
    }

    public function upload_ba_uji_laik($id, $id_user, $file_name)
    {
        $now  = date('Y-m-d H:i:s');
        $data = [
            'file_ba_uji_laik'          => $file_name,
            'status_kelayakan'          => 'Layak',
            'verifikasi_equipment'      => 'Disetujui',
            'verifikasi_equipment_at'   => $now,
            'verifikasi_equipment_oleh' => $id_user,
            'status'                    => 'Menunggu Verifikasi Sales',
        ];
        return $this->Permohonan_masuk_model->update($id, $data);
    }
}