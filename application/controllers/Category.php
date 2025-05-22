<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('cart_model');
        $this->load->model('wishlist_model');
        $this->load->library('pagination');
    }
    
    public function index($category = 'all') {
        // Set page title
        $data['title'] = ucfirst($category) . ' Products';
        
        // Pass category to view
        $data['category'] = $category;
        
        // Get products by category
        $data['products'] = $this->product_model->get_products_by_category($category);
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('category/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function plants() {
        // Get all categories for display
        $data['categories'] = $this->category_model->get_all_categories();
        $data['title'] = 'Kategori Tanaman';
        
        // Define collection categories for the view with descriptions
        $data['collection_categories'] = [
            [
                'name' => 'Tanaman Indoor',
                'route' => 'collection/indoor',
                'image' => 'indoor.jpg',
                'description' => 'Tanaman yang cocok untuk di dalam ruangan'
            ],
            [
                'name' => 'Tanaman Outdoor',
                'route' => 'collection/outdoor',
                'image' => 'outdoor.jpg',
                'description' => 'Tanaman yang cocok untuk di luar ruangan'
            ],
            [
                'name' => 'Florikultura',
                'route' => 'collection/florikultura',
                'image' => 'florikultura.jpg',
                'description' => 'Tanaman hias dan bunga'
            ],
            [
                'name' => 'Mudah Dirawat',
                'route' => 'collection/mudah_dirawat',
                'image' => 'mudah_dirawat.jpg',
                'description' => 'Tanaman yang tidak memerlukan perawatan khusus'
            ]
        ];
        
        // Create an array of category names to exclude from database query
        $exclude_names = [];
        foreach ($data['collection_categories'] as $cat) {
            $exclude_names[] = $cat['name'];
            // Also add variations without "Tanaman " prefix
            if (strpos($cat['name'], 'Tanaman ') === 0) {
                $exclude_names[] = substr($cat['name'], 8); // "Tanaman " is 8 characters
            }
        }
        
        // Pass the exclude names to the view
        $data['exclude_names'] = $exclude_names;
        
        $this->load->view('templates/header', $data);
        $this->load->view('category/plants', $data);
        $this->load->view('templates/footer');
    }
    
    public function view($category_slug) {
        // Get category ID from slug
        $category = $this->category_model->get_category_by_slug($category_slug);
        
        if (!$category) {
            // If category doesn't exist, redirect to coming soon
            redirect('category/coming_soon');
        }
        
        // Config for pagination
        $config['base_url'] = base_url('category/view/' . $category_slug);
        $config['total_rows'] = $this->product_model->count_products_by_category($category['id_kategori']);
        $config['per_page'] = 12;
        $config['uri_segment'] = 4;
        
        // Styling pagination (same as above)
        $config['full_tag_open'] = '<div class="flex justify-center mt-8"><ul class="flex space-x-2">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-green-500 hover:text-white">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-green-500 hover:text-white">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-green-500 hover:text-white">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-green-500 hover:text-white">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="px-3 py-2 bg-green-500 text-white rounded-md">';
        $config['cur_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-green-500 hover:text-white">';
        $config['num_tag_close'] = '</li>';
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        // Get products for this category with pagination
        $data['products'] = $this->product_model->get_products_by_category_with_pagination($category['id_kategori'], $config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['category'] = $category_slug;
        $data['category_info'] = $category;
        $data['title'] = $category['nama_kategori'];
        
        // Initialize wishlist status for all products
        $data['is_wishlisted'] = false;
        
        $this->load->view('templates/header', $data);
        $this->load->view('category/view', $data);
        $this->load->view('templates/footer');
    }
    
    public function filter() {
        // Handle AJAX filter requests
        $category_id = $this->input->post('category_id');
        $sort_by = $this->input->post('sort_by');
        $price_min = $this->input->post('price_min');
        $price_max = $this->input->post('price_max');
        
        $products = $this->product_model->filter_products($category_id, $sort_by, $price_min, $price_max);
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['products' => $products]);
    }
    
    public function search() {
        $keyword = $this->input->get('keyword');
        
        if (empty($keyword)) {
            redirect('category/plants');
        }
        
        $data['products'] = $this->product_model->search_products($keyword);
        $data['title'] = 'Search Results: ' . $keyword;
        $data['keyword'] = $keyword;
        
        $this->load->view('templates/header', $data);
        $this->load->view('category/search', $data);
        $this->load->view('templates/footer');
    }
    
    public function coming_soon() {
        $data['title'] = 'Coming Soon';
        $data['category'] = $this->uri->segment(3) ? $this->uri->segment(3) : 'default';
        
        $this->load->view('templates/header', $data);
        $this->load->view('category/coming_soon', $data);
        $this->load->view('templates/footer');
    }
    
    // Methods for subcategories
    public function tanaman_indoor() {
        redirect('category/view/tanaman-indoor');
    }
    
    public function tanaman_outdoor() {
        redirect('category/view/tanaman-outdoor');
    }
    
    public function tanaman_hias_daun() {
        redirect('category/view/tanaman-hias-daun');
    }
    
    public function tanaman_hias_bunga() {
        redirect('category/view/tanaman-hias-bunga');
    }
    
    public function kaktus_sukulen() {
        redirect('category/view/kaktus-sukulen');
    }
    
    public function tanaman_gantung() {
        redirect('category/view/tanaman-gantung');
    }
    
    public function tanaman_air() {
        redirect('category/view/tanaman-air');
    }
    
    public function tanaman_herbal() {
        redirect('category/view/tanaman-herbal');
    }
    
    public function pots() {
        $data['title'] = 'Pots & Planters';
        
        // Get products in the "Pot" category
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('product p');
        $this->db->join('category c', 'p.id_kategori = c.id_kategori');
        $this->db->like('c.nama_kategori', 'Pot');
        $this->db->or_like('c.nama_kategori', 'pot');
        $this->db->order_by('p.id_product', 'DESC');
        $data['products'] = $this->db->get()->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('category/pots', $data);
        $this->load->view('templates/footer');
    }
    
    public function tools() {
        $data['title'] = 'Gardening Tools';
        
        // Get products in the "Tools" category
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('product p');
        $this->db->join('category c', 'p.id_kategori = c.id_kategori');
        $this->db->like('c.nama_kategori', 'Tool');
        $this->db->or_like('c.nama_kategori', 'tool');
        $this->db->or_like('c.nama_kategori', 'Alat');
        $this->db->order_by('p.id_product', 'DESC');
        $data['products'] = $this->db->get()->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('category/tools', $data);
        $this->load->view('templates/footer');
    }
    
    public function seeds() {
        $data['title'] = 'Seeds & Bulbs';
        
        // Get products in the "Seeds" category
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('product p');
        $this->db->join('category c', 'p.id_kategori = c.id_kategori');
        $this->db->like('c.nama_kategori', 'Seed');
        $this->db->or_like('c.nama_kategori', 'seed');
        $this->db->or_like('c.nama_kategori', 'Benih');
        $this->db->or_like('c.nama_kategori', 'Bibit');
        $this->db->order_by('p.id_product', 'DESC');
        $data['products'] = $this->db->get()->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('category/seeds', $data);
        $this->load->view('templates/footer');
    }
}