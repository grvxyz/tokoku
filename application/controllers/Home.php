<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Pembeli_model');
        $this->load->model('Promo_model');
        $this->load->model('Produk_model'); // Tambahkan ini
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
    }
    

    public function index() {
        $data['promo'] = $this->Promo_model->get_promo_aktif(); 
        $data['produk_terbaru'] = $this->Produk_model->get_produk_terbaru();
        $data['produk_terlaris'] = $this->Produk_model->get_produk_terlaris();
         $data['merek'] = $this->db->get('merek')->result();
    $data['toko'] = $this->db->get('toko')->result();
        $this->load->view('homepage/header');
        $this->load->view('homepage/menu');
        $this->load->view('homepage/content', $data);
        $this->load->view('homepage/footer');
    }

    public function register() {
        $this->load->view('homepage/header');
        $this->load->view('homepage/menu');
        $this->load->view('homepage/register');
        $this->load->view('homepage/footer');
    }
    
    public function act_register() {
        $nama      = $this->input->post('nama');
        $email     = $this->input->post('email');
        $password  = $this->input->post('password');
        $password2 = $this->input->post('password2');
        $alamat    = $this->input->post('alamat');
        $nomor_hp  = $this->input->post('nomor_hp');
    
        if ($password != $password2) {
            $this->session->set_flashdata('error', 'Password tidak cocok!');
            redirect('home/register');
        }
    
        if ($this->Pembeli_model->cek_email($email)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar!');
            redirect('home/register');
        }
    
        $data = [
            'nama' => $nama,
            'email'        => $email,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'status'       => 'aktif',
            'alamat'       => $alamat,
            'nomor_hp'     => $nomor_hp
        ];
        
        $this->Pembeli_model->insert($data);
        $this->session->set_flashdata('success', 'Registrasi berhasil, silakan login!');
        redirect('home/login');
    }
    
    public function login() {
        $this->load->view('homepage/header');
        $this->load->view('homepage/menu');
        $this->load->view('homepage/login');
        $this->load->view('homepage/footer');
    }
    
    public function act_login() {
        $email    = $this->input->post('email');
        $password = $this->input->post('password');
    
        $user = $this->Pembeli_model->get_by_email($email);
    
        if ($user && password_verify($password, $user->password)) {
            $this->session->set_userdata([
                'id_pembeli' => $user->id_pembeli,
                'nama' => $user->nama,
                'logged_in_pembeli' => TRUE
            ]);
            redirect('home');
        } else {
            $this->session->set_flashdata('error', 'Email atau password salah!');
            redirect('home/login');
        }
    }
    
    public function edit_profile() {
        if (!$this->session->userdata('logged_in_pembeli')) {
            redirect('home/login');
        }
    
        $id = $this->session->userdata('id_pembeli');
        $data['pembeli'] = $this->Pembeli_model->get_by_id($id);
    
        $this->load->view('homepage/header');
        $this->load->view('homepage/menu');
        $this->load->view('homepage/edit_profile', $data);
        $this->load->view('homepage/footer');
    }

    public function act_profile() {
        if (!$this->session->userdata('logged_in_pembeli')) {
            redirect('home/login');
        }
    
        $id = $this->session->userdata('id_pembeli');
    
        $data = [
            'nama'     => $this->input->post('nama'),
            'email'    => $this->input->post('email'),
            'alamat'   => $this->input->post('alamat'),
            'nomor_hp' => $this->input->post('nomor_hp')
        ];
    
        $password = $this->input->post('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
    
        $this->Pembeli_model->update($id, $data);
        $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
        redirect('home/edit_profile');
    }
    public function logout()
    {
        $this->session->unset_userdata('logged_in_pembeli');
        $this->session->sess_destroy();
        redirect('home/login');
    }
    
}
