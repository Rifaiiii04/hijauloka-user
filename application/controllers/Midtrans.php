<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

class Midtrans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->library('session');
        
        // Load Midtrans config
        $this->load->config('midtrans');
        
        // Configure Midtrans with values from config file
        \Midtrans\Config::$serverKey = $this->config->item('midtrans_server_key');
        \Midtrans\Config::$isProduction = $this->config->item('midtrans_is_production');
        \Midtrans\Config::$isSanitized = $this->config->item('midtrans_is_sanitized');
        \Midtrans\Config::$is3ds = $this->config->item('midtrans_is_3ds');
    }
    
    public function process_payment() {
        $id_user = $this->session->userdata('id_user');
        if (!$id_user) {
            redirect('auth');
        }
        
        // Ambil data dari POST
        $amount = $this->input->post('amount');
        $shipping_cost = $this->input->post('shipping_cost');
        $kurir = $this->input->post('kurir');
        $metode_pembayaran = $this->input->post('metode_pembayaran'); // Ambil metode pembayaran
        
        // Pastikan metode pembayaran adalah midtrans
        if ($metode_pembayaran !== 'midtrans') {
            $this->session->set_flashdata('error', 'Metode pembayaran tidak valid');
            redirect('checkout/metode');
        }
        
        // Ambil data cart
        // Get selected cart items from session
        $selected_cart_items = $this->session->userdata('selected_cart_items');
        
        if (empty($selected_cart_items)) {
            redirect('cart');
            return;
        }
        
        // Convert comma-separated string to array if needed
        if (is_string($selected_cart_items)) {
            $selected_cart_items = explode(',', $selected_cart_items);
        }
        
        // Get only the selected cart items
        $cart_items = $this->cart_model->get_selected_cart_items($id_user, $selected_cart_items);
        
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        
        // Buat order ID unik
        $order_id = 'HJL-' . time();
        
        // Siapkan item details untuk Midtrans
        $item_details = [];
        foreach ($cart_items as $item) {
            $item_details[] = [
                'id' => $item['id_product'],
                'price' => $item['harga'],
                'quantity' => $item['jumlah'],
                'name' => substr($item['nama_product'], 0, 50) // Batasi panjang nama
            ];
        }
        
        // Tambahkan biaya pengiriman
        $item_details[] = [
            'id' => 'SHIPPING',
            'price' => $shipping_cost,
            'quantity' => 1,
            'name' => 'Biaya Pengiriman'
        ];
        
        // Ambil data customer - perbaikan untuk mengakses properti yang benar
        $customer = $this->db->get_where('user', ['id_user' => $id_user])->row();
        
        // Ambil alamat pengiriman utama
        $shipping_address = $this->db->get_where('shipping_addresses', [
            'user_id' => $id_user,
            'is_primary' => 1
        ])->row();
        
        // Siapkan parameter transaksi
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $amount
        ];
        
        // Perbaikan untuk mengakses properti yang benar
        $billing_address = [
            'first_name' => $customer->nama,
            'phone' => $customer->no_tlp, // Perbaikan: no_tlp bukan no_telp
            'address' => $shipping_address ? $shipping_address->address : '',
            'postal_code' => $shipping_address ? $shipping_address->postal_code : '',
            'country_code' => 'IDN'
        ];
        
        // Perbaikan untuk mengakses properti yang benar
        $shipping_address_params = [
            'first_name' => $shipping_address ? $shipping_address->recipient_name : $customer->nama,
            'phone' => $customer->no_tlp, // Perbaikan: no_tlp bukan no_telp
            'address' => $shipping_address ? $shipping_address->address : '',
            'postal_code' => $shipping_address ? $shipping_address->postal_code : '',
            'country_code' => 'IDN'
        ];
        
        // Perbaikan untuk mengakses properti yang benar
        $customer_details = [
            'first_name' => $customer->nama,
            'email' => $customer->email,
            'phone' => $customer->no_tlp, // Perbaikan: no_tlp bukan no_telp
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address_params
        ];
        
        // Siapkan parameter untuk Snap
        $params = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details
        ];
        
        try {
            // Dapatkan Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Get payment method
            $metode_pembayaran = $this->input->post('metode_pembayaran');
            
            // Make sure it's a valid method
            if (!in_array($metode_pembayaran, ['cod', 'midtrans', 'transfer'])) {
                $metode_pembayaran = 'midtrans'; // Default to midtrans if invalid
            }
            
            // Add to order data
            $order_data = [
                'id_user' => $id_user,
                'tgl_pemesanan' => date('Y-m-d H:i:s'),
                'stts_pemesanan' => 'pending',
                'total_harga' => $amount,
                'stts_pembayaran' => 'lunas', // Set to 'lunas' for Midtrans payments
                'metode_pembayaran' => $metode_pembayaran,
                'kurir' => $kurir,
                'ongkir' => $shipping_cost,
                'id_admin' => 1,
                'midtrans_order_id' => $order_id
            ];
            $this->db->insert('orders', $order_data);
            $id_order = $this->db->insert_id();
            
            // Insert ke order_items dan update stok produk
            foreach ($cart_items as $item) {
                $this->db->insert('order_items', [
                    'id_order' => $id_order,
                    'id_product' => $item['id_product'],
                    'quantity' => $item['jumlah'],
                    'subtotal' => $item['harga'] * $item['jumlah']
                ]);
                
                // Update stok produk
                $product = $this->db->get_where('product', ['id_product' => $item['id_product']])->row_array();
                if ($product) {
                    $new_stock = $product['stok'] - $item['jumlah'];
                    $this->db->where('id_product', $item['id_product']);
                    $this->db->update('product', ['stok' => $new_stock]);
                }
            }
            
            // Insert ke transaksi
            // Update transaksi data to include payment method
            $transaksi_data = [
                'order_id' => $id_order,
                'user_id' => $id_user,
                'total_bayar' => $amount,
                'metode_pembayaran' => $metode_pembayaran, // Use the payment method from form
                'status_pembayaran' => 'pending',
                'tanggal_transaksi' => date('Y-m-d H:i:s'),
                'payment_token' => $snapToken,
                'payment_response' => json_encode(['snap_token' => $snapToken]),
                'expired_at' => date('Y-m-d H:i:s', strtotime('+1 day'))
            ];
            $this->db->insert('transaksi', $transaksi_data);
            
            // Hapus cart user di database
            // CHANGE THIS: Only delete the selected cart items instead of all items
            if (is_string($selected_cart_items)) {
                $selected_cart_items = explode(',', $selected_cart_items);
            }
            
            // Delete only selected cart items
            $this->db->where('id_user', $id_user);
            $this->db->where_in('id_cart', $selected_cart_items);
            $this->db->delete('cart');
            
            // Kirim token ke view dengan opsi untuk menampilkan langsung di halaman
            $data['snap_token'] = $snapToken;
            $data['order_id'] = $order_id;
            $data['id_order'] = $id_order;
            $data['amount'] = $amount;
            $data['customer_name'] = $customer->nama;
            $data['customer_email'] = $customer->email;
            $data['customer_phone'] = $customer->no_tlp;
            $this->load->view('checkout/midtrans_payment', $data);
            
        } catch (\Exception $e) {
            // Log error
            error_log('Midtrans Error: ' . $e->getMessage());
            
            // Tampilkan pesan error
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
            redirect('checkout/metode');
        }
    }
    
    public function notification_handler() {
        // Ambil notification JSON dari Midtrans
        $notification = new \Midtrans\Notification();
        
        // Ambil data transaksi
        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $order_id = $notification->order_id;
        $fraud = $notification->fraud_status;
        
        // Cari order berdasarkan midtrans_order_id
        $order = $this->db->get_where('orders', ['midtrans_order_id' => $order_id])->row();
        if (!$order) {
            header('HTTP/1.1 404 Not Found');
            echo "Order ID not found";
            exit;
        }
        
        $id_order = $order->id_order;
        
        // Handle berbagai status transaksi
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // Update status order
                    $this->db->where('id_order', $id_order);
                    $this->db->update('orders', ['stts_pembayaran' => 'challenge']);
                    
                    // Update status transaksi
                    $this->db->where('order_id', $id_order);
                    $this->db->update('transaksi', ['status_pembayaran' => 'challenge']);
                } else {
                    // Update status order
                    $this->db->where('id_order', $id_order);
                    $this->db->update('orders', ['stts_pembayaran' => 'lunas', 'stts_pemesanan' => 'diproses']);
                    
                    // Update status transaksi
                    $this->db->where('order_id', $id_order);
                    $this->db->update('transaksi', ['status_pembayaran' => 'dibayar']);
                }
            }
        } else if ($transaction == 'settlement') {
            // Update status order
            $this->db->where('id_order', $id_order);
            $this->db->update('orders', ['stts_pembayaran' => 'dibayar', 'stts_pemesanan' => 'diproses']);
            
            // Update status transaksi
            $this->db->where('order_id', $id_order);
            $this->db->update('transaksi', ['status_pembayaran' => 'success']);
        } else if ($transaction == 'pending') {
            // Update status order
            $this->db->where('id_order', $id_order);
            $this->db->update('orders', ['stts_pembayaran' => 'pending']);
            
            // Update status transaksi
            $this->db->where('order_id', $id_order);
            $this->db->update('transaksi', ['status_pembayaran' => 'pending']);
        } else if ($transaction == 'deny') {
            // Update status order
            $this->db->where('id_order', $id_order);
            $this->db->update('orders', ['stts_pembayaran' => 'ditolak']);
            
            // Update status transaksi
            $this->db->where('order_id', $id_order);
            $this->db->update('transaksi', ['status_pembayaran' => 'denied']);
        } else if ($transaction == 'expire') {
            // Update status order
            $this->db->where('id_order', $id_order);
            $this->db->update('orders', ['stts_pembayaran' => 'kadaluarsa']);
            
            // Update status transaksi
            $this->db->where('order_id', $id_order);
            $this->db->update('transaksi', ['status_pembayaran' => 'expired']);
        } else if ($transaction == 'cancel') {
            // Update status order
            $this->db->where('id_order', $id_order);
            $this->db->update('orders', ['stts_pembayaran' => 'dibatalkan']);
            
            // Update status transaksi
            $this->db->where('order_id', $id_order);
            $this->db->update('transaksi', ['status_pembayaran' => 'canceled']);
        }
        
        // Kirim respon OK ke Midtrans
        header('HTTP/1.1 200 OK');
    }
    
    public function finish() {
        $order_id = $this->input->get('order_id');
        $status = $this->input->get('transaction_status');
        
        // Cari order berdasarkan midtrans_order_id
        $order = $this->db->get_where('orders', ['midtrans_order_id' => $order_id])->row();
        if (!$order) {
            show_404();
        }
        
        if ($status == 'settlement' || $status == 'capture') {
            // Pembayaran berhasil
            $this->session->set_flashdata('success', 'Pembayaran berhasil!');
        } else {
            // Pembayaran gagal atau pending
            $this->session->set_flashdata('info', 'Status pembayaran: ' . $status);
        }
        
        redirect('checkout/sukses');
    }
    
    public function check_status($id_order) {
        // Ambil data order
        $order = $this->db->get_where('orders', ['id_order' => $id_order])->row();
        if (!$order) {
            show_404();
        }
        
        // Ambil midtrans_order_id
        $midtrans_order_id = $order->midtrans_order_id;
        
        try {
            // Get transaction status dari Midtrans API
            $status = \Midtrans\Transaction::status($midtrans_order_id);
            
            // Update status pembayaran berdasarkan response dari Midtrans
            $transaction_status = $status->transaction_status;
            
            if ($transaction_status == 'settlement' || $transaction_status == 'capture') {
                // Update status order
                $this->db->where('id_order', $id_order);
                $this->db->update('orders', ['stts_pembayaran' => 'dibayar', 'stts_pemesanan' => 'diproses']);
                
                // Update status transaksi
                $this->db->where('order_id', $id_order);
                $this->db->update('transaksi', ['status_pembayaran' => 'success']);
                
                $this->session->set_flashdata('success', 'Pembayaran berhasil!');
                redirect('checkout/sukses');
            } else if ($transaction_status == 'pending') {
                // Redirect kembali ke halaman pembayaran dengan pesan
                $this->session->set_flashdata('info', 'Pembayaran masih dalam status pending. Silakan selesaikan pembayaran Anda.');
                
                // Ambil token dari database
                $transaksi = $this->db->get_where('transaksi', ['order_id' => $id_order])->row();
                
                if ($transaksi) {
                    $data['snap_token'] = $transaksi->payment_token;
                    $data['order_id'] = $midtrans_order_id;
                    $data['id_order'] = $id_order;
                    $this->load->view('checkout/midtrans_payment', $data);
                } else {
                    redirect('checkout/metode');
                }
            } else {
                // Status lainnya (deny, cancel, expire, dll)
                $this->session->set_flashdata('error', 'Status pembayaran: ' . $transaction_status . '. Silakan coba lagi.');
                redirect('checkout/metode');
            }
        } catch (\Exception $e) {
            // Log error
            error_log('Midtrans Status Check Error: ' . $e->getMessage());
            
            // Tampilkan pesan error
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memeriksa status pembayaran: ' . $e->getMessage());
            redirect('checkout/metode');
        }
    }

    public function direct_payment($id_order) {
        // Ambil data order
        $order = $this->db->get_where('orders', ['id_order' => $id_order])->row();
        if (!$order) {
            show_404();
        }
        
        // Ambil data transaksi
        $transaksi = $this->db->get_where('transaksi', ['order_id' => $id_order])->row();
        if (!$transaksi) {
            $this->session->set_flashdata('error', 'Data transaksi tidak ditemukan');
            redirect('user/orders');
        }
        
        // Ambil data user
        $user = $this->db->get_where('user', ['id_user' => $order->id_user])->row();
        
        // Siapkan data untuk view
        $data['snap_token'] = $transaksi->payment_token;
        $data['order_id'] = $order->midtrans_order_id;
        $data['id_order'] = $id_order;
        $data['amount'] = $order->total_harga;
        $data['customer_name'] = $user->nama;
        $data['customer_email'] = $user->email;
        $data['customer_phone'] = $user->no_tlp;
        
        $this->load->view('checkout/midtrans_payment', $data);
    }
}