<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('user');
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        
        return false;
    }
    
    // Keeping only one version of the update_password method
    public function update_password($user_id, $new_password) {
        $this->db->where('id_user', $user_id);
        $this->db->update('user', array('password' => $new_password));
        
        return $this->db->affected_rows() > 0;
    }
    
    public function insert_user($data) {
        return $this->db->insert('user', $data);
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