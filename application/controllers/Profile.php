<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('id_user');
        $data['user'] = $this->db->get_where('user', ['id_user' => $user_id])->row_array();
        $data['shipping_addresses'] = $this->db->get_where('shipping_addresses', ['user_id' => $user_id])->result_array();
        
        // Get order status counts
        $this->db->select('stts_pemesanan, COUNT(*) as count');
        $this->db->from('orders');
        $this->db->where('id_user', $user_id);
        $this->db->group_by('stts_pemesanan');
        $status_counts = $this->db->get()->result_array();
        
        // Initialize counts
        $data['order_counts'] = [
            'pending' => 0,
            'diproses' => 0,
            'dikirim' => 0,
            'selesai' => 0
        ];
        
        // Update counts from database
        foreach ($status_counts as $status) {
            $data['order_counts'][$status['stts_pemesanan']] = $status['count'];
        }
        
        $this->load->view('profile/index', $data);
    }

    public function update() {
        $user_id = $this->session->userdata('id_user');
        $data = [
            'nama' => $this->input->post('nama'),
            'no_tlp' => $this->input->post('no_tlp'),
            'alamat' => $this->input->post('alamat')
        ];

        $this->db->where('id_user', $user_id);
        $this->db->update('user', $data);
        
        $this->session->set_flashdata('success', 'Profile updated successfully');
        redirect('profile');
    }

    public function add_shipping_address() {
        $user_id = $this->session->userdata('id_user');
        $data = [
            'user_id' => $user_id,
            'recipient_name' => $this->input->post('recipient_name'),
            'phone' => $this->input->post('phone'),
            'address_label' => $this->input->post('address_label'),
            'address' => $this->input->post('address'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'house_number' => $this->input->post('house_number'),
            'postal_code' => $this->input->post('postal_code'),
            'detail_address' => $this->input->post('detail_address'),
            'is_primary' => empty($this->db->get_where('shipping_addresses', ['user_id' => $user_id])->result()) ? 1 : 0
        ];

        $this->db->insert('shipping_addresses', $data);
        $this->session->set_flashdata('success', 'Shipping address added successfully');
        redirect('profile');
    }

    public function update_shipping_address($id) {
        $user_id = $this->session->userdata('id_user');
        $data = [
            'recipient_name' => $this->input->post('recipient_name'),
            'phone' => $this->input->post('phone'),
            'address_label' => $this->input->post('address_label'),
            'address' => $this->input->post('address'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'house_number' => $this->input->post('house_number'),
            'postal_code' => $this->input->post('postal_code'),
            'detail_address' => $this->input->post('detail_address')
        ];

        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->update('shipping_addresses', $data);
        
        $this->session->set_flashdata('success', 'Shipping address updated successfully');
        redirect('profile');
    }

    public function delete_shipping_address($id) {
        $user_id = $this->session->userdata('id_user');
        $address = $this->db->get_where('shipping_addresses', ['id' => $id, 'user_id' => $user_id])->row();
        
        if ($address && !$address->is_primary) {
            $this->db->where('id', $id);
            $this->db->where('user_id', $user_id);
            $this->db->delete('shipping_addresses');
            $this->session->set_flashdata('success', 'Shipping address deleted successfully');
        }
        
        redirect('profile');
    }

    public function set_primary_address($id) {
        $user_id = $this->session->userdata('id_user');
        
        // Remove current primary
        $this->db->where('user_id', $user_id);
        $this->db->update('shipping_addresses', ['is_primary' => 0]);
        
        // Set new primary
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->update('shipping_addresses', ['is_primary' => 1]);
        
        $this->session->set_flashdata('success', 'Primary shipping address updated');
        redirect('profile');
    }
}