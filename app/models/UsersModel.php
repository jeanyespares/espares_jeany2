<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {

    // FIX: Must be 'protected' to comply with the parent Model class.
    protected $table = 'students'; 

    // --- Utility Methods for Counting ---

    /**
     * Counts all students based on search query.
     * @param string $q Search query.
     * @return int
     */
    private function count_students($q = '')
    {
        $this->db->table($this->table);
        if (!empty($q)) {
            $this->db->group_start()
                    ->or_like('fname', $q)
                    ->or_like('lname', $q)
                    ->or_like('email', $q)
                    ->group_end();
        }
        // FIX: Use num_rows() instead of get_num_rows()
        return $this->db->num_rows(); 
    }

    public function count_all_users()
    {
        $this->db->table($this->table);
        // FIX: Use num_rows() instead of get_num_rows()
        return $this->db->num_rows(); 
    }

    // --- CRUD and Login/Register Methods ---

    public function register_user($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->table($this->table)
                        ->insert($data)
                        ->exec();
    }

    public function get_user_by_username($username)
    {
        return $this->db->table($this->table)
                        ->where('username', $username)
                        ->get();
    }

    public function get_student_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    public function add_student($data)
    {
        $data['role'] = 'student'; 
        if (!isset($data['username'])) $data['username'] = strtolower($data['fname'] . rand(10, 99));
        if (!isset($data['password'])) $data['password'] = password_hash('default123', PASSWORD_DEFAULT); 

        return $this->db->table($this->table)
                        ->insert($data)
                        ->exec();
    }

    public function update_student($id, $data)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data)
                        ->exec();
    }

    public function delete_student($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->delete()
                        ->exec();
    }

    // --- Pagination Method with Fixes ---

    /**
     * Retrieves all students with search and MANUAL pagination support.
     * @param string $q Search query.
     * @param int $records_per_page
     * @param int $page Current page number.
     * @return array Contains 'records' and 'pagination_html'.
     */
    public function get_all_students($q = '', $records_per_page = 5, $page = 1)
    {
        try {
            // 1. Count Total Records (with search filter)
            $total_records = $this->count_students($q);
            $total_pages = ceil($total_records / $records_per_page);
            $offset = ($page - 1) * $records_per_page;
            
            // 2. Build the query for the current page records
            $this->db->table($this->table);
            
            if (!empty($q)) {
                $this->db->group_start()
                        ->or_like('fname', $q)
                        ->or_like('lname', $q)
                        ->or_like('email', $q)
                        ->group_end();
            }

            // Manual LIMIT and OFFSET
            $this->db->order_by('id', 'DESC');
            $this->db->limit($records_per_page, $offset);
            $records = $this->db->get_all();
            
            // 3. Generate Pagination HTML manually
            $pagination_html = '';
            if ($total_pages > 1) {
                // Determine base URL (including search query 'q')
                $base_url = site_url('users/index') . '?';
                if (!empty($q)) {
                    $base_url .= 'q=' . urlencode($q) . '&';
                }

                $pagination_html .= '<div class="flex space-x-2">';
                
                // Previous page link
                if ($page > 1) {
                    $pagination_html .= '<a class="hp-page" href="' . $base_url . 'page=' . ($page - 1) . '">Previous</a>';
                }

                // Page numbers
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        $pagination_html .= '<span class="hp-current">' . $i . '</span>';
                    } else {
                        $pagination_html .= '<a class="hp-page" href="' . $base_url . 'page=' . $i . '">' . $i . '</a>';
                    }
                }

                // Next page link
                if ($page < $total_pages) {
                    $pagination_html .= '<a class="hp-page" href="' . $base_url . 'page=' . ($page + 1) . '">Next</a>';
                }
                $pagination_html .= '</div>';
            }
            
            // 4. Return the result
            return [
                'records' => $records ?: [],
                'pagination' => $pagination_html
            ];

        } catch (Exception $e) {
             log_message('error', 'Database Error in get_all_students: ' . $e->getMessage());
             return [
                'records' => [],
                'pagination' => ''
            ];
        }
    }
}