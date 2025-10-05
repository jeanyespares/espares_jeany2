<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model
{
    /**
     * UsersModel constructor.
     * Automatically runs initialization logic upon creation.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initialize_data(); // Calls the function to check and insert initial data
    }

    /**
     * Initializes the database with an Admin user and sample students
     * if the tables are empty.
     */
    private function initialize_data()
    {
        // 1. Check and create the Admin user (if no users exist)
        if ($this->count_all_users() === 0) {
            $admin_data = [
                'username' => 'admin_jeany',
                'password' => 'admin123', // This will be hashed in register_user
                'role' => 'admin',
                'fname' => 'Jeany', 
                'lname' => 'Admin',
                'email' => 'jeany.admin@example.com' 
            ];
            $this->register_user($admin_data);
            error_log("Initial Admin created: admin_jeany / admin123");
        }

        // 2. Check and create sample student records
        if ($this->db->table('students')->get()->num_rows() === 0) {
            $sample_students = [
                ['fname' => 'Maria', 'lname' => 'Dela Cruz', 'email' => 'maria.dela.cruz@school.ph'],
                ['fname' => 'Juan', 'lname' => 'Luna', 'email' => 'juan.luna@school.ph'],
                ['fname' => 'Jose', 'lname' => 'Rizal', 'email' => 'jose.rizal@school.ph'],
            ];

            foreach ($sample_students as $student) {
                $this->db->table('students')->insert($student);
            }
            error_log("Sample students created.");
        }
    }

    // ========================================================
    // STUDENT CRUD OPERATIONS
    // ========================================================

    public function get_all_students($q = '', $limit = 5, $page = 1)
    {
        $offset = ($page - 1) * $limit;
        $total_records = 0;
        
        $this->db->table('students');

        if (!empty($q)) {
            $search_q = '%' . $q . '%';
            $this->db->or_like('fname', $search_q);
            $this->db->or_like('lname', $search_q);
            $this->db->or_like('email', $search_q);
            
            // Count only search results
            $total_records = $this->db->get()->num_rows();
            
            // Re-initialize query for data fetch
            $this->db->table('students');
            $this->db->or_like('fname', $search_q);
            $this->db->or_like('lname', $search_q);
            $this->db->or_like('email', $search_q);
        } else {
            // Count all records if no search query
            $total_records = $this->db->get()->num_rows();
            
            // Re-initialize query for data fetch
            $this->db->table('students');
        }

        // Fetch the records for the current page
        $records = $this->db->limit($limit, $offset)->order_by('id', 'desc')->get()->result_array();

        $total_pages = ceil($total_records / $limit);
        $pagination = $this->generate_pagination($total_pages, $page, $q);

        return [
            'records' => $records,
            'pagination' => $pagination
        ];
    }

    public function get_student_by_id($id)
    {
        return $this->db->table('students')->where('id', $id)->get()->row_array();
    }

    public function add_student($data)
    {
        return $this->db->table('students')->insert($data);
    }

    public function update_student($id, $data)
    {
        return $this->db->table('students')->where('id', $id)->update($data);
    }

    public function delete_student($id)
    {
        return $this->db->table('students')->where('id', $id)->delete();
    }

    // ========================================================
    // USER AUTHENTICATION
    // ========================================================
    
    public function count_all_users()
    {
        return $this->db->table('users')->get()->num_rows();
    }

    public function get_user_by_username($username)
    {
        return $this->db->table('users')->where('username', $username)->get()->row_array();
    }

    public function register_user($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->table('users')->insert($data);
    }

    // ========================================================
    // PAGINATION HELPER
    // ========================================================

    private function generate_pagination($total_pages, $current_page, $q)
    {
        $output = '<nav class="flex items-center space-x-2">';
        $base_url = site_url('users/index');

        // Previous button
        $prev_disabled = ($current_page <= 1) ? 'opacity-50 cursor-not-allowed' : '';
        $prev_page = max(1, $current_page - 1);
        $prev_link = $base_url . '?page=' . $prev_page . (!empty($q) ? '&q=' . urlencode($q) : '');
        $output .= "<a href='{$prev_link}' class='px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 {$prev_disabled}'>Previous</a>";

        // Page numbers
        $start = max(1, $current_page - 2);
        $end = min($total_pages, $current_page + 2);

        for ($i = $start; $i <= $end; $i++) {
            $active_class = ($i == $current_page) ? 'bg-blue-600 text-white' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-100';
            $link = $base_url . '?page=' . $i . (!empty($q) ? '&q=' . urlencode($q) : '');
            $output .= "<a href='{$link}' class='px-3 py-1 text-sm font-medium rounded-lg {$active_class}'>{$i}</a>";
        }
        
        if ($total_pages > $end) {
            $output .= '<span class="px-3 py-1 text-sm text-gray-500">...</span>';
            $last_link = $base_url . '?page=' . $total_pages . (!empty($q) ? '&q=' . urlencode($q) : '');
            $output .= "<a href='{$last_link}' class='px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100'>{$total_pages}</a>";
        }

        // Next button
        $next_disabled = ($current_page >= $total_pages) ? 'opacity-50 cursor-not-allowed' : '';
        $next_page = min($total_pages, $current_page + 1);
        $next_link = $base_url . '?page=' . $next_page . (!empty($q) ? '&q=' . urlencode($q) : '');
        $output .= "<a href='{$next_link}' class='px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 {$next_disabled}'>Next</a>";

        $output .= '</nav>';
        return $output;
    }
}