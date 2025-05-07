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
        $data['order'] = $this->order_model->get_order_detail($id_order);
        
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

    public function cancel_order() {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $order_id = $this->input->post('order_id');
        $user_id = $this->session->userdata('id_user');

        $result = $this->order_model->cancel_order($order_id, $user_id);
        echo json_encode($result);
    }

    public function mark_paid() {
        $order_id = $this->input->post('order_id');
        $this->db->where('id_order', $order_id);
        $this->db->update('orders', ['stts_pembayaran' => 'lunas']);
        echo json_encode(['success' => true]);
    }
} 