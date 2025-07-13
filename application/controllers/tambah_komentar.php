<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model');
        $this->load->model('Komentar_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    // Halaman detail produk
    public function detail($id_produk)
    {
        $data['produk'] = $this->Produk_model->get($id_produk); // ambil detail produk
        $data['komentar'] = $this->Komentar_model->get_by_produk($id_produk);

        // Jika user login sebagai pemilik toko, izinkan membalas komentar
        $data['is_pemilik_toko'] = false;
        if ($this->session->userdata('id_pembeli') && isset($data['produk']->id_toko)) {
            $id_pembeli = $this->session->userdata('id_pembeli');
            $this->db->where(['id_pembeli' => $id_pembeli, 'id_toko' => $data['produk']->id_toko]);
            $data['is_pemilik_toko'] = $this->db->get('toko')->num_rows() > 0;
        }

        $this->load->view('produk/detail', $data);
    }

    // Menyimpan komentar baru dari pembeli
    public function tambah_komentar($id_produk)
    {
        if (!$this->session->userdata('id_pembeli')) {
            redirect('home/login');
        }

        $data = [
            'id_produk' => $id_produk,
            'id_pembeli' => $this->session->userdata('id_pembeli'),
            'komentar' => $this->input->post('komentar'),
            'rating' => $this->input->post('rating'),
            'tanggal' => date('Y-m-d H:i:s'),
        ];

        $this->Komentar_model->insert($data);
        redirect('produk/detail/' . $id_produk);
    }

    // Menyimpan balasan komentar dari pemilik toko
    public function tambah_balasan($id_komentar)
    {
        // Cek login dan role
        if (!$this->session->userdata('id_pembeli')) {
            redirect('home/login');
        }

        $id_pembeli = $this->session->userdata('id_pembeli');

        $komentar = $this->Komentar_model->get_by_id($id_komentar);
        if (!$komentar) {
            show_404();
        }

        // Cek apakah yang login adalah pemilik toko dari produk komentar tsb
        $this->db->where(['id_pembeli' => $id_pembeli]);
        $toko = $this->db->get('toko')->row();

        if (!$toko || !$this->Komentar_model->is_toko_owner($id_komentar, $toko->id_toko)) {
            show_error('Anda tidak berhak membalas komentar ini.');
        }

        $data = [
            'id_produk' => $komentar->id_produk,
            'id_toko' => $toko->id_toko,
            'parent_id' => $id_komentar,
            'komentar' => $this->input->post('balasan'),
            'tanggal' => date('Y-m-d H:i:s'),
            'is_admin_reply' => 1
        ];

        $this->Komentar_model->insert($data);
        redirect('produk/detail/' . $komentar->id_produk);
    }
}
