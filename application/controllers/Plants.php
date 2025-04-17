<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plants extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function indoor()
    {
        // Example static data for indoor plants
        $data['plants'] = [
            [
                'name' => 'Monstera Deliciosa',
                'image' => base_url('assets/img/products/monstera.jpg'),
                'price' => 'Rp 120.000'
            ],
            [
                'name' => 'Peace Lily',
                'image' => base_url('assets/img/products/peace_lily.jpg'),
                'price' => 'Rp 95.000'
            ],
            [
                'name' => 'Snake Plant',
                'image' => base_url('assets/img/products/snake_plant.jpg'),
                'price' => 'Rp 80.000'
            ]
        ];
        $data['title'] = 'Indoor Plants';
        $this->load->view('templates/header', $data);
        $this->load->view('plants/indoor', $data);
        $this->load->view('templates/footer');
    }

    public function outdoor()
    {
        // Example static data for outdoor plants
        $data['plants'] = [
            [
                'name' => 'Bougainvillea',
                'image' => base_url('assets/img/products/bougainvillea.jpg'),
                'price' => 'Rp 70.000'
            ],
            [
                'name' => 'Jasmine',
                'image' => base_url('assets/img/products/jasmine.jpg'),
                'price' => 'Rp 60.000'
            ],
            [
                'name' => 'Hibiscus',
                'image' => base_url('assets/img/products/hibiscus.jpg'),
                'price' => 'Rp 85.000'
            ]
        ];
        $data['title'] = 'Outdoor Plants';
        $this->load->view('templates/header', $data);
        $this->load->view('plants/outdoor', $data);
        $this->load->view('templates/footer');
    }

    public function mudah_dirawat()
    {
        // Example static data for easy-care plants
        $data['plants'] = [
            [
                'name' => 'Aloe Vera',
                'image' => base_url('assets/img/products/aloe_vera.jpg'),
                'price' => 'Rp 50.000'
            ],
            [
                'name' => 'Cactus',
                'image' => base_url('assets/img/products/cactus.jpg'),
                'price' => 'Rp 40.000'
            ],
            [
                'name' => 'Spider Plant',
                'image' => base_url('assets/img/products/spider_plant.jpg'),
                'price' => 'Rp 55.000'
            ]
        ];
        $data['title'] = 'Easy Care Plants';
        $this->load->view('templates/header', $data);
        $this->load->view('plants/mudah_dirawat', $data);
        $this->load->view('templates/footer');
    }
}