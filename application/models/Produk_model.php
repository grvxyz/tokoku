<?php
class Produk_model extends CI_Model {

    // Ambil semua produk berdasarkan ID toko
    public function get_by_toko($id_toko) {
        $this->db->select('produk.*, kategori.nama_kategori, merek.nama_merek');
        $this->db->from('produk');
        $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori', 'left');
        $this->db->join('merek', 'produk.id_merek = merek.id_merek', 'left');
        $this->db->where('produk.id_toko', $id_toko);
        return $this->db->get()->result();
    }

    // Insert produk baru
    public function insert($data) {
        return $this->db->insert('produk', $data);
    }

    // Ambil 1 produk berdasarkan ID (digunakan di detail dan lainnya)
    public function get($id) {
        $this->db->select('produk.*, kategori.nama_kategori, merek.nama_merek');
        $this->db->from('produk');
        $this->db->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left');
        $this->db->join('merek', 'merek.id_merek = produk.id_merek', 'left');
        $this->db->where('produk.id_produk', $id);
        return $this->db->get()->row();
    }

    // Alias untuk get(), supaya fleksibel di controller
    public function getById($id_produk) {
        return $this->get($id_produk);
    }

    // Update data produk
    public function update($id, $data) {
        $this->db->where('id_produk', $id);
        return $this->db->update('produk', $data);
    }

    // Hapus produk berdasarkan ID
    public function delete($id) {
        return $this->db->delete('produk', ['id_produk' => $id]);
    }

    // Ambil produk terbaru, default 6
// Ambil produk terbaru + info kategori, merek, dan toko
public function get_produk_terbaru($limit = 6) {
    $this->db->select('produk.*, kategori.nama_kategori, merek.nama_merek, toko.nama_toko');
    $this->db->from('produk');
    $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori', 'left');
    $this->db->join('merek', 'produk.id_merek = merek.id_merek', 'left');
    $this->db->join('toko', 'produk.id_toko = toko.id_toko', 'left'); // ✅ tambah join ke toko
    $this->db->order_by('produk.id_produk', 'DESC');
    $this->db->limit($limit);
    return $this->db->get()->result();
}


    // Ambil 1 produk untuk halaman detail
    public function get_detail($id_produk) {
        return $this->get($id_produk);
    }

    // Ambil produk terlaris berdasarkan jumlah transaksi
// Ambil produk terlaris berdasarkan jumlah terjual, termasuk info toko
public function get_produk_terlaris($limit = 6) {
    $this->db->select('produk.*, kategori.nama_kategori, merek.nama_merek, toko.nama_toko, SUM(pesanan_detail.jumlah) as jumlah_terjual');
    $this->db->from('produk');
    $this->db->join('pesanan_detail', 'pesanan_detail.produk_id = produk.id_produk', 'left');
    $this->db->join('pesanan', 'pesanan.id_pesanan = pesanan_detail.id_pesanan', 'left');
    $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori', 'left');
    $this->db->join('merek', 'produk.id_merek = merek.id_merek', 'left');
    $this->db->join('toko', 'produk.id_toko = toko.id_toko', 'left'); // ✅ tambah join ke toko
    $this->db->where('pesanan.status', 'selesai');
    $this->db->group_by('produk.id_produk');
    $this->db->order_by('jumlah_terjual', 'DESC');
    $this->db->limit($limit);
    return $this->db->get()->result();
}



    // Tambah jumlah dilihat
    public function tambah_dilihat($id_produk) {
        $this->db->set('dilihat', 'dilihat + 1', FALSE);
        $this->db->where('id_produk', $id_produk);
        $this->db->update('produk');
    }
}
