<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
    {
        parent::__construct();
		$this->_int();
        $this->output->set_template('advertisers/non-user');
    }
	
	public function _int() 
	{
		if($this->session->userdata('user_id')>0) {
			redirect('local-partner/home');
			exit;
		}
	}
	
    public function index()   //changed
    {
        $this->output->set_title('Local Partner Login');  // SETS TITLE OF THE PAGE
        $this->output->set_meta('description', 'Local Partner login page'); //SETS META DESCRIPTION
        $this->output->set_meta('keywords', 'Local Partner Login'); //SETS META KEYWORDS1

        $this->output->set_template('advertisers/non-user');

        $this->load->view('advertisers/non-user/login');
    }

    public function user() //changed
    {
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[6]|max_length[60]|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]|xss_clean');
		
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            extract($_POST);
			if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
				$data = array('email'=>$username, 'password'=>md5($password), 'active'=>'y');
			} else {
				$data = array('user'=>$username, 'password'=>md5($password), 'active'=>'y');
			}
            $this->load->model('advertisers/login_handler');
            $results = $this->login_handler->login($data);
            if($results) {
                redirect('local-partner/home');
                exit;
            } else {
                $this->session->set_flashdata('error', 'Incorrect Username Or Password, Try Again');
            }
        }
        redirect('local-partner/login');
        exit;
    }

}