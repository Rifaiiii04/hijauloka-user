<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('notification_model');
        $this->load->model('cart_model'); // Add this line to load the cart model
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }
    
    public function index() {
        $data['title'] = 'Notifikasi';
        $data['notifications'] = $this->notification_model->get_user_notifications(
            $this->session->userdata('id_user'),
            0, // offset
            10 // limit
        );
        
        $this->load->view('notification/index', $data);
    }
    
    public function mark_as_read() {
        $user_id = $this->session->userdata('id_user');
        $success = $this->notification_model->mark_all_as_read($user_id);
        
        // Return JSON response for AJAX request
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }
    
    public function load_more() {
        $page = $this->input->get('page', TRUE);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $notifications = $this->notification_model->get_user_notifications(
            $this->session->userdata('id_user'),
            $offset,
            $limit
        );
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['notifications' => $notifications]);
    }
}