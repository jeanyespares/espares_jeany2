<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// FALLBACK: Defines is_post_request() if the framework helper doesn't load it
if (!function_exists('is_post_request')) {
    function is_post_request() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     * Loads necessary models and helpers.
     */
    public function __construct()
    {
        // ⭐️ FIX: Must call parent constructor first to initialize $this->call, $this->io, etc.
        parent::__construct(); 
        
        // Load the UsersModel to interact with the database
        // ⭐️ FIX: Use the correct PascalCase model name (UsersModel)
        $this->call->model('UsersModel');
        
        // Load necessary helpers
        $this->call->helper('url');
        $this->call->helper('session');
        $this->call->helper('input');
        $this->call->helper('form'); // Loaded for robustness
    }

    /**
     * Checks if the currently logged-in user is an admin.
     * @return bool
     */
    private function is_admin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Main student directory view with search and pagination.
     */
    public function index()
    {
        // Pass login status and admin status to the view
        $is_admin = $this->is_admin();
        $is_logged_in = isset($_SESSION['user']);
        
        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;

        // ⭐️ FIX: Safely retrieve 'q' and 'page' using isset to prevent "Undefined array key" warnings
        $q = isset($_GET['q']) ? $this->io->get('q') : '';
        $page = isset($_GET['page']) ? $this->io->get('page') : 1; 

        $records_per_page = 5;

        $results = $this->UsersModel->get_all_students($q, $records_per_page, $page);

        $data['users'] = $results['records'];
        $data['pagination'] = $results['pagination'];
        $data['q'] = $q;

        // View file will use $data['is_admin'] to conditionally show the action column/buttons
        $this->call->view('users/index', $data);
    }

    /**
     * Displays form to create a new student or handles submission. (Admin only)
     */
    public function create()
    {
        if (!$this->is_admin()) {
            redirect(site_url('users/index'));
        }

        if (is_post_request()) {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
            ];

            if ($this->UsersModel->add_student($data)) {
                $this->session->set_flashdata('success', 'Student added successfully.');
                redirect(site_url('users/index'));
            } else {
                $this->session->set_flashdata('error', 'Failed to add student.');
            }
        }
        $this->call->view('users/create');
    }

    /**
     * Displays form to update a student or handles submission. (Admin only)
     * @param int $id The student ID.
     */
    public function update($id = null)
    {
        if (!$this->is_admin()) {
            redirect(site_url('users/index'));
        }

        if (is_post_request()) {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
            ];

            if ($this->UsersModel->update_student($this->io->post('id'), $data)) {
                $this->session->set_flashdata('success', 'Student updated successfully.');
                redirect(site_url('users/index'));
            } else {
                $this->session->set_flashdata('error', 'Failed to update student.');
            }
        }

        $data['user'] = $this->UsersModel->get_student_by_id($id);
        if (!$data['user']) {
            redirect(site_url('users/index'));
        }
        
        $this->call->view('users/update', $data);
    }

    /**
     * Handles deletion of a student. (Admin only)
     * @param int $id The student ID.
     */
    public function delete($id = null)
    {
        if (!$this->is_admin()) {
            redirect(site_url('users/index'));
        }

        if ($this->UsersModel->delete_student($id)) {
            $this->session->set_flashdata('success', 'Student deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete student.');
        }
        redirect(site_url('users/index'));
    }

    /**
     * Registration handler. 
     * ⭐️ BLOCKER: Only allows registration if no users exist (for the first Admin: Jeany).
     */
    public function register()
    {
        $total_users = $this->UsersModel->count_all_users();
        
        // Block registration if an admin already exists (total users > 0)
        if ($total_users > 0) {
            $data['error'] = 'Registration is currently closed. Only one admin user is allowed.';
            $this->session->set_flashdata('error', $data['error']);
            redirect(site_url('users/login'));
        }

        $data = [];
        if (is_post_request()) {
            // ⭐️ FIX: Use $this->io->post() instead of post() function
            $username = trim($this->io->post('username')); 
            $password = $this->io->post('password');

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
            } elseif ($this->UsersModel->get_user_by_username($username)) {
                $data['error'] = 'Username already exists.';
            } else {
                // First user is automatically set as 'admin'
                $role = 'admin'; 
                
                // You may want to prompt for fname, lname, email here if needed for the admin's student profile
                $admin_data = [
                    'username' => $username,
                    'password' => $password, // Model will hash this
                    'role' => $role,
                    // Note: You must ensure your register form collects fname, lname, and email 
                    // or set default values for 'students' table fields if they are required.
                    'fname' => 'Jeany', // Assuming 'jeany' is the admin's name
                    'lname' => 'Admin',
                    'email' => 'jeany.admin@example.com' 
                ];

                if ($this->UsersModel->register_user($admin_data)) {
                    $this->session->set_flashdata('success', 'Admin registered successfully. Please log in.');
                    redirect(site_url('users/login'));
                } else {
                    $data['error'] = 'Registration failed due to a server error.';
                }
            }
        }
        $this->call->view('users/register', $data);
    }

    /**
     * Login handler.
     */
    public function login()
    {
        $data = [];
        $data['error'] = '';
        if (is_post_request()) {
            // ⭐️ FIX: Use $this->io->post() instead of post() function
            $username = trim($this->io->post('username')); 
            $password = $this->io->post('password');

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
            } else {
                $user = $this->UsersModel->get_user_by_username($username);

                if ($user && password_verify($password, $user['password'])) {
                    // SUCCESS: Set session variables, including the essential 'role'
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role'], 
                    ];
                    // Redirect straight to the directory table
                    redirect(site_url('users/index'));
                } else {
                    $data['error'] = 'Invalid username or password.';
                }
            }
        }
        $this->call->view('users/login', $data);
    }

    /**
     * Logout handler.
     */
    public function logout()
    {
        unset($_SESSION['user']);
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect(site_url('users/login'));
    }
}
