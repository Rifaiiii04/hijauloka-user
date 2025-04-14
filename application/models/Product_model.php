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
}