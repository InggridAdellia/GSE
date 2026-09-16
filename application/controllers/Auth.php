<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Ids_model');
    }

    public function index()
    {
        // Kalau sudah login, langsung ke dashboard
        if ($this->session->userdata('login')) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    public function login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $ip       = $this->input->ip_address();

        // ── 0. Cek SQL Injection di input username & password ───────────────
        if ($this->Ids_model->deteksi_sql_injection($username) ||
            $this->Ids_model->deteksi_sql_injection($password)) {

            $this->Ids_model->catat_log($username, 'failed',
                "Terdeteksi pola SQL Injection dari IP {$ip}");

            $this->session->set_flashdata('error', 'Input tidak valid.');
            redirect('auth');
            return;
        }

        // ── 1. Cek apakah IP sedang diblokir ─────────────────────────────────
        $blokir = $this->Ids_model->ip_diblokir();
        if ($blokir) {
            $sisa = $blokir->blocked_until
                ? 'hingga ' . date('H:i', strtotime($blokir->blocked_until))
                : 'secara permanen';

            $this->Ids_model->catat_log($username, 'failed',
                "IP diblokir — akses ditolak {$sisa}");

            $this->session->set_flashdata('error',
                "Akses ditolak. IP Anda diblokir {$sisa} karena terlalu banyak percobaan login gagal.");
            redirect('auth');
            return;
        }

        // ── 2. Validasi username & password ──────────────────────────────────
        $user = $this->User_model->cek_login($username, $password);

        if (!$user) {
            $cek_password_saja = $this->User_model->cek_password_saja($username, $password);
            if ($cek_password_saja) {
                $this->Ids_model->catat_log($username, 'failed', "Percobaan login akun nonaktif dari IP {$ip}");
                $this->session->set_flashdata('error', 'Akun Anda sedang nonaktif. Hubungi Admin untuk mengaktifkan kembali.');
                redirect('auth');
                return;
            }
        }

        if ($user) {
            // ── LOGIN BERHASIL ────────────────────────────────────────────────
            $this->Ids_model->catat_log($username, 'success', 'Login berhasil');

            $this->session->set_userdata([
                'id_user'    => $user->id_user,
                'username'   => $user->username,
                'nama'       => $user->nama,
                'role'       => $user->role,
                'id_airline' => $user->id_airline,
                'login'      => TRUE,
            ]);

            redirect('dashboard');

        } else {
            // ── LOGIN GAGAL ───────────────────────────────────────────────────
            $this->Ids_model->catat_log($username, 'failed',
                "Password salah dari IP {$ip}");

            // Analisis ancaman & auto-block jika perlu
            $ancaman = $this->Ids_model->analisis_setelah_gagal($username);

            if (!empty($ancaman)) {
                // Ada ancaman terdeteksi
                $level_tertinggi = 'RENDAH';
                foreach ($ancaman as $a) {
                    if ($a['level'] === 'TINGGI') {
                        $level_tertinggi = 'TINGGI';
                        break;
                    }
                    if ($a['level'] === 'SEDANG') {
                        $level_tertinggi = 'SEDANG';
                    }
                }

                if ($level_tertinggi === 'TINGGI') {
                    $this->session->set_flashdata('error',
                        'Akses Anda telah diblokir karena terlalu banyak percobaan login gagal. Coba lagi dalam 30 menit.');
                    redirect('auth');
                    return;
                }
            }

            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth');
        }
    }

    public function logout()
    {
        $username = $this->session->userdata('username');
        if ($username) {
            $this->Ids_model->catat_log($username, 'success', 'Logout');
        }
        $this->session->sess_destroy();
        redirect('auth');
    }
}