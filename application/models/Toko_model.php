<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Toko_model extends CI_Model {

    // Ambil data toko berdasarkan ID pembeli
    public function get_by_pembeli($id_pembeli) {
        if (empty($id_pembeli)) {
            log_message('error', 'get_by_pembeli() dipanggil dengan ID pembeli kosong.');
            return null;
        }

        $this->db->where('id_pembeli', $id_pembeli);
        $query = $this->db->get('toko');

        if ($query->num_rows() === 0) {
            log_message('debug', 'Tidak ditemukan toko untuk ID pembeli: ' . $id_pembeli);
            return null;
        }

        return $query->row();
    }

    public function get_all_toko() {
    return $this->db->get('toko')->result();
}

    // Insert data toko baru
    public function insert($data) {
        if (empty($data) || !is_array($data)) {
            log_message('error', 'insert() dipanggil dengan data kosong atau tidak valid.');
            return false;
        }

        return $this->db->insert('toko', $data);
    }

    // Ambil data toko berdasarkan ID toko
    public function get($id) {
        if (empty($id)) {
            log_message('error', 'get() dipanggil dengan ID toko kosong.');
            return null;
        }

        return $this->db->get_where('toko', ['id_toko' => $id])->row();
    }

    // Update data toko berdasarkan ID
    public function update($id, $data) {
        if (empty($id) || empty($data)) {
            log_message('error', 'update() dipanggil dengan ID atau data kosong.');
            return false;
        }

        return $this->db->where('id_toko', $id)->update('toko', $data);
    }

    // Hapus data toko berdasarkan ID
    public function delete($id) {
        if (empty($id)) {
            log_message('error', 'delete() dipanggil dengan ID toko kosong.');
            return false;
        }

        return $this->db->delete('toko', ['id_toko' => $id]);
    }
}
