<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('blog_model');
        $this->load->model('cart_model'); // Add this line to load the cart_model
        $this->load->library('pagination');
    }
    
    public function index() {
        // Pagination configuration
        $config['base_url'] = base_url('blog/index');
        $config['total_rows'] = $this->blog_model->count_published_posts();
        $config['per_page'] = 9;
        $config['uri_segment'] = 3;
        
        // Pagination styling
        $config['full_tag_open'] = '<div class="flex justify-center mt-8"><ul class="flex space-x-2">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-green-50 transition-colors">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="px-3 py-2 bg-green-600 text-white rounded-lg shadow-sm">';
        $config['cur_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-green-50 transition-colors">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-green-50 transition-colors">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-green-50 transition-colors">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-green-50 transition-colors">';
        $config['last_tag_close'] = '</li>';
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // Get blog posts with pagination
        $data['posts'] = $this->blog_model->get_published_posts($config['per_page'], $page);
        $data['categories'] = $this->blog_model->get_categories();
        $data['pagination'] = $this->pagination->create_links();
        $data['title'] = 'Blog - HijauLoka';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('blog/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function post($slug = NULL) {
        if ($slug === NULL) {
            redirect('blog');
        }
        
        $data['post'] = $this->blog_model->get_post_by_slug($slug);
        
        if (empty($data['post'])) {
            show_404();
        }
        
        // Increment view count
        $this->blog_model->increment_views($data['post']['id']);
        
        // Get related posts
        $data['related_posts'] = $this->blog_model->get_related_posts($data['post']['id'], $data['post']['category_id'], 3);
        $data['categories'] = $this->blog_model->get_categories();
        $data['title'] = $data['post']['title'] . ' - HijauLoka Blog';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('blog/post', $data);
        $this->load->view('templates/footer');
    }
    
    public function category($slug = NULL) {
        if ($slug === NULL) {
            redirect('blog');
        }
        
        $data['category'] = $this->blog_model->get_category_by_slug($slug);
        
        if (empty($data['category'])) {
            show_404();
        }
        
        // Pagination configuration
        $config['base_url'] = base_url('blog/category/' . $slug);
        $config['total_rows'] = $this->blog_model->count_posts_by_category($data['category']['id']);
        $config['per_page'] = 9;
        $config['uri_segment'] = 4;
        
        // Pagination styling (same as index)
        $config['full_tag_open'] = '<div class="flex justify-center mt-8"><ul class="flex space-x-2">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-green-50 transition-colors">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="px-3 py-2 bg-green-600 text-white rounded-lg shadow-sm">';
        $config['cur_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-green-50 transition-colors">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-green-50 transition-colors">';
        $config['prev_tag_close'] = '</li>';
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        
        // Get blog posts with pagination
        $data['posts'] = $this->blog_model->get_posts_by_category($data['category']['id'], $config['per_page'], $page);
        $data['categories'] = $this->blog_model->get_categories();
        $data['pagination'] = $this->pagination->create_links();
        $data['title'] = $data['category']['name'] . ' - HijauLoka Blog';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('blog/category', $data);
        $this->load->view('templates/footer');
    }
}