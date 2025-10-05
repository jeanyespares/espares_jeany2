<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {
    // Default table for students list
    protected $table = 'students';
    protected $primary_key = 'id';
    protected $allowed_fields = ['fname', 'lname', 'email'];
    protected $validation_rules = [
        'lname' => 'required|min_length[2]|max_length[100]',
        'fname' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    // ==========================
    // EXISTING PAGINATION LOGIC
    // ==========================
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

    // ==========================
    // AUTHENTICATION SECTION
    // ==========================
    public function get_user_by_username($username)
    {
        // use the users table for login credentials
        return $this->db->table('users')->where('username', $username)->get()->row_array();
    }
}
