<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlist_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_user_wishlist($user_id) {
        $this->db->select('p.*');
        $this->db->from('wishlist w');
        $this->db->join('product p', 'p.id_product = w.id_product');
        $this->db->where('w.id_user', $user_id);
        return $this->db->get()->result_array();
    }

    public function remove_from_wishlist($user_id, $product_id) {
        $this->db->where('id_user', $user_id);
        $this->db->where('id_product', $product_id);
        $this->db->delete('wishlist');
    }

    public function is_wishlisted($id_user, $id_product) {
        return $this->db->get_where('wishlist', [
            'id_user' => $id_user,
            'id_product' => $id_product
        ])->num_rows() > 0;
    }

    public function toggle_wishlist($id_user, $id_product) {
        $exists = $this->db->get_where('wishlist', [
            'id_user' => $id_user,
            'id_product' => $id_product
        ])->num_rows();

        if ($exists) {
            $this->db->delete('wishlist', [
                'id_user' => $id_user,
                'id_product' => $id_product
            ]);
            return ['status' => 'success', 'action' => 'removed'];
        } else {
            $this->db->insert('wishlist', [
                'id_user' => $id_user,
                'id_product' => $id_product
            ]);
            return ['status' => 'success', 'action' => 'added'];
        }
    }
}