<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getProdukTerbaru($limit = 6) {
        $this->db->select('id_product, nama_product, desk_product, harga, stok, gambar, rating');
        $this->db->from('product'); 
        $this->db->order_by('id_product', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }
    public function get_produk_terlaris($limit = 5) {
        $this->db->select('p.id_product, p.nama_product AS nama_produk, p.gambar, p.harga, COUNT(o.id_order) AS jumlah_pembelian');
        $this->db->from('orders o');
        $this->db->join('order_items oi', 'o.id_order = oi.id_order', 'inner');
        $this->db->join('product p', 'oi.id_product = p.id_product', 'inner');
        $this->db->where('o.stts_pemesanan', 'selesai');
        $this->db->group_by('p.id_product');
        $this->db->order_by('jumlah_pembelian', 'DESC');
        $this->db->limit($limit);
    
        return $this->db->get()->result();
    }
    
    
    
}
