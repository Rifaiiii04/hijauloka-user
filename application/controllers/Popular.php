<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popular extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function index() {
        $data['produk_populer'] = $this->Product_model->get_popular_products();
        $this->load->view('popular/index', $data);
    }
}