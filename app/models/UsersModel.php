<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {

    // ⭐️ FIX: Changed 'private' to 'protected' to comply with parent Model class's access level.
    protected $table = 'students'; 

    /**
     * Counts all users in the table (used to block registration after first admin).
     * @return int
     */
    public function count_all_users()
    {
        return $this->db->table($this->table)->get_num_rows();
    }

    /**
     * Registers the first user (Admin).
     * @param array $data User details including unhashed password.
     * @return bool
     */
    public function register_user($data)
    {
        // Hash the password before saving
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $this->db->table($this->table)
                        ->insert($data)
                        ->exec();
    }

    /**
     * Retrieves user by username for login.
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
     * Retrieves a single student by ID.
     * @param int $id
     * @return array|null
     */
    public function get_student_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    /**
     * Adds a new student record.
     * @param array $data Student details.
     * @return bool
     */
    public function add_student($data)
    {
        // Ensure that role is set to 'student' for added students
        $data['role'] = 'student'; 
        // Ensure username and password columns are handled if required by the table structure
        if (!isset($data['username'])) $data['username'] = strtolower($data['fname'] . rand(10, 99));
        if (!isset($data['password'])) $data['password'] = password_hash('default123', PASSWORD_DEFAULT); 

        return $this->db->table($this->table)
                        ->insert($data)
                        ->exec();
    }

    /**
     * Updates an existing student record.
     * @param int $id
     * @param array $data Student details.
     * @return bool
     */
    public function update_student($id, $data)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data)
                        ->exec();
    }

    /**
     * Handles deletion of a student record.
     * @param int $id
     * @return bool
     */
    public function delete_student($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->delete()
                        ->exec();
    }

    /**
     * Retrieves all students with search and pagination support.
     * @param string $q Search query.
     * @param int $records_per_page
     * @param int $page Current page number.
     * @return array Contains 'records' and 'pagination'.
     */
    public function get_all_students($q = '', $records_per_page = 5, $page = 1)
    {
        try {
            $this->db->table($this->table);

            // Apply search filter
            if (!empty($q)) {
                $this->db->group_start()
                        ->or_like('fname', $q)
                        ->or_like('lname', $q)
                        ->or_like('email', $q)
                        ->group_end();
            }

            // Apply ordering (Order by ID descending for latest records first)
            $this->db->order_by('id', 'DESC');
            
            // Paginate results
            $pagination = $this->db->paginate($records_per_page, $page);
            $records = $this->db->get_all();
            
            // Laging magbabalik ng kumpletong array structure
            return [
                'records' => $records ?: [],
                'pagination' => $pagination ?: ''
            ];
        } catch (Exception $e) {
             // Kung may database error, magbabalik pa rin ng safe structure
             log_message('error', 'Database Error in get_all_students: ' . $e->getMessage());
             return [
                'records' => [],
                'pagination' => ''
            ];
        }
    }
}