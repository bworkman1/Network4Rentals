<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->_init();

	}
	
	private function _init() {
        $title = ucwords(str_replace('-', ' ', end($this->uri->segment_array())));
        $this->output->set_title($title);  // SETS TITLE OF THE PAGE
		$this->output->set_meta('description', 'Affliates login page'); //SETS META DESCRIPTION
		$this->output->set_meta('keywords', 'Affliates, login, page'); //SETS META KEYWORDS1
		
		$this->output->set_template('affiliates/basic');


		if($this->session->userdata('user_online')) {
			redirect('affiliates/dashboard');
			exit;
		}
	}

	public function index()
	{
		
		$this->load->library('oauth2');
		$provider = 'Google';
		$provider = $this->oauth2->provider($provider, array(
			'id' => '474343487201-t6pj4fbl9qjlppdihknqmev930svr3bt.apps.googleusercontent.com',
			'secret' => 'GsswwfmHXijLGbRRcYUTZrbw',
		));

		if ( ! $this->input->get('code')) {
            // By sending no options it'll come back here
            //$url = $provider->authorize();
            //redirect($url);
            //exit;
		} else {
			try {
				// Have a go at creating an access token from the code
				$token = $provider->access($_GET['code']);

				// Use this object to try and get some user details (username, full name, etc)
				$user = $provider->get_user_info($token);

				// Here you should use this information to A) look for a user B) help a new user sign up with existing data.
				// If you store it all in a cookie and redirect to a registration page this is crazy-simple.

			}

			catch (OAuth2_Exception $e) {
				show_error('That didnt work: '.$e);
			}
		}


		$this->load->view('affiliates/non-user/login');
	}

	public function submit()
	{
		$this->form_validation->set_rules('username', 'Username/Email', 'trim|required|min_length[5]|max_length[50]|xss_clean|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[15]|xss_clean');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
			redirect('affiliates/login');
			exit;
		} else {
            extract($_POST);
            $data = array(
				'email' => $username,
				'password' => $password,
			);

            $this->load->model('modules/Auth_signin');
            if($this->Auth_signin->validateUser($data)) { //Returns True or False
                //USER LOGGED IN
				redirect('affiliates/dashboard');
				exit;
			} else {
				//INVALID USER DETAILS
				$this->session->set_flashdata('error', 'Invalid user name or password, try again');
				redirect('affiliates/login');
				exit;
			}
		}
	}



}