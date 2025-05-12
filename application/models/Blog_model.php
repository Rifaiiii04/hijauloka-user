<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {
    
    public function get_latest_posts($limit = 5, $status = 'published', $offset = 0) {
        $this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug, user.nama as author_name');
        $this->db->from('blog_posts');
        $this->db->join('blog_categories', 'blog_posts.category_id = blog_categories.id', 'left');
        $this->db->join('user', 'blog_posts.author_id = user.id_user', 'left');
        $this->db->where('blog_posts.status', $status);
        $this->db->order_by('blog_posts.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_published_posts($limit = 10, $offset = 0) {
        $this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug, user.nama as author_name');
        $this->db->from('blog_posts');
        $this->db->join('blog_categories', 'blog_posts.category_id = blog_categories.id', 'left');
        $this->db->join('user', 'blog_posts.author_id = user.id_user', 'left');
        $this->db->where('blog_posts.status', 'published');
        $this->db->order_by('blog_posts.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function count_published_posts() {
        $this->db->where('status', 'published');
        return $this->db->count_all_results('blog_posts');
    }
    
    public function get_post_by_slug($slug) {
        $this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug, user.nama as author_name');
        $this->db->from('blog_posts');
        $this->db->join('blog_categories', 'blog_posts.category_id = blog_categories.id', 'left');
        $this->db->join('user', 'blog_posts.author_id = user.id_user', 'left');
        $this->db->where('blog_posts.slug', $slug);
        $this->db->where('blog_posts.status', 'published');
        
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function increment_views($post_id) {
        $this->db->set('views', 'views+1', FALSE);
        $this->db->where('id', $post_id);
        $this->db->update('blog_posts');
    }
    
    public function get_categories() {
        $query = $this->db->get('blog_categories');
        return $query->result_array();
    }
    
    public function get_category_by_slug($slug) {
        $query = $this->db->get_where('blog_categories', ['slug' => $slug]);
        return $query->row_array();
    }
    
    public function count_posts_by_category($category_id) {
        $this->db->where('category_id', $category_id);
        $this->db->where('status', 'published');
        return $this->db->count_all_results('blog_posts');
    }
    
    public function get_posts_by_category($category_id, $limit = 10, $offset = 0) {
        $this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug, user.nama as author_name');
        $this->db->from('blog_posts');
        $this->db->join('blog_categories', 'blog_posts.category_id = blog_categories.id', 'left');
        $this->db->join('user', 'blog_posts.author_id = user.id_user', 'left');
        $this->db->where('blog_posts.category_id', $category_id);
        $this->db->where('blog_posts.status', 'published');
        $this->db->order_by('blog_posts.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_related_posts($post_id, $category_id, $limit = 3) {
        $this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
        $this->db->from('blog_posts');
        $this->db->join('blog_categories', 'blog_posts.category_id = blog_categories.id', 'left');
        $this->db->where('blog_posts.id !=', $post_id);
        $this->db->where('blog_posts.category_id', $category_id);
        $this->db->where('blog_posts.status', 'published');
        $this->db->order_by('blog_posts.created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }
}