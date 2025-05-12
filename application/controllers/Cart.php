<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session', 'form_validation']);
        $this->load->model(['cart_model', 'product_model', 'order_model']);
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
    
    // Add a new method to handle checkout with selected items
    public function checkout() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        $selected_items = $this->input->post('selected_items');
        
        if (empty($selected_items)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu produk untuk checkout');
            redirect('cart');
        }
        
        // Store selected items in session for use in checkout process
        $this->session->set_userdata('selected_cart_items', $selected_items);
        
        // Redirect to checkout page
        redirect('checkout/metode');
    }
    
    public function add() {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login']);
            return;
        }
        $id_user = $this->session->userdata('id_user');
        $id_product = $this->input->post('id_product');
        $jumlah = $this->input->post('quantity');

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

    public function update_quantity() {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $cart_id = $this->input->post('cart_id');
        $quantity = $this->input->post('quantity');
        $user_id = $this->session->userdata('id_user');

        // Validate quantity
        if ($quantity < 1) {
            echo json_encode(['success' => false, 'message' => 'Jumlah minimal 1']);
            return;
        }

        // Get cart item
        $cart_item = $this->db->get_where('cart', [
            'id_cart' => $cart_id,
            'id_user' => $user_id
        ])->row_array();

        if (!$cart_item) {
            echo json_encode(['success' => false, 'message' => 'Item tidak ditemukan']);
            return;
        }

        // Get product stock
        $product = $this->db->get_where('product', ['id_product' => $cart_item['id_product']])->row_array();
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
            return;
        }

        // Check stock
        if ($quantity > $product['stok']) {
            echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
            return;
        }

        // Update quantity
        $this->db->where('id_cart', $cart_id);
        $this->db->update('cart', ['jumlah' => $quantity]);

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengupdate jumlah']);
        }
    }

    public function remove() {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $cart_id = $this->input->post('cart_id');
        $user_id = $this->session->userdata('id_user');

        // Verify cart item belongs to user
        $cart_item = $this->db->get_where('cart', [
            'id_cart' => $cart_id,
            'id_user' => $user_id
        ])->row_array();

        if (!$cart_item) {
            echo json_encode(['success' => false, 'message' => 'Item tidak ditemukan']);
            return;
        }

        // Remove item
        $this->db->where('id_cart', $cart_id);
        $this->db->delete('cart');

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus item']);
        }
    }

    public function cancel_order() {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $order_id = $this->input->post('order_id');
        $user_id = $this->session->userdata('id_user');

        // Get order details
        $order = $this->db->get_where('orders', [
            'id_order' => $order_id,
            'id_user' => $user_id
        ])->row_array();

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan']);
            return;
        }

        // Check if order status is 'menunggu'
        if ($order['status'] !== 'menunggu') {
            echo json_encode(['success' => false, 'message' => 'Pesanan tidak dapat dibatalkan karena status sudah berubah']);
            return;
        }

        // Start transaction
        $this->db->trans_start();

        // Update order status to 'dibatalkan'
        $this->db->where('id_order', $order_id);
        $this->db->update('orders', ['status' => 'dibatalkan']);

        // Get order items
        $order_items = $this->db->get_where('order_items', ['id_order' => $order_id])->result_array();

        // Return stock for each item
        foreach ($order_items as $item) {
            $this->db->set('stok', 'stok + ' . $item['jumlah'], FALSE);
            $this->db->where('id_product', $item['id_product']);
            $this->db->update('product');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['success' => false, 'message' => 'Gagal membatalkan pesanan']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Pesanan berhasil dibatalkan']);
        }
    }

    public function clear() {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
            return;
        }
    
        $user_id = $this->session->userdata('id_user');
        
        // Clear cart in database
        $this->db->where('id_user', $user_id);
        $this->db->delete('cart');
        
        // Clear cart in session
        $this->session->unset_userdata('cart');
        
        echo json_encode(['success' => true, 'message' => 'Keranjang berhasil dikosongkan']);
    }
}