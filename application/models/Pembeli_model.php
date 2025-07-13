<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembeli_model extends CI_Model {

    public function get_all_pembeli() {
        return $this->db->get('pembeli')->result_array();
    }

    public function insert($data) {
        return $this->db->insert('pembeli', $data);
    }

    public function update_status($id, $status) {
        $this->db->where('id_pembeli', $id);
        return $this->db->update('pembeli', ['status' => $status]);
    }

    public function delete_pembeli($id) {
        $this->db->where('id_pembeli', $id);
        return $this->db->delete('pembeli');
    }
    public function cek_email($email) {
        return $this->db->get_where('pembeli', ['email' => $email])->row();
    }
    public function get_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('pembeli');
        return $query->row();  
    }

    public function get_by_id($id) {
        return $this->db->get_where('pembeli', ['id_pembeli' => $id])->row();
    }
    
    public function update($id, $data) {
        $this->db->where('id_pembeli', $id);
        return $this->db->update('pembeli', $data);
    }
    
    
}
