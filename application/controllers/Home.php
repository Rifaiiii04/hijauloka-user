<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('wishlist_model');
    }

    public function index() {
        $this->load->model('Product_model');
        $data['produk_terlaris'] = $this->Product_model->get_best_sellers();
        $data['produk_terbaru'] = $this->Product_model->get_latest_products();
        $this->load->view('home/index', $data);
    }
}
