<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlist extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('wishlist_model');
        $this->load->model('product_model');
        $this->load->library('session');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    
        $user_id = $this->session->userdata('id_user');
        $data['wishlist'] = $this->wishlist_model->get_user_wishlist($user_id);
        $data['title'] = 'Wishlist';
        
        $this->load->view('templates/header', $data);
        $this->load->view('wishlist/index', $data);
        $this->load->view('templates/footer');
    }

    public function remove($product_id)
    {
        $user_id = $this->session->userdata('id_user');
        $this->wishlist_model->remove_from_wishlist($user_id, $product_id);
        redirect('wishlist');
    }

    public function toggle($product_id)
    {
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(['error' => 'Login required']);
            return;
        }
    
        $user_id = $this->session->userdata('id_user');
        $is_wishlisted = $this->wishlist_model->is_wishlisted($user_id, $product_id);
    
        if ($is_wishlisted) {
            $this->wishlist_model->remove_from_wishlist($user_id, $product_id);
            $wishlisted = false;
        } else {
            $this->wishlist_model->add_to_wishlist($user_id, $product_id);
            $wishlisted = true;
        }
    
        echo json_encode(['wishlisted' => $wishlisted]);
    }
}