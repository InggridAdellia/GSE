<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gse_model');
        $this->load->model('Permohonan_masuk_model');
        $this->load->model('Permohonan_keluar_model');
        $this->load->model('Approval_model');
    }

     public function index()
    {
        if ($this->user_role === 'ground_handling') {
            $id_airline = $this->user_airline;
 
            $data['total_gse']     = count($this->Gse_model->get_all_by_airline($id_airline));
            $data['total_masuk']   = $this->Permohonan_masuk_model->count_by_airline($id_airline);
            $data['total_keluar']  = $this->Permohonan_keluar_model->count_by_airline($id_airline);
            $data['total_pending'] = count($this->Permohonan_masuk_model->get_pending_by_airline($id_airline))
                                   + count($this->Permohonan_keluar_model->get_pending_by_airline($id_airline));
        } elseif (in_array($this->user_role, ['unit_operasi', 'unit_equipment', 'unit_sales', 'unit_security'])) {
            $antrean = $this->Approval_model->get_by_role($this->user_role);

            $data['total_gse']     = $this->db->count_all('gse');
            $data['total_masuk']   = $this->Permohonan_masuk_model->count_all();
            $data['total_keluar']  = $this->db->count_all('permohonan_keluar');
            $data['total_pending'] = count($antrean['masuk']) + count($antrean['keluar']);
        } else {
            $data['total_gse']     = $this->db->count_all('gse');
            $data['total_masuk']   = $this->Permohonan_masuk_model->count_all();
            $data['total_keluar']  = $this->db->count_all('permohonan_keluar');
            $data['total_pending'] = count($this->Permohonan_masuk_model->get_pending())
                                   + count($this->Permohonan_keluar_model->get_pending());
        }
 
        $semua_gse = ($this->user_role === 'ground_handling')
            ? $this->Gse_model->get_all_by_airline($this->user_airline)
            : $this->Gse_model->get_all();

        $jumlah_segera_berakhir = 0;
        $jumlah_berakhir        = 0;
        foreach ($semua_gse as $g) {
            $st = $this->Gse_model->cek_status_kontrak($g->masa_selesai ?? null);
            if ($st === 'segera_berakhir') $jumlah_segera_berakhir++;
            if ($st === 'berakhir') $jumlah_berakhir++;
        }
        $data['jumlah_segera_berakhir'] = $jumlah_segera_berakhir;
        $data['jumlah_berakhir']        = $jumlah_berakhir;
        $data['role']                   = $this->user_role;

        $this->load->view('template/header');
        $this->load->view('dashboard/index', $data);
        $this->load->view('template/footer');
    }
 
}