<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');

        // ✅ Ensure session is started without error
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===========================
       🔐 AUTHENTICATION SECTION
    =========================== */

    // 🧾 Register new user
    public function register()
    {
        if (isset($_SESSION['user'])) {
            redirect(site_url('/'));
        }

        if ($this->io->method() === 'post') {
            $username = trim($this->io->post('username'));
            $password = trim($this->io->post('password'));
            $fname = trim($this->io->post('fname'));
            $lname = trim($this->io->post('lname'));
            $email = trim($this->io->post('email'));
            $role = 'user';

            if (empty($username) || empty($password) || empty($fname) || empty($lname) || empty($email)) {
                echo "All fields are required.";
                return;
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $data = [
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'username' => $username,
                'password' => $hashed,
                'role' => $role
            ];

            if ($this->UsersModel->insert($data)) {
                redirect(site_url('users/login'));
            } else {
                echo "Error in registration.";
            }
        } else {
            $this->call->view('users/register');
        }
    }

    // 🔑 Login user
    public function login()
    {
        if (isset($_SESSION['user'])) {
            redirect(site_url('/'));
        }

        if ($this->io->method() === 'post') {
            $username = trim($this->io->post('username'));
            $password = trim($this->io->post('password'));

            $user = $this->UsersModel->get_by_username($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
                redirect(site_url('/'));
            } else {
                echo "Invalid username or password.";
            }
        } else {
            $this->call->view('users/login');
        }
    }

    // 🚪 Logout
    public function logout()
    {
        session_destroy();
        redirect(site_url('/'));
    }

    // 🏠 Dashboard
    public function dashboard()
    {
        if (!isset($_SESSION['user'])) {
            redirect(site_url('users/login'));
        }

        $user = $_SESSION['user'];
        $data['username'] = $user['username'];
        $data['role'] = $user['role'];

        $this->call->view('users/dashboard', $data);
    }

    // 👑 Admin-only section
    public function admin_only()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Access denied.";
            return;
        }

        echo "Welcome Admin!";
    }

    /* ===========================
       📋 ORIGINAL CRUD SECTION
    =========================== */

    public function index()
    {
        $page = isset($_GET['page']) ? $this->io->get('page') : 1;
        $q = isset($_GET['q']) ? trim($this->io->get('q')) : '';

        $records_per_page = 5;
        $all = $this->UsersModel->page($q, $records_per_page, $page);
        $data['users'] = $all['records'];
        $total_rows = $all['total_rows'];

        $this->pagination->set_options([
            'first_link' => '⏮ First',
            'last_link' => 'Last ⏭',
            'next_link' => 'Next →',
            'prev_link' => '← Prev',
            'page_delimiter' => '&page='
        ]);

        $this->pagination->set_theme('default');
        $this->pagination->initialize($total_rows, $records_per_page, $page, site_url() . '?q=' . urlencode($q));
        $data['page'] = $this->pagination->paginate();

        $this->call->view('users/index', $data);
    }

    function create()
    {
        if ($this->io->method() == 'post') {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
                // NOTE: Add username/password if needed
            ];

            if ($this->UsersModel->insert($data)) {
                redirect(site_url('/'));
            } else {
                echo "Error in creating user.";
            }
        } else {
            $this->call->view('users/create');
        }
    }

    function update($id)
    {
        $user = $this->UsersModel->find($id);
        if (!$user) {
            echo "User not found.";
            return;
        }

        if ($this->io->method() == 'post') {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email')
            ];

            if ($this->UsersModel->update($id, $data)) {
                redirect(site_url('/'));
            } else {
                echo "Error in updating information.";
            }
        } else {
            $data['user'] = $user;
            $this->call->view('users/update', $data);
        }
    }

    function delete($id)
    {
        if ($this->UsersModel->delete($id)) {
            redirect(site_url('/'));
        } else {
            echo "Error in deleting user.";
        }
    }
}