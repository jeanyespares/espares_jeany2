<?php
class AuthController extends Controller
{
    public function register()
    {
        $this->call->library('auth');

        if ($this->io->method() == 'post') {
            $username = $this->io->post('username');
            $password = $this->io->post('password');
            $role = $this->io->post('role') ?? 'user';

            if ($this->auth->register($username, $password, $role)) {
                redirect('auth/login');
            } else {
                echo 'Registration failed!';
            }
        }

        $this->call->view('auth/register');
    }

    public function login()
    {
        $this->call->library('auth');

        if ($this->io->method() == 'post') {
            $username = $this->io->post('username');
            $password = $this->io->post('password');

            if ($this->auth->login($username, $password)) {
                // redirect based on role
                $role = $_SESSION['role'] ?? 'user';
                if ($role === 'admin') {
                    redirect('/users'); // admin page
                } else {
                    redirect('auth/dashboard'); // user page
                }
            } else {
                echo 'Login failed!';
            }
        }

        $this->call->view('auth/login');
    }

    public function dashboard()
    {
        $this->call->library('auth');

        if (!$this->auth->is_logged_in()) {
            redirect('auth/login');
        }

        $role = $_SESSION['role'] ?? 'user';

        // Redirect admin to full access page
        if ($role === 'admin') {
            redirect('/users');
        }

        // --- USER VIEW ONLY ---
        $this->call->model('UsersModel');

        $page = isset($_GET['page']) ? (int) $this->io->get('page') : 1;
        $q = isset($_GET['q']) ? trim($this->io->get('q')) : '';
        $records_per_page = 5;

        $all = $this->UsersModel->page($q, $records_per_page, $page);
        $data['users'] = $all['records'];
        $total_rows = $all['total_rows'];

        // Pagination
        $this->call->library('pagination');
        $this->pagination->set_options([
            'first_link'     => '⏮ First',
            'last_link'      => 'Last ⏭',
            'next_link'      => 'Next →',
            'prev_link'      => '← Prev',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('default');
        $this->pagination->initialize(
            $total_rows,
            $records_per_page,
            $page,
            site_url('auth/dashboard') . '?q=' . urlencode($q)
        );

        $data['page'] = $this->pagination->paginate();

        $this->call->view('auth/dashboard', $data);
    }

    public function logout()
    {
        $this->call->library('auth');
        $this->auth->logout(); // this should clear session completely
        session_destroy(); // ensure all session data is cleared
        redirect('auth/login');
    }
}
?>
