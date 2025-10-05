<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller { 
    
    // Walang __construct()
    // Walang local properties para i-store ang model
    
    // Ang lahat ng model/library/helper loading ay ilalagay natin DIREKTA sa index()

    // --- Helper function for checking admin status ---
    // Aasahan natin na ang session ay handa na bago tawagin ito
    private function is_admin() {
        return $this->session->has_userdata('user') && $this->session->userdata('user')['role'] === 'admin';
    }

    // --- Helper function for redirecting if not admin ---
    private function check_admin() {
        // Aasahan na ang site_url() at session ay na-load na ng function na tumawag
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
        // Tanging dito lang tayo maglo-load ng resources
        $this->model->load('users_model'); // 👈 Dito lang tayo mag-lo-load
        $this->call->library('session'); 
        $this->call->helper('url'); 
        
        $q = $this->io->get('q');
        $page = $this->io->get('page') ?? 1;

        // Gagamitin ang standard property na dapat ay sinet ng framework:
        $results = $this->users_model->get_all_students($q, 5, $page);

        $data['users'] = $results['records']; 
        $data['pagination'] = $results['pagination']; 
        $data['q'] = $q;

        $data['is_logged_in'] = $this->session->has_userdata('user');
        $data['is_admin'] = $this->is_admin();

        $this->call->view('users/index', $data);
    }

    public function login() {
        $this->model->load('users_model'); // 👈 Dito lang tayo mag-lo-load
        $this->call->library('session'); 
        $this->call->helper('url'); 
        
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
        $this->model->load('users_model'); // 👈 Dito lang tayo mag-lo-load
        $this->call->library('session'); 
        $this->call->helper('url'); 

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
        $this->call->library('session'); // Load session for logout
        $this->call->helper('url'); // Load helper for redirect

        $this->session->unset_userdata('user');
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect(site_url('users/login'));
    }

    // ========================================================
    // CRUD OPERATIONS (Admin only)
    // ========================================================

    public function create() {
        $this->model->load('users_model'); // 👈 Dito lang tayo mag-lo-load
        $this->call->library('session'); 
        $this->call->helper('url'); 
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
        $this->model->load('users_model'); // 👈 Dito lang tayo mag-lo-load
        $this->call->library('session'); 
        $this->call->helper('url'); 
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
        $this->model->load('users_model'); // 👈 Dito lang tayo mag-lo-load
        $this->call->library('session'); 
        $this->call->helper('url'); 
        $this->check_admin();

        if ($this->users_model->delete_student($id)) {
            $this->session->set_flashdata('success', 'Student record deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete student record.');
        }

        redirect(site_url('users/index')); 
    }
}