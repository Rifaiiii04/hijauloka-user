<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function get_user_by_email($email) {
        $query = $this->db->where('email', $email)
                         ->get('user');
        
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return null;
    }
    
    public function insert_user($data) {
        return $this->db->insert('user', $data);
    }

    public function update_password($id_user, $hashed_password) {
        return $this->db->where('id_user', $id_user)
                        ->update('user', ['password' => $hashed_password]);
    }
    
    // Add this method to help debug
    public function check_table() {
        return $this->db->get('user')->result();
    }

    /**
     * Get all addresses for a user
     */
    public function get_user_addresses($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('shipping_addresses');
        return $query->result_array();
    }

    /**
     * Get the primary address for a user
     */
    public function get_primary_address($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_primary', 1);
        $query = $this->db->get('shipping_addresses');
        return $query->row_array();
    }
}