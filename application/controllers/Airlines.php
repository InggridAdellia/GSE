<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Airlines extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Airline_model');

        if ($this->user_role !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman Kelola Airline.');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['airlines'] = $this->Airline_model->get_all();

        $this->load->view('template/header');
        $this->load->view('airlines/index', $data);
        $this->load->view('template/footer');
    }

    public function tambah()
    {
        $this->load->view('template/header');
        $this->load->view('airlines/tambah');
        $this->load->view('template/footer');
    }

    public function simpan()
    {
        $nama = trim($this->input->post('nama_airline'));
        $kode = trim($this->input->post('kode_airline'));
        $kode = $kode === '' ? null : strtoupper($kode);

        if (empty($nama)) {
            $this->session->set_flashdata('error', 'Nama Airline wajib diisi.');
            redirect('airlines/tambah');
        }

        if ($kode && $this->Airline_model->get_by_kode($kode)) {
            $this->session->set_flashdata('error', 'Kode "' . htmlspecialchars($kode) . '" sudah dipakai airline lain.');
            redirect('airlines/tambah');
        }

        $this->Airline_model->insert([
            'nama_airline' => $nama,
            'kode_airline' => $kode,
        ]);

        $this->session->set_flashdata('success', 'Airline "' . htmlspecialchars($nama) . '" berhasil ditambahkan.');
        redirect('airlines');
    }

    public function edit($id)
    {
        $data['airline'] = $this->Airline_model->get_by_id($id);

        if (!$data['airline']) {
            show_404();
        }

        $this->load->view('template/header');
        $this->load->view('airlines/edit', $data);
        $this->load->view('template/footer');
    }

    public function update($id)
    {
        $airline = $this->Airline_model->get_by_id($id);
        if (!$airline) {
            show_404();
        }

        $nama = trim($this->input->post('nama_airline'));
        $kode = trim($this->input->post('kode_airline'));
        $kode = $kode === '' ? null : strtoupper($kode);

        if (empty($nama)) {
            $this->session->set_flashdata('error', 'Nama Airline wajib diisi.');
            redirect('airlines/edit/' . $id);
        }

        if ($kode) {
            $existing = $this->Airline_model->get_by_kode($kode);
            if ($existing && (int) $existing->id_airline !== (int) $id) {
                $this->session->set_flashdata('error', 'Kode "' . htmlspecialchars($kode) . '" sudah dipakai airline lain.');
                redirect('airlines/edit/' . $id);
            }
        }

        $this->Airline_model->update($id, [
            'nama_airline' => $nama,
            'kode_airline' => $kode,
        ]);

        $this->session->set_flashdata('success', 'Airline "' . htmlspecialchars($nama) . '" berhasil diperbarui.');
        redirect('airlines');
    }

    public function ubah_status($id)
    {
        $airline = $this->Airline_model->get_by_id($id);
        if (!$airline) {
            show_404();
        }

        $status = ($airline->status === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $this->Airline_model->ubah_status($id, $status);

        $this->session->set_flashdata('success', 'Status airline "' . htmlspecialchars($airline->nama_airline) . '" berhasil diubah menjadi ' . $status . '.');
        redirect('airlines');
    }

}