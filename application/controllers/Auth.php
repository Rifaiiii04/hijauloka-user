<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
    }

    public function index() {
        $this->load->view('auth/login');
    }

    public function login() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->User_model->get_user_by_email($email);
        
        if ($user) {
            // Check if password is plain text (temporary fix)
            if ($user->password === $password || password_verify($password, $user->password)) {
                // If plain text password, update it to hashed version
                if ($user->password === $password) {
                    $this->User_model->update_password($user->id_user, password_hash($password, PASSWORD_DEFAULT));
                }

                $user_data = array(
                    'id_user' => $user->id_user,
                    'email' => $user->email,
                    'nama' => $user->nama,
                    'logged_in' => TRUE
                );
                
                $this->session->set_userdata($user_data);
                redirect('home');
            }
        }
        
        $this->session->set_flashdata('error', 'Invalid email or password');
        redirect('auth');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }

    public function register() {
        if ($this->input->method() === 'post') {
            $data = array(
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'alamat' => $this->input->post('alamat'),
                'no_tlp' => $this->input->post('no_tlp')
            );
    
            // Check if email already exists
            if ($this->User_model->get_user_by_email($data['email'])) {
                $this->session->set_flashdata('error', 'Email already registered');
                redirect('auth/register');
                return;
            }
    
            // Insert user
            if ($this->User_model->insert_user($data)) {
                $this->session->set_flashdata('success', 'Registration successful. Please login.');
                redirect('auth');
            } else {
                $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                redirect('auth/register');
            }
        }
    
        $this->load->view('auth/register');
    }
}