<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {

    protected $table = 'students'; 

    // --- Utility Methods ---

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
        return $this->db->get_num_rows();
    }

    // --- CRUD Methods ---

    public function count_all_users()
    {
        return $this->db->table($this->table)->get_num_rows();
    }

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

    // --- Pagination Fix Method ---

    public function get_all_students($q = '', $records_per_page = 5, $page = 1)
    {
        try {
            $total_records = $this->count_students($q);
            $total_pages = ceil($total_records / $records_per_page);
            $offset = ($page - 1) * $records_per_page;
            
            $this->db->table($this->table);
            
            if (!empty($q)) {
                $this->db->group_start()
                        ->or_like('fname', $q)
                        ->or_like('lname', $q)
                        ->or_like('email', $q)
                        ->group_end();
            }

            $this->db->order_by('id', 'DESC');
            $this->db->limit($records_per_page, $offset);
            $records = $this->db->get_all();
            
            $pagination_html = '';
            if ($total_pages > 1) {
                $base_url = site_url('users/index') . '?';
                if (!empty($q)) {
                    $base_url .= 'q=' . urlencode($q) . '&';
                }

                if ($page > 1) {
                    $pagination_html .= '<a href="' . $base_url . 'page=' . ($page - 1) . '">Previous</a>';
                } else {
                    $pagination_html .= '<span>Previous</span>';
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        $pagination_html .= '<strong>' . $i . '</strong>';
                    } else {
                        $pagination_html .= '<a href="' . $base_url . 'page=' . $i . '">' . $i . '</a>';
                    }
                }

                if ($page < $total_pages) {
                    $pagination_html .= '<a href="' . $base_url . 'page=' . ($page + 1) . '">Next</a>';
                } else {
                    $pagination_html .= '<span>Next</span>';
                }
            }
            
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
