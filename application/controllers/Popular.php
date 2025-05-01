<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popular extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('product_model');
        $this->load->model('wishlist_model'); // Add this line
    }

    public function index() {
        $kategori_id = $this->input->get('kategori');
        $data['categories'] = $this->product_model->get_categories();
        $data['produk_populer'] = $this->product_model->get_popular_products_by_category($kategori_id);
        $data['selected_category'] = $kategori_id;
        $this->load->view('popular/index', $data);
    }
}