<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popular extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->model('wishlist_model');
        $this->load->library('session');
    }

    public function index() {
        $kategori_id = $this->input->get('kategori');
        $data['categories'] = $this->Product_model->get_categories();
        $data['produk_populer'] = $this->Product_model->get_popular_products_by_category($kategori_id);
        $data['selected_category'] = $kategori_id;
        $this->load->view('popular/index', $data);
    }
}