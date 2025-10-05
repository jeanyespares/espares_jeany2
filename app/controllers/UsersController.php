<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Users extends Controller {

    public function __construct() {
        parent::__construct();
        // Load the UsersModel to handle all data and auth logic
        $this->model->load('users_model');
        // Load the session library for auth state and flash messages
        $this->call->library('session');
    }

    /**
     * Main Index page (Student Directory)
     */
    public function index() {
        // 1. Get current search query and page number
        $q = $this->io->get('q');
        $page = $this->io->get('page') ?? 1;

        // 2. Fetch data from the Model
        $results = $this->users_model->get_all_students($q, 5, $page);

        // 3. Prepare View Data
        $data['users'] = $results['records'];
        $data['pagination'] = $results['pagination'];

        // 4. Authentication and Role Check
        $data['is_logged_in'] = $this->session->has_userdata('user');
        $data['is_admin'] = false;
        if ($data['is_logged_in']) {
            $user_data = $this->session->userdata('user');
            $data['is_admin'] = ($user_data['role'] === 'admin');
        }

        // 5. Load the main view
        $this->call->view('users/index', $data);
    }

    // ========================================================
    // AUTHENTICATION LOGIC
    // ========================================================

    /**
     * Display Login Form or process POST data
     */
    public function login() {
        if ($this->session->has_userdata('user')) {
            redirect('/');
        }

        $data = ['error' => ''];
        $data['flash_error'] = $this->session->flashdata('error');
        $data['flash_success'] = $this->session->flashdata('success');

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
                $this->session->set_flashdata('success', 'Welcome, ' . $user['username'] . '! You have successfully logged in.');
                redirect('/');
            } else {
                $data['error'] = 'Invalid username or password. Please try again.';
                $this->session->set_flashdata('error', 'Login failed. Invalid credentials.');
                redirect('users/login');
            }
        }

        $this->call->view('users/login', $data);
    }

    /**
     * Logout
     */
    public function logout() {
        $this->session->unset_userdata('user');
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect('users/login');
    }

    // ========================================================
    // ADMIN CHECK AND CRUD OPERATIONS
    // ========================================================

    private function check_admin() {
        if (!$this->session->has_userdata('user') || $this->session->userdata('user')['role'] !== 'admin') {
            $this->session->set_flashdata('error', 'Access Denied. Admin privilege required.');
            redirect('/');
        }
    }

    public function create() {
        $this->check_admin();

        if ($this->io->post()) {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
            ];

            if ($this->users_model->add_student($data)) {
                $this->session->set_flashdata('success', 'Student added successfully!');
                redirect('/');
            } else {
                $this->session->set_flashdata('error', 'Failed to add student.');
                redirect('users/create');
            }
        }

        $this->call->view('users/create_student');
    }

    public function update($id) {
        $this->check_admin();
        $data['student'] = $this->users_model->get_student_by_id($id);

        if (!$data['student']) {
            $this->session->set_flashdata('error', 'Student not found.');
            redirect('/');
        }

        if ($this->io->post()) {
            $update_data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
            ];

            if ($this->users_model->update_student($id, $update_data)) {
                $this->session->set_flashdata('success', 'Student record updated successfully!');
                redirect('/');
            } else {
                $this->session->set_flashdata('error', 'Failed to update student record.');
                redirect('users/update/' . $id);
            }
        }

        $this->call->view('users/update_student', $data);
    }

    public function delete($id) {
        $this->check_admin();

        if ($this->users_model->delete_student($id)) {
            $this->session->set_flashdata('success', 'Student record deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete student record.');
        }

        redirect('/');
    }

    // NOTE: Dashboard and Register routes are omitted for brevity.
}