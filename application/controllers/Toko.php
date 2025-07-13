<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Toko extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Toko_model');
        $this->load->library('session');
        if (!$this->session->userdata('id_pembeli')) {
            redirect('home/login');
        }
        
    }

    public function index() {
    $data['toko'] = $this->Toko_model->get_all_toko();
    $this->load->view('admin/pembeli/toko/index', $data);
}

    public function tambah() {
        $this->load->view('admin/pembeli/toko/tambah');
    }

    public function simpan() {
        $config['upload_path'] = './uploads/pembeli/toko/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $this->load->library('upload', $config);

        $logo = '';
        if ($this->upload->do_upload('logo_toko')) {
            $logo = $this->upload->data('file_name');
        }

        $data = [
            'id_pembeli' => $this->session->userdata('id_pembeli'),
            'nama_toko' => $this->input->post('nama_toko'),
            'deskripsi' => $this->input->post('deskripsi'),
            'logo_toko' => $logo
        ];
        $this->Toko_model->insert($data);
        redirect('toko');
    }

    public function edit($id) {
        $data['toko'] = $this->Toko_model->get($id);
        $this->load->view('admin/pembeli/toko/edit', $data);
    }

    public function update($id) {
        $data = [
            'nama_toko' => $this->input->post('nama_toko'),
            'deskripsi' => $this->input->post('deskripsi'),
        ];

        if (!empty($_FILES['logo_toko']['name'])) {
            $config['upload_path'] = './uploads/pembeli/toko/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo_toko')) {
                $data['logo_toko'] = $this->upload->data('file_name');
            }
        }

        $this->Toko_model->update($id, $data);
        redirect('toko');
    }

    public function hapus($id) {
        $this->Toko_model->delete($id);
        redirect('toko');
    }
}
