<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    // Add these methods if they don't exist
    
    public function get_order_by_id($id_order) {
        $this->db->where('id_order', $id_order);
        $query = $this->db->get('orders');
        return $query->row_array();
    }
    
    public function get_order_items($id_order) {
        $this->db->select('oi.*, p.nama_product as nama_produk, p.gambar');
        $this->db->from('order_items oi');
        $this->db->join('product p', 'oi.id_product = p.id_product'); // Changed id_produk to id_product
        $this->db->where('oi.id_order', $id_order);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function update_order_status($id_order, $status) {
        $data = ['stts_pemesanan' => $status];
        
        // Add timestamp based on status
        if ($status == 'dikirim') {
            $data['tgl_dikirim'] = date('Y-m-d H:i:s');
        } else if ($status == 'selesai') {
            $data['tgl_selesai'] = date('Y-m-d H:i:s');
        } else if ($status == 'dibatalkan') {
            $data['tgl_batal'] = date('Y-m-d H:i:s');
        }
        
        $this->db->where('id_order', $id_order);
        $this->db->update('orders', $data);
        
        return $this->db->affected_rows() > 0;
    }
    
    public function get_user_orders($user_id) {
        $this->db->where('id_user', $user_id);
        $this->db->order_by('tgl_pemesanan', 'DESC');
        $query = $this->db->get('orders');
        return $query->result_array();
    }
}