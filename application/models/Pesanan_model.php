<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan_model extends CI_Model {

    public function buatPesanan($id_pembeli, $total) {
        $data = [
            'pembeli_id' => $id_pembeli,
            'total_harga' => $total,
            'tanggal_pembelian' => date('Y-m-d'),
            'status' => 'pending'
        ];
        $this->db->insert('pesanan', $data);
        return $this->db->insert_id();
    }

    public function tambahDetail($data) {
        return $this->db->insert('pesanan_detail', $data);
    }

    public function getPesanan($id_pesanan) {
        return $this->db->get_where('pesanan', ['id_pesanan' => $id_pesanan])->row();
    }

    public function getDetailPesanan($id_pesanan) {
        $this->db->select('pesanan_detail.*, produk.nama_produk, produk.foto');
        $this->db->from('pesanan_detail');
        $this->db->join('produk', 'produk.id_produk = pesanan_detail.produk_id');
        $this->db->where('id_pesanan', $id_pesanan);
        return $this->db->get()->result();
    }
}
