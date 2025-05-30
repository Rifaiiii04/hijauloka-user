<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session', 'form_validation']);
        $this->load->model(['order_model', 'product_model']);
    }

    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        $data['title'] = 'Pesanan Saya';
        $data['orders'] = $this->order_model->get_user_orders($this->session->userdata('id_user'));
        
        $this->load->view('orders/index', $data);
    }

    public function detail($id_order) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $data['title'] = 'Detail Pesanan';
        $data['order'] = $this->order_model->get_order_by_id($id_order);
        
        if (!$data['order'] || $data['order']['id_user'] != $this->session->userdata('id_user')) {
            show_404();
        }
        
        $data['order_items'] = $this->order_model->get_order_items($id_order);
        
        $shipping_address = $this->db->get_where('shipping_addresses', [
            'user_id' => $this->session->userdata('id_user'),
            'is_primary' => 1
        ])->row_array();
        
        $data['shipping_address'] = $shipping_address;
        
        $this->load->view('orders/detail', $data);
    }

    public function cancel($id_order) {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        // Get order details
        $order = $this->order_model->get_order_by_id($id_order);
        
        // Check if order exists, belongs to the current user, and is in 'pending' status
        if (!$order || $order['id_user'] != $this->session->userdata('id_user') || $order['stts_pemesanan'] != 'pending') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Pesanan tidak dapat dibatalkan']);
            return;
        }
        
        // Update order status to 'dibatalkan'
        $success = $this->order_model->update_order_status($id_order, 'dibatalkan');
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Pesanan berhasil dibatalkan' : 'Gagal membatalkan pesanan'
        ]);
    }

    public function mark_paid() {
        $order_id = $this->input->post('order_id');
        $this->db->where('id_order', $order_id);
        $this->db->update('orders', ['stts_pembayaran' => 'lunas']);
        echo json_encode(['success' => true]);
    }

    public function get_order_products($order_id)
    {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $id_user = $this->session->userdata('id_user');
        
        // Check if order belongs to user
        $order = $this->db->get_where('orders', [
            'id_order' => $order_id,
            'id_user' => $id_user
        ])->row_array();
        
        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            return;
        }
        
        // Get order products
        $this->db->select('oi.*, p.nama_product, p.gambar, p.desk_product');
        $this->db->from('order_items oi');
        $this->db->join('product p', 'p.id_product = oi.id_product');
        $this->db->where('oi.id_order', $order_id);
        $products = $this->db->get()->result_array();
        
        echo json_encode(['success' => true, 'products' => $products]);
    }

    public function complete($id_order)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $order = $this->order_model->get_order_by_id($id_order);
        
        if (!$order || $order['id_user'] != $this->session->userdata('id_user')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Pesanan tidak ditemukan'
                ]);
                return;
            }
            redirect('orders');
        }

        if ($order['stts_pemesanan'] != 'dikirim') {
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Status pesanan tidak valid'
                ]);
                return;
            }
            redirect('orders/detail/' . $id_order);
        }

        $data = [
            'stts_pemesanan' => 'selesai',
            'tgl_selesai' => date('Y-m-d H:i:s')
        ];

        if ($this->order_model->update_order($id_order, $data)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pesanan berhasil ditandai sebagai selesai'
                ]);
                return;
            }
            $this->session->set_flashdata('success', 'Pesanan berhasil ditandai sebagai selesai');
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui status pesanan'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Gagal memperbarui status pesanan');
        }

        if (!$this->input->is_ajax_request()) {
            redirect('orders/detail/' . $id_order);
        }
    }
}