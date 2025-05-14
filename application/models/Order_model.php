<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_order($data) {
        // Set default courier if not specified
        if (!isset($data['kurir'])) {
            $data['kurir'] = 'hijauloka';
        }
        
        // Set shipping cost based on courier
        if (!isset($data['ongkir'])) {
            $data['ongkir'] = $this->get_shipping_cost($data['kurir']);
        }
        
        $this->db->insert('orders', $data);
        return $this->db->insert_id();
    }
    
    private function get_shipping_cost($courier) {
        // Default shipping costs
        $shipping_costs = [
            'hijauloka' => 15000, // Rp 15.000
            'jne' => 0, // Coming soon
            'jnt' => 0  // Coming soon
        ];
        
        return $shipping_costs[$courier] ?? 15000; // Default to HijauLoka courier cost
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
    
    public function get_order_items($order_id) {
        $this->db->select('oi.*, p.nama_product, p.gambar, p.desk_product');
        $this->db->from('order_items oi');
        $this->db->join('product p', 'p.id_product = oi.id_product');
        $this->db->where('oi.id_order', $order_id);
        return $this->db->get()->result_array();
    }
    
    public function get_user_orders($user_id) {
        $this->db->select('o.*, COUNT(oi.id_item) as total_items');
        $this->db->from('orders o');
        $this->db->join('order_items oi', 'o.id_order = oi.id_order', 'left');
        $this->db->where('o.id_user', $user_id);
        $this->db->group_by('o.id_order');
        $this->db->order_by('o.tgl_pemesanan', 'DESC');
        return $this->db->get()->result_array();
    }
    
    public function update_order($id_order, $data) {
        $this->db->where('id_order', $id_order);
        return $this->db->update('orders', $data);
    }
    
    public function update_payment($id_order, $data) {
        $this->db->where('id_order', $id_order);
        return $this->db->update('payments', $data);
    }

    public function get_order_detail($order_id) {
        $this->db->select('o.*, u.nama as nama_user, u.email as email_user');
        $this->db->from('orders o');
        $this->db->join('user u', 'u.id_user = o.id_user');
        $this->db->where('o.id_order', $order_id);
        return $this->db->get()->row_array();
    }

    public function cancel_order($order_id, $user_id) {
        // Get order details
        $order = $this->db->get_where('orders', [
            'id_order' => $order_id,
            'id_user' => $user_id
        ])->row_array();

        if (!$order) {
            return ['success' => false, 'message' => 'Pesanan tidak ditemukan'];
        }

        // Check if order status is 'pending'
        if ($order['stts_pemesanan'] !== 'pending') {
            return ['success' => false, 'message' => 'Pesanan tidak dapat dibatalkan karena status sudah berubah'];
        }

        // Start transaction
        $this->db->trans_start();

        // Update order status to 'dibatalkan'
        $this->db->where('id_order', $order_id);
        $this->db->update('orders', [
            'stts_pemesanan' => 'dibatalkan',
            'tgl_batal' => date('Y-m-d H:i:s')
        ]);

        // Get order items
        $order_items = $this->db->get_where('order_items', ['id_order' => $order_id])->result_array();

        // Return stock for each item
        foreach ($order_items as $item) {
            $this->db->set('stok', 'stok + ' . $item['quantity'], FALSE);
            $this->db->where('id_product', $item['id_product']);
            $this->db->update('product');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'message' => 'Gagal membatalkan pesanan'];
        }

        return ['success' => true, 'message' => 'Pesanan berhasil dibatalkan'];
    }

    public function get_completed_orders_by_user_and_product($id_user, $id_product)
    {
        // First, let's check if the user has any completed orders
        $this->db->select('orders.id_order');
        $this->db->from('orders');
        $this->db->where('orders.id_user', $id_user);
        $this->db->where('orders.stts_pemesanan', 'selesai');
        $user_completed_orders = $this->db->get()->result_array();
        
        log_message('debug', 'User completed orders: ' . json_encode($user_completed_orders));
        
        if (empty($user_completed_orders)) {
            return [];
        }
        
        // Get the order IDs
        $order_ids = array_column($user_completed_orders, 'id_order');
        
        // Now check if any of these orders contain the specific product
        $this->db->select('order_items.id_order, order_items.id_product, order_items.quantity');
        $this->db->from('order_items');
        $this->db->where('order_items.id_product', $id_product);
        $this->db->where_in('order_items.id_order', $order_ids);
        $product_orders = $this->db->get()->result_array();
        
        log_message('debug', 'Product in completed orders: ' . json_encode($product_orders));
        
        if (empty($product_orders)) {
            return [];
        }
        
        // Finally, get the full order details for orders that contain this product
        $this->db->select('orders.*');
        $this->db->from('orders');
        $this->db->where('orders.id_user', $id_user);
        $this->db->where('orders.stts_pemesanan', 'selesai');
        $this->db->where_in('orders.id_order', array_column($product_orders, 'id_order'));
        $this->db->order_by('orders.tgl_selesai', 'DESC');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        log_message('debug', 'Final order query: ' . $this->db->last_query());
        log_message('debug', 'Final order result count: ' . count($result));
        
        return $result;
    }
}