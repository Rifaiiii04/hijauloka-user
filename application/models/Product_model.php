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
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_best_sellers() {
        $this->db->select('p.id_product, p.nama_product, p.harga, p.gambar, p.rating, p.desk_product, COUNT(oi.id_product) as jumlah_pembelian');
        $this->db->from('product p');
        $this->db->join('order_items oi', 'p.id_product = oi.id_product', 'left');
        $this->db->where('p.stok >', 0);
        $this->db->group_by('p.id_product');
        $this->db->order_by('jumlah_pembelian', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_popular_products() {
        $this->db->select('id_product, nama_product, harga, gambar, rating, desk_product');
        $this->db->from('product');
        $this->db->where('stok >', 0);
        $this->db->order_by('rating', 'DESC');
        $this->db->limit(12);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_categories() {
        $this->db->select('id_kategori, nama_kategori');
        $this->db->from('category');
        $query = $this->db->get();
        return $query->result_array();
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
        
        $query = $this->db->get();
        return $query->result_array();
    }
}