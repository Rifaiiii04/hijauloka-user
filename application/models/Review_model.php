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
    
    public function get_product_reviews($product_id, $limit = 10, $offset = 0) {
        $this->db->select('r.*, u.nama, u.foto_profil');
        $this->db->from('review_rating r');
        $this->db->join('user u', 'r.id_user = u.id_user');
        $this->db->where('r.id_product', $product_id);
        $this->db->where('r.stts_review', 'disetujui');
        $this->db->order_by('r.tgl_review', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result_array();
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
}