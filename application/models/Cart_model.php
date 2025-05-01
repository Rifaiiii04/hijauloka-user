<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_cart_count($id_user) {
        return $this->db->where('id_user', $id_user)->count_all_results('cart');
    }

    public function get_cart_items($id_user) {
        $this->db->select('c.*, p.nama_product, p.harga, p.gambar, p.stok');
        $this->db->from('cart c');
        $this->db->join('product p', 'p.id_product = c.id_product');
        $this->db->where('c.id_user', $id_user);
        return $this->db->get()->result_array();
    }

    public function add_to_cart($data) {
        $existing = $this->db->get_where('cart', [
            'id_user' => $data['id_user'],
            'id_product' => $data['id_product']
        ])->row();
        
        if ($existing) {
            $this->db->where('id_cart', $existing->id_cart);
            return $this->db->update('cart', ['jumlah' => $existing->jumlah + $data['jumlah']]);
        } else {
            return $this->db->insert('cart', $data);
        }
    }

    public function update_cart($id_cart, $jumlah) {
        return $this->db->update('cart', ['jumlah' => $jumlah], ['id_cart' => $id_cart]);
    }

    public function delete_cart_item($id_cart) {
        return $this->db->delete('cart', ['id_cart' => $id_cart]);
    }
}