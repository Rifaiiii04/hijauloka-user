<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('product_model');
        $this->load->model('wishlist_model');
        $this->load->model('review_model'); // Add this line to load the review model
        $this->load->model('order_model');  // Also load the order model since you're using it
    }

    public function detail($id_product)
    {
        // Get product details
        $data['product'] = $this->product_model->get_product_by_id($id_product);
        
        if (!$data['product']) {
            show_404();
        }
        
        // Get product categories
        $data['categories'] = $this->product_model->get_product_categories($id_product);
        
        // Get product reviews
        $data['reviews'] = $this->review_model->get_approved_reviews_by_product($id_product);
        $data['rating'] = $data['product']['rating'] ?? 0;
        
        // Check if user can review this product
        $data['can_review'] = false;
        $data['completed_orders'] = [];
        $data['existing_review'] = null;
        
        if ($this->session->userdata('logged_in')) {
            $id_user = $this->session->userdata('id_user');
            
            // Check if user has purchased this product and order is completed
            $completed_orders = $this->order_model->get_completed_orders_by_user_and_product($id_user, $id_product);
            $data['completed_orders'] = $completed_orders;
            
            // Check if user has already reviewed this product
            $existing_review = $this->review_model->get_review_by_user_and_product($id_user, $id_product);
            $data['existing_review'] = $existing_review;
            
            // User can review if they have completed orders and haven't reviewed yet
            $data['can_review'] = !empty($completed_orders) && !$existing_review;
        }
        
        // Check if product is in user's wishlist
        $is_wishlisted = $this->session->userdata('logged_in') ? 
            $this->wishlist_model->is_wishlisted($this->session->userdata('id_user'), $id_product) : 
            false;
        $data['is_wishlisted'] = $is_wishlisted;
        
        $this->load->view('product/detail', $data);
    }

    public function submit_review()
    {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Get form data
        $id_product = $this->input->post('id_product');
        $rating = $this->input->post('rating');
        $ulasan = $this->input->post('ulasan');
        $id_user = $this->session->userdata('id_user');
        
        // Validate input
        if (!$id_product || !$rating || !$ulasan) {
            $this->session->set_flashdata('error', 'Semua field harus diisi');
            redirect('product/detail/' . $id_product);
        }
        
        // Check if user has purchased this product
        $orders = $this->order_model->get_completed_orders_by_user_and_product($id_user, $id_product);
        
        if (empty($orders)) {
            $this->session->set_flashdata('error', 'Anda hanya dapat memberikan ulasan untuk produk yang telah Anda beli');
            redirect('product/detail/' . $id_product);
        }
        
        // Check if user has already reviewed this product
        $existing_review = $this->review_model->get_review_by_user_and_product($id_user, $id_product);
        
        if ($existing_review) {
            // Update existing review
            $review_data = [
                'rating' => $rating,
                'ulasan' => $ulasan,
                'tgl_review' => date('Y-m-d H:i:s'),
                'stts_review' => 'pending'
            ];
            
            $this->review_model->update_review($existing_review['id_review'], $review_data);
            $this->session->set_flashdata('success', 'Ulasan Anda telah diperbarui dan sedang menunggu persetujuan');
        } else {
            // Get the most recent completed order for this product
            $order = $orders[0];
            
            // Create new review
            $review_data = [
                'id_order' => $order['id_order'],
                'id_user' => $id_user,
                'id_product' => $id_product,
                'rating' => $rating,
                'ulasan' => $ulasan,
                'tgl_review' => date('Y-m-d H:i:s'),
                'stts_review' => 'pending'
            ];
            
            $this->review_model->add_review($review_data);
            $this->session->set_flashdata('success', 'Terima kasih atas ulasan Anda. Ulasan sedang menunggu persetujuan');
        }
        
        // Update product rating
        $this->update_product_rating($id_product);
        
        redirect('product/detail/' . $id_product);
    }

    private function update_product_rating($id_product)
    {
        // Get all approved reviews for this product
        $reviews = $this->review_model->get_approved_reviews_by_product($id_product);
        
        if (!empty($reviews)) {
            $total_rating = 0;
            foreach ($reviews as $review) {
                $total_rating += $review['rating'];
            }
            
            $average_rating = $total_rating / count($reviews);
            
            // Update product rating
            $this->product_model->update_product_rating($id_product, $average_rating);
        }
    }

    public function category($id_kategori) {
        // Get category details
        $category = $this->db->get_where('category', ['id_kategori' => $id_kategori])->row();
        
        if (!$category) {
            show_404();
        }
        
        // Set page title
        $data['title'] = $category->nama_kategori;
        $data['category'] = $category;
        
        // Get products in this category
        $this->db->where('id_kategori', $id_kategori);
        $this->db->where('stok >', 0); // Only show products in stock
        $data['products'] = $this->db->get('product')->result_array();
        
        $this->load->view('product/category', $data);
    }
}