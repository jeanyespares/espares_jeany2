
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');
        // load auth library to check permissions
        $this->call->library('Auth');
    }

    public function index()
    {
        // make current user available to views
        $data['current_user'] = $this->Auth->current_user();

        // Current page
        $page = 1;
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $page = $this->io->get('page');
        }

    // Search query
    $q = '';
    if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = trim($this->io->get('q'));
    }

    $records_per_page = 5;

    // Fetch paginated results
    $all = $this->UsersModel->page($q, $records_per_page, $page);
    $data['users'] = $all['records'];
    $total_rows = $all['total_rows'];


        // Pagination 
        
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
            site_url() . '?q=' . urlencode($q)
        );
        $data['page'] = $this->pagination->paginate();

        $this->call->view('users/index', $data);
    }

    function create(){
        // only admin can create
        $this->Auth->require_login();
        $this->Auth->require_role('admin');

        if($this->io->method() == 'post'){
            $data = [
                'fname' => $this->io->post('fname'),
                'lname'  => $this->io->post('lname'),
                'email'      => $this->io->post('email')
            ];

            if($this->UsersModel->insert($data)){
                redirect(site_url());
            }else{
                echo "Error in creating user.";
            }

        }else{
            $this->call->view('users/create');
        }
    }

    function update($id){
        // only admin can update
        $this->Auth->require_login();
        $this->Auth->require_role('admin');
        $user = $this->UsersModel->find($id);
        if(!$user){
            echo "User not found.";
            return;
        }

        if($this->io->method() == 'post'){
            $data = [
                'fname' => $this->io->post('fname'),
                'lname'  => $this->io->post('lname'),
                'email'      => $this->io->post('email')
            ];

            if($this->UsersModel->update($id, $data)){
                redirect(site_url());
            }else{
                echo "Error in updating information.";
            }
        }else{
            $data['user'] = $user;
            $this->call->view('users/update', $data);
        }
    }
    
    function delete($id){
        // only admin can delete
        $this->Auth->require_login();
        $this->Auth->require_role('admin');
        if($this->UsersModel->delete($id)){
            redirect(site_url());
        }else{
            echo "Error in deleting user.";
        }
    }
}
