<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Usersmodel extends Model {
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

    public function page($q = '', $records_per_page = null, $page = null)
    {
        if (is_null($page)) {
            return [
                'total_rows' => $this->db->table($this->table)->count_all(),
                'records'    => $this->db->table($this->table)->get_all()
            ];
        } else {
            $query = $this->db->table($this->table);

            // 🔍 search across id, fname, lname, email
            if (!empty($q)) {
                $query->group_start()
                      ->like('id', $q)
                      ->or_like('fname', $q)
                      ->or_like('lname', $q)
                      ->or_like('email', $q)
                      ->group_end();
            }

            // total count
            $countQuery = clone $query;
            $data['total_rows'] = $countQuery->select_count('*', 'count')->get()['count'];

            // paginated records
            $data['records'] = $query->pagination($records_per_page, $page)->get_all();

            return $data;
        }
    }
}
