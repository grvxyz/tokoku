<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model {

    public function get_cart_items($id_pembeli) {
        $this->db->select('keranjang.*, produk.nama_produk, produk.harga AS harga_produk, produk.foto, toko.nama_toko, (keranjang.qty * keranjang.harga) AS subtotal');
        $this->db->from('keranjang');
        $this->db->join('produk', 'keranjang.id_produk = produk.id_produk');
        $this->db->join('toko', 'produk.id_toko = toko.id_toko');
        $this->db->where('keranjang.id_pembeli', $id_pembeli);
        return $this->db->get()->result();
    }

    public function add_to_cart($data) {
        // Cek apakah item sudah ada
        $this->db->where('id_pembeli', $data['id_pembeli']);
        $this->db->where('id_produk', $data['id_produk']);
        $existing = $this->db->get('keranjang')->row();

        if ($existing) {
            // Jika sudah ada, update qty
            $new_qty = $existing->qty + $data['qty'];
            return $this->db->update('keranjang', ['qty' => $new_qty], ['id_keranjang' => $existing->id_keranjang]);
        } else {
            // Jika belum, insert baru
            return $this->db->insert('keranjang', $data);
        }
    }

    public function update_qty($id_keranjang, $qty) {
        return $this->db->update('keranjang', ['qty' => $qty], ['id_keranjang' => $id_keranjang]);
    }

    public function remove_from_cart($id_keranjang) {
        return $this->db->delete('keranjang', ['id_keranjang' => $id_keranjang]);
    }

    public function clear_cart($id_pembeli) {
        return $this->db->delete('keranjang', ['id_pembeli' => $id_pembeli]);
    }

    public function get_item_subtotal($id_keranjang) {
        $this->db->select('harga_produk, qty');
        $this->db->from('keranjang');
        $this->db->where('id_keranjang', $id_keranjang);
        $item = $this->db->get()->row();
        
        if ($item) {
            return $item->harga_produk * $item->qty;
        }
        return 0;
    }
}