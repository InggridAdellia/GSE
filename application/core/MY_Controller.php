<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $user_id;
    protected $user_role;
    protected $user_nama;
    protected $user_airline;
    protected $public_methods = [];


    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Makassar');

        $method    = $this->router->fetch_method();
        $is_public = in_array($method, $this->public_methods, true);

        if (!$this->session->userdata('login')) {
            redirect('auth');
        }

        $this->user_id      = $this->session->userdata('id_user');
        $this->user_role    = $this->session->userdata('role');
        $this->user_nama    = $this->session->userdata('nama');
        $this->user_airline = $this->session->userdata('id_airline');
    }
}