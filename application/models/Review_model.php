<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model {
    
    public function add_review($data)
    {
        $this->db->insert('review_rating', $data);
        return $this->db->insert_id();
    }
    
    public function update_review($id_review, $data)
    {
        $this->db->where('id_review', $id_review);
        return $this->db->update('review_rating', $data);
    }
    
    public function get_review_by_id($id_review)
    {
        $this->db->where('id_review', $id_review);
        $query = $this->db->get('review_rating');
        return $query->row_array();
    }
    
    public function get_review_by_user_and_product($id_user, $id_product)
    {
        $this->db->where('id_user', $id_user);
        $this->db->where('id_product', $id_product);
        $query = $this->db->get('review_rating');
        return $query->row_array();
    }
    
    public function get_approved_reviews_by_product($id_product)
    {
        $this->db->select('review_rating.*, user.nama, user.profile_image');
        $this->db->from('review_rating');
        $this->db->join('user', 'user.id_user = review_rating.id_user');
        $this->db->where('review_rating.id_product', $id_product);
        $this->db->where('review_rating.stts_review', 'disetujui');
        $this->db->order_by('review_rating.tgl_review', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_all_reviews_by_product($id_product)
    {
        $this->db->select('review_rating.*, user.nama, user.profile_image');
        $this->db->from('review_rating');
        $this->db->join('user', 'user.id_user = review_rating.id_user');
        $this->db->where('review_rating.id_product', $id_product);
        $this->db->order_by('review_rating.tgl_review', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
}