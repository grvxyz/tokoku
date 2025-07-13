<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->library('session');
    }

    public function index() {
        $this->load->view('admin/login');
    }

    public function login() {
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password', TRUE);

        $admin = $this->Admin_model->check_admin($email, $password);

        if ($admin) {
            $this->session->set_userdata([
                'admin_id' => $admin->id,
                'admin_name' => $admin->nama,
                'admin_email' => $admin->email,
                'is_admin_logged_in' => TRUE
            ]);
            //echo"Berhasil login";
            redirect('admindashboard');
        } else {
            $this->session->set_flashdata('error', 'Email atau password salah!');
            redirect('admin/auth/');
        }
    }

    public function logout() {
        $this->session->unset_userdata(['admin_id', 'admin_name', 'admin_email', 'is_admin_logged_in']);
        $this->session->set_flashdata('success', 'Anda telah logout.');
        redirect('admin/auth/');
    }
}

?>
