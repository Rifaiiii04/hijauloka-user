<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('wishlist_model');
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Cart_model', 'cart_model');
    }

    public function index() {
        // Load blog model
        $this->load->model('blog_model');
        
        // Get featured products (existing code)
        $data['featured_products'] = $this->product_model->get_featured_products();
        
        // Get latest blog posts (3 featured posts and 3 small posts)
        $data['featured_blog_posts'] = $this->blog_model->get_latest_posts(2, 'published');
        $data['small_blog_posts'] = $this->blog_model->get_latest_posts(3, 'published', 2); // Skip the first 2
        
        // Load view with data
        $this->load->view('home/index', $data);
    }
}
