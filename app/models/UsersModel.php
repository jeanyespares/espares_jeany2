<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {
    protected $table = 'students';
    protected $primary_key = 'id';
    // Removed username, password, role from validation since they are set automatically 
    // or validated separately in the Controller/Auth process for students.
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
        🔐 AUTHENTICATION HELPERS
    =========================== */

    /**
     * Counts the total number of users in the database.
     * Used by the Controller to block registration after the Admin is created.
     * @return int
     */
    public function count_all_users()
    {
        // Counts all records in the table, regardless of role.
        return $this->db->table($this->table)->count_all();
    }

    /**
     * Gets user data by username (used for login verification).
     * @param string $username
     * @return array|null
     */
    public function get_user_by_username($username)
    {
        return $this->db->table($this->table)
                        ->where('username', $username)
                        ->get();
    }

    /**
     * Registers a new user (usually the Admin).
     * @param array $data Contains fname, lname, email, username, password (plain text), role.
     * @return bool
     */
    public function register_user($data)
    {
        // Hash password before insert
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        // Only insert fields that are allowed
        $insert_data = array_intersect_key($data, array_flip($this->allowed_fields));
        return $this->db->table($this->table)->insert($insert_data);
    }

    /* ===========================
        🔎 PAGINATION + SEARCH (Student Directory)
    =========================== */

    /**
     * Fetches paginated and searchable list of students.
     * @param string $q Search query
     * @param int $records_per_page
     * @param int $page Current page number
     * @return array An array containing 'total_rows' and 'records'.
     */
    public function get_all_students($q = '', $records_per_page = 5, $page = 1)
    {
        $query = $this->db->table($this->table);

        // Filter out the main Admin user from the student list if needed, 
        // but for now, we include everyone and rely on CRUD checks.

        if (!empty($q)) {
            // Updated to use proper query binding or safer string interpolation (depending on framework features)
            $query->where("id LIKE '%{$q}%' OR fname LIKE '%{$q}%' OR lname LIKE '%{$q}%' OR email LIKE '%{$q}%'");
        }

        // Count total rows matching search criteria
        // Note: Cloning the query might be necessary depending on the framework's DB class.
        $countQuery = clone $query;
        $data['total_rows'] = $countQuery->select_count('*', 'count')->get()['count'];

        // Fetch paginated records
        // Order by ID (primary key) for consistency
        $data['records'] = $query->order_by($this->primary_key, 'asc')->pagination($records_per_page, $page)->get_all();

        return $data;
    }

    /* ===========================
        ✏️ CRUD OPERATIONS (Student Data)
    =========================== */

    /**
     * Adds a new student record.
     * @param array $data Student data.
     * @return bool
     */
    public function add_student($data)
    {
        // Assuming validation is run before this, otherwise call $this->validate($data)
        $insert_data = array_intersect_key($data, array_flip($this->allowed_fields));
        return $this->db->table($this->table)->insert($insert_data);
    }

    /**
     * Finds a single student record by ID.
     * @param int $id The primary key.
     * @return array|null
     */
    public function get_student_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->get();
    }

    /**
     * Updates an existing student record.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_student($id, $data)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->update($data);
    }

    /**
     * Deletes a student record.
     * @param int $id
     * @return bool
     */
    public function delete_student($id)
    {
        return $this->db->table($this->table)
                        ->where($this->primary_key, $id)
                        ->delete();
    }
}
