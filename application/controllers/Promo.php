<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Promo_model');
        $this->load->helper(['form', 'url']);
        $this->load->library('pagination');
    }

  public function index() {
    $status = $this->input->get('status');
    $config['base_url'] = site_url('promo/index');
    $config['per_page'] = 5;
    $config['total_rows'] = $this->Promo_model->count_promo($status);
    $config['reuse_query_string'] = TRUE;
    $this->pagination->initialize($config);

    $start = $this->uri->segment(3, 0);
    $data['promo'] = $this->Promo_model->get_all_promo($config['per_page'], $start, $status);
    $data['status'] = $status;
    
    $this->load->view('admin/header');
    $this->load->view('admin/menu');
    $this->load->view('admin/promo/index', $data);
    $this->load->view('admin/footer');
}


    public function tambah() {
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/promo/tambah');
        $this->load->view('admin/footer');
    }

    public function simpan() {
        $poster = $_FILES['poster']['name'];
        if ($poster != '') {
            $config['upload_path'] = './uploads/promo/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload('poster')) {
                $this->session->set_flashdata('error', 'Upload Gagal: ' . $this->upload->display_errors());
                redirect('promo/index');
                return;
            } else {
                $poster = $this->upload->data('file_name');
            }
        } else {
            $poster = '';
        }
    
        $data = [
            'nama_promo' => $this->input->post('nama_promo'),
            'poster'     => $poster,
            'status'     => $this->input->post('status'),
            'link'       => $this->input->post('link')
        ];
        
        if ($this->Promo_model->insert($data)) {
            $this->session->set_flashdata('success', 'Promo berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Promo gagal disimpan');
        }
    
        redirect('promo/index');
    }
    
    public function edit($id_promo) {
        $data['promo'] = $this->Promo_model->get_by_id($id_promo);
        $this->load->view('admin/header');
        $this->load->view('admin/menu');
        $this->load->view('admin/promo/edit', $data);
        $this->load->view('admin/footer');
    }

    public function update($id_promo) {
        $poster = $_FILES['poster']['name'];
        if ($poster != '') {
            $config['upload_path'] = './uploads/promo/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload('poster')) {
                echo "Upload Gagal"; die();
            } else {
                $poster = $this->upload->data('file_name');
            }
        } else {
            $poster = $this->input->post('old_poster');
        }
    
        $data = [
            'nama_promo' => $this->input->post('nama_promo'),
            'poster'     => $poster,
            'status'     => $this->input->post('status'),
            'link'       => $this->input->post('link')
        ];
    
        if ($this->Promo_model->update($id_promo, $data)) {
            $this->session->set_flashdata('success', 'Promo berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Promo gagal diupdate');
        }
    
        redirect('promo');
    }
    
    public function hapus($id_promo) {
        if ($this->Promo_model->delete($id_promo)) {
            $this->session->set_flashdata('success', 'Promo berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Promo gagal dihapus');
        }
        redirect('promo');
    }
    
}
