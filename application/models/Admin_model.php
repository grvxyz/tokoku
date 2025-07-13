<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
    
    public function check_admin($email, $password) {
        $this->db->where('email', $email);
        $admin = $this->db->get('admin1')->row();

        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        } else {
            return false;
        }
    }
}
?>