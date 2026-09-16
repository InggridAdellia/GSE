<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function cek_login($username, $password)
    {
        return $this->db
            ->where('username', $username)
            ->where('password', md5($password))
            ->where('status', 'Aktif')
            ->get('users')
            ->row();
    }

    public function cek_password_saja($username, $password)
    {
        return $this->db
            ->where('username', $username)
            ->where('password', md5($password))
            ->get('users')
            ->row();
    }

    public function get_by_id($id_user)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->get('users')
            ->row();
    }

    public function get_all()
    {
        return $this->db
            ->select('u.*, a.nama_airline')
            ->from('users u')
            ->join('airlines a', 'a.id_airline = u.id_airline', 'left')
            ->order_by('u.id_user', 'ASC')
            ->get()->result();
    }

    public function get_by_username($username)
    {
        return $this->db
            ->where('username', $username)
            ->get('users')
            ->row();
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('users', $data);
    }

    public function update($id_user, $data)
    {
        return $this->db->where('id_user', $id_user)->update('users', $data);
    }

    public function ubah_status($id, $status)
    {
        return $this->db->where('id_user', $id)->update('users', [
            'status' => $status,
        ]);
    }

    /**
     * Cek apakah user ini sudah pernah "terpakai" sebagai pembuat atau
     * verifikator di permohonan_masuk / permohonan_keluar.
     * Kalau iya, sebaiknya jangan dihapus (akan gagal karena foreign key,
     * dan akan merusak jejak riwayat verifikasi).
     */
    public function is_used($id_user)
    {
        $count_masuk = $this->db
            ->where('created_by', $id_user)
            ->or_where('verifikasi_operasi_oleh', $id_user)
            ->or_where('verifikasi_equipment_oleh', $id_user)
            ->or_where('verifikasi_sales_oleh', $id_user)
            ->count_all_results('permohonan_masuk');

        $count_keluar = $this->db
            ->where('created_by', $id_user)
            ->count_all_results('permohonan_keluar');

        return ($count_masuk + $count_keluar) > 0;
    }

}