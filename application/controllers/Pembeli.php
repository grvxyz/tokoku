<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembeli_model extends CI_Model {

    public function get_by_id($id)
    {
        return $this->db->get_where('pembeli', ['id_pembeli' => $id])->row();
    }


    public function get_all_pembeli()
    {
        return $this->db->get('pembeli')->result();
    }

    public function update_status($id, $status)
    {
        return $this->db->update('pembeli', ['status' => $status], ['id_pembeli' => $id]);
    }

    public function delete_pembeli($id)
    {
        return $this->db->delete('pembeli', ['id_pembeli' => $id]);
    }

    public function insert($data)
    {
        return $this->db->insert('pembeli', $data);
    }

    public function get_by_email($email)
    {
        return $this->db->get_where('pembeli', ['email' => $email])->row();
    }
}
