<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDashboard extends CI_Controller {
    public function index() {
        if(empty($this->session->userdata('is_admin_logged_in'))){
            redirect('admin/auth/');

        } 

        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/dashboard');
        $this->load->view('admin/footer');
    }
}
