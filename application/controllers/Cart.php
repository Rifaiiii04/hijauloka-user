<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('product_model');
    }
    
    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        $data['title'] = 'Keranjang Belanja';
        $data['cart_items'] = $this->cart_model->get_cart_items($this->session->userdata('id_user'));
        
        // Calculate total
        $total = 0;
        foreach ($data['cart_items'] as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        $data['total'] = $total;
        
        $this->load->view('cart/index', $data);
    }
    
    public function add() {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login']);
            return;
        }
        $id_user = $this->session->userdata('id_user');
        $id_product = $this->input->post('id_product');
        $jumlah = $this->input->post('quantity'); // Pastikan 'quantity' sesuai dengan JS

        if (!$id_product || !$jumlah) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Cek apakah produk sudah ada di cart user
        $existing = $this->db->get_where('cart', [
            'id_user' => $id_user,
            'id_product' => $id_product
        ])->row();

        if ($existing) {
            // Update jumlah
            $this->db->where('id_cart', $existing->id_cart);
            $this->db->update('cart', ['jumlah' => $existing->jumlah + $jumlah]);
        } else {
            // Insert baru
            $this->db->insert('cart', [
                'id_user' => $id_user,
                'id_product' => $id_product,
                'jumlah' => $jumlah
            ]);
        }

        echo json_encode(['success' => true]);
    }
}