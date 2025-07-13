<?php
class Admin1_model extends CI_Model {

    public function get_all() {
        return $this->db->get('admin1')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('admin1', ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('admin1', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('admin1', $data);
    }

    public function delete($id) {
        return $this->db->delete('admin1', ['id' => $id]);
    }

    public function check_admin($email, $password) {
        $admin = $this->db->get_where('admin1', ['email' => $email])->row();
        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        }
        return false;
    }
}
