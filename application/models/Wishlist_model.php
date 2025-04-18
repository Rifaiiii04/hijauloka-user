<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlist_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_user_wishlist($id_user) {
        $this->db->select('w.*, p.*, GROUP_CONCAT(c.nama_kategori) as categories');
        $this->db->from('wishlist w');
        $this->db->join('product p', 'p.id_product = w.id_product');
        $this->db->join('product_category pc', 'pc.id_product = p.id_product');
        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
        $this->db->where('w.id_user', $id_user);
        $this->db->group_by('p.id_product');
        return $this->db->get()->result();
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