<?php
class Orders extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('id_user');
        $orders = $this->db->get_where('orders', ['id_user' => $user_id])->result_array();
        $data['orders'] = $orders;
        $this->load->view('orders/index', $data);
    }

    public function detail($id_order) {
        $user_id = $this->session->userdata('id_user');
        $order = $this->db->get_where('orders', [
            'id_order' => $id_order,
            'id_user' => $user_id
        ])->row_array();
        if (!$order) {
            show_404();
        }
        
        // Get order items with product details
        $this->db->select('oi.*, p.nama_product, p.gambar, p.desk_product');
        $this->db->from('order_items oi');
        $this->db->join('product p', 'p.id_product = oi.id_product');
        $this->db->where('oi.id_order', $id_order);
        $order_items = $this->db->get()->result_array();
        
        $shipping_address = $this->db->get_where('shipping_addresses', [
            'user_id' => $user_id,
            'is_primary' => 1
        ])->row_array();
        
        $data = [
            'order' => $order,
            'order_items' => $order_items,
            'shipping_address' => $shipping_address
        ];
        $this->load->view('orders/detail', $data);
    }
} 