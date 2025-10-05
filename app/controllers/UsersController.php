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
        parent::__construct(); 
        $this->call->model('UsersModel');
        $this->call->helper('url');
        $this->call->helper('session');
        $this->call->helper('input');
        $this->call->helper('form'); 
    }

    private function is_admin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    public function index()
    {
        $data['is_admin'] = $this->is_admin();
        $data['is_logged_in'] = isset($_SESSION['user']);

        $q = isset($_GET['q']) ? $this->io->get('q') : '';
        $page = isset($_GET['page']) ? $this->io->get('page') : 1; 

        $results = $this->UsersModel->get_all_students($q, 5, $page);
        $data['users'] = $results['records'];
        $data['pagination'] = $results['pagination'];
        $data['q'] = $q;

        $this->call->view('users/index', $data);
    }

    public function create()
    {
        if (!$this->is_admin()) redirect(site_url('users/index'));

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

    public function update($id = null)
    {
        if (!$this->is_admin()) redirect(site_url('users/index'));

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
        if (!$data['user']) redirect(site_url('users/index'));
        
        $this->call->view('users/update', $data);
    }

    public function delete($id = null)
    {
        if (!$this->is_admin()) redirect(site_url('users/index'));

        if ($this->UsersModel->delete_student($id)) {
            $this->session->set_flashdata('success', 'Student deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete student.');
        }
        redirect(site_url('users/index'));
    }

    public function register()
    {
        $total_users = $this->UsersModel->count_all_users();
        if ($total_users > 0) {
            $this->session->set_flashdata('error', 'Registration is currently closed. Only one admin user is allowed.');
            redirect(site_url('users/login'));
        }

        $data = [];
        if (is_post_request()) {
            $username = trim($this->io->post('username')); 
            $password = $this->io->post('password');

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
            } elseif ($this->UsersModel->get_user_by_username($username)) {
                $data['error'] = 'Username already exists.';
            } else {
                $admin_data = [
                    'username' => $username,
                    'password' => $password, 
                    'role' => 'admin',
                    'fname' => 'Jeany', 
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

    public function login()
    {
        $data = ['error' => ''];
        if (is_post_request()) {
            $username = trim($this->io->post('username')); 
            $password = $this->io->post('password');

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
            } else {
                $user = $this->UsersModel->get_user_by_username($username);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role'], 
                    ];
                    redirect(site_url('users/index'));
                } else {
                    $data['error'] = 'Invalid username or password.';
                }
            }
        }
        $this->call->view('users/login', $data);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect(site_url('users/login'));
    }
}
