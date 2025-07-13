<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin1 extends CI_Controller {
    // di Auth.php
public function login() {
    $email = $this->input->post('email', TRUE);
    $password = $this->input->post('password', TRUE);

    $admin = $this->Admin1_model->check_admin_login($email); // model berdasarkan admin1 table

    if ($admin && password_verify($password, $admin->password)) {
        $this->session->set_userdata([
            'admin_id' => $admin->id,
            'admin_name' => $admin->nama,
            'admin_email' => $admin->email,
            'is_admin_logged_in' => TRUE
        ]);
        redirect('admin1');
    } else {
        $this->session->set_flashdata('error', 'Email atau password salah!');
        redirect('admin/auth/');
    }
}


    public function __construct() {
        parent::__construct();
        $this->load->model('Admin1_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    public function index() {
        $data['admin'] = $this->Admin1_model->get_all();
        $this->load->view('admin/admin1/index', $data);
    }

    public function create() {
        $this->load->view('admin/admin1/create');
    }

    public function store() {
        $data = [
            'nama'     => $this->input->post('nama', TRUE),
            'email'    => $this->input->post('email', TRUE),
            'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
        ];

        $this->Admin1_model->insert($data);
        $this->session->set_flashdata('success', 'Admin berhasil ditambahkan.');
        redirect('admin1');
    }

    public function edit($id) {
        $data['admin'] = $this->Admin1_model->get_by_id($id);
        if (!$data['admin']) {
            $this->session->set_flashdata('error', 'Admin tidak ditemukan.');
            redirect('admin1');
            return;
        }

        $this->load->view('admin/admin1/edit', $data);
    }

    public function update($id) {
        $data = [
            'nama'  => $this->input->post('nama', TRUE),
            'email' => $this->input->post('email', TRUE)
        ];

        $password = $this->input->post('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->Admin1_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Admin berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui admin.');
        }

        redirect('admin1');
    }

    public function ganti_password($id) {
        $admin = $this->Admin1_model->get_by_id($id);
        if (!$admin) {
            $this->session->set_flashdata('error', 'Admin tidak ditemukan.');
            redirect('admin1');
            return;
        }

        $data['admin'] = $admin;
        $this->load->view('admin/admin1/ganti_password', $data);
    }

    public function update_password() {
        $id               = $this->input->post('id');
        $new_password     = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');

        if ($new_password !== $confirm_password) {
            $this->session->set_flashdata('error', 'Password konfirmasi tidak cocok!');
            redirect('admin1/ganti_password/' . $id);
            return;
        }

        $password_baru = password_hash($new_password, PASSWORD_DEFAULT);
        $this->Admin1_model->update($id, ['password' => $password_baru]);

        $this->session->set_flashdata('success', 'Password berhasil diubah.');
        redirect('admin1');
    }

    public function ubah_status_pesanan($id_pesanan) {
    if (!$this->session->userdata('is_admin_logged_in')) {
    redirect('admin/auth');    ;
}

    $status = $this->input->post('status');

    // Validasi status
    $status_valid = ['pending', 'diproses', 'dikirim', 'selesai', 'batal'];
    if (!in_array($status, $status_valid)) {
        show_error('Status tidak valid.');
    }

    $this->db->where('id_pesanan', $id_pesanan);
    $this->db->update('pesanan', ['status' => $status]);

    $this->session->set_flashdata('success', 'Status pesanan berhasil diubah menjadi "' . $status . '".');
    redirect('admin1/pesanan');
}

    public function pesanan() {
    $this->db->select('pesanan.*, pembeli.nama as nama_pembeli');
    $this->db->from('pesanan');
    $this->db->join('pembeli', 'pembeli.id_pembeli = pesanan.pembeli_id', 'left');
    $data['pesanan'] = $this->db->get()->result();

    $this->load->view('admin/admin1/pesanan', $data);
}

}
