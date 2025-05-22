<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_latest_products($limit = 8, $user_id = null) {
        $this->db->select('p.*, IF(w.id_wishlist IS NOT NULL, 1, 0) as is_wishlisted');
        $this->db->from('product p');
        if ($user_id) {
            $this->db->join('wishlist w', "w.id_product = p.id_product AND w.id_user = $user_id", 'left');
        } else {
            $this->db->join('wishlist w', "w.id_product = p.id_product AND w.id_user IS NULL", 'left');
        }
        $this->db->order_by('p.id_product', 'DESC');
        $this->db->limit($limit);
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

    public function get_featured_products() {
        $this->db->select('product.*, featured_products.position');
        $this->db->from('featured_products');
        $this->db->join('product', 'product.id_product = featured_products.id_product');
        $this->db->order_by('featured_products.position', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_products_by_category($category) {
        if ($category != 'all') {
            $this->db->where('kategori', $category);
        }
        
        $this->db->where('stok >', 0); // Only show products in stock
        $this->db->order_by('id_product', 'DESC'); // Newest first
        
        $query = $this->db->get('product');
        return $query->result_array();
    }

    public function get_products_by_category_id($category_id)
    {
        $this->db->where('id_kategori', $category_id);
        $this->db->where('stok >', 0); // Only show products in stock
        $query = $this->db->get('product');
        
        return $query->result_array();
    }
    
    // Method to count all products
    public function count_all_products() {
        return $this->db->count_all('product');
    }
    
    // Method for pagination
    public function get_products_with_pagination($limit, $start) {
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('product p');
        $this->db->join('category c', 'p.id_kategori = c.id_kategori', 'left');
        $this->db->order_by('p.id_product', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }
    
    public function filter_products($category_id = null, $sort_by = null, $price_min = null, $price_max = null) {
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('product p');
        $this->db->join('category c', 'p.id_kategori = c.id_kategori', 'left');
        
        // Apply category filter
        if (!empty($category_id)) {
            $this->db->where('p.id_kategori', $category_id);
        }
        
        // Apply price range filter
        if (!empty($price_min)) {
            $this->db->where('p.harga >=', $price_min);
        }
        
        if (!empty($price_max)) {
            $this->db->where('p.harga <=', $price_max);
        }
        
        // Apply sorting
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'price_low':
                    $this->db->order_by('p.harga', 'ASC');
                    break;
                case 'price_high':
                    $this->db->order_by('p.harga', 'DESC');
                    break;
                case 'rating':
                    $this->db->order_by('p.rating', 'DESC');
                    break;
                case 'newest':
                default:
                    $this->db->order_by('p.id_product', 'DESC');
                    break;
            }
        } else {
            $this->db->order_by('p.id_product', 'DESC');
        }
        
        return $this->db->get()->result_array();
    }
    
    // Method for searching products
    public function search_products($keyword) {
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('product p');
        $this->db->join('category c', 'p.id_kategori = c.id_kategori', 'left');
        $this->db->like('p.nama_product', $keyword);
        $this->db->or_like('p.desk_product', $keyword);
        $this->db->or_like('c.nama_kategori', $keyword);
        $this->db->order_by('p.id_product', 'DESC');
        return $this->db->get()->result_array();
    }
    
    // Add these methods to your existing Product_model.php file
    
    public function count_products_by_tag($tag) {
        $this->db->select('COUNT(DISTINCT p.id_product) as total');
        $this->db->from('product p');
        $this->db->join('product_category pc', 'p.id_product = pc.id_product');
        $this->db->join('category c', 'pc.id_kategori = c.id_kategori');
        
        // Check for tag in product name or category name
        $this->db->group_start();
        $this->db->like('p.nama_product', $tag);
        $this->db->or_like('p.desk_product', $tag);
        $this->db->or_like('c.nama_kategori', $tag);
        $this->db->group_end();
        
        $query = $this->db->get();
        return $query->row()->total;
    }
    
    public function get_products_by_tag_with_pagination($tag, $limit, $start) {
        $this->db->select('p.*, c.nama_kategori, 0 as diskon'); // Add default diskon value
        $this->db->from('product p');
        $this->db->join('product_category pc', 'p.id_product = pc.id_product');
        $this->db->join('category c', 'pc.id_kategori = c.id_kategori');
        
        // Check for tag in product name or category name
        $this->db->group_start();
        $this->db->like('p.nama_product', $tag);
        $this->db->or_like('p.desk_product', $tag);
        $this->db->or_like('c.nama_kategori', $tag);
        $this->db->group_end();
        
        $this->db->group_by('p.id_product');
        
        // Sort products
        $sort = $this->input->get('sort');
        if ($sort) {
            switch ($sort) {
                case 'price_low':
                    $this->db->order_by('p.harga', 'ASC');
                    break;
                case 'price_high':
                    $this->db->order_by('p.harga', 'DESC');
                    break;
                case 'name_asc':
                    $this->db->order_by('p.nama_product', 'ASC');
                    break;
                case 'name_desc':
                    $this->db->order_by('p.nama_product', 'DESC');
                    break;
                default:
                    $this->db->order_by('p.id_product', 'DESC');
                    break;
            }
        } else {
            $this->db->order_by('p.id_product', 'DESC');
        }
        
        // Check for limit parameter from URL
        $url_limit = $this->input->get('limit');
        if ($url_limit && is_numeric($url_limit)) {
            $limit = $url_limit;
        }
        
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    public function update_product_rating($id_product, $rating)
    {
        $this->db->where('id_product', $id_product);
        return $this->db->update('product', ['rating' => $rating]);
    }
}