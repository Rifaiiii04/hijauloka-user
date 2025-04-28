<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_latest_products() {
        $this->db->select('id_product, nama_product, harga, gambar, rating, desk_product');
        $this->db->from('product');
        $this->db->where('stok >', 0);
        $this->db->order_by('id_product', 'DESC');
        $this->db->limit(10);
        return $this->db->get()->result_array();
    }

    public function get_best_sellers() {
        $this->db->select('p.*, COALESCE(SUM(oi.quantity), 0) as total_sales');
        $this->db->from('product p');
        $this->db->join('order_items oi', 'p.id_product = oi.id_product', 'left');
        $this->db->where('p.stok >', 0);
        $this->db->group_by('p.id_product');
        $this->db->having('total_sales >', 5);
        $this->db->order_by('total_sales', 'DESC');
        $this->db->limit(8);
        return $this->db->get()->result_array();
    }

    public function get_popular_products() {
        $this->db->select('id_product, nama_product, harga, gambar, rating, desk_product');
        $this->db->from('product');
        $this->db->where('stok >', 0);
        $this->db->order_by('rating', 'DESC');
        $this->db->limit(12);
        return $this->db->get()->result_array();
    }

    public function get_categories() {
        $this->db->select('id_kategori, nama_kategori');
        $this->db->from('category');
        return $this->db->get()->result_array();
    }

    public function get_popular_products_by_category($kategori_id = null) {
        $this->db->select('p.*');
        $this->db->from('product p');
        
        if ($kategori_id) {
            $this->db->join('product_category pc', 'p.id_product = pc.id_product');
            $this->db->where('pc.id_kategori', $kategori_id);
        }
        
        $this->db->where('p.stok >', 0);
        $this->db->order_by('p.rating', 'DESC');
        $this->db->group_by('p.id_product'); // Prevent duplicates if multiple categories
        
        return $this->db->get()->result_array();
    }

    public function getIndoorPlants() {
        $this->db->select('p.*, GROUP_CONCAT(c.nama_kategori) as categories');
        $this->db->from('product p');
        $this->db->join('product_category pc', 'p.id_product = pc.id_product');
        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
        $this->db->where('c.nama_kategori', 'Indoor');
        $this->db->group_by('p.id_product');
        $result = $this->db->get()->result_array();

        foreach ($result as &$item) {
            $item['categories'] = explode(',', $item['categories']);
            $item['name'] = $item['nama_product'];
            $item['price'] = (float)($item['harga'] ?? 0); // Handle null price
            $item['image'] = base_url('uploads/' . $item['gambar']);
        }

        return $result;
    }

    public function getOutdoorPlants() {
        $this->db->select('p.*, GROUP_CONCAT(c.nama_kategori) as categories');
        $this->db->from('product p');
        $this->db->join('product_category pc', 'p.id_product = pc.id_product');
        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
        $this->db->where('c.nama_kategori', 'Outdoor');
        $this->db->group_by('p.id_product');
        $result = $this->db->get()->result_array();

        foreach ($result as &$item) {
            $item['categories'] = explode(',', $item['categories']);
            $item['name'] = $item['nama_product'];
            $item['price'] = (float)($item['harga'] ?? 0); // Handle null price
            $item['image'] = base_url('uploads/' . $item['gambar']);
        }

        return $result;
    }
    public function getEasyCarePlants() {
        $this->db->select('p.*, GROUP_CONCAT(c.nama_kategori) as categories');
        $this->db->from('product p');
        $this->db->join('product_category pc', 'p.id_product = pc.id_product');
        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
        $this->db->where('c.nama_kategori', 'Mudah dirawat');
        $this->db->group_by('p.id_product');
        $result = $this->db->get()->result_array();

        foreach ($result as &$item) {
            $item['categories'] = explode(',', $item['categories']);
            $item['name'] = $item['nama_product'];
            $item['price'] = (float)($item['harga'] ?? 0); // Handle null price
            $item['image'] = base_url('uploads/' . $item['gambar']);
        }

        return $result;
    }
    public function getFlorikulturaPlants() {
        $this->db->select('p.*, GROUP_CONCAT(c.nama_kategori) as categories');
        $this->db->from('product p');
        $this->db->join('product_category pc', 'p.id_product = pc.id_product');
        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
        $this->db->where('c.nama_kategori', 'Florikultura');
        $this->db->group_by('p.id_product');
        $result = $this->db->get()->result_array();

        foreach ($result as &$item) {
            $item['categories'] = explode(',', $item['categories']);
            $item['name'] = $item['nama_product'];
            $item['price'] = (float)($item['harga'] ?? 0); // Handle null price
            $item['image'] = base_url('uploads/' . $item['gambar']);
        }

        return $result;
    }

    // Add new method for getting product details
    public function get_product_by_id($id) {
        $this->db->select('p.*, GROUP_CONCAT(c.nama_kategori) as categories');
        $this->db->from('product p');
        $this->db->join('product_category pc', 'p.id_product = pc.id_product', 'left');
        $this->db->join('category c', 'c.id_kategori = pc.id_kategori', 'left');
        $this->db->where('p.id_product', $id);
        $this->db->group_by('p.id_product');
        
        $result = $this->db->get()->row_array();
        
        if ($result) {
            $result['categories'] = $result['categories'] ? explode(',', $result['categories']) : [];
            $result['gambar'] = explode(',', $result['gambar']);
        }
        
        return $result;
    }

    public function get_product_categories($product_id) {
        $this->db->select('c.id_kategori, c.nama_kategori');
        $this->db->from('product_category pc');
        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
        $this->db->where('pc.id_product', $product_id);
        return $this->db->get()->result_array();
    }

    public function get_product_rating($product_id) {
        $this->db->select('COALESCE(AVG(rating), 0) as rating');
        $this->db->from('review_rating'); // Changed from 'reviews' to 'review_rating'
        $this->db->where('id_product', $product_id);
        $this->db->where('stts_review', 'disetujui'); // Only count approved reviews
        $result = $this->db->get()->row();
        return $result ? $result->rating : 0;
    }
}