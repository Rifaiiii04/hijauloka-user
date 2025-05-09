<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {
    
    public function get_all_categories() {
        $this->db->order_by('nama_kategori', 'ASC');
        $query = $this->db->get('category');
        return $query->result_array();
    }
    
    public function get_category_by_slug($slug) {
        // Convert slug to category name
        $category_name = str_replace('-', ' ', $slug);
        $category_name = ucwords($category_name); // Capitalize first letter of each word
        
        $this->db->where('nama_kategori', $category_name);
        $query = $this->db->get('category');
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
    
    public function get_category_by_id($id) {
        $this->db->where('id_kategori', $id);
        $query = $this->db->get('category');
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }
    
    public function get_category_with_product_count() {
        $this->db->select('c.*, COUNT(p.id_product) as product_count');
        $this->db->from('category c');
        $this->db->join('product p', 'c.id_kategori = p.id_kategori', 'left');
        $this->db->group_by('c.id_kategori');
        $this->db->order_by('c.nama_kategori', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_featured_categories($limit = 8) {
        $this->db->select('c.*, COUNT(p.id_product) as product_count');
        $this->db->from('category c');
        $this->db->join('product p', 'c.id_kategori = p.id_kategori', 'left');
        $this->db->group_by('c.id_kategori');
        $this->db->order_by('product_count', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function create_category($data) {
        $this->db->insert('category', $data);
        return $this->db->insert_id();
    }
    
    public function update_category($id, $data) {
        $this->db->where('id_kategori', $id);
        return $this->db->update('category', $data);
    }
    
    public function delete_category($id) {
        $this->db->where('id_kategori', $id);
        return $this->db->delete('category');
    }
    
    public function get_category_slug($id) {
        $this->db->select('nama_kategori');
        $this->db->where('id_kategori', $id);
        $query = $this->db->get('category');
        
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return strtolower(str_replace(' ', '-', $row['nama_kategori']));
        }
        return false;
    }
    
    public function search_categories($keyword) {
        $this->db->like('nama_kategori', $keyword);
        $this->db->order_by('nama_kategori', 'ASC');
        $query = $this->db->get('category');
        return $query->result_array();
    }
}