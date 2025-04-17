<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlist extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load model if you have a Wishlist model
        // $this->load->model('Wishlist_model');
    }

    public function index()
    {
        // Example static wishlist data, replace with data from your model/database as needed
        $data['wishlist'] = [
            [
                'name' => 'Monstera Deliciosa',
                'image' => base_url('assets/img/products/monstera.jpg'),
                'price' => 'Rp 120.000'
            ],
            [
                'name' => 'Sansevieria',
                'image' => base_url('assets/img/products/sansevieria.jpg'),
                'price' => 'Rp 80.000'
            ],
            [
                'name' => 'Pothos Marble Queen',
                'image' => base_url('assets/img/products/pothos.jpg'),
                'price' => 'Rp 65.000'
            ]
        ];
        $data['title'] = 'Wishlist';
        $this->load->view('templates/header', $data);
        $this->load->view('wishlist/index', $data);
        $this->load->view('templates/footer');
    }
}