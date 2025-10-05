<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {
    protected $table = 'students'; // your table name
    protected $primary_key = 'id';
    protected $allowed_fields = ['fname', 'lname', 'email', 'username', 'password', 'role'];
    protected $validation_rules = [
        'lname' => 'required|min_length[2]|max_length[100]',
        'fname' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
        'username' => 'required|min_length[3]|max_length[50]',
        'password' => 'required|min_length[6]',
        'role' => 'required'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /* ===========================
       📄 PAGINATION (your original)
    ============================ */
    public function page($q = '', $records_per_page = null, $page = null)
    {
        if (is_null($page)) {
            return [
                'total_rows' => $this->db->table($this->table)->count_all(),
                'records'    => $this->db->table($this->table)->get_all()
            ];
        } else {
            $query = $this->db->table($this->table);

            if (!empty($q)) {
                $query->where("id LIKE '%{$q}%' 
                            OR fname LIKE '%{$q}%' 
                            OR lname LIKE '%{$q}%' 
                            OR email LIKE '%{$q}%'");
            }

            $countQuery = clone $query;
            $data['total_rows'] = $countQuery->select_count('*', 'count')->get()['count'];
            $data['records'] = $query->pagination($records_per_page, $page)->get_all();

            return $data;
        }
    }

    /* ===========================
       🔐 AUTHENTICATION
    ============================ */

    // Fetch user by username (for login)
    public function get_user($username)
    {
        return $this->db->table($this->table)
                        ->where('username', $username)
                        ->get();
    }

    // Create a new user (with password hashing)
    public function register_user($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->table($this->table)->insert($data);
    }
}
