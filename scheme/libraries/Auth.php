<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth {
    protected $session;
    protected $db;
    protected $accounts;

    public function __construct()
    {
        // lava instance
        $lava =& lava_instance();
        $this->session =& load_class('Session', 'libraries');
        $this->db =& $lava->db;

        // ensure AccountsModel is available through call loader
        $lava->call->model('AccountsModel');
        $this->accounts =& $lava->AccountsModel;
    }

    /**
     * Attempt login with username and password
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login($username, $password)
    {
        $user = $this->accounts->verify_password($username, $password);
        if ($user) {
            // store minimal user info in session
            $this->session->set_userdata('user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'] ?? 'user'
            ]);
            return true;
        }
        return false;
    }

    public function logout()
    {
        $this->session->unset_userdata('user');
    }

    public function current_user()
    {
        return $this->session->has_userdata('user') ? $_SESSION['user'] : null;
    }

    public function require_login()
    {
        if (!$this->session->has_userdata('user')) {
            // redirect to login
            header('Location: ' . site_url('login'));
            exit;
        }
    }

    public function require_role($role)
    {
        $user = $this->current_user();
        if (!$user || ($user['role'] ?? '') !== $role) {
            // unauthorized
            show_error('Unauthorized', 'You do not have permission to access this page.', 'error_general', 403);
        }
    }
}
