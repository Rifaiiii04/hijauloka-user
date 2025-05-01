<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('product_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('id_user');
        
        // Get cart items with product details
        $data['cart_items'] = $this->cart_model->get_cart_items($user_id);
        $data['total'] = 0;
        
        // Calculate total
        foreach ($data['cart_items'] as $item) {
            $data['total'] += $item['harga'] * $item['jumlah'];
        }
        
        $this->load->view('templates/header');
        $this->load->view('cart/index', $data);
        $this->load->view('templates/footer');
    }

    public function update() {
        $cart_id = $this->input->post('cart_id');
        $quantity = $this->input->post('quantity');
        
        $result = $this->cart_model->update_quantity($cart_id, $quantity);
        
        echo json_encode(['success' => $result]);
    }

    public function remove() {
        $cart_id = $this->input->post('cart_id');
        $result = $this->cart_model->remove_item($cart_id);
        
        echo json_encode(['success' => $result]);
    }

    public function add() {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
    
        $id_product = $this->input->post('id_product');
        $jumlah = $this->input->post('jumlah');
        $user_id = $this->session->userdata('id_user');
    
        // Check if product already in cart
        $existing_cart = $this->db->get_where('cart', [
            'id_user' => $user_id,
            'id_product' => $id_product
        ])->row();
    
        if ($existing_cart) {
            // Update quantity if already in cart
            $this->db->where('id_cart', $existing_cart->id_cart);
            $this->db->update('cart', ['jumlah' => $existing_cart->jumlah + $jumlah]);
        } else {
            // Add new item to cart
            $this->db->insert('cart', [
                'id_user' => $user_id,
                'id_product' => $id_product,
                'jumlah' => $jumlah
            ]);
        }
    
        echo json_encode(['success' => true]);
    }
}