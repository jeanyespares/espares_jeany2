<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersAdminController extends Controller {
    public function __construct()
    {
        // Load Auth and AccountsModel early so before_action (called in parent)
        // can access $this->Auth. Use load_class to avoid depending on $this->call
        // which isn't set until parent::__construct() runs.
        try {
            load_class('invoker', 'kernel');
            $invoker =& load_class('invoker', 'kernel');
            $invoker->library('Auth');
            $invoker->model('AccountsModel');
        } catch (Exception $e) {
            // proceed; parent may still initialize autoloaded libraries
        }

        parent::__construct();
    }

    public function before_action() {
        // ensure admin only
        $this->Auth->require_login();
        $this->Auth->require_role('admin');
    }

    public function index()
    {
        $users = $this->AccountsModel->all();
        $this->call->view('admin/users/index', ['users' => $users]);
    }

    public function create()
    {
        if ($this->io->method() === 'post') {
            $username = $this->io->post('username');
            $password = $this->io->post('password');
            $role = $this->io->post('role', 'user');

            $id = $this->AccountsModel->create_user($username, $password, $role);
            if ($id) {
                redirect(site_url('admin/users'));
            } else {
                $data['error'] = 'Unable to create user (maybe username exists)';
                $this->call->view('admin/users/create', $data);
            }
        } else {
            $this->call->view('admin/users/create');
        }
    }
}
