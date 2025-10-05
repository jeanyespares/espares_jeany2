<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');
        session_start();
    }

    /* ===========================
       🔐 AUTHENTICATION
    ============================ */

    public function login()
    {
        if ($this->io->method() == 'post') {
            $username = $this->io->post('username');
            $password = $this->io->post('password');

            $user = $this->UsersModel->get_user($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    redirect(site_url('users/admin_dashboard'));
                } else {
                    redirect(site_url('users/dashboard'));
                }
            } else {
                $data['error'] = 'Invalid username or password.';
                $this->call->view('users/login', $data);
            }
        } else {
            $this->call->view('users/login');
        }
    }

    public function logout()
    {
        session_destroy();
        redirect(site_url('users/login'));
    }

    public function register()
    {
        if ($this->io->method() == 'post') {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
                'username' => $this->io->post('username'),
                'password' => $this->io->post('password'),
                'role' => $this->io->post('role')
            ];

            if ($this->UsersModel->register_user($data)) {
                redirect(site_url('users/login'));
            } else {
                $data['error'] = "Registration failed. Try again.";
                $this->call->view('users/register', $data);
            }
        } else {
            $this->call->view('users/register');
        }
    }

    /* ===========================
       👤 DASHBOARDS
    ============================ */

    public function dashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect(site_url('users/login'));
        }

        $this->call->view('users/dashboard');
    }

    public function admin_dashboard()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            redirect(site_url('users/dashboard'));
        }

        $data['users'] = $this->UsersModel->page('', null, null)['records'];
        $this->call->view('users/admin_dashboard', $data);
    }

    /* ===========================
       🧾 ORIGINAL CRUD (with pagination + search)
    ============================ */

    public function index()
    {
        $page = isset($_GET['page']) ? $this->io->get('page') : 1;
        $q = isset($_GET['q']) ? trim($this->io->get('q')) : '';
        $records_per_page = 5;

        $all = $this->UsersModel->page($q, $records_per_page, $page);
        $data['users'] = $all['records'];
        $total_rows = $all['total_rows'];

        $this->pagination->set_options([
            'first_link'     => '⏮ First',
            'last_link'      => 'Last ⏭',
            'next_link'      => 'Next →',
            'prev_link'      => '← Prev',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('default');

        $this->pagination->initialize(
            $total_rows,
            $records_per_page,
            $page,
            site_url() . '?q=' . urlencode($q)
        );
        $data['page'] = $this->pagination->paginate();

        $this->call->view('users/index', $data);
    }

    public function create()
    {
        if ($this->io->method() == 'post') {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email')
            ];

            if ($this->UsersModel->insert($data)) {
                redirect(site_url());
            } else {
                echo "Error in creating user.";
            }
        } else {
            $this->call->view('users/create');
        }
    }

    public function update($id)
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
                redirect(site_url());
            } else {
                echo "Error in updating information.";
            }
        } else {
            $data['user'] = $user;
            $this->call->view('users/update', $data);
        }
    }

    public function delete($id)
    {
        if ($this->UsersModel->delete($id)) {
            redirect(site_url());
        } else {
            echo "Error in deleting user.";
        }
    }
}
