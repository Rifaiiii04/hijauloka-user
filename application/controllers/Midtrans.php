<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

class Midtrans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('cart_model');
        $this->load->library('session');
        
        // Enable error reporting for debugging
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        // Load Midtrans configuration
        $this->load->config('midtrans');
        
        // Check if Midtrans library is available
        if (!class_exists('\Midtrans\Config')) {
            log_message('error', 'Midtrans library not found. Make sure it is installed via Composer.');
            show_error('Midtrans library not found. Please contact administrator.');
        }
        
        // Initialize Midtrans configuration
        \Midtrans\Config::$serverKey = $this->config->item('midtrans_server_key');
        \Midtrans\Config::$clientKey = $this->config->item('midtrans_client_key');
        \Midtrans\Config::$isProduction = $this->config->item('midtrans_is_production');
        \Midtrans\Config::$isSanitized = $this->config->item('midtrans_is_sanitized');
        \Midtrans\Config::$is3ds = $this->config->item('midtrans_is_3ds');
    }
    
    public function process_payment() {
        // Check if user is logged in
        $id_user = $this->session->userdata('id_user');
        if (!$id_user) {
            redirect('auth');
        }
        
        // Get checkout data from POST
        $checkout_data = $this->input->post('checkout_data');
        if ($checkout_data) {
            $checkout_data = json_decode($checkout_data, true);
            $this->session->set_userdata('checkout_data', $checkout_data);
        } else {
            // If no checkout data in POST, try to get from session
            $checkout_data = $this->session->userdata('checkout_data');
        }
        
        if (empty($checkout_data)) {
            $this->session->set_flashdata('error', 'Sesi checkout telah berakhir, silakan pilih produk kembali');
            redirect('cart');
        }
        
        // Extract data
        $cart_items = $checkout_data['cart_items'];
        $total = $checkout_data['total'];
        $ongkir = $checkout_data['ongkir'];
        $kurir = $checkout_data['kurir'];
        $total_amount = $checkout_data['total_amount'];
        
        // Create unique order ID
        $order_id = 'HJL-' . time();
        
        // Prepare item details for Midtrans
        $item_details = [];
        foreach ($cart_items as $item) {
            $item_details[] = [
                'id' => $item['id_product'],
                'price' => $item['harga'],
                'quantity' => $item['jumlah'],
                'name' => substr($item['nama_product'], 0, 50) // Limit name length
            ];
        }
        
        // Add shipping cost
        $item_details[] = [
            'id' => 'shipping',
            'price' => $ongkir,
            'quantity' => 1,
            'name' => 'Ongkos Kirim'
        ];
        
        // Get customer data
        $customer = $this->db->get_where('user', ['id_user' => $id_user])->row();
        
        // Get primary shipping address
        $shipping_address = $this->db->get_where('shipping_addresses', [
            'user_id' => $id_user,
            'is_primary' => 1
        ])->row();
        
        // Prepare transaction parameters
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $total_amount
        ];
        
        // Customer details
        $customer_details = [
            'first_name' => $customer->nama,
            'email' => $customer->email,
            'phone' => $customer->no_tlp,
            'billing_address' => [
                'first_name' => $customer->nama,
                'phone' => $customer->no_tlp,
                'address' => $shipping_address ? $shipping_address->address : '',
                'postal_code' => $shipping_address ? $shipping_address->postal_code : '',
                'country_code' => 'IDN'
            ],
            'shipping_address' => [
                'first_name' => $shipping_address ? $shipping_address->recipient_name : $customer->nama,
                'phone' => $customer->no_tlp,
                'address' => $shipping_address ? $shipping_address->address : '',
                'postal_code' => $shipping_address ? $shipping_address->postal_code : '',
                'country_code' => 'IDN'
            ]
        ];
        
        // Create transaction
        $transaction = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details
        ];
        
        try {
            // Get Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($transaction);
            
            // Insert order to database
            $order_data = [
                'id_user' => $id_user,
                'tgl_pemesanan' => date('Y-m-d H:i:s'),
                'stts_pemesanan' => 'pending',
                'stts_pembayaran' => 'belum_dibayar',
                'total_harga' => $total,
                'ongkir' => $ongkir,
                'kurir' => $kurir,
                'metode_pembayaran' => 'midtrans',
                'order_id' => $order_id,
                'snap_token' => $snapToken
            ];
            
            $this->db->insert('orders', $order_data);
            $id_order = $this->db->insert_id();
            
            // Insert order items
            foreach ($cart_items as $item) {
                $order_item = [
                    'id_order' => $id_order,
                    'id_product' => $item['id_product'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['jumlah']
                ];
                $this->db->insert('order_items', $order_item);
            }
            
            // Delete cart items
            foreach ($checkout_data['cart_items'] as $item) {
                $this->db->where('id_cart', $item['id_cart']);
                $this->db->delete('cart');
            }
            
            // Clear checkout session
            $this->session->unset_userdata('checkout_data');
            $this->session->unset_userdata('selected_cart_items');
            
            // Load payment view
            $data = [
                'title' => 'Pembayaran',
                'snap_token' => $snapToken,
                'order_id' => $order_id,
                'id_order' => $id_order,
                'amount' => $total_amount,
                'client_key' => $this->config->item('midtrans_client_key')
            ];
            
            $this->load->view('checkout/midtrans_payment', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Error: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('checkout/metode');
        }
    }
    
    public function notification() {
        try {
            $notification = new \Midtrans\Notification();
            
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $order_id = $notification->order_id;
            $fraud = $notification->fraud_status;
            
            // Get order from database
            $order = $this->db->get_where('orders', ['order_id' => $order_id])->row();
            
            if (!$order) {
                exit;
            }
            
            // Handle different transaction status
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $this->db->where('order_id', $order_id);
                        $this->db->update('orders', ['stts_pembayaran' => 'challenge']);
                    } else {
                        $this->db->where('order_id', $order_id);
                        $this->db->update('orders', ['stts_pembayaran' => 'lunas']);
                    }
                }
            } else if ($transaction == 'settlement') {
                $this->db->where('order_id', $order_id);
                $this->db->update('orders', ['stts_pembayaran' => 'lunas']);
            } else if ($transaction == 'pending') {
                $this->db->where('order_id', $order_id);
                $this->db->update('orders', ['stts_pembayaran' => 'pending']);
            } else if ($transaction == 'deny') {
                $this->db->where('order_id', $order_id);
                $this->db->update('orders', ['stts_pembayaran' => 'ditolak']);
            } else if ($transaction == 'expire') {
                $this->db->where('order_id', $order_id);
                $this->db->update('orders', ['stts_pembayaran' => 'kadaluarsa']);
            } else if ($transaction == 'cancel') {
                $this->db->where('order_id', $order_id);
                $this->db->update('orders', ['stts_pembayaran' => 'dibatalkan']);
            }
            
            echo "OK";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function finish() {
        $order_id = $this->input->get('order_id');
        $status_code = $this->input->get('status_code');
        
        if ($status_code == 200) {
            $this->session->set_flashdata('success', 'Pembayaran berhasil');
        } else {
            $this->session->set_flashdata('error', 'Pembayaran belum selesai');
        }
        
        redirect('checkout/sukses');
    }
}