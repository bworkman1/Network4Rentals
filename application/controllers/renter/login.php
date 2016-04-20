<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->_init();
	}
	
	private function _init() {
		$this->load->section('alerts', 'common/user-alerts');
		
        $title = ucwords(str_replace('-', ' ', end($this->uri->segment_array())));
        $this->output->set_title('Renters | '.$title);  // SETS TITLE OF THE PAGE
		$this->output->set_meta('description', 'Renters login page'); //SETS META DESCRIPTION
		$this->output->set_meta('keywords', 'Renters, login, page'); //SETS META KEYWORDS1
		
		$this->output->set_template('renters/basic');
		
		$this->load->css('assets/themes/blue-moon/css/renter.css');
		
		if($this->session->userdata('user_online')) {
			redirect('renter/dashboard');
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


		$this->load->view('renters/non-user/login');
	}

	public function submit()
	{
		$this->form_validation->set_rules('username', 'Username/Email', 'trim|required|min_length[5]|max_length[60]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('remember', 'Remember Me', 'trim|min_length[1]|max_length[1]|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
			redirect('renter/login');
			exit;
		} else {
            extract($_POST); 
            $this->load->model('modules/user_security');
			$loggedIn = $this->user_security->runLogin('renters', $username, $password, $remember);
			if($loggedIn) {
				redirect('renter/dashboard');
				exit;
			} else {
				$this->session->set_flashdata('error', 'Username and/or password are incorrect');
				redirect('renter/login');
			}
		}
	}



}