<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merek extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Merek_model');
        $this->load->library('session');
    }

    public function index() {
        $data['merek'] = $this->Merek_model->get_all_merek();
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/merek/index', $data);
        $this->load->view('admin/footer');
    }

    public function create() {
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/merek/create');
        $this->load->view('admin/footer');
    }

    private function uploadGambar() {
        $config['upload_path'] = './uploads/merek/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            return $this->upload->data('file_name');
        }

        return null;
    }

    public function store() {
        $gambar = $this->uploadGambar();

        $data = [
            'nama_merek' => $this->input->post('nama_merek'),
            'gambar' => $gambar
        ];

        if ($this->Merek_model->insert_merek($data)) {
            $this->session->set_flashdata('success', 'Merek berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan merek.');
        }
        redirect('merek');
    }

    public function edit($id) {
        $data['merek'] = $this->Merek_model->get_merek_by_id($id);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/merek/edit', $data);
        $this->load->view('admin/footer');
    }

    public function update($id) {
        $merek_lama = $this->Merek_model->get_merek_by_id($id);
        $gambarBaru = $this->uploadGambar();

        if ($gambarBaru && $merek_lama['gambar']) {
            $path = './uploads/merek/' . $merek_lama['gambar'];
            if (file_exists($path)) unlink($path);
        }

        $data = [
            'nama_merek' => $this->input->post('nama_merek'),
            'gambar' => $gambarBaru ?? $merek_lama['gambar']
        ];

        if ($this->Merek_model->update_merek($id, $data)) {
            $this->session->set_flashdata('success', 'Merek berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui merek.');
        }
        redirect('merek');
    }

    public function delete($id) {
        $merek = $this->Merek_model->get_merek_by_id($id);
        if ($merek['gambar']) {
            $path = './uploads/merek/' . $merek['gambar'];
            if (file_exists($path)) unlink($path);
        }

        if ($this->Merek_model->delete_merek($id)) {
            $this->session->set_flashdata('success', 'Merek berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus merek.');
        }
        redirect('merek');
    }
}