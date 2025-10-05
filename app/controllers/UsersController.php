<?php
// Note: This controller assumes that the 'Controller' base class,
// the necessary Model ('Users_model'), and helper functions (like redirect, post, is_post_request, etc.) are available.

class UsersController extends Controller
{
    public function __construct()
    {
        // ⭐ THE FIX: Call the parent constructor first to initialize framework dependencies like $this->call
        parent::__construct(); 

        // Load the Users_model to interact with the database
        $this->call->model('Users_model');

        // Load necessary helpers 
        $this->call->helper('url');
        $this->call->helper('session');
        $this->call->helper('input');
    }

    /**
     * Helper function to check if the current user is logged in as an administrator.
     * @return bool
     */
    private function is_admin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Displays the student directory list (Index page).
     */
    public function index()
    {
        $data = [];
        $data['is_admin'] = $this->is_admin();
        $data['is_logged_in'] = isset($_SESSION['user']);

        $page = (int)($_GET['page'] ?? 1);
        $per_page = 10;
        $search_query = html_escape($_GET['q'] ?? '');

        $result = $this->Users_model->get_all_users($page, $per_page, $search_query);

        $data['users'] = $result['users'];
        $data['page'] = $result['pagination'];

        $this->call->view('users/index', $data);
    }

    /**
     * Handles user login authentication.
     */
    public function login()
    {
        if (isset($_SESSION['user'])) {
            redirect('users/index');
        }

        $data = ['error' => ''];

        if (is_post_request()) {
            $username = post('username');
            $password = post('password');

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
            } else {
                $user = $this->Users_model->get_user_by_username($username);

                if ($user && password_verify($password, $user['password'])) {
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

        $this->call->view('users/login', $data);
    }

    /**
     * Handles user registration.
     */
    public function register()
    {
        if ($this->Users_model->count_all_users() > 0) {
            redirect('users/login');
        }

        $data = ['error' => ''];

        if (is_post_request()) {
            $username = post('username');
            $password = post('password');
            $role = 'admin';

            if ($this->Users_model->register_user($username, $password, $role)) {
                $user = $this->Users_model->get_user_by_username($username);
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

        $this->call->view('users/register', $data);
    }

    /**
     * Displays the form to add a new student. (Admin Only)
     */
    public function create()
    {
        if (!$this->is_admin()) {
            redirect('users/index');
        }

        $data = ['error' => ''];

        if (is_post_request()) {
            $fname = post('fname');
            $lname = post('lname');
            $email = post('email');

            if ($this->Users_model->add_student(['fname' => $fname, 'lname' => $lname, 'email' => $email])) {
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
        if (!$this->is_admin()) {
            redirect('users/index');
        }

        $id = (int)$id;
        $data['user'] = $this->Users_model->get_student_by_id($id);

        if (!$data['user']) {
            redirect('users/index');
        }

        $data['error'] = '';

        if (is_post_request()) {
            $fname = post('fname');
            $lname = post('lname');
            $email = post('email');

            if ($this->Users_model->update_student($id, ['fname' => $fname, 'lname' => $lname, 'email' => $email])) {
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
        if (!$this->is_admin()) {
            redirect('users/index');
        }

        $id = (int)$id;
        $this->Users_model->delete_student($id);
        redirect('users/index');
    }

    /**
     * Logs out the user.
     */
    public function logout()
    {
        session_destroy();
        redirect('users/index');
    }
}