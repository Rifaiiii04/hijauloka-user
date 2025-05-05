<?php

class Checkout extends CI_Controller {

    public function proses_checkout() {
        // 1. Ambil data user, cart, alamat, dll
        $id_user = $this->session->userdata('id_user');
        $cart_items = $this->cart_model->get_cart_items($id_user);
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // 2. Insert ke orders
        $order_data = [
            'id_user' => $id_user,
            'tgl_pemesanan' => date('Y-m-d H:i:s'),
            'stts_pemesanan' => 'pending',
            'total_harga' => $total,
            'stts_pembayaran' => 'lunas' // langsung lunas untuk dummy
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
            'metode_pembayaran' => 'dummy',
            'status_pembayaran' => 'lunas',
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'payment_token' => 'DUMMY-' . uniqid(),
            'payment_response' => json_encode(['dummy' => true]),
            'expired_at' => null
        ];
        $this->db->insert('transaksi', $transaksi_data);

        // 5. (Opsional) Hapus cart user
        $this->db->delete('cart', ['id_user' => $id_user]);

        // 6. Redirect ke halaman sukses
        redirect('checkout/sukses');
    }
}
