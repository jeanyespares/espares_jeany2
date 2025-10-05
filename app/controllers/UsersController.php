<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // LOGIN
    public function login() {
        if ($this->io->method() == 'post') {
            $username = $this->io->post('username');
            $password = $this->io->post('password');

            if ($username === 'jeany' && $password === 'jeany21') {
                $_SESSION['user'] = $username;
                redirect(site_url());
            } else {
                $data['error'] = "Invalid username or password.";
                $this->call->view('users/login', $data);
            }
        } else {
            $this->call->view('users/login');
        }
    }

    // LOGOUT
    public function logout() {
        session_destroy();
        redirect(site_url('users/login'));
    }

    // INDEX (protected)
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'jeany') {
            redirect(site_url('users/login'));
        }

        $page = 1;
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $page = $this->io->get('page');
        }

        $q = '';
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($this->io->get('q'));
        }

        $records_per_page = 5;
        $all = $this->UsersModel->page($q, $records_per_page, $page);
        $data['users'] = $all['records'];
        $total_rows = $all['total_rows'];

        $this->pagination->set_options([
            'first_link' => '⏮ First',
            'last_link'  => 'Last ⏭',
            'next_link'  => 'Next →',
            'prev_link'  => '← Prev',
            'page_delimiter' => '&page='
        ]);

        $this->pagination->set_theme('default');
        $this->pagination->initialize($total_rows, $records_per_page, $page, site_url() . '?q=' . urlencode($q));
        $data['page'] = $this->pagination->paginate();

        $this->call->view('users/index', $data);
    }

    public function create() {
        if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'jeany') {
            redirect(site_url('users/login'));
        }

        if ($this->io->method() == 'post') {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email')
            ];

            if ($this->UsersModel->insert($data)) {
                redirect(site_url());
            } else {
                echo "Error creating user.";
            }
        } else {
            $this->call->view('users/create');
        }
    }

    public function update($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'jeany') {
            redirect(site_url('users/login'));
        }

        $user = $this->UsersModel->find($id);
        if (!$user) { echo "User not found."; return; }

        if ($this->io->method() == 'post') {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email')
            ];

            if ($this->UsersModel->update($id, $data)) redirect(site_url());
            else echo "Error updating user.";
        } else {
            $data['user'] = $user;
            $this->call->view('users/update', $data);
        }
    }

    public function delete($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'jeany') {
            redirect(site_url('users/login'));
        }

        if ($this->UsersModel->delete($id)) redirect(site_url());
        else echo "Error deleting user.";
    }
}
