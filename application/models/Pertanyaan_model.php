<?php
class Pertanyaan_model extends CI_Model {
    public function get_by_produk($id_produk) {
        return $this->db->get_where('pertanyaan_produk', ['id_produk' => $id_produk])->result();
    }

    public function insert($data) {
        return $this->db->insert('pertanyaan_produk', $data);
    }
}