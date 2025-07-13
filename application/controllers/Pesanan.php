<?php
class Pesanan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pesanan_model');
    }

    public function detail($id_pesanan) {
        $data['pesanan'] = $this->Pesanan_model->getPesanan($id_pesanan);
        $data['items'] = $this->Pesanan_model->getDetailPesanan($id_pesanan);

        $this->load->view('homepage/header');
        $this->load->view('homepage/menu');
        $this->load->view('admin/pembeli/pesanan_detail', $data);
        $this->load->view('homepage/footer');
    }
}
