<?php
// Note: This controller assumes that the 'Controller' base class,
// the necessary Model ('UsersModel'), and helper functions (like redirect, post, is_post_request, etc.) are available.

class UsersController extends Controller
{
    public function __construct()
    {
        // ⭐ THE FIX: Call the parent constructor first to initialize framework dependencies like $this->call
        parent::__construct(); 

        // Load the UsersModel to interact with the database. 
        $this->call->model('UsersModel');

        // Load necessary helpers 
        $this->call->helper('url');
        $this->call->helper('session');
        $this->call->helper('input');
        // ⭐ THE NEW FIX: Loading the 'form' helper, where is_post_request() is commonly defined.
        $this->call->helper('form');
    }

    /**
     * Helper function to check if the current user is logged in as an administrator.
     * @return bool
     */
    private function is_admin()
    {
        // Checks if the user session exists AND if the user role is set to 'admin'
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Displays the student directory list (Index page).
     * Passes role information to the view for conditional element display (hiding/showing Action column/Add button).
     */
    public function index()
    {
        $data = [];
        
        // Pass login status and role to the view (users/index)
        $data['is_admin'] = $this->is_admin();
        $data['is_logged_in'] = isset($_SESSION['user']);

        // --- Pagination and Search Logic ---
        $page = (int)($_GET['page'] ?? 1);
        $per_page = 10;
        $search_query = html_escape($_GET['q'] ?? '');

        // Fetch students and pagination links (Using $this->UsersModel now)
        $result = $this->UsersModel->get_all_users($page, $per_page, $search_query);

        $data['users'] = $result['users'];
        $data['page'] = $result['pagination'];

        // Render the view
        $this->call->view('users/index', $data);
    }

    /**
     * Handles user login authentication.
     */
    public function login()
    {
        if (isset($_SESSION['user'])) {
            // Already logged in, redirect to the main list
            redirect('users/index');
        }

        $data = [];
        $data['error'] = '';

        if (is_post_request()) {
            $username = post('username');
            $password = post('password');

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
            } else {
                // Attempt login via model (Using $this->UsersModel now)
                $user = $this->UsersModel->get_user_by_username($username);

                if ($user && password_verify($password, $user['password'])) {
                    // Successful login: Set session data
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role'] 
                    ];
                    redirect('users/index');
                } else {
                    $data['error'] = 'Invalid username or password.';
                }
            }
        }

        // Render the login form
        $this->call->view('users/login', $data);
    }

    /**
     * Handles user registration.
     * IMPORTANT: Only the first user registered is allowed (and is automatically set as 'admin').
     * All subsequent attempts are redirected to the login page.
     */
    public function register()
    {
        // ⭐ BLOCK REGISTRATION: Check if any user already exists. (Using $this->UsersModel now)
        if ($this->UsersModel->count_all_users() > 0) {
            // If the admin user exists, immediately redirect to login.
            redirect('users/login');
        }

        $data = [];
        $data['error'] = '';

        if (is_post_request()) {
            $username = post('username');
            $password = post('password');
            $role = 'admin'; // Automatically assign 'admin' role to the first user

            // Note: In a real app, you would add more validation here

            // Register the user (Using $this->UsersModel now)
            if ($this->UsersModel->register_user($username, $password, $role)) {
                // Registration successful, manually log them in
                $user = $this->UsersModel->get_user_by_username($username);
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
                redirect('users/index');
            } else {
                $data['error'] = 'Registration failed. Username may be taken.';
            }
        }

        // Render the registration form (only if no users exist yet)
        $this->call->view('users/register', $data);
    }

    // --- Student Management Functions (Admin Only) ---

    /**
     * Displays the form to add a new student. (Admin Only)
     */
    public function create()
    {
        // ⭐ SECURITY CHECK: Only allow admin to access
        if (!$this->is_admin()) {
            redirect('users/index');
        }

        $data = [];
        $data['error'] = '';

        if (is_post_request()) {
            $fname = post('fname');
            $lname = post('lname');
            $email = post('email');

            // Add student using the model (Using $this->UsersModel now)
            if ($this->UsersModel->add_student(['fname' => $fname, 'lname' => $lname, 'email' => $email])) {
                // Success
                redirect('users/index');
            } else {
                $data['error'] = 'Failed to add student. Please check input.';
            }
        }

        $this->call->view('users/create', $data);
    }

    /**
     * Displays the form to update an existing student. (Admin Only)
     */
    public function update($id)
    {
        // ⭐ SECURITY CHECK: Only allow admin to access
        if (!$this->is_admin()) {
            redirect('users/index');
        }

        $id = (int)$id;
        // Get the specific student data (Using $this->UsersModel now)
        $data['user'] = $this->UsersModel->get_student_by_id($id);

        if (!$data['user']) {
            redirect('users/index');
        }

        $data['error'] = '';

        if (is_post_request()) {
            $fname = post('fname');
            $lname = post('lname');
            $email = post('email');

            // Update student using the model (Using $this->UsersModel now)
            if ($this->UsersModel->update_student($id, ['fname' => $fname, 'lname' => $lname, 'email' => $email])) {
                // Success
                redirect('users/index');
            } else {
                $data['error'] = 'Failed to update student.';
            }
        }

        $this->call->view('users/update', $data);
    }

    /**
     * Deletes a student record. (Admin Only)
     */
    public function delete($id)
    {
        // ⭐ SECURITY CHECK: Only allow admin to access
        if (!$this->is_admin()) {
            redirect('users/index');
        }

        $id = (int)$id;

        // Delete student using the model (Using $this->UsersModel now)
        $this->UsersModel->delete_student($id);

        redirect('users/index');
    }

    /**
     * Logs out the user.
     */
    public function logout()
    {
        session_destroy(); // Destroy the entire session
        redirect('users/index');
    }
}
