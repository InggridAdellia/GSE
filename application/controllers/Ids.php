<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ids extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ids_model');

        // Hanya admin yang boleh akses halaman IDS
        if ($this->user_role !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('dashboard');
        }
    }

            // Di Ids controller
        public function statistik_mingguan()
        {
            $data = $this->db->query("
                SELECT 
                    DATE(created_at) as tanggal,
                    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as berhasil,
                    SUM(CASE WHEN status = 'failed'  THEN 1 ELSE 0 END) as gagal
                FROM login_log
                WHERE created_at >= NOW() - INTERVAL '7 days'
                GROUP BY DATE(created_at)
                ORDER BY tanggal ASC
            ")->result();

            echo json_encode($data);
        }

        

    public function index()
    {
        $filter_aktivitas = [
            'username'       => $this->input->get('username'),
            'tanggal_dari'   => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
        ];

        $data['stat']       = $this->Ids_model->statistik_hari_ini();
        $data['log']        = $this->Ids_model->get_log_terbaru(50);
        $data['blocked']    = $this->Ids_model->get_blocked_ip();
        $data['mencurigai'] = $this->Ids_model->get_ip_mencurigakan();
        $data['ancaman']    = $this->Ids_model->get_ancaman_terbaru(50);
        $data['aktivitas']  = $this->Ids_model->get_log_aktivitas(50, $filter_aktivitas);
        $data['filter']     = $filter_aktivitas;

        $this->load->view('template/header');
        $this->load->view('ids/index', $data);
        $this->load->view('template/footer');
    }

    public function unblock($id)
    {
        $this->Ids_model->unblock_ip($id);
        $this->session->set_flashdata('success', 'IP berhasil di-unblock.');
        redirect('ids');
    }

    public function block_manual($ip)
    {
        $ip = urldecode($ip);
        $this->Ids_model->block_ip_manual($ip, 'Diblokir manual oleh admin: ' . $this->user_nama);
        $this->session->set_flashdata('success', "IP {$ip} berhasil diblokir permanen.");
        redirect('ids');
    }
}