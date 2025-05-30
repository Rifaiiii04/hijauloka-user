<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function add_review($data) {
        $this->db->insert('review_rating', $data);
        return $this->db->insert_id();
    }
    
    public function get_review_by_user_and_product($user_id, $product_id) {
        $this->db->where('id_user', $user_id);
        $this->db->where('id_product', $product_id);
        $query = $this->db->get('review_rating');
        return $query->row_array();
    }
    
    public function update_review($id_review, $data) {
        $this->db->where('id_review', $id_review);
        return $this->db->update('review_rating', $data);
    }
    
    public function get_product_reviews($product_id, $limit = 10, $offset = 0) {
        $this->db->select('r.*, u.nama, u.profile_image as foto_profil');
        $this->db->from('review_rating r');
        $this->db->join('user u', 'r.id_user = u.id_user');
        $this->db->where('r.id_product', $product_id);
        $this->db->where('r.stts_review', 'disetujui');
        $this->db->order_by('r.tgl_review', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result_array();
    }
    
    // Alias for get_product_reviews to match the method name used in the controller
    public function get_approved_reviews_by_product($product_id, $limit = 10, $offset = 0) {
        return $this->get_product_reviews($product_id, $limit, $offset);
    }
    
    public function get_product_rating($product_id) {
        $this->db->select('AVG(rating) as average_rating, COUNT(*) as review_count');
        $this->db->from('review_rating');
        $this->db->where('id_product', $product_id);
        $this->db->where('stts_review', 'disetujui');
        
        $result = $this->db->get()->row_array();
        
        return [
            'average' => round($result['average_rating'], 1),
            'count' => $result['review_count']
        ];
    }
    
    public function has_user_reviewed($user_id, $product_id) {
        $this->db->where('id_user', $user_id);
        $this->db->where('id_product', $product_id);
        $count = $this->db->count_all_results('review_rating');
        
        return $count > 0;
    }
    
    public function get_user_reviews($user_id, $limit = 10, $offset = 0) {
        $this->db->select('r.*, p.nama_product, p.gambar');
        $this->db->from('review_rating r');
        $this->db->join('product p', 'r.id_product = p.id_product');
        $this->db->where('r.id_user', $user_id);
        $this->db->order_by('r.tgl_review', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result_array();
    }
    
    public function upload_review_image($file_data) {
        // Set upload configuration
        $config['upload_path'] = './uploads/reviews/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;
        
        // Create directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }
        
        // Load upload library
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('foto_review')) {
            return [
                'status' => FALSE,
                'error' => $this->upload->display_errors()
            ];
        } else {
            $upload_data = $this->upload->data();
            return [
                'status' => TRUE,
                'file_name' => $upload_data['file_name']
            ];
        }
    }
}