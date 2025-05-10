<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collection extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('cart_model');
        $this->load->model('wishlist_model');
        $this->load->library('pagination');
    }
    
    public function index() {
        redirect('category/plants');
    }
    
    public function indoor() {
        // Config for pagination
        $config['base_url'] = base_url('collection/indoor');
        $config['total_rows'] = $this->product_model->count_products_by_tag('indoor');
        $config['per_page'] = 12;
        $config['uri_segment'] = 3;
        
        // Styling pagination
        $this->_pagination_styling($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // Get products for indoor category
        $data['products'] = $this->product_model->get_products_by_tag_with_pagination('indoor', $config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['title'] = 'Tanaman Indoor';
        $data['category_name'] = 'Tanaman Indoor';
        $data['category_description'] = 'Tanaman yang cocok untuk di dalam ruangan';
        $data['category_icon'] = 'fa-house-chimney';
        
        $this->load->view('templates/header', $data);
        $this->load->view('collection/category_view', $data);
        $this->load->view('templates/footer');
    }
    
    public function outdoor() {
        // Config for pagination
        $config['base_url'] = base_url('collection/outdoor');
        $config['total_rows'] = $this->product_model->count_products_by_tag('outdoor');
        $config['per_page'] = 12;
        $config['uri_segment'] = 3;
        
        // Styling pagination
        $this->_pagination_styling($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // Get products for outdoor category
        $data['products'] = $this->product_model->get_products_by_tag_with_pagination('outdoor', $config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['title'] = 'Tanaman Outdoor';
        $data['category_name'] = 'Tanaman Outdoor';
        $data['category_description'] = 'Tanaman yang cocok untuk di luar ruangan';
        $data['category_icon'] = 'fa-tree';
        
        $this->load->view('templates/header', $data);
        $this->load->view('collection/category_view', $data);
        $this->load->view('templates/footer');
    }
    
    public function florikultura() {
        // Config for pagination
        $config['base_url'] = base_url('collection/florikultura');
        $config['total_rows'] = $this->product_model->count_products_by_tag('florikultura');
        $config['per_page'] = 12;
        $config['uri_segment'] = 3;
        
        // Styling pagination
        $this->_pagination_styling($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // Get products for florikultura category
        $data['products'] = $this->product_model->get_products_by_tag_with_pagination('florikultura', $config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['title'] = 'Florikultura';
        $data['category_name'] = 'Florikultura';
        $data['category_description'] = 'Tanaman hias dan bunga';
        $data['category_icon'] = 'fa-seedling';
        
        $this->load->view('templates/header', $data);
        $this->load->view('collection/category_view', $data);
        $this->load->view('templates/footer');
    }
    
    public function mudah_dirawat() {
        // Config for pagination
        $config['base_url'] = base_url('collection/mudah_dirawat');
        $config['total_rows'] = $this->product_model->count_products_by_tag('mudah_dirawat');
        $config['per_page'] = 12;
        $config['uri_segment'] = 3;
        
        // Styling pagination
        $this->_pagination_styling($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // Get products for mudah dirawat category
        $data['products'] = $this->product_model->get_products_by_tag_with_pagination('mudah_dirawat', $config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['title'] = 'Mudah Dirawat';
        $data['category_name'] = 'Mudah Dirawat';
        $data['category_description'] = 'Tanaman yang tidak memerlukan perawatan khusus';
        $data['category_icon'] = 'fa-thumbs-up';
        
        $this->load->view('templates/header', $data);
        $this->load->view('collection/category_view', $data);
        $this->load->view('templates/footer');
    }
    
    // Helper method for pagination styling
    private function _pagination_styling(&$config) {
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
    }
}