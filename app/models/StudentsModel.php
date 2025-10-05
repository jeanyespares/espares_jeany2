<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentsModel extends Model {

    public function page($q = '', $limit = 5, $page = 1) {
        $offset = ($page - 1) * $limit;

        $builder = $this->db->table('students');

        if (!empty($q)) {
            $builder->like('lname', $q)
                    ->or_like('fname', $q)
                    ->or_like('email', $q);
        }

        $total_rows = $builder->count_all_results(false);

        $records = $builder->limit($limit, $offset)
                           ->get()
                           ->getResultArray();

        return [
            'records'    => $records,
            'total_rows' => $total_rows
        ];
    }
}
