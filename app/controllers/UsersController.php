<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller { 
    
    // We will use this property to hold the loaded model instance.
    protected $users_model;
    
    public function __construct() {
        // TANGING parent::__construct() lang ang iwanan dito. 
        // Huwag gumamit ng $this->model, $this->call, o $this->session dito.
        parent::__construct();
    }

    /**
     * Initializes the model, session, and helper resources if they haven't been loaded yet.
     * This is called at the start of every method that needs framework components.
     */
    private function initialize_resources() {
        // Check if the model is already loaded (lazy loading)
        if (isset($this->users_model)) {
            return;
        }
        
        // Load the Model and assign it to the local property
        $this->model->load('users_model');
        $this->users_model = $this->model->users_model;
        
        // Load Library and Helper (These must be loaded before they are used)
        $this->call->library('session');
        $this->call->helper('url'); 
    }

    // --- Helper function for checking admin status ---
    private function is_admin() {
        // This helper calls initialize_resources() indirectly via its methods
        return $this->session->has_userdata('user') && $this->session->userdata('user')['role'] === 'admin';
    }

    // --- Helper function for redirecting if not admin ---
    private function check_admin() {
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
        $this->initialize_resources(); // 👈 Load resources FIRST!
        
        $q = $this->io->get('q');
        $page = $this->io->get('page') ?? 1;

        $results = $this->users_model->get_all_students($q, 5, $page);

        $data['users'] = $results['records']; 
        $data['pagination'] = $results['pagination']; 
        $data['q'] = $q;

        $data['is_logged_in'] = $this->session->has_userdata('user');
        $data['is_admin'] = $this->is_admin();

        $this->call->view('users/index', $data);
    }

    public function login() {
        $this->initialize_resources(); // 👈 Load resources FIRST!
        
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
        $this->initialize_resources(); // 👈 Load resources FIRST!
        
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
        $this->initialize_resources(); // 👈 Load resources FIRST!
        
        $this->session->unset_userdata('user');
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect(site_url('users/login'));
    }

    // ========================================================
    // CRUD OPERATIONS (Admin only)
    // ========================================================

    public function create() {
        $this->initialize_resources(); // 👈 Load resources FIRST!
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
        $this->initialize_resources(); // 👈 Load resources FIRST!
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
        $this->initialize_resources(); // 👈 Load resources FIRST!
        $this->check_admin();

        if ($this->users_model->delete_student($id)) {
            $this->session->set_flashdata('success', 'Student record deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete student record.');
        }

        redirect(site_url('users/index')); 
    }
}