<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Home_model'); // Load model Home
    }

    public function index() {
        
        $data['judul'] = 'Beranda - Hijauloka';
        $data['produk_terbaru'] = $this->Home_model->getProdukTerbaru();
        $data['produk_terlaris'] = $this->Home_model->get_produk_terlaris();
        
        $this->load->view('home/index', $data);
    }
}
