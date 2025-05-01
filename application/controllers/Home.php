<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('wishlist_model');
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Cart_model', 'cart_model');
    }

    public function index() {
        $user_id = $this->session->userdata('logged_in') ? $this->session->userdata('id_user') : null;
        
        $data = [
            'cart_count' => $user_id ? $this->cart_model->get_cart_count($user_id) : 0,
            'produk_terbaru' => $this->product_model->get_latest_products(8, $user_id),
            'featured_products' => $this->product_model->get_featured_products()
        ];
        
        $this->load->view('templates/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/footer');
    }
}
