<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

require_once __DIR__ . '/../models/UsersModel.php';

class UsersController extends Controller {

    protected $users_model_instance;

    public function __construct() {
        parent::__construct();
        $this->initialize_resources();
    }

    private function initialize_resources() {
        if (isset($this->users_model_instance)) return;

        $this->call->library('session');
        $this->call->helper('url');
        $this->users_model_instance = new UsersModel();
    }

    private function is_admin() {
        $user = $this->session->userdata('user');
        return $user && isset($user['role']) && $user['role'] === 'admin';
    }

    private function check_admin() {
        if (!$this->is_admin()) {
            $this->session->set_flashdata('error', 'Access Denied. Admin privilege required.');
            redirect(site_url('users/index'));
        }
    }

    // ===================== PUBLIC METHODS =====================

    public function index() {
        $q = $this->io->get('q') ?? '';
        $page = (int) ($this->io->get('page') ?? 1);
        $limit = 5;

        $results = $this->users_model_instance->get_all_students($q, $limit, $page);

        $data = [
            'users' => $results['records'],
            'pagination' => $results['pagination'],
            'q' => $q,
            'is_logged_in' => $this->session->has_userdata('user'),
            'is_admin' => $this->is_admin()
        ];

        $this->call->view('users/index', $data);
    }

    public function login() {
        if ($this->session->has_userdata('user')) {
            redirect(site_url('users/index'));
        }

        if ($this->io->post()) {
            $username = $this->io->post('username');
            $password = $this->io->post('password');
            $user = $this->users_model_instance->get_user_by_username($username);

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
        if ($this->io->post()) {
            $data = [
                'username' => $this->io->post('username'),
                'password' => password_hash($this->io->post('password'), PASSWORD_DEFAULT),
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
                'role' => 'admin'
            ];

            if ($this->users_model_instance->register_user($data)) {
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
        $this->session->unset_userdata('user');
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect(site_url('users/login'));
    }

    // ===================== CRUD OPERATIONS =====================

    public function create() {
        $this->check_admin();

        if ($this->io->post()) {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email')
            ];

            if ($this->users_model_instance->add_student($data)) {
                $this->session->set_flashdata('success', 'Student added successfully!');
                redirect(site_url('users/index'));
            } else {
                $this->session->set_flashdata('error', 'Failed to add student.');
            }
        }

        $this->call->view('users/create_student');
    }

    public function update($id) {
        $this->check_admin();

        $student = $this->users_model_instance->get_student_by_id($id);
        if (!$student) {
            $this->session->set_flashdata('error', 'Student not found.');
            redirect(site_url('users/index'));
        }

        if ($this->io->post()) {
            $update_data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email')
            ];

            if ($this->users_model_instance->update_student($id, $update_data)) {
                $this->session->set_flashdata('success', 'Student record updated successfully!');
                redirect(site_url('users/index'));
            } else {
                $this->session->set_flashdata('error', 'Failed to update student record.');
            }
        }

        $this->call->view('users/update_student', ['student' => $student]);
    }

    public function delete($id) {
        $this->check_admin();

        if ($this->users_model_instance->delete_student($id)) {
            $this->session->set_flashdata('success', 'Student record deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete student record.');
        }

        redirect(site_url('users/index'));
    }
}