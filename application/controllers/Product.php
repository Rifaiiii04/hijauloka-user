<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('product_model');
        $this->load->model('wishlist_model');
    }

    public function detail($id) {
        // Get product details
        $data['product'] = $this->db->get_where('product', ['id_product' => $id])->row_array();
        
        if (!$data['product']) {
            show_404();
        }

        // Get product categories
        $this->db->select('c.nama_kategori');
        $this->db->from('category c');
        $this->db->join('product_category pc', 'c.id_kategori = pc.id_kategori');
        $this->db->where('pc.id_product', $id);
        $data['categories'] = $this->db->get()->result_array();

        // Process product images
        $data['images'] = !empty($data['product']['gambar']) ? 
            array_map('trim', explode(',', $data['product']['gambar'])) : 
            ['default.jpg'];

        // Check if product is wishlisted
        $data['is_wishlisted'] = false;
        if ($this->session->userdata('logged_in')) {
            $data['is_wishlisted'] = $this->wishlist_model->is_wishlisted(
                $this->session->userdata('id_user'),
                $id
            );
        }

        $this->load->view('product/detail', $data);
    }
}