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

        // Ambil metode pembayaran dari POST
        $metode = $this->input->post('metode_pembayaran') ?? 'dummy';

        // 2. Insert ke orders
        $order_data = [
            'id_user' => $id_user,
            'tgl_pemesanan' => date('Y-m-d H:i:s'),
            'stts_pemesanan' => 'pending',
            'total_harga' => $total,
            'stts_pembayaran' => $metode == 'cod' ? 'belum_dibayar' : 'lunas',
            'id_admin' => 1
        ];
        $this->db->insert('orders', $order_data);
        $id_order = $this->db->insert_id();

        // 3. Insert ke order_items
        foreach ($cart_items as $item) {
            $this->db->insert('order_items', [
                'id_order' => $id_order,
                'id_product' => $item['id_product'],
                'quantity' => $item['jumlah'],
                'subtotal' => $item['harga'] * $item['jumlah']
            ]);
        }

        // 4. Insert ke transaksi (dummy)
        $transaksi_data = [
            'order_id' => $id_order,
            'user_id' => $id_user,
            'total_bayar' => $total,
            'metode_pembayaran' => $metode,
            'status_pembayaran' => $metode == 'cod' ? 'pending' : 'lunas',
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'payment_token' => 'DUMMY-' . uniqid(),
            'payment_response' => json_encode(['dummy' => true]),
            'expired_at' => null
        ];
        $this->db->insert('transaksi', $transaksi_data);

        // Hapus cart user
        $this->db->delete('cart', ['id_user' => $id_user]);

        // Redirect ke halaman sukses
        redirect('checkout/sukses');
    }

    public function sukses() {
        $this->load->view('checkout/sukses');
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
}
