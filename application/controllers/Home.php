<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('wishlist_model');
    }

    public function index() {
        $this->load->model('product_model');
        $this->load->model('wishlist_model');
        
        $data['produk_terbaru'] = $this->product_model->get_latest_products();
        
        // Get categories and ratings for each product
        foreach ($data['produk_terbaru'] as &$produk) {
            $produk['categories'] = $this->product_model->get_product_categories($produk['id_product']);
            $produk['rating'] = $this->product_model->get_product_rating($produk['id_product']);
            
            // Check if product is in user's wishlist
            $produk['is_wishlisted'] = false;
            if ($this->session->userdata('logged_in')) {
                $produk['is_wishlisted'] = $this->wishlist_model->is_wishlisted(
                    $this->session->userdata('id_user'),
                    $produk['id_product']
                );
            }
        }
        
        $this->load->view('home/index', $data);
    }
}
