<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentsController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->call->model('StudentsModel'); // Load StudentsModel instead of UsersModel
        // load auth library
        $this->call->library('Auth');
    }

    public function index() {
        // Require authenticated admin users
        $this->Auth->require_login();
        $this->Auth->require_role('admin');

        $q = $this->io->get('q', '');
        $page = $this->io->get('page') ?? 1;
        $records_per_page = 5;

        // Call StudentsModel::page()
        $all = $this->StudentsModel->page($q, $records_per_page, $page);

        $data['students'] = $all['records'];
        $total_rows = $all['total_rows'];

        // Simple pagination
        $data['pagination'] = ceil($total_rows / $records_per_page);

        $this->call->view('students/index', $data);
    }
}
