<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {
    protected $table = 'students';
    protected $primary_key = 'id';
    protected $allowed_fields = ['fname', 'lname', 'email', 'username', 'password', 'role'];
    protected $validation_rules = [
        'lname' => 'required|min_length[2]|max_length[100]',
        'fname' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /* ===========================
       🔎 PAGINATION + SEARCH
    =========================== */
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
                            OR email LIKE '%{$q}%' 
                            OR username LIKE '%{$q}%'");
            }

            // Count total rows
            $countQuery = clone $query;
            $data['total_rows'] = $countQuery->select_count('*', 'count')->get()['count'];

            // Fetch paginated records
            $data['records'] = $query->pagination($records_per_page, $page)->get_all();

            return $data;
        }
    }

    /* ===========================
       🔐 AUTHENTICATION HELPERS
    =========================== */

    // 🔎 Get user by username (used for login)
    public function get_by_username($username)
    {
        return $this->db->table($this->table)
                        ->where('username', $username)
                        ->get();
    }

    // ➕ Register new user (optional separate method)
    public function register_user($data)
    {
        // Hash password before insert
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->table($this->table)->insert($data);
    }

    /* ===========================
       ✏️ CRUD OPERATIONS
    =========================== */

    public function insert($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function find($id)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->get();
    }

    public function update($id, $data)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->update($data);
    }

    public function delete($id)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->delete();
    }
}
