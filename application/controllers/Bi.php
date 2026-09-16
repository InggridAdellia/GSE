<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bi extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Bi_model');

        if (!in_array($this->user_role, ['admin', 'unit_operasi'])) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['rasio_persetujuan'] = $this->Bi_model->rasio_persetujuan_per_airline();
        $data['waktu_proses']      = $this->Bi_model->waktu_rata_rata_proses();
        $data['utilisasi_gse']     = $this->Bi_model->utilisasi_gse();
        $data['tren_bulanan']      = $this->Bi_model->tren_permohonan_bulanan();
        $data['bottleneck']        = $this->Bi_model->bottleneck_verifikasi();

        $this->load->view('template/header');
        $this->load->view('bi/index', $data);
        $this->load->view('template/footer');
    }

    /** Endpoint JSON untuk grafik Chart.js (tren bulanan) */
    public function data_tren_bulanan()
    {
        $data = $this->Bi_model->tren_permohonan_bulanan();
        echo json_encode($data);
    }
}