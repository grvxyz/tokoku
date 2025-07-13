<?php
class Promo_model extends CI_Model {
    public function get_all_promo($limit, $start, $status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->get('promo', $limit, $start)->result();
    }
    public function get_promo_aktif() {
        return $this->db->where('status', 'aktif')->get('promo')->result();
    }
    
    
    public function count_promo($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results('promo');
    }

    public function get_by_id($id) {
        $this->db->where('id_promo', $id);  
        return $this->db->get('promo')->row();  
    }

    public function insert($data) {
        return $this->db->insert('promo', $data);
    }

    public function update($id, $data) {
        $this->db->where('id_promo', $id); 
        return $this->db->update('promo', $data);  
    }

    public function delete($id) {
        $this->db->where('id_promo', $id); 
        return $this->db->delete('promo'); 
    }
    
}    
