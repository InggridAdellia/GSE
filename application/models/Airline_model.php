<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Airline_model extends CI_Model {

    protected $table = 'airlines';

    public function get_all()
    {
        return $this->db->order_by('nama_airline', 'ASC')->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id_airline', $id)->get($this->table)->row();
    }

    public function get_by_kode($kode)
    {
        return $this->db->where('kode_airline', $kode)->get($this->table)->row();
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id_airline', $id)->update($this->table, $data);
    }

    public function ubah_status($id, $status)
    {
        return $this->db->where('id_airline', $id)->update('airlines', [
            'status' => $status,
        ]);
    }

    public function is_used($id_airline)
    {
        $count_users = $this->db->where('id_airline', $id_airline)->count_all_results('users');
        $count_gse   = $this->db->where('id_airline', $id_airline)->count_all_results('gse');
        $count_pm    = $this->db->where('id_airline', $id_airline)->count_all_results('permohonan_masuk');

        return ($count_users + $count_gse + $count_pm) > 0;
    }

}