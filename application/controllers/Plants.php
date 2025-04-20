<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plants extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model'); // Load model untuk kategori
        $this->load->model('Product_model');  // Load model untuk produk
    }

    public function index()
    {
        // Ambil subkategori dari database untuk kategori Plants
        $data['plants_subcategories'] = $this->Category_model->get_subcategories(1); // id_admin = 1 untuk Plants

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
        $this->load->view('templates/header', $data);
        $this->load->view('collection/indoor', $data);
        $this->load->view('templates/footer');
    }

    public function outdoor()
    {
        // Ambil data tanaman outdoor dari database
        $data['plants'] = $this->Product_model->getOutdoorPlants();

        // Tambahkan judul halaman
        $data['title'] = 'Outdoor Plants';

        // Load view dengan data tanaman outdoor
        $this->load->view('templates/header', $data);
        $this->load->view('plants/outdoor', $data);
        $this->load->view('templates/footer');
    }

    public function mudah_dirawat()
    {
        // Ambil data tanaman mudah dirawat dari database
        $data['plants'] = $this->Product_model->getEasyCarePlants();

        // Tambahkan judul halaman
        $data['title'] = 'Easy Care Plants';

        // Load view dengan data tanaman mudah dirawat
        $this->load->view('templates/header', $data);
        $this->load->view('plants/mudah_dirawat', $data);
        $this->load->view('templates/footer');
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
        $this->load->view('templates/footer');
    }
}