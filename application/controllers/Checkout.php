<?php

class Checkout extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('cart_model');
        $this->load->library('session');
    }

    // In your proses_checkout method
    public function proses_checkout() {
        $id_user = $this->session->userdata('id_user');
        if (!$id_user) {
            redirect('auth');
        }
        
        // Get selected cart items from session
        $selected_cart_ids = $this->session->userdata('selected_cart_items');
        if (empty($selected_cart_ids)) {
            $this->session->set_flashdata('error', 'Sesi checkout telah berakhir, silakan pilih produk kembali');
            redirect('cart');
        }
        
        // Get only the selected cart items
        $cart_items = $this->cart_model->get_selected_cart_items($id_user, $selected_cart_ids);
        
        // Get payment method from form
        // Get the payment method from POST
        $metode_pembayaran = $this->input->post('metode_pembayaran');
        
        // Validate payment method against ENUM values
        if (!in_array($metode_pembayaran, ['cod', 'midtrans', 'transfer'])) {
            $metode_pembayaran = 'cod'; // Default to COD if invalid
        }
        
        // Add to order data
        $order_data = [
            'id_user' => $id_user,
            'tgl_pemesanan' => date('Y-m-d H:i:s'),
            'stts_pemesanan' => 'pending',
            'total_harga' => $total_amount,
            'stts_pembayaran' => 'pending',
            'metode_pembayaran' => $metode_pembayaran, // Save payment method
            'kurir' => $kurir,
            'ongkir' => $shipping_cost,
            'id_admin' => 1
        ];
        
        // Jika metode pembayaran adalah Midtrans, redirect ke controller Midtrans
        if ($metode_pembayaran == 'midtrans') {
            // Simpan data checkout ke session untuk digunakan oleh controller Midtrans
            $cart_items = $this->cart_model->get_cart_items($id_user);
            $total = 0;
            foreach ($cart_items as $item) {
                $total += $item['harga'] * $item['jumlah'];
            }
            
            // Ambil kurir dari POST
            $kurir = $this->input->post('kurir') ?? 'hijauloka';
            
            // Hitung ongkir berdasarkan jarak
            $ongkir = 0;
            if ($kurir === 'hijauloka') {
                // Ambil alamat utama user
                $primary_address = $this->db->get_where('shipping_addresses', [
                    'user_id' => $id_user,
                    'is_primary' => 1
                ])->row_array();

                if ($primary_address) {
                    $ongkir = $primary_address['jarak'] <= 1 ? 5000 : 10000;
                } else {
                    $ongkir = 5000; // Default ongkir jika tidak ada alamat
                }
            }
            
            $this->session->set_userdata('checkout_data', [
                'cart_items' => $cart_items,
                'total' => $total,
                'ongkir' => $ongkir,
                'kurir' => $kurir,
                'total_amount' => $total + $ongkir
            ]);
            
            redirect('midtrans/process_payment');
        }
        
        // Proses untuk COD
        $cart_items = $this->cart_model->get_cart_items($id_user);
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // Ambil metode pembayaran dan kurir dari POST
        $metode = $this->input->post('metode_pembayaran') ?? 'dana';
        $kurir = $this->input->post('kurir') ?? 'hijauloka';
        
        // Hitung ongkir berdasarkan jarak
        $ongkir = 0;
        if ($kurir === 'hijauloka') {
            // Ambil alamat utama user
            $primary_address = $this->db->get_where('shipping_addresses', [
                'user_id' => $id_user,
                'is_primary' => 1
            ])->row_array();

            if ($primary_address) {
                $ongkir = $primary_address['jarak'] <= 1 ? 5000 : 10000;
            } else {
                $ongkir = 5000; // Default ongkir jika tidak ada alamat
            }
        }

        // Insert ke orders
        $order_data = [
            'id_user' => $id_user,
            'tgl_pemesanan' => date('Y-m-d H:i:s'),
            'stts_pemesanan' => 'pending',
            'total_harga' => $total + $ongkir,
            'stts_pembayaran' => $metode == 'cod' ? 'belum_dibayar' : 'pending',
            'kurir' => $kurir,
            'ongkir' => $ongkir,
            'id_admin' => 1
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

        // Insert ke transaksi (dummy)
        $transaksi_data = [
            'order_id' => $id_order,
            'user_id' => $id_user,
            'total_bayar' => $total,
            'metode_pembayaran' => $metode,
            'status_pembayaran' => $metode == 'cod' ? 'pending' : 'pending',
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'payment_token' => 'DUMMY-' . uniqid(),
            'payment_response' => json_encode(['dummy' => true]),
            'expired_at' => $metode == 'dana' ? date('Y-m-d H:i:s', strtotime('+10 minutes')) : null
        ];
        $this->db->insert('transaksi', $transaksi_data);

        // Hapus cart user di database - Penting untuk semua metode pembayaran
        $this->db->where('id_user', $id_user);
        $this->db->delete('cart');
        
        // Log untuk debugging
        error_log("Cart cleared for user ID: $id_user after checkout with method: $metode");

        // Return JSON response untuk AJAX
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => true]);
            return;
        }

        // Redirect ke halaman QRIS jika DANA/QRIS, jika tidak ke sukses
        if ($metode == 'dana') {
            redirect('checkout/qris/' . $id_order);
        } else {
            redirect('checkout/sukses');
        }
    }

    // Metode lainnya tetap sama
    public function sukses() {
        $id_user = $this->session->userdata('id_user');
        
        // Double-check to ensure cart is cleared for all payment methods
        $this->db->where('id_user', $id_user);
        $this->db->delete('cart');
        
        // Clear cart in session if exists
        $this->session->unset_userdata('cart');
        
        $data['title'] = 'Checkout Berhasil';
        $this->load->view('checkout/sukses', $data);
    }

    public function metode() {
        $id_user = $this->session->userdata('id_user');
        if (!$id_user) {
            redirect('auth');
        }
        
        // Get selected items from POST instead of GET
        $selected_items = $this->input->post('selected_items');
        if (empty($selected_items)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu produk untuk checkout');
            redirect('cart');
        }
        
        // Convert comma-separated string to array
        $selected_item_ids = explode(',', $selected_items);
        
        // Get only the selected cart items
        $cart_items = $this->cart_model->get_selected_cart_items($id_user, $selected_item_ids);
        
        if (empty($cart_items)) {
            $this->session->set_flashdata('error', 'Produk yang dipilih tidak ditemukan');
            redirect('cart');
        }
        
        // Calculate total
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        
        // Get shipping addresses
        $shipping_addresses = $this->db->get_where('shipping_addresses', ['user_id' => $id_user])->result_array();
        
        // Get primary address
        $primary_address = $this->db->get_where('shipping_addresses', [
            'user_id' => $id_user,
            'is_primary' => 1
        ])->row_array();
        
        // Store selected items in session for later use
        $this->session->set_userdata('selected_cart_items', $selected_item_ids);
        
        $data = [
            'cart_items' => $cart_items,
            'total' => $total,
            'shipping_addresses' => $shipping_addresses,
            'primary_address' => $primary_address
        ];
        
        $this->load->view('checkout/metode', $data);
    }

    public function set_primary_address() {
        $user_id = $this->session->userdata('id_user');
        $primary_id = $this->input->post('primary_id');
        if ($primary_id) {
            // Set semua alamat user jadi non-primary
            $this->db->where('user_id', $user_id);
            $this->db->update('shipping_addresses', ['is_primary' => 0]);
            // Set alamat terpilih jadi primary
            $this->db->where('id', $primary_id);
            $this->db->where('user_id', $user_id);
            $this->db->update('shipping_addresses', ['is_primary' => 1]);
        }
        redirect('checkout/metode');
    }

    public function qris($id_order) {
        $order = $this->db->get_where('orders', ['id_order' => $id_order])->row_array();
        if (!$order) show_404();
        $expired_at = $this->db->get_where('transaksi', ['order_id' => $id_order])->row('expired_at');
        $data = [
            'order' => $order,
            'expired_at' => $expired_at
        ];
        $this->load->view('checkout/qris', $data);
    }
}
