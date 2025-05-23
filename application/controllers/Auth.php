<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['session', 'form_validation']);
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
            // Validation rules
            $this->form_validation->set_rules('nama', 'Name', 'required|trim', [
                'required' => 'Name is required'
            ]);
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
                'required' => 'Email is required',
                'valid_email' => 'Please enter a valid email address',
                'is_unique' => 'This email is already registered'
            ]);
            $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]', [
                'required' => 'Password is required',
                'min_length' => 'Password must be at least 6 characters'
            ]);
            $this->form_validation->set_rules('alamat', 'Address', 'required|trim', [
                'required' => 'Address is required'
            ]);
            $this->form_validation->set_rules('no_tlp', 'Phone Number', 'required|trim|numeric', [
                'required' => 'Phone number is required',
                'numeric' => 'Please enter a valid phone number'
            ]);
    
            if ($this->form_validation->run() == FALSE) {
                $error_messages = str_replace(['<p>', '</p>'], '', validation_errors());
                $this->session->set_flashdata('error', $error_messages);
                redirect('auth/register');
                return;
            }
    
            $data = array(
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'alamat' => htmlspecialchars($this->input->post('alamat', true)),
                'no_tlp' => htmlspecialchars($this->input->post('no_tlp', true))
            );
    
            if ($this->User_model->insert_user($data)) {
                $this->session->set_flashdata('success', 'Your account has been created successfully! Please login.');
                redirect('auth');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again.');
                redirect('auth/register');
            }
        }
    
        $this->load->view('auth/register');
    }
    
    public function forgot_password() {
        // Check if user is already logged in
        if ($this->session->userdata('logged_in')) {
            redirect('home');
        }
        
        $this->load->view('auth/forgot_password');
    }
    
    public function find_account() {
        $email = $this->input->post('email');
        
        // Validate email
        if (!$email) {
            $this->session->set_flashdata('error', 'Please enter your email address');
            redirect('auth/forgot_password');
        }
        
        // Check if email exists in database
        $this->load->model('user_model');
        $user = $this->user_model->get_user_by_email($email);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'No account found with that email address');
            redirect('auth/forgot_password');
        }
        
        // Pass user data to reset password page
        $data = array(
            'user_id' => $user['id_user'],
            'email' => $user['email']
        );
        
        $this->load->view('auth/reset_password', $data);
    }
    
    public function update_password() {
        $user_id = $this->input->post('user_id');
        $email = $this->input->post('email');
        $new_password = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');
        
        // Validate inputs
        if (!$user_id || !$email || !$new_password || !$confirm_password) {
            $this->session->set_flashdata('error', 'All fields are required');
            redirect('auth/forgot_password');
        }
        
        // Check if passwords match
        if ($new_password !== $confirm_password) {
            $this->session->set_flashdata('error', 'Passwords do not match');
            
            // Pass back the user data to the reset password page
            $data = array(
                'user_id' => $user_id,
                'email' => $email
            );
            
            $this->load->view('auth/reset_password', $data);
            return;
        }
        
        // Update password in database
        $this->load->model('user_model');
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $result = $this->user_model->update_password($user_id, $hashed_password);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Password has been reset successfully. You can now login with your new password.');
            redirect('auth');
        } else {
            $this->session->set_flashdata('error', 'Failed to update password. Please try again.');
            
            // Pass back the user data to the reset password page
            $data = array(
                'user_id' => $user_id,
                'email' => $email
            );
            
            $this->load->view('auth/reset_password', $data);
        }
    }
}