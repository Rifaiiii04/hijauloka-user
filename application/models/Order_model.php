<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function create_order($data) {
        $this->db->insert('orders', $data);
        return $this->db->insert_id();
    }
    
    public function create_order_item($data) {
        $this->db->insert('order_items', $data);
        return $this->db->insert_id();
    }
    
    public function create_payment($data) {
        $this->db->insert('payments', $data);
        return $this->db->insert_id();
    }
    
    public function get_order_by_id($id_order) {
        return $this->db->get_where('orders', ['id_order' => $id_order])->row_array();
    }
    
    public function get_payment_by_order_id($id_order) {
        return $this->db->get_where('payments', ['id_order' => $id_order])->row_array();
    }
    
    public function get_order_items($id_order) {
        $this->db->select('oi.*, p.nama_product, p.gambar');
        $this->db->from('order_items oi');
        $this->db->join('products p', 'p.id_product = oi.id_product');
        $this->db->where('oi.id_order', $id_order);
        return $this->db->get()->result_array();
    }
    
    public function get_user_orders($id_user) {
        $this->db->where('id_user', $id_user);
        $this->db->order_by('tgl_pemesanan', 'DESC');
        return $this->db->get('orders')->result_array();
    }
    
    public function update_order($id_order, $data) {
        $this->db->where('id_order', $id_order);
        return $this->db->update('orders', $data);
    }
    
    public function update_payment($id_order, $data) {
        $this->db->where('id_order', $id_order);
        return $this->db->update('payments', $data);
    }
}