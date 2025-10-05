<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller {
    public function __construct()
    {
        parent::__construct();
        // load auth library
        $this->call->library('Auth');
    }

    public function login()
    {
        // show login form or process POST
        if ($this->io->method() === 'post') {
            $username = $this->io->post('username');
            $password = $this->io->post('password');

            if ($this->Auth->login($username, $password)) {
                // redirect to home
                redirect(site_url());
            } else {
                $data['error'] = 'Invalid username or password';
                $this->call->view('auth/login', $data);
            }
        } else {
            $this->call->view('auth/login');
        }
    }

    public function logout()
    {
        $this->Auth->logout();
        redirect(site_url());
    }
}
