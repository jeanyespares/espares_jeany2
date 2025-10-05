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

    public function get_all_users($page = 1, $per_page = 10, $q = '')
    {
        $query = $this->db->table($this->table);

        if (!empty($q)) {
            $query->where("id LIKE '%{$q}%' 
                           OR fname LIKE '%{$q}%' 
                           OR lname LIKE '%{$q}%' 
                           OR email LIKE '%{$q}%' 
                           OR username LIKE '%{$q}%'");
        }

        $countQuery = clone $query;
        $total_rows = $countQuery->select_count('*', 'count')->get()['count'];

        $records = $query->pagination($per_page, $page)->get_all();

        $pagination_html = $this->generate_pagination_links($total_rows, $per_page, $page, $q);

        return [
            'users' => $records,
            'pagination' => $pagination_html
        ];
    }

    private function generate_pagination_links($total_rows, $per_page, $current_page, $q)
    {
        $total_pages = ceil($total_rows / $per_page);
        $html = '';

        if ($total_pages > 1) {
            for ($i = 1; $i <= $total_pages; $i++) {
                $url = site_url("users/index?page={$i}") . (!empty($q) ? "&q={$q}" : '');
                if ($i == $current_page) {
                    $html .= "<strong>{$i}</strong>";
                } else {
                    $html .= "<a href='{$url}'>{$i}</a>";
                }
            }
        }
        return $html;
    }

    /* ===========================
       🔐 AUTHENTICATION HELPERS
    =========================== */

    public function count_all_users()
    {
        return $this->db->table($this->table)->count_all();
    }

    public function get_user_by_username($username)
    {
        return $this->db->table($this->table)
                        ->where('username', $username)
                        ->get();
    }

    public function register_user($username, $password, $role)
    {
        $data = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role
        ];
        return $this->db->table($this->table)->insert($data);
    }

    /* ===========================
       ✏️ CRUD OPERATIONS
    =========================== */

    public function add_student($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function get_student_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->get();
    }

    public function update_student($id, $data)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->update($data);
    }

    public function delete_student($id)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->delete();
    }
}