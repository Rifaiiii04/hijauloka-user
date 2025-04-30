<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('product_model');
        $this->load->model('user_model');
        $this->load->library('session');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function checkout() {
        // Get cart items from session
        $cart = $this->session->userdata('cart') ?? [];
        
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang belanja kosong');
            redirect('cart');
        }
        
        // Calculate total
        $total = 0;
        $items = [];
        
        foreach ($cart as $item) {
            $product = $this->product_model->get_product_by_id($item['id_product']);
            if (!$product) continue;
            
            $subtotal = $product['harga'] * $item['quantity'];
            $total += $subtotal;
            
            $items[] = [
                'id_product' => $item['id_product'],
                'nama_product' => $product['nama_product'],
                'harga' => $product['harga'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
                'gambar' => $product['gambar']
            ];
        }
        
        $data['items'] = $items;
        $data['total'] = $total;
        $data['title'] = 'Checkout';
        
        $this->load->view('templates/header', $data);
        $this->load->view('payment/checkout', $data);
        $this->load->view('templates/footer');
    }
    
    public function process() {
        // Get cart items from session
        $cart = $this->session->userdata('cart') ?? [];
        
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang belanja kosong');
            redirect('cart');
        }
        
        $payment_method = $this->input->post('payment_method');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $notes = $this->input->post('notes');
        
        if (empty($payment_method) || empty($address) || empty($phone)) {
            $this->session->set_flashdata('error', 'Semua field harus diisi');
            redirect('payment/checkout');
        }
        
        // Calculate total
        $total = 0;
        $items = [];
        
        foreach ($cart as $item) {
            $product = $this->product_model->get_product_by_id($item['id_product']);
            if (!$product) continue;
            
            // Check stock
            if ($product['stok'] < $item['quantity']) {
                $this->session->set_flashdata('error', 'Stok ' . $product['nama_product'] . ' tidak mencukupi');
                redirect('payment/checkout');
            }
            
            $subtotal = $product['harga'] * $item['quantity'];
            $total += $subtotal;
            
            $items[] = [
                'id_product' => $item['id_product'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal
            ];
        }
        
        // Start transaction
        $this->db->trans_start();
        
        try {
            // Create order
            $order_data = [
                'id_user' => $this->session->userdata('id_user'),
                'tgl_pemesanan' => date('Y-m-d H:i:s'),
                'stts_pemesanan' => 'Menunggu Konfirmasi',
                'stts_pembayaran' => ($payment_method == 'cod') ? 'Belum Dibayar' : 'Menunggu Pembayaran',
                'total_harga' => $total,
                'alamat_pengiriman' => $address,
                'no_telepon' => $phone,
                'catatan' => $notes,
                'metode_pembayaran' => $payment_method
            ];
            
            $id_order = $this->order_model->create_order($order_data);
            
            // Create order items
            foreach ($items as $item) {
                $order_item = [
                    'id_order' => $id_order,
                    'id_product' => $item['id_product'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal']
                ];
                
                $this->order_model->create_order_item($order_item);
                
                // Update stock
                $product = $this->product_model->get_product_by_id($item['id_product']);
                $new_stock = $product['stok'] - $item['quantity'];
                $this->product_model->update_product($item['id_product'], ['stok' => $new_stock]);
            }
            
            // Generate payment details
            $payment_data = [
                'id_order' => $id_order,
                'payment_method' => $payment_method,
                'amount' => $total,
                'status' => ($payment_method == 'cod') ? 'pending' : 'waiting',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->order_model->create_payment($payment_data);
            
            // Commit transaction
            $this->db->trans_complete();
            
            // Clear cart
            $this->session->unset_userdata('cart');
            
            // Redirect to payment page
            redirect('payment/confirmation/' . $id_order);
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->trans_rollback();
            
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('payment/checkout');
        }
    }
    
    public function confirmation($id_order) {
        $order = $this->order_model->get_order_by_id($id_order);
        
        if (!$order || $order['id_user'] != $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan');
            redirect('account/orders');
        }
        
        $payment = $this->order_model->get_payment_by_order_id($id_order);
        
        $data['order'] = $order;
        $data['payment'] = $payment;
        $data['title'] = 'Konfirmasi Pembayaran';
        
        $this->load->view('templates/header', $data);
        $this->load->view('payment/confirmation', $data);
        $this->load->view('templates/footer');
    }
    
    public function upload_proof($id_order) {
        $order = $this->order_model->get_order_by_id($id_order);
        
        if (!$order || $order['id_user'] != $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan');
            redirect('account/orders');
        }
        
        // Upload configuration
        $config['upload_path'] = './uploads/payment_proofs/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = 'payment_' . $id_order . '_' . time();
        
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('payment_proof')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('payment/confirmation/' . $id_order);
        } else {
            $upload_data = $this->upload->data();
            
            // Update payment
            $payment_data = [
                'proof_image' => $upload_data['file_name'],
                'status' => 'verifying',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->order_model->update_payment($id_order, $payment_data);
            
            // Update order status
            $order_data = [
                'stts_pembayaran' => 'Menunggu Verifikasi'
            ];
            
            $this->order_model->update_order($id_order, $order_data);
            
            $this->session->set_flashdata('success', 'Bukti pembayaran berhasil diunggah');
            redirect('account/orders');
        }
    }
}