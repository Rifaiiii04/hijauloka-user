<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {
    public function get_categories() {
        // Query untuk mengambil data dari tabel 'category'
        return $this->db->get('category')->result_array();
    }

    public function get_categories_with_subcategories() {
        // Ambil kategori utama
        $this->db->select('id_kategori, nama_kategori');
        $this->db->from('category');
        $this->db->where('id_admin IS NULL'); // Misalnya, kategori utama tidak memiliki id_admin
        $categories = $this->db->get()->result_array();

        // Ambil subkategori untuk setiap kategori utama
        foreach ($categories as &$category) {
            $this->db->select('id_kategori, nama_kategori');
            $this->db->from('category');
            $this->db->where('id_admin', $category['id_kategori']); // Subkategori terkait
            $category['subcategories'] = $this->db->get()->result_array();
        }

        return $categories;
    }

    public function get_subcategories($id_admin)
    {
        // Ambil subkategori berdasarkan id_admin
        $this->db->select('id_kategori, nama_kategori');
        $this->db->from('category');
        $this->db->where('id_admin', $id_admin);
        return $this->db->get()->result_array();
    }
}