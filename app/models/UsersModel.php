<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {

    private $table = 'students';

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

    public function get_all_students($q = '', $records_per_page = 5, $page = 1)
    {
        try {
            $this->db->table($this->table);

            if (!empty($q)) {
                $this->db->group_start()
                        ->or_like('fname', $q)
                        ->or_like('lname', $q)
                        ->or_like('email', $q)
                        ->group_end();
            }

            $this->db->order_by('id', 'DESC');
            
            $pagination = $this->db->paginate($records_per_page, $page);
            $records = $this->db->get_all();
            
            return [
                'records' => $records ?: [],
                'pagination' => $pagination ?: ''
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
