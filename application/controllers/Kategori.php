<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kategori_model');
        $this->load->library('session'); // Load library session untuk flashdata
    }

    public function index() {
        $data['kategori'] = $this->Kategori_model->get_all_kategori();
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/kategori/index', $data);
        $this->load->view('admin/footer');
    }

    public function create() {
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/kategori/create');
        $this->load->view('admin/footer');
    }

    public function store() {
        $data = ['nama_kategori' => $this->input->post('nama_kategori')];

        if ($this->Kategori_model->insert_kategori($data)) {
            $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan kategori.');
        }
        redirect('kategori');
    }

    public function edit($id) {
        $data['kategori'] = $this->Kategori_model->get_kategori_by_id($id);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/kategori/edit', $data);
        $this->load->view('admin/footer');
    }

    public function update($id) {
        $data = ['nama_kategori' => $this->input->post('nama_kategori')];

        if ($this->Kategori_model->update_kategori($id, $data)) {
            $this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui kategori.');
        }
        redirect('kategori');
    }

    public function delete($id) {
        if ($this->Kategori_model->delete_kategori($id)) {
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kategori.');
        }
        redirect('kategori');
    }
}
