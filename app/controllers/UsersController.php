<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// Fix for is_post_request() error in some environments
if (!function_exists('is_post_request')) {
    /**
     * Checks if the current request method is POST.
     * @return bool
     */
    function is_post_request() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     * Loads necessary models and helpers.
     */
    public function __construct()
    {
        // ⭐ FIX: Call parent constructor first to initialize $this->call, etc.
        parent::__construct(); 

        // Load the correct model name (UsersModel, not Users_model)
        $this->call->model('UsersModel');

        // Load necessary helpers 
        $this->call->helper('url');
        $this->call->helper('session');
        $this->call->helper('input');
        $this->call->helper('form'); 

        // Prevent session_start() warning
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===========================
        🔐 AUTHENTICATION SECTION
    =========================== */

    /**
     * Handles user registration. Restricted after the first user (Admin) registers.
     */
    public function register()
    {
        // Block registration if there is already at least one user (the Admin)
        if ($this->UsersModel->count_all_users() > 0) {
            echo "<h1 style='text-align:center; color: #d946ef; margin-top: 50px;'>Registration is currently closed. Only one administrative account is allowed.</h1>";
            echo "<p style='text-align:center;'><a href='" . site_url('users/login') . "'>Login here</a> or <a href='" . site_url('/') . "'>Go to Directory</a></p>";
            return;
        }

        $data = [];
        $data['error'] = '';

        if (is_post_request()) {
            $username = trim(post('username'));
            $password = post('password');
            $fname = trim(post('fname'));
            $lname = trim(post('lname'));
            $email = trim(post('email'));
            
            // First user is automatically set as 'admin'
            $role = 'admin'; 

            if (empty($username) || empty($password) || empty($fname) || empty($lname) || empty($email)) {
                $data['error'] = "All fields are required.";
                // Fall-through to display the form with error
            } else {
                $user_data = [
                    'fname' => $fname,
                    'lname' => $lname,
                    'email' => $email,
                    'username' => $username,
                    'password' => $password, // Password will be hashed in the Model
                    'role' => $role
                ];

                if ($this->UsersModel->register_user($user_data)) {
                    // Success: Redirect to the main table after admin registration
                    redirect(site_url('/'));
                } else {
                    $data['error'] = "Error in registration. Username or email might already be taken.";
                }
            }
        } 
        
        $this->call->view('users/register', $data);
    }

    /**
     * Handles user login.
     */
    public function login()
    {
        // Redirect to main table if user is already logged in
        if (isset($_SESSION['user'])) {
            redirect(site_url('/')); 
        }

        $data = [];
        $data['error'] = '';

        if (is_post_request()) {
            $username = trim(post('username'));
            $password = post('password');

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
                // Fall-through to display the form with error
            } else {
                $user = $this->UsersModel->get_user_by_username($username);

                if ($user && password_verify($password, $user['password'])) {
                    // Successful Login
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role']
                    ];

                    // Redirect to the main student table after login
                    redirect(site_url('/')); 
                } else {
                    $data['error'] = "Invalid username or password.";
                }
            }
        }
        
        $this->call->view('users/login', $data);
    }

    /**
     * Logs out the user and redirects to the main table.
     */
    public function logout()
    {
        session_destroy();
        // Redirect to the main student table after logout
        redirect(site_url('/')); 
    }

    /* ===========================
        📋 CRUD SECTION
    =========================== */

    /**
     * Displays the student directory table with search and pagination.
     */
    public function index()
    {
        // Auth check for View visibility
        $is_admin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
        $is_logged_in = isset($_SESSION['user']);
        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;

        $page = isset($_GET['page']) ? $this->io->get('page') : 1;
        $q = isset($_GET['q']) ? trim($this->io->get('q')) : '';

        $records_per_page = 5;
        $all = $this->UsersModel->get_all_students($q, $records_per_page, $page);
        $data['users'] = $all['records'];
        $total_rows = $all['total_rows'];

        // Pagination setup
        $this->pagination->set_options([
            'first_link' => '⏮ First',
            'last_link' => 'Last ⏭',
            'next_link' => 'Next →',
            'prev_link' => '← Prev',
            'page_delimiter' => '&page='
        ]);

        $this->pagination->set_theme('default');
        // Note: site_url() alone leads to index.php
        $this->pagination->initialize($total_rows, $records_per_page, $page, site_url() . '?q=' . urlencode($q));
        $data['page'] = $this->pagination->paginate();

        $this->call->view('users/index', $data);
    }

    /**
     * Displays the create form or handles the creation of a new student (Admin Only).
     */
    function create()
    {
        // Admin Check: Only Admin can access
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            redirect(site_url('users/login'));
            return;
        }

        if ($this->io->method() == 'post') {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email'),
                // Set default/dummy username/password for students
                'username' => uniqid('std_'),
                'password' => password_hash(uniqid(), PASSWORD_DEFAULT),
                'role' => 'student'
            ];

            if ($this->UsersModel->add_student($data)) {
                redirect(site_url());
            } else {
                echo "Error in creating user.";
            }
        } else {
            $this->call->view('users/create');
        }
    }

    /**
     * Displays the update form or handles the update of an existing student (Admin Only).
     */
    function update($id)
    {
        // Admin Check: Only Admin can access
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            redirect(site_url('users/login'));
            return;
        }

        $user = $this->UsersModel->get_student_by_id($id);
        if (!$user) {
            echo "Student not found.";
            return;
        }

        if ($this->io->method() == 'post') {
            $data = [
                'fname' => $this->io->post('fname'),
                'lname' => $this->io->post('lname'),
                'email' => $this->io->post('email')
                // Note: Not updating username/password/role here for security/simplicity
            ];

            if ($this->UsersModel->update_student($id, $data)) {
                redirect(site_url());
            } else {
                echo "Error in updating information.";
            }
        } else {
            $data['user'] = $user;
            $this->call->view('users/update', $data);
        }
    }

    /**
     * Deletes a student record (Admin Only).
     */
    function delete($id)
    {
        // Admin Check: Only Admin can access
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            redirect(site_url('users/login'));
            return;
        }

        if ($this->UsersModel->delete_student($id)) {
            redirect(site_url());
        } else {
            echo "Error in deleting user.";
        }
    }
}
