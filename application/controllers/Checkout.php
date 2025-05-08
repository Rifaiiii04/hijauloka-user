<?php

class Checkout extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('cart_model');
        $this->load->library('session');
    }

    public function proses_checkout() {
        $id_user = $this->session->userdata('id_user');
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

        // Redirect ke halaman QRIS jika DANA/QRIS, jika tidak ke sukses
        if ($metode == 'dana') {
            redirect('checkout/qris/' . $id_order);
        } else {
            redirect('checkout/sukses');
        }
    }

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
        $user_id = $this->session->userdata('id_user');
        $shipping_addresses = $this->db->get_where('shipping_addresses', [
            'user_id' => $user_id
        ])->result_array();

        // Ambil primary address
        $primary_address = null;
        foreach ($shipping_addresses as $addr) {
            if ($addr['is_primary']) {
                $primary_address = $addr;
                break;
            }
        }
        if (!$primary_address && !empty($shipping_addresses)) {
            $primary_address = $shipping_addresses[0];
        }

        $cart_items = $this->cart_model->get_cart_items($user_id);
        $total = 0;
        $total_items = 0;
        foreach ($cart_items as $item) {
            $total += $item['harga'] * $item['jumlah'];
            $total_items += $item['jumlah'];
        }

        $data = [
            'shipping_addresses' => $shipping_addresses,
            'primary_address' => $primary_address,
            'cart_items' => $cart_items,
            'total' => $total,
            'total_items' => $total_items
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
