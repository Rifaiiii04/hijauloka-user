<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plants extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');  // Changed to match exact model name
        $this->load->model('Category_model'); // Changed to match exact model name
        $this->load->model('wishlist_model');
        $this->load->library('session');
    }

    public function index()
    {
        $data['plants_subcategories'] = $this->Category_model->get_subcategories(1);

        // Load view dengan data subkategori
        $this->load->view('templates/header');
        $this->load->view('collection/index', $data); // Pastikan path view benar
        $this->load->view('templates/footer');
    }

    public function indoor()
    {
        // Ambil data tanaman indoor dari database
        $data['plants'] = $this->Product_model->getIndoorPlants();

        // Tambahkan judul halaman
        $data['title'] = 'Indoor Plants';

        // Load view dengan data tanaman indoor
        $this->load->view('templates/header2', $data);
        $this->load->view('collection/indoor', $data);
        // $this->load->view('templates/footer');
    }

    public function outdoor()
    {
        // Ambil data tanaman outdoor dari database
        $data['plants'] = $this->Product_model->getOutdoorPlants();

        // Tambahkan judul halaman
        $data['title'] = 'Outdoor Plants';

        // Load view dengan data tanaman outdoor
        $this->load->view('templates/header2', $data);
        $this->load->view('collection/outdoor', $data);
        // $this->load->view('templates/footer');
    }

    public function mudah_dirawat()
    {
        // Ambil data tanaman mudah dirawat dari database
        $data['plants'] = $this->Product_model->getEasyCarePlants();

        // Tambahkan judul halaman
        $data['title'] = 'Easy Care Plants';

        // Load view dengan data tanaman mudah dirawat
        $this->load->view('templates/header2', $data);
        $this->load->view('collection/mudah_dirawat', $data);
        // $this->load->view('templates/footer');
    }
    public function florikultura()
    {
        // Ambil data tanaman mudah dirawat dari database
        $data['plants'] = $this->Product_model->getFlorikulturaPlants();

        // Tambahkan judul halaman
        $data['title'] = 'Florikultura Plants';

        // Load view dengan data tanaman mudah dirawat
        $this->load->view('templates/header2', $data);
        $this->load->view('collection/florikultura', $data);
        // $this->load->view('templates/footer');
    }

    public function detail($id)
    {
        // Ambil detail produk berdasarkan ID
        $data['product'] = $this->Product_model->getProductById($id);

        // Jika produk tidak ditemukan, tampilkan halaman 404
        if (empty($data['product'])) {
            show_404();
        }

        // Tambahkan judul halaman
        $data['title'] = $data['product']['nama_product'];

        // Load view dengan data detail produk
        $this->load->view('templates/header', $data);
        $this->load->view('plants/detail', $data);
        // $this->load->view('templates/footer');
    }

    // public function back(){
    //     $this->load->view('templates/header');
    //     $this->load->view('category/plan');
    //     $this->load->view('templates/footer');
    // }

}