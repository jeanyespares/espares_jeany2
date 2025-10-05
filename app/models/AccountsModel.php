<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AccountsModel extends Model {

    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find user by username
     * @param string $username
     * @return array|null
     */
    public function find_by_username($username)
    {
        // The Database builder's get() returns the fetched row (or false/null) directly.
        $row = $this->db->table($this->table)->where('username', $username)->get();
        return $row ? $row : null;
    }

    /**
     * Verify password using password_verify
     * @param string $username
     * @param string $password
     * @return array|false    Returns user array on success, false on failure
     */
    public function verify_password($username, $password)
    {
        $user = $this->find_by_username($username);
        if (!$user) return false;
        if (isset($user['password']) && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Create a new user record
     * @param string $username
     * @param string $password_plain
     * @param string $role
     * @return int|false inserted id or false on failure
     */
    public function create_user($username, $password_plain, $role = 'user')
    {
        if ($this->find_by_username($username)) {
            return false; // already exists
        }

        $hash = password_hash($password_plain, PASSWORD_DEFAULT);
        $data = [
            'username' => $username,
            'password' => $hash,
            'role' => $role
        ];

        return $this->insert($data);
    }
}
