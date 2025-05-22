<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('cart_model');
        
        // Check if user is logged in for most methods
        if (!in_array($this->router->fetch_method(), ['index', 'view'])) {
            if (!$this->session->userdata('logged_in')) {
                redirect('auth');
            }
        }
    }
    
    public function detail($id_order = null) {
        // If no ID provided, redirect to history
        if ($id_order === null) {
            redirect('order/history');
        }
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Get order details
        $data['order'] = $this->order_model->get_order_by_id($id_order);
        
        // Check if order exists and belongs to the current user
        if (!$data['order'] || $data['order']['id_user'] != $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan');
            redirect('order/history');
        }
        
        // Get order items
        $data['items'] = $this->order_model->get_order_items($id_order);
        
        // Load view
        $data['title'] = 'Detail Pesanan';
        $this->load->view('order/detail', $data);
    }
    
    public function complete($id_order) {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        // Get order details
        $order = $this->order_model->get_order_by_id($id_order);
        
        // Check if order exists, belongs to the current user, and is in 'dikirim' status
        if (!$order || $order['id_user'] != $this->session->userdata('id_user') || $order['stts_pemesanan'] != 'dikirim') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid order']);
            return;
        }
        
        // Update order status to 'selesai'
        $success = $this->order_model->update_order_status($id_order, 'selesai');
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }
    
    public function history() {
        $data['title'] = 'Riwayat Pesanan';
        $data['orders'] = $this->order_model->get_user_orders($this->session->userdata('id_user'));
        $this->load->view('order/history', $data);
    }
}