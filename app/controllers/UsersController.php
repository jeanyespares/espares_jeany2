<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller { 
    
    // Walang __construct() para iwasan ang error. Ipapasa ang burden sa bawat method.

    /**
     * Tinitiyak na naka-load ang model at resources.
     * Ito ay tatawagin sa simula ng bawat function.
     */
    private function load_resources() {
        // I-check kung naka-load na ang model sa standard property name
        if (!isset($this->users_model)) {
            // Dahil sa error, i-assume natin na gumagana ang $this->model->load,
            // at automatic na sine-set ng framework ang $this->users_model
            // at hindi ang manual $this->model->users_model
            $this->model->load('users_model');
            
            // I-load ang libraries/helpers bago gamitin
            $this->call->library('session');
            $this->call->helper('url'); 
        }
    }

    // --- Helper function for checking admin status ---
    private function is_admin() {
        // Hindi na kailangan i-load ang resources dito, dahil ang public methods 
        // na tumatawag dito (tulad ng index) ang gagawa.
        return $this->session->has_userdata('user') && $this->session->userdata('user')['role'] === 'admin';
    }

    // --- Helper function for redirecting if not admin ---
    private function check_admin() {
        // Tiyakin na ang public method na tumatawag dito ay nag-load muna ng resources.
        if (!$this->is_admin()) {
            $this->session->set_flashdata('error', 'Access Denied. Admin privilege required.');
            redirect(site_url('users/index')); 
        }
    }

    // ========================================================
    // PUBLIC METHODS
    // ========================================================

    /*** Main Index page (Student Directory) */
    public function index() {
        $this->load_resources(); // 👈 Load resources FIRST!
        
        $q = $this->io->get('q');
        $page = $this->io->get('page') ?? 1;

        // Gamitin ang standard property name: $this->users_model
        $results = $this->users_model->get_all_students($q, 5, $page);

        $data['users'] = $results['records']; 
        $data['pagination'] = $results['pagination']; 
        $data['q'] = $q;

        $data['is_logged_in'] = $this->session->has_userdata('user');
        $data['is_admin'] = $this->is_admin();

        $this->call->view('users/index', $data);
    }

    public function login() {
        $this->load_resources(); // 👈 Load resources FIRST!
        
        if ($this->session->has_userdata('user')) {
            redirect(site_url('users/index')); 
        }

        if ($this->io->post()) {
            $username = $this->io->post('username');
            $password = $this->io->post('password');
            $user = $this->users_model->get_user_by_username($username);

            if ($user && password_verify($password, $user['password'])) {
                $this->session->set_userdata('user', [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ]);
                $this->session->set_flashdata('success', 'Login successful.');
                redirect(site_url('users/index')); 
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password.');
                redirect(site_url('users/login'));
            }
        }
        $this->call->view('users/login');
    }

    public function register() {
        $this->load_resources(); // 👈 Load resources FIRST!
        
        if ($this->io->post()) {
            $data = [
                'username' => $this->io->post('username'),
                'password' => $this->io->post('password'),
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
                'role' => 'admin' 
            ];

            if ($this->users_model->register_user($data)) {
                $this->session->set_flashdata('success', 'Registration successful! You can now log in.');
                redirect(site_url('users/login'));
            } else {
                $this->session->set_flashdata('error', 'Registration failed. Username or email might already be in use.');
                redirect(site_url('users/register'));
            }
        }
        $this->call->view('users/register');
    }

    public function logout() {
        $this->load_resources(); // 👈 Load resources FIRST!
        
        $this->session->unset_userdata('user');
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect(site_url('users/login'));
    }

    // ========================================================
    // CRUD OPERATIONS (Admin only)
    // ========================================================

    public function create() {
        $this->load_resources(); // 👈 Load resources FIRST!
        $this->check_admin();

        if ($this->io->post()) {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
            ];

            if ($this->users_model->add_student($data)) {
                $this->session->set_flashdata('success', 'Student added successfully!');
                redirect(site_url('users/index')); 
            } else {
                $this->session->set_flashdata('error', 'Failed to add student.');
            }
        }
        $this->call->view('users/create_student');
    }

    public function update($id) {
        $this->load_resources(); // 👈 Load resources FIRST!
        $this->check_admin();

        $data['student'] = $this->users_model->get_student_by_id($id);
        if (!$data['student']) {
            $this->session->set_flashdata('error', 'Student not found.');
            redirect(site_url('users/index')); 
        }

        if ($this->io->post()) {
            $update_data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
            ];

            if ($this->users_model->update_student($id, $update_data)) {
                $this->session->set_flashdata('success', 'Student record updated successfully!');
                redirect(site_url('users/index')); 
            } else {
                $this->session->set_flashdata('error', 'Failed to update student record.');
            }
        }
        $this->call->view('users/update_student', $data);
    }

    public function delete($id) {
        $this->load_resources(); // 👈 Load resources FIRST!
        $this->check_admin();

        if ($this->users_model->delete_student($id)) {
            $this->session->set_flashdata('success', 'Student record deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete student record.');
        }

        redirect(site_url('users/index')); 
    }
}