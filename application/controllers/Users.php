<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Airline_model');
        $this->load->model('Ids_model');

        if ($this->user_role !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman Kelola User.');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['users'] = $this->User_model->get_all();

        $this->load->view('template/header');
        $this->load->view('users/index', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {
        $data['airlines'] = $this->Airline_model->get_all();

        $this->load->view('template/header');
        $this->load->view('users/tambah', $data);
        $this->load->view('template/footer');
    }

    public function simpan()
    {
        $username   = trim($this->input->post('username'));
        $password   = $this->input->post('password');
        $nama       = trim($this->input->post('nama'));
        $role       = $this->input->post('role');
        $id_airline = $this->input->post('id_airline');

        $role_valid = ['admin', 'ground_handling', 'unit_operasi', 'unit_equipment', 'unit_sales', 'unit_security'];

        if (empty($username) || empty($password) || empty($nama) || empty($role)) {
            $this->session->set_flashdata('error', 'Semua field wajib diisi.');
            redirect('users/tambah');
        }

        if (!in_array($role, $role_valid)) {
            $this->session->set_flashdata('error', 'Role tidak valid.');
            redirect('users/tambah');
        }

        if (!preg_match('/^(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/', $password)) {
            $this->session->set_flashdata('error', 'Password minimal 8 karakter dan harus mengandung angka serta tanda baca (contoh: Admin@123).');
            redirect('users/tambah');
        }

        if ($this->User_model->get_by_username($username)) {
            $this->session->set_flashdata('error', 'Username "' . htmlspecialchars($username) . '" sudah dipakai. Pilih username lain.');
            redirect('users/tambah');
        }

        if ($role === 'ground_handling') {
            if (empty($id_airline)) {
                $this->session->set_flashdata('error', 'Role GH Airline wajib memilih airline.');
                redirect('users/tambah');
            }
        } else {
            $id_airline = null;
        }

        $data = [
            'username'   => $username,
            'password'   => md5($password),
            'nama'       => $nama,
            'role'       => $role,
            'id_airline' => $id_airline,
        ];

        $this->User_model->insert($data);

        $this->Ids_model->catat_aktivitas('Users', 'tambah_user', 'User baru: ' . $username . ' (role: ' . $role . ')', 'success');

        $this->session->set_flashdata('success', 'Akun user "' . htmlspecialchars($username) . '" berhasil dibuat.');
        redirect('users');
    }

    public function edit($id_user)
    {
        $data['user']     = $this->User_model->get_by_id($id_user);
        $data['airlines'] = $this->Airline_model->get_all();

        if (!$data['user']) {
            show_404();
        }

        $this->load->view('template/header');
        $this->load->view('users/edit', $data);
        $this->load->view('template/footer');
    }

    public function update($id_user)
    {
        $user = $this->User_model->get_by_id($id_user);
        if (!$user) {
            show_404();
        }

        $username   = trim($this->input->post('username'));
        $password   = $this->input->post('password');
        $nama       = trim($this->input->post('nama'));
        $role       = $this->input->post('role');
        $id_airline = $this->input->post('id_airline');

        $role_valid = ['admin', 'ground_handling', 'unit_operasi', 'unit_equipment', 'unit_sales', 'unit_security'];

        if (empty($username) || empty($nama) || empty($role)) {
            $this->session->set_flashdata('error', 'Username, Nama, dan Role wajib diisi.');
            redirect('users/edit/' . $id_user);
        }

        if (!in_array($role, $role_valid)) {
            $this->session->set_flashdata('error', 'Role tidak valid.');
            redirect('users/edit/' . $id_user);
        }

        $existing = $this->User_model->get_by_username($username);
        if ($existing && (int) $existing->id_user !== (int) $id_user) {
            $this->session->set_flashdata('error', 'Username "' . htmlspecialchars($username) . '" sudah dipakai user lain.');
            redirect('users/edit/' . $id_user);
        }

        if ($role === 'ground_handling') {
            if (empty($id_airline)) {
                $this->session->set_flashdata('error', 'Role GH Airline wajib memilih airline.');
                redirect('users/edit/' . $id_user);
            }
        } else {
            $id_airline = null;
        }

        $data = [
            'username'   => $username,
            'nama'       => $nama,
            'role'       => $role,
            'id_airline' => $id_airline,
        ];

        if (!empty($password)) {
            if (!preg_match('/^(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/', $password)) {
                $this->session->set_flashdata('error', 'Password minimal 8 karakter dan harus mengandung angka serta tanda baca (contoh: Admin@123).');
                redirect('users/edit/' . $id_user);
            }
            $data['password'] = md5($password);
        }

        $this->User_model->update($id_user, $data);

        $this->Ids_model->catat_aktivitas('Users', 'edit_user', 'User "' . $username . '" (ID ' . $id_user . ') diperbarui', 'success');

        $this->session->set_flashdata('success', 'Akun user "' . htmlspecialchars($username) . '" berhasil diperbarui.');
        redirect('users');
    }

    public function ubah_status($id)
    {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            show_404();
        }

        $status = ($user->status === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $this->User_model->ubah_status($id, $status);

        $this->Ids_model->catat_aktivitas('Users', 'ubah_status_user', 'User "' . $user->nama . '" (ID ' . $id . ') diubah ke ' . $status, 'success');

        $this->session->set_flashdata('success', 'Status user "' . htmlspecialchars($user->nama) . '" berhasil diubah menjadi ' . $status . '.');
        redirect('users');
    }
}