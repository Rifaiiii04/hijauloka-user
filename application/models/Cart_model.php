<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model {
    public function get_cart_items($user_id) {
        $this->db->select('cart.*, product.nama_product, product.harga, product.gambar');
        $this->db->from('cart');
        $this->db->join('product', 'product.id_product = cart.id_product');
        $this->db->where('cart.id_user', $user_id);
        
        return $this->db->get()->result_array();
    }

    public function update_quantity($cart_id, $quantity) {
        return $this->db->update('cart', ['jumlah' => $quantity], ['id_cart' => $cart_id]);
    }

    public function remove_item($cart_id) {
        return $this->db->delete('cart', ['id_cart' => $cart_id]);
    }

    public function get_cart_count($user_id) {
        if (!$user_id) return 0;
        
        $this->db->select('COUNT(id_cart) as total');
        $this->db->where('id_user', $user_id);
        $result = $this->db->get('cart')->row();
        
        return $result->total ?? 0;
    }
}