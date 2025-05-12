<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model {
    
    public function get_cart_items($id_user) {
        $this->db->select('cart.*, product.nama_product, product.harga, product.gambar, product.stok');
        $this->db->from('cart');
        $this->db->join('product', 'product.id_product = cart.id_product');
        $this->db->where('cart.id_user', $id_user);
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
    public function get_cart_item($id_user, $id_product) {
        $this->db->where('id_user', $id_user);
        $this->db->where('id_product', $id_product);
        $query = $this->db->get('cart');
        
        return $query->row_array();
    }
    
    public function add_to_cart($id_user, $id_product, $quantity) {
        $data = array(
            'id_user' => $id_user,
            'id_product' => $id_product,
            'jumlah' => $quantity
        );
        
        return $this->db->insert('cart', $data);
    }
    
    public function update_cart_item($id_cart, $quantity) {
        $this->db->where('id_cart', $id_cart);
        return $this->db->update('cart', array('jumlah' => $quantity));
    }
    
    public function remove_from_cart($id_cart) {
        $this->db->where('id_cart', $id_cart);
        return $this->db->delete('cart');
    }
    
    public function get_cart_count($id_user) {
        $this->db->where('id_user', $id_user);
        return $this->db->count_all_results('cart');
    }
    
    public function clear_cart($id_user) {
        $this->db->where('id_user', $id_user);
        return $this->db->delete('cart');
    }
    
    public function get_selected_cart_items($id_user, $selected_cart_ids) {
        $this->db->select('cart.*, product.nama_product, product.harga, product.gambar, product.stok');
        $this->db->from('cart');
        $this->db->join('product', 'product.id_product = cart.id_product');
        $this->db->where('cart.id_user', $id_user);
        $this->db->where_in('cart.id_cart', $selected_cart_ids);
        $query = $this->db->get();
        return $query->result_array();
    }
}