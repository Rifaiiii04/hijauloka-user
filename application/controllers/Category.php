<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model(['product_model', 'category_model']);
    }

    public function plants() {
        $data['title'] = 'Tanaman Hias';
        $data['products'] = $this->product_model->get_products_by_category('plants');
        $data['category'] = 'plants';
        
        $this->load->view('templates/header2', $data);
        $this->load->view('category/index', $data);
        $this->load->view('templates/footer');
    }

    public function seeds() {
        $data['title'] = 'Benih Tanaman';
        $data['category'] = 'seeds';
        
        $this->load->view('templates/header2', $data);
        $this->load->view('category/coming_soon', $data);
        $this->load->view('templates/footer');
    }

    public function pots() {
        $data['title'] = 'Pot Tanaman';
        $data['category'] = 'pots';
        
        $this->load->view('templates/header2', $data);
        $this->load->view('category/coming_soon', $data);
        $this->load->view('templates/footer');
    }
} 