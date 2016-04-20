<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Renters extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->section('sidebar', 'advertiser-sidebar');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->_init();
	}

	public function testingCookie()
	{
		$this->output->enable_profiler(TRUE);
	}
	
	function check_if_loggedin() 
	{	
		// THIS WAS TO IMPLEMENT INTERCOM IO AND CAN BE DELETED
		$userEmailSet = $this->session->userdata('user_email');
		if(empty($userEmailSet)) {
			$this->session->set_flashdata('errror', 'You have been logged out for updates. Please log back in to access your account');
		}
		
		
		$userId = $this->session->userdata('user_id');
		if(empty($userId)) {
			redirect('renters/logout');
			exit;
		}
		
		$cookie = $this->input->cookie('rental');
		$side = $this->session->userdata('side_logged_in');
		if(!empty($cookie) && $side != '898465406540564') {
			$this->session->set_flashdata('createAccount', 'You must create an account or login to link to a landlord. Once you create your account you will be redirected to link to your landlord');
			$this->session->sess_destroy();
			redirect('renters/login');
			exit;
		}
		
		if($side != '898465406540564') {
			$cookie = array(
				'name'   => 'logged_in',
				'domain' => '.network4rentals.com',
				'path'   => '/',
			);
			delete_cookie($cookie);
			$this->session->sess_destroy();
			redirect('renters/login');
			exit;
		}
	}
	
	public function test_email()
	{
		$this->check_if_loggedin();
		$this->load->model('renters/user_model');
		$info = $this->user_model->get_user_emails();

		if(!empty($info['email'])) {
			$name = explode(' ', $info['name']);
			$message = '<h3>Hello '.$name[0].'</h3>';
			$message .= '<p>It looks like your email is working fine and you should be all set to start using the system.</p>';
			$subject = 'Test Email From Network4Rentals';
			$this->load->model('special/send_email');
			if($this->send_email->sendEmail($info['email'], $message, $subject)) {
				if(!empty($info['forwarding_email'])) {
					$this->send_email->sendEmail($info['forwarding_email'], $message, $subject);
					$this->session->set_flashdata('feedback_success', 'Your test email has been sent to '.$info['email'].' and '.$info['forwarding_email']);
				} else {
					$this->session->set_flashdata('feedback_success', 'Your test email has been sent to '.$info['email']);
				}
				
			} else {
				$this->session->set_flashdata('feedback_error', 'There was an error sending the email to '.$info['email']);
			}
		} else {
			$this->session->set_flashdata('feedback_error', 'There was an error finding your email address, check your account settings to make sure you have a valid email in the system.');
		}
		redirect('renters/edit-account');
		exit;
	}
	
	private function _init()
	{
		$this->load->model('special/ads_output');
		$data['result'] = $this->ads_output->get_ads_in_location();
		$this->load->vars($data);
		
		$this->output->set_template('default');
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/js/bootstrap.min.js');
		$this->load->js('assets/themes/default/js/custom.js');
		$this->load->js('assets/themes/default/js/fitvids.js');
		$this->load->js('assets/themes/default/js/bootstrap-datepicker.js');
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->css('assets/themes/default/css/datepicker.css');
		$this->load->js('assets/themes/default/js/select2.min.js');
		
		$this->load->model('special/ads_output');
		$ad['result'] = $this->ads_output->get_ads_in_location();
		$this->load->section('sidebar', 'sidebar', $ad);
	}

	function resend_confirm_email() 
	{
		$hash = $this->uri->segment(3);
		// DB Updated with hash now need to email user that hash
		$this->db->select('email, loginHash');
		$query = $this->db->get_where('renters', array('loginHash'=>$hash));
		if ($query->num_rows() > 0) {
			$row = $query->row(); 
		}
	
	
		$message = '<p>Your account has been created, click <a href="'.base_url().'renters/account_verified/'.$row->loginHash.'">here</a> to verify your email address.</p>';
		
		$subject = 'N4R | Confirm Your Account';
		if(!empty($row->email)) {
			$this->load->model('special/send_email');
			$this->send_email->sendEmail($row->email, $message, $subject);
			echo 'Email Sent';
		} else {
			echo 'Email Not Sent';
		}
		
	}
	
	function check_if_mobile($email)
	{
		$carrier_emails = array('@myboostmobile.com', '@messaging.sprintpcs.com', '@cingularme.com', '@vtext.com', '@tmomail.net');
		$email_array = explode('@', $email);
		$is_mobile = false;
		foreach($carrier_emails as $val) {
			if($val == '@'.$email_array[1]) {
				$is_mobile = true;
			}
		}
		return $is_mobile;
	}
	
	public function index()
	{	
		$url = $this->uri->segment("1");
		if($url == "affiliates") {
			redirect('affiliates/login');
			exit;
		}
		$url = $this->uri->segment("1");
		if($url == "local-partner") {
			redirect('local-partner/login');
			exit;
		}
		
		
		$cookie = $this->input->cookie('logged_in');
		if($cookie==1) {
			redirect('renters/activity');
			exit;
		}
		if($this->session->userdata('logged_in'))
		{
			redirect('renters/activity');
			exit;
		}
		
		$this->load->view('renters/home');
	}

	public function login()
	{	
		$cookie = $this->input->cookie('logged_in');
		if($cookie==1) {
			redirect('renters/activity');
			exit;
		}
		if($this->session->userdata('logged_in'))
		{	
			redirect('renters/activity');
		}
		else 
		{
			$this->load->view('renters/login');
		}
	}

	public function user() // Login Handler, Should Have Named It user_login
	{
		$this->form_validation->set_rules('username', 'Username', 'required|trim|max_length[50]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|max_length[200]|xss_clean');
		
		if($this->form_validation->run() == FALSE) {
			$this->load->view('renters/login');
		} else {
			// process user input and login the user
			extract($_POST);
			
			$this->load->model('renters/user_model');
			$userData = $this->user_model->check_login($username, $password);

			if($userData === false) {
				$this->session->set_flashdata('error', 'Username and/or password are incorrect');
				redirect('renters/login');
			} else {
				
				if($userData->confirmed == 'y'){
					//logged in
					$renter = $this->user_model->get_users_details($userData->id);

					$this->session->set_userdata('side_logged_in', '898465406540564');
					$this->session->set_userdata('side', 'Renters');
					$this->session->set_userdata('user_id', $userData->id);
					$this->session->set_userdata('logged_in', TRUE);
					$this->session->set_userdata('username', $username);

					$this->session->set_userdata('ad_zipCode', $renter[1]['rental_zip']);

					$this->session->set_userdata('user_email', $userData->email);
					$this->session->set_userdata('full_name', $userData->name);
					$this->session->set_userdata('user_created', strtotime($userData->sign_up));

					$cookie = array(
						'name'   => 'logged_in',
						'value'  => '1',
						'expire' => '86500',
						'domain' => '.network4rentals.com',
						'path'   => '/',
						'secure' => TRUE
					);
                    $cords = $this->user_model->getCords($renter[1]['rental_zip']);
                    $this->session->set_userdata('lat', $cords['lat']);
                    $this->session->set_userdata('long', $cords['lng']);
					$this->input->set_cookie($cookie);
					
					$linked_rental = $this->input->cookie('rental');
					if(!empty($linked_rental)) {
						redirect('renters/link-landlord-rentals/'.$linked_rental);
						exit;
					}
					redirect('renters/activity');
					exit;
				} else {
					// User needs to confirm their email
					$this->session->set_flashdata('error', 'You need to confirm your account before you can login, we sent a conformation email to the email address associated with this account. Please check your email for instructions on how to confirm your account.');
					redirect('renters/login');
				}
			}
		}
	}
	
	//POSSIBLE REMOVE THIS
	public function create_user_accountsss() 
	{
		if($this->session->userdata('logged_in'))
		{
			redirect('renters/activity');
		}
		$this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[6]|max_length[50]|xss_clean|is_unique[renters.user]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[200]|matches[password1]|xss_clean|md5');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[100]|xss_clean|is_unique[renters.email]');
		$this->form_validation->set_rules('password1', 'Confirm Password', 'required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[10]|max_length[15]|xss_clean');
		$this->form_validation->set_rules('terms', 'Terms Of Service', 'required|trim|min_length[1]|max_length[1]|xss_clean');
		$this->form_validation->set_rules('hear', 'Hear About Us', 'required|trim|min_length[1]|max_length[50]|xss_clean');
		
		$this->form_validation->set_message('is_unique', '%s is already being used, try logging into your account by using the forgot password link.');
		
		if($this->form_validation->run() == FALSE) 
		{
			$this->load->view('renters/create-account');
		}
		else 
		{
			// Form Is Valid
			extract($_POST);
			$this->load->model('renters/create_user_model');
			$phone = preg_replace("/[^0-9]/", '', $phone);
			$user_hash = $this->create_user_model->create_user_account($username, $password, $fullname, $email, $phone, $hear);
			if($user_hash != FALSE) 
			{
				$message = '<h3>'.$fullname.'</h3><p>Your account has been created, click <a href="'.base_url().'renters/account_verified/'.$user_hash.'">here</a> to verify your email address.</p>';
				$subject = "N4R | Account Created";
				
				$this->session->set_userdata('user_email', $email);
				$this->session->set_userdata('message', $message);
				$this->session->set_userdata('subject', $subject);
				$alt_message = '';
				$this->load->model('special/send_email');
				$this->send_email->sendEmail($email, $message, $subject, $alt_message);
				redirect('renters/account-created');
				exit;
			}
			else 
			{
				$this->session->set_flashdata('user_created', 'n');
			}
			$this->load->view('renters/create-account');
			
		}
		
	}
	
	public function account_created() 
	{
		$this->load->js('assets/themes/default/js/notify.min.js');
		$this->form_validation->set_rules('cell_phone', 'Cell Phone', 'trim|min_length[14]|max_length[14]|xss_clean');
		$this->form_validation->set_rules('sms_msgs', 'Text Messages', 'trim|min_length[1]|max_length[1]|xss_clean');
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			$cell_phone = preg_replace("/[^0-9]/", '', $cell_phone);
			$data = array(
				'cell_phone' => $cell_phone,
				'sms_msgs' => $sms_msgs
			);
		
			$this->load->model('renters/create_user_model');
			$results = $this->create_user_model->update_text_message_option($data);
			if($results) {
				$this->session->set_userdata('cell', $cell_phone);
				$this->session->set_userdata('sms', $sms_msgs);
				
				$info = array(
					'phone_to' => $this->session->userdata('cell'),
					'message' => $this->session->userdata('sms_msg'),
					'page' => 'renters/account-created'
				);
				$texted = $this->send_sms($info);
				if($texted) {
					$this->session->set_flashdata('success', 'Text message sent, please enter your verification code into the box');
				} else {
					$this->session->set_flashdata('error', 'Text message failed to send, please contact support');
				}
				
			} else {
				$this->session->set_flashdata('error', 'There was a problem updating your text settings, try again');
			}
			redirect('renters/account-created');
			exit;
		}
		echo validation_errors();
		$this->load->view('renters/account-created');
	}
	
	public function account_verified() 
	{
		$hash = $this->uri->segment(3);

		if(empty($hash)) {
			$this->session->set_flashdata('error', 'No user found');
			redirect('renters/create-account');
		} else {
			$this->load->model('renters/create_user_model');
			$data = $this->create_user_model->verify_account($hash);
			if(!empty($data)) {
				$message = '<h3>'.$data['name'].'</h3>';
				$message .= 'A new tenant has linked to your account on Network 4 Rentals. Once you login go to my tenants and you will see the tenants details.<br><a href="'.base_url().'landlords/login">Login</a>';
				$subject = "New Tenant Linked To You On N4R";
				$this->load->model('special/send_email');
				$this->send_email->sendEmail($data['landlord_email'], $message, $subject, $message);
				
				if(empty($data['group_id'])) {
					$data['group_id'] = NULL;
				}
				
				$d = $this->create_user_model->sms_verification_data();
				$this->load->model('renters/user_model');				
				$data = array('action'=>'New Tenant Linked To You<br><b><small>'.$data['name'].'</small></b>', 'user_id'=>$d['link_id'], 'type'=>'landlords', 'action_id'=>$data['rental_id'], 'group_id'=>$d['group_id']);
				$this->user_model->add_activity($data);
				 
				//Attempt to notify landlord of link by sms
				$this->load->model('renters/sms_handler');
				$link = base_url().'landlords/view-tenant-info/'.$d['rental_id'];
				$msg = 'New tenant linked to you on N4R: '.$link;
				$this->sms_handler->send_sms($d['link_id'], $msg);
				  
				$this->load->view('renters/account-verified');
			} else {
				$this->session->set_flashdata('error', 'Account failed to verify, please contact us.');
				redirect('renters/create-account');
				exit;
			}	
		}	
	}
	
	public function account_verified_no_landlord() 
	{
		$hash = $this->uri->segment(3);

		if(empty($hash)) {
			$this->session->set_flashdata('error', 'No user found');
			redirect('renters/create-account');
		} else {
			$this->load->model('renters/create_user_model');
			$data = $this->create_user_model->verify_account_no_landlord($hash);
			if(!empty($data)) {
				$message = '<h3>'.$data['name'].'</h3>';
				$message .= 'A new tenant has linked to your account on Network 4 Rentals. Once you login go to my tenants and you will see the tenants details.<br><a href="'.base_url().'landlords/login">Login</a>';
				$subject = "New Tenant Linked To You On N4R";
				$this->load->model('special/send_email');
				$this->send_email->sendEmail($data['landlord_email'], $message, $subject, $message);
				
				if(empty($data['group_id'])) {
					$data['group_id'] = NULL;
				}
				
				$d = $this->create_user_model->sms_verification_data();
				
				$this->load->model('renters/user_model');	
				$renterName = $this->user_model->getUsersFullName($this->session->userdata('user_id'));				
				$data = array('action'=>'New Tenant Linked To You<br><b><small>'.$renterName.'</small></b>', 'user_id'=>$d['link_id'], 'type'=>'landlords', 'action_id'=>$data['rental_id'], 'group_id'=>$d['group_id']);
				$this->user_model->add_activity($data);
				 
				//Attempt to notify landlord of link by sms
				$this->load->model('renters/sms_handler');
				$link = base_url().'landlords/view-tenant-info/'.$d['rental_id'];
				$msg = 'New tenant linked to you on N4R: '.$link;
				$this->sms_handler->send_sms($d['link_id'], $msg);
				  
				$this->load->view('renters/account-verified');
			} else {
				$this->session->set_flashdata('error', 'Account failed to verify, please contact us.');
				redirect('renters/create-account');
				exit;
			}	
		}	
	}
	
	public function activity_old_remove() 
	{
		
		$this->check_if_loggedin();
		$this->output->set_template('logged-in');		
			
		$this->form_validation->set_rules('date_to', 'Date To', 'required|trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('date_from', 'Date From', 'required|trim|max_length[15]|xss_clean');
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			$this->session->set_userdata('date_to', $date_to);
			$this->session->set_userdata('date_from', $date_from);
		} 
		
		
		$this->load->model('renters/fetch_activity_model');
		$this->load->library('pagination');
		
		$config['base_url'] = base_url().'renters/activity';
		
		$config['total_rows'] = $this->fetch_activity_model->record_count($this->session->userdata('user_id'),  $this->session->userdata('date_to'), $this->session->userdata('date_from'));
		$config['per_page'] = 20; 
		$config['full_tag_open'] = '<div><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div><!--pagination-->';
		 
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		 
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		 
		$config['next_link'] = 'Next &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		 
		$config['prev_link'] = '&larr; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		 
		$config['cur_tag_open'] = '<li class="active text-warning"><a href="">';
		$config['cur_tag_close'] = '</a></li>';
		 
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config); 
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		
		$data["results"] = $this->fetch_activity_model->fetch_recent_activity($config["per_page"], $page, $this->session->userdata('user_id'), $this->session->userdata('date_to'), $this->session->userdata('date_from'));
		if($this->fetch_activity_model->landlord_check() == true) {
			$data['landlord_check'] = 1;
		} else {
			$data['landlord_check'] = 0;
		}
		$data['date_to'] = $this->session->userdata('date_to');
		$data['date_from'] = $this->session->userdata('date_from');
		$data['reset'] = $this->session->flashdata('reset');
		
		$data['sortOptions'] = $this->fetch_activity_model->sort_options();

		$data["links"] = $this->pagination->create_links();
		$this->load->view('renters/activity', $data);

	}
	
	public function activity()
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in');
		$this->load->model('special/user_activity_page');
		
		$this->form_validation->set_rules('date_to', 'Date To', 'required|trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('date_from', 'Date From', 'required|trim|max_length[15]|xss_clean');
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			$this->session->set_userdata('date_to', $date_to);
			$this->session->set_userdata('date_from', $date_from);
		} 
		
		$offset = $this->uri->segment(3);
		
		$data = $this->user_activity_page->activity('renters', $offset);
		$data['date_to'] = $this->session->userdata('date_to');
		$data['date_from'] = $this->session->userdata('date_from');
		$data['reset'] = $this->session->flashdata('reset');
		
		$this->load->view('renters/activity', $data);
	}
	
	public function sort_activity()
	{
		$this->check_if_loggedin();
		$this->load->model('renters/fetch_activity_model');
		$this->form_validation->set_rules('sort', 'Sort By', 'trim|max_length[45]|xss_clean');
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			if(empty($sort)) {
				$this->session->set_flashdata('success', 'Activity Is Showing All Data');
				$this->session->unset_userdata('sort_activity_by');
			} else {
				$options = $this->fetch_activity_model->sort_options();
				$found = false;
				foreach($options as $val) {
					
					if($val['action'] == $sort) {
						$found = true;
					}
				}
				if($found) {
					$this->session->set_flashdata('success', 'Activity Is Sorted');
					$this->session->set_userdata('sort_activity_by', $sort);
				} else {
					$this->session->set_flashdata('error', 'Invalid Sorting Option, Try Again');
				}	
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		
	
		
		redirect('renters/activity');
		exit;
	}
	
	public function turn_phone_into_email($phone) 
	{
		if(strlen($phone) == 10) {
			$link = 'http://www.carrierlookup.com/index.php/api/lookup?key=66ed44b57ad050d6b2d5eb14c366871dd0afb5d6&number='.$phone;
			$return = file_get_contents($link);
			$object = json_decode($return, true);

			$response_carriers = array(
				'sprint' 		=> '@messaging.sprintpcs.com', 
				'AT&T' 			=> '@cingularme.com', 
				'verizon'		=> '@vtext.com', 
				't-mobile' 		=> '@tmomail.net',
				'bell' 			=> 'txt.bellmobility.ca',
				'bluegrass_cellular' => '@sms.bluecell.com',
				'carolina_west'	=> '@cwwsms.com',
				'cellular_south'=>	'@csouth1.com',
				'centennial'	=> '@cwemail.com',
				'dobson'		=> '@mobile.dobson.net',
				'fido'			=> '@fido.ca',
				'inland'		=> '@inlandlink.com',
				'mts'			=> '@mobilecomm.net',
				'nextel'		=> '@messaging.nextel.com',
				'cricket'		=> '@mms.mycricket.com',
				'alaska'		=> '@msg.acsalaska.com',
				'arch'			=> '@archwireless.net',
				'alltel'		=> '@message.alltel.com');  
			foreach($response_carriers as $key => $val) {
				if($key == $object['Response']['carrier']) {
					$new_phone = $phone.$val;
				}
			}
			if(!empty($new_phone)) {
				return $new_phone;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function forgot_password() 
	{
    	 $this->load->helper('captcha');
		/* Set a few basic form validation rules */
		$this->form_validation->set_rules('email', 'Email', 'required|trim|min_length[3]|max_length[100]|xss_clean|valid_email');
		$this->form_validation->set_rules('captcha', "Captcha", 'required|callback_check_captcha');
		
		/* Get the user's entered captcha value from the form */
		if(!empty($_POST['captcha'])) {
			$userCaptcha = $_POST['captcha'];
		}
		
		/* Get the actual captcha value that we stored in the session (see below) */
		$word = $this->session->userdata('captchaWord');
		
		extract($_POST);
		
		$this->session->set_flashdata('userCaptcha', $captcha);
		
		/* Check if form (and captcha) passed validation*/
		if ($this->form_validation->run() == TRUE && strcmp(strtolower($userCaptcha),strtolower($word)) == 0)
		{
			
			$this->session->unset_userdata('captchaWord');
		 	extract($_POST);
			$this->load->model('renters/reset_password');
			$hash = $this->reset_password->check_user_email($email);
			
			
			if($hash != false) 
			{
				// DB Updated with hash now need to email user that hash
				$message = '
					<h3>Reset Your Password</h3>
					<p>You have requested to reset your password. If you did not request to have your password reset you can ignore this email. Else click the link below to go through the steps to reset your password.</p><a href="'.base_url().'renters/reset_password/'.$hash['hash'].'">Reset Password</a>
					<p><b>Username: </b> '.$hash['user'];
				$subject = 'N4R | Password Reset Instructions';
				$this->load->model('special/send_email');
				if($this->send_email->sendEmail($email, $message, $subject)) {
					
				} else {
					
				}
				$data = array('email' => $email);
				$this->load->view('renters/password-reset', $data);
			} 
			else
			{
				 /** Validation was not successful - Generate a captcha **/
			
				$this->session->set_flashdata('error', 'No account found with that email, try again');
				redirect('renters/forgot-password');
				exit;
			}		  
	
		}
		else 
		{
		  /** Validation was not successful - Generate a captcha **/
		  $this->session->set_flashdata('captchas', 'Invalid captcha, try again');
		
		  /* Setup vals to pass into the create_captcha function */
		  $vals = array(
				'img_path' 		=> 	'./captcha/',
				'img_url' 		=> 	base_url().'captcha/',
				'img_width'	 	=> 	'180',
				'img_height' 	=> 	'55',
				'experation' 	=> 	'3600',
			);
			
		  /* Generate the captcha */
		  $captcha = create_captcha($vals);
		 
		  /* Store the captcha value (or 'word') in a session to retrieve later */
		  $this->session->set_userdata('captchaWord', $captcha['word']);
		  
		  /* Load the captcha view containing the form (located under the 'views' folder) */
		  $this->load->view('renters/forgot-password', $captcha);
		}
	}	

	public function password_reset()
	{
		$this->load->view('renters/password-reset');
	}
	
	public function check_captcha()
	{
		$cap=$this->input->post('captcha');
		if(strtoupper($this->session->userdata('captchaWord')) == strtoupper($cap))
		{
			return true;
		}
		else{
			$this->form_validation->set_message('check_captcha', '<b>Error:</b> Security Number Does Not Match');
			return false;
		}
	}
	
	function reset_password() //Handles the view after the link in the email has been clicked
	{
		if($this->session->userdata('token') == '') {
			$token = $this->uri->segment(3);
			$token = $this->security->xss_clean($token);
			$this->load->model('renters/reset_password');
			if($this->reset_password->check_token($token) == true) {
				$this->session->set_userdata('token', $token);
				$this->load->view('renters/change-password');
			} else {
				$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
				redirect('renters/forgot-password');
				exit;
			}
		}
		else 
		{
			$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[50]|xss_clean');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|min_length[6]|max_length[200]|matches[password]|xss_clean');
			$this->form_validation->set_rules('token', 'Link', 'required|trim|max_length[100]|xss_clean');
		
			if($this->form_validation->run() == FALSE) 
			{
				$this->load->view('renters/change-password');
			}
			else 
			{
				extract($_POST);
				$this->load->model('renters/reset_password');
				if($this->reset_password->check_token($token) == true) {
					if($this->reset_password->change_password($token, $password) == true) {
						$this->load->model('renters/user_model');
						$this->session->set_flashdata('success', 'Your Password Has Now Been Changed, You Can Login Now With Your New Password');
						redirect('renters/login');
						exit;
					} else {
						$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
						redirect('renters/forgot-password');
						exit;
					}
				} else {
					$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
					redirect('renters/forgot-password');
					exit;
				}
				$this->session->sess_destroy();
			}
		}
	}
	
	public function reset_dates() // Resets Dates In The Activity Page
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->session->unset_userdata('date_to');
		$this->session->unset_userdata('date_from');
		$this->session->set_flashdata('reset', '<div class="alert alert-success"><p><b>Success:</b> Dates have been reset</p></div>');
		redirect('renters/activity');
		exit;
	}
	
	function logout() 
	{
		$this->session->sess_destroy();
		$cookie = array(
			'name'   => 'logged_in',
			'domain' => '.network4rentals.com',
			'path'   => '/',
		);
		delete_cookie($cookie);
		redirect('renters/login');
	}
	
	public function search_businesses() // Requested by ajax request to grab business names of landlords (create account - edit-account)
	{
		$this->output->set_template('');
		$searched_for = $this->uri->segment(3);	
		if(!empty($searched_for)) 
		{
			$this->load->model('renters/create_user_model');
			$results = $this->create_user_model->search_business($searched_for);
			return $results;
		}
	}
	
	public function edit_account() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		
		$this->load->model('renters/user_model');
		$data['user_info'] = $this->user_model->get_users_details();
		
		$this->form_validation->set_rules('fullname', 'Full Name', 'required|trim|min_length[4]|max_length[100]|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|min_length[4]|max_length[100]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[10]|max_length[18]|xss_clean');
		$this->form_validation->set_rules('sms_msgs', 'Alternative Phone', 'trim|min_length[1]|max_length[1]|xss_clean');
		$this->form_validation->set_rules('cell_phone', 'Alternative Phone', 'trim|min_length[12]|max_length[14]|xss_clean');
		
		if($this->form_validation->run() == TRUE) 
		{
			extract($_POST);
			$name_array = explode(' ', $fullname);
			if(sizeof($name_array) > 1) {
				$this->load->model('renters/edit_user_account');
				$phone = preg_replace("/[^0-9]/", '', $phone);
				if(!empty($cell_phone)) {
					$cell_phone = preg_replace("/[^0-9]/", '', $cell_phone);
					$result = $this->edit_user_account->update_personal_info($fullname, $email, $phone, $cell_phone, $sms_msgs);
				} else {
					$result = $this->edit_user_account->update_personal_info($fullname, $email, $phone, $cell_phone = NULL, $sms_msgs);
				}
				if($result == true) {
					
					if($data['user_info'][0]['sms_msgs'] == 'n' && $_POST['sms_msgs'] == 'y') { // SEND SMS MSG
						$this->load->model('renters/sms_handler');
						$phone_data = array(
							'phone_to' => $_POST['cell_phone'], 
							'message'=> 'You have updated your account to receive text messages on N4R.', 
							'page' => 'renters/edit-account'
						);
						$this->send_sms($phone_data);
					}
					
					$this->load->model('renters/user_model');
					
					// Send Action To Tenant Activity Feed
					$data = array('action'=>'Changed Account Details', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>'');
					$this->user_model->add_activity($data);
					
					$this->session->set_flashdata('feedback_success', 'Your Account Details Have Been Saved');
				} else {
					$this->session->set_flashdata('feedback_error', 'Something Went Wrong, Try Again');
				}
			} else {
				$this->session->set_flashdata('feedback_error', 'Full Name Should Container Your First And Last Name, Try Again');
			}
			redirect('renters/edit-account');
		} 
		else 
		{
			$this->form_validation->set_message('email', 'This Email %s, Is Already Being Used, Try Another Email Address');
		}
		
		$this->load->view('renters/edit-account', $data);

	}
	
	function add_forwarding_email() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		if(!empty($_POST['email'])) {
			$this->form_validation->set_rules('f_email', 'Email','required|trim|min_length[4]|max_length[100]|xss_clean|valid_email');
		} else {
			$this->form_validation->set_rules('f_email', 'Email','trim|max_length[100]|xss_clean');
		}
		if($this->form_validation->run() == FALSE) 
		{
			$this->session->set_flashdata('feedback_error', 'Invalid Forwarding Email Address, Try Again');
			redirect('renters/edit-account'); 
			exit;
		}
		else 
		{
			extract($_POST);
			
			$this->load->model('renters/edit_user_account');
			$this->load->model('renters/user_model');
			
			$result = $this->edit_user_account->edit_forwarding_address($f_email);
			if($result == true) {
			
				// Send Action To Tenant Activity Feed
				$data = array('action'=>'Added A Forwarding Email - '.$f_email, 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>'');
				$this->user_model->add_activity($data);		
				
				$this->session->set_flashdata('feedback_success', 'Forwarding Email Address Has Been Saved');
				redirect('renters/edit-account');
				exit;
			} else {
				$this->session->set_flashdata('feedback_error', 'No Changes Where Made');
				redirect('renters/edit-account');
				exit;
			}
		}
	}
	
	function change_password()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[100]|xss_clean');
		$this->form_validation->set_rules('password1', 'Confirmed Password', 'required|trim|min_length[6]|max_length[100]|xss_clean|matches[password]');
		if($this->form_validation->run() == FALSE) 
		{
			$this->session->set_flashdata('feedback_error', 'Password Was Not 6 Characters And/Or Did Not Match, Try Again');
			redirect('renters/edit-account');
			exit;
		}
		else 
		{
			extract($_POST);	
			$this->load->model('renters/edit_user_account');
			$result = $this->edit_user_account->update_password($password);
			if($result == true) {
				$this->load->model('renters/user_model');
				
				// Send Action To Tenant Activity Feed
				$data = array('action'=>'Changed Password', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>'');
				$this->user_model->add_activity($data);			
				
				$this->session->set_flashdata('feedback_success', 'Your Password Has Been Changed');
				redirect('renters/edit-account');
				exit;
			} else {
				$this->session->set_flashdata('feedback_error', 'Something Went Wrong, Try Again');
				redirect('renters/edit-account');
				exit;
			}
		}
	}
	
	function link_landlord() //links landlord through the edit screen
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('landlords_email', 'Landlords Email', 'trim|min_length[4]|max_length[100]|xss_clean|valid_email');
		$this->form_validation->set_rules('business_name', 'Business Name', 'trim|min_length[4]|max_length[100]|xss_clean');
		
		if($this->form_validation->run() == TRUE) 
		{	
			extract($_POST);
			$this->load->model('renters/user_model');
			$data = $this->user_model->get_users_details();
			if(!empty($business_name)) {
				$updated = $this->user_model->set_landlord_through_bName($business_name);
				if($updated == true) {
					$this->session->set_flashdata('feedback_success', 'Your Landlord Has Been Added And Saved');
					$action = 'Linked Landlord - '.$business_name;
					$action_id = 'na';
					$message_from = 'na';
					$this->user_model->add_activity_feed($action, $action_id, $message_from);
				} else {
					$this->session->set_flashdata('feedback_error', 'Your Landlord Was Not Found, Check Your Spelling Or Contact Your Landlord To Make Sure You Have The Right Name');
				}
			} elseif($landlords_email != $data['landlordEmail']) {
				$updated = $this->user_model->set_landlord_email($landlords_email);
				if($updated == true) {
					$action = 'Linked Landlord - '.$landlords_email;
					$action_id = 'na';
					$message_from = 'na';
					$this->user_model->add_activity_feed($action, $action_id, $message_from);
					$this->session->set_flashdata('feedback_success', 'Your Landlord Has Been Added And Saved');
				} else {
					$this->session->set_flashdata('feedback_error', 'Something Went Wrong, Try Again');
				}
			}
			
			
		} else {
			$this->session->set_flashdata('feedback_error', 'Field Validation Failed, Try Again');
		}
		redirect('renters/edit-account');
		exit;
	}
	
	function resend_account_email() // Resends the link for email confirmation 
	{
		$email = $this->session->userdata('user_email');
		$message = $this->session->userdata('message');
		$subject = $this->session->userdata('subject');
		if(!empty($email) && !empty($message) && !empty($subject)) {
			$this->session->set_flashdata('resent', 'Verification Email Has Been Sent Again');
			$alt_message = '';
			$this->load->model('special/send_email');
			$this->send_email->sendEmail($email, $message, $subject, $alt_message);
			redirect('renters/account-created');
		} else {
			redirect('renters/create-account');
			exit;
		}
	}

	function message_landlords() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		
		$this->load->section('sidebar', 'renters/sidebar'); 
		
		$this->load->model('renters/message_user');
		$data['results'] = $this->message_user->show_landlords();
		$this->load->view('renters/message-landlords', $data);
		
	}
	
	function my_history() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		$this->load->model('renters/user_model');
		$results = $this->user_model->show_my_rental_history();
		
		if($results != false) {
			$data['results'] = $results;
		} else {
			
		}
		$this->session->unset_userdata('renter_history_row', '');
		if(!empty($data)) {
			$this->load->view('renters/my-rental-history', $data);
		} else {
			$this->load->view('renters/my-rental-history');
		}
	}
	
	function add_landlord() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		$this->load->js('assets/themes/default/js/jquery.formatCurrency-1.4.0.min.js');
		$this->load->js('assets/themes/default/js/searchify.js');

		//Form Validations
		$this->form_validation->set_rules('bName', 'Business Name', 'trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('landlord_name', 'Landlord/Contact Name', 'trim|max_length[100]|xss_clean|required');
		$this->form_validation->set_rules('landlord_email', 'Landlord/Contact Email', 'trim|max_length[200]|xss_clean|valid_email');
		$this->form_validation->set_rules('landlord_phone', 'Landlord/Contact Phone', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('landlord_address', 'Landlord Address', 'trim|max_length[150]|xss_clean');
		$this->form_validation->set_rules('landlord_city', 'Landlord City', 'trim|max_length[60]|xss_clean');
		$this->form_validation->set_rules('state', 'Landlord State', 'trim|max_length[2]|xss_clean');
		$this->form_validation->set_rules('zip', 'Landlord Zip', 'trim|max_length[10]|xss_clean|alpha_dash');
		$this->form_validation->set_rules('link_id', '', 'trim|max_length[11]|xss_clean|numeric');
		$this->form_validation->set_rules('payments', 'Rent Per Month', 'trim|max_length[8]|xss_clean|required');
		$this->form_validation->set_rules('lease', '', 'trim|max_length[60]|xss_clean|required');
		$this->form_validation->set_rules('rental_address', 'Rental Address', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('rental_city', 'Rental City', 'required|trim|max_length[60]|xss_clean');
		$this->form_validation->set_rules('rental_state', 'Rental State', 'required|trim|max_length[2]|xss_clean');
		$this->form_validation->set_rules('rental_zip', 'Rental Zip', 'required|trim|max_length[10]|xss_clean|alpha_dash');
		$this->form_validation->set_rules('move_in', 'Move In Date', 'required|trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('move_out', 'Move Out Date', 'trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('group_id', 'Select Your Landlord Again', 'trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('cell_phone', 'Cell Phone', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('deposit', 'Deposit', 'trim|max_length[8]|xss_clean|required');
		$this->form_validation->set_rules('day_rent_due', 'Day Rent Is Due', 'trim|max_length[2]|xss_clean|required|integer');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			$form_error = false;
			if(isset($_FILES)) { // File Upload Handler
				if(!empty($_FILES['file']['name'])) {
					$config['upload_path'] = './lease-uploads/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|PNG|JPG|JPEG|docx';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					 
					$file = "file";
					
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				} else {
					$file = '';
				}
			} 
			if(empty($bName) && empty($landlord_name)) {
				$this->session->set_flashdata('error', 'Business Name Or Landlord Name For The Landlord Is Required');
				$form_error = true;
			}
			if(empty($landlord_email) && empty($landlord_phone)) {
				$this->session->set_flashdata('error', 'Landlords Phone Or Landlords Email Is Required');
				$form_error = true;
			}
			if(!empty($landlord_phone) && empty($landlord_email)) {
				$landlord_phone = preg_replace("/[^0-9]/", '', $landlord_phone);
				if(strlen($landlord_phone) == 10) {
					$check = $this->turn_phone_into_email($landlord_phone);
					if($check != false) {
						$landlord_email = $check;
					}
				} else {
					$this->session->set_flashdata('error', 'Landlords Phone Must Be 10 Digits Long');
					$form_error = true;
				}
			}
			if(!empty($cell_phone) && empty($landlord_email)) {
				$cell_phone = preg_replace("/[^0-9]/", '', $cell_phone);
				if(strlen($landlord_phone) == 10) {
					$check = $this->turn_phone_into_email($cell_phone);
					if($check != false) {
						$cell_phone = $check;
					}
				} else {
					$this->session->set_flashdata('error', 'Landlords Phone Must Be 10 Digits Long');
					$form_error = true;
				}
			}
			
			if($form_error == true) {
				redirect('renters/add-landlord');
				exit;
			}
			$move_in = date('Y-m-d', strtotime($move_in));
			if(!empty($move_out)) {
				$move_out = date('Y-m-d', strtotime($move_out));
			}
			$landlord_email = strtolower($landlord_email);
			$landlord_name = ucwords(strtolower($landlord_name));
			$bName = ucwords(strtolower($bName));

			$this->load->model('renters/renter_history_handler');
			
			$user_id = $this->session->userdata('user_id');
			
			$form_input = array('bName' => $bName, 'deposit'=>$deposit, 'landlord_name' => $landlord_name, 'landlord_phone' => $landlord_phone, 'landlord_email' => $landlord_email, 'landlord_address' => $landlord_address, 'landlord_city' => $landlord_city, 'rental_state' => $rental_state, 'zip' => $zip, 'link_id' => $link_id, 'rental_address' => ucwords(strtolower($rental_address)), 'rental_city' => $rental_city, 'rental_zip' => $rental_zip, 'move_in' => $move_in, 'move_out' => $move_out, 'tenant_id' => $user_id, 'payments' => $payments, 'lease' => $lease, 'current_residence' => 'y', 'lease_upload' => $file, 'group_id' => $group_id, 'timestamp'=>date('Y-m-d h:i:s'), 'day_rent_due'=>$day_rent_due);
			
			$added = $this->renter_history_handler->add_landlord($form_input);
			if($added>0) {	
				if($form_input['current_residence'] == 'y') {
					$email = $form_input['landlord_email'];
					$message = '<h3>New Tenant Linked To You</h3>
						One of your tenants has linked to you account and ready to communicate with you through our network.
					';
					$message .= '<p>You can verify their info by visiting the link and clicking "verify tenant".</p><p>
					<a href="'.base_url().'landlords/view-tenant-info/'.$added.'">View Tenant Info</a></p>';
					$subject = 'A New Tenant Has Linked To You On N4R';
					$alt_message = 'A new tenant has linked to you on network4rentals.com';
					$this->load->model('special/send_email');
					$this->send_email->sendEmail($email, $message, $subject, $alt_message = null);
				}
				
				//Add Activity To Landlord
				$this->load->model('renters/user_model');
				if(!empty($group_id)) {
					$link_id = $this->user_model->get_admin_id($group_id);
				} else {
					$group_id = NULL;
				}
				
				$renterName = $this->user_model->getUsersFullName($this->session->userdata('user_id'));
				$data = array('action'=>'New Tenant Linked To You<br><b><small>'.$renterName.'</small></b>', 'user_id'=>$link_id, 'type'=>'landlords', 'action_id'=>$added, 'group_id'=>$group_id);
				
				$this->user_model->add_activity($data);
				//Add Activity To Renter
				$data = array('action'=>'New Landlord Added', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>'');
				$this->user_model->add_activity($data);

				//Attempt to notify landlord of payment by sms
				if(!empty($group_id)) {
					$sms_id = $this->user_model->get_sub_admin_id($group_id);
				} else {
					$sms_id = $link_id;
				}
				$this->load->model('renters/sms_handler');
				$link = base_url().'landlords/view-tenant-info/'.$added;
				$msg = 'New tenant linked to you on N4R: '.$link;
				$this->sms_handler->send_sms($sms_id, $msg);
				
				$this->session->set_flashdata('success', 'Landlord Added Successfully');
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			}		
			redirect('renters/my-history');
			exit;
		}
		
		
		$this->load->view('renters/add-landlord');
	}

	function add_past_landlord() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		$this->load->js('assets/themes/default/js/jquery.formatCurrency-1.4.0.min.js');
		$this->load->js('assets/themes/default/js/searchify.js');

		//Form Validations
		$this->form_validation->set_rules('bName', 'Business Name', 'trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('landlord_name', 'Landlord/Contact Name', 'trim|max_length[100]|xss_clean|required');
		$this->form_validation->set_rules('landlord_email', 'Landlord/Contact Email', 'trim|max_length[200]|xss_clean|valid_email');
		$this->form_validation->set_rules('landlord_phone', 'Landlord/Contact Phone', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('landlord_address', 'Landlord Address', 'trim|max_length[150]|xss_clean');
		$this->form_validation->set_rules('landlord_city', 'Landlord City', 'trim|max_length[60]|xss_clean');
		$this->form_validation->set_rules('state', 'Landlord State', 'trim|max_length[2]|xss_clean');
		$this->form_validation->set_rules('zip', 'Landlord Zip', 'trim|max_length[10]|xss_clean|alpha_dash');
		$this->form_validation->set_rules('link_id', '', 'trim|max_length[11]|xss_clean|numeric');
		$this->form_validation->set_rules('payments', 'Rent Per Month', 'trim|max_length[60]|xss_clean|numeric|required');
		$this->form_validation->set_rules('lease', '', 'trim|max_length[60]|xss_clean|required');
		$this->form_validation->set_rules('rental_address', 'Rental Address', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('rental_city', 'Rental City', 'required|trim|max_length[60]|xss_clean');
		$this->form_validation->set_rules('rental_state', 'Rental State', 'required|trim|max_length[2]|xss_clean');
		$this->form_validation->set_rules('rental_zip', 'Rental Zip', 'required|trim|max_length[10]|xss_clean|alpha_dash');
		$this->form_validation->set_rules('move_in', 'Move In Date', 'required|trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('move_out', 'Move Out Date', 'trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('group_id', 'Select Your Landlord Again', 'trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('cell_phone', 'Cell Phone', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('deposit', 'Deposit', 'trim|max_length[5]|xss_clean|required|integer');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			$form_error = false;
			if(isset($_FILES)) { // File Upload Handler
				if(!empty($_FILES['file']['name'])) {
					$config['upload_path'] = './message-uploads/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|PNG|JPG|JPEG|docx';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					 
					$file = "file";
					
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				} else {
					$file = '';
				}
			} 
			if(empty($bName) && empty($landlord_name)) {
				$this->session->set_flashdata('error', 'Business Name Or Landlord Name For The Landlord Is Required');
				$form_error = true;
			}
			if(!empty($landlord_phone) && empty($landlord_email)) {
				$landlord_phone = preg_replace("/[^0-9]/", '', $landlord_phone);
				if(strlen($landlord_phone) == 10) {
					$check = $this->turn_phone_into_email($landlord_phone);
					if($check != false) {
						$landlord_email = $check;
					}
				}
			}
			if(!empty($cell_phone) && empty($landlord_email)) {
				$cell_phone = preg_replace("/[^0-9]/", '', $cell_phone);
				if(strlen($landlord_phone) == 10) {
					$check = $this->turn_phone_into_email($cell_phone);
					if($check != false) {
						$cell_phone = $check;
					}
				}
			}
			
			if($form_error == true) {
				redirect('renters/add-past-landlord');
				exit;
			}
			$move_in = date('Y-m-d', strtotime($move_in));
			if(!empty($move_out)) {
				$move_out = date('Y-m-d', strtotime($move_out));
			}
			$landlord_email = strtolower($landlord_email);
			$landlord_name = ucwords(strtolower($landlord_name));
			$bName = ucwords(strtolower($bName));
			if(empty($current)) {
				$current = 'n';
			}
			$this->load->model('renters/renter_history_handler');
			
			$user_id = $this->session->userdata('user_id');
		
			$form_input = array('bName' => $bName, 'deposit'=>$deposit, 'landlord_name' => $landlord_name, 'landlord_phone' => $landlord_phone, 'landlord_email' => $landlord_email, 'landlord_address' => $landlord_address, 'landlord_city' => $landlord_city, 'rental_state' => $rental_state, 'zip' => $zip, 'link_id' => $link_id, 'rental_address' => ucwords(strtolower($rental_address)), 'rental_city' => $rental_city, 'rental_zip' => $rental_zip, 'move_in' => $move_in, 'move_out' => $move_out, 'tenant_id' => $user_id, 'payments' => $payments, 'lease' => $lease, 'current_residence' => 'n', 'lease_upload' => $file, 'group_id' => $group_id, 'timestamp'=>date('Y-m-d h:i:s'));
			
			$added = $this->renter_history_handler->add_landlord($form_input);
			if($added == true) {	
				if($form_input['current_residence'] == 'y') {
					$email = $form_input['landlord_email'];
					$message = '<h3>New Tenant Linked To You</h3>
						One of your tenants has linked to you account and ready to communicate with you through our network.
					';
					
					$message .= '<p>You can verify there info by visiting the link and clicking "verify tenant".</p><p><a href="'.base_url().'landlords/view-tenant-info/'.$this->session->userdata('user_id').'">View Tenant Info.</p>';
					$subject = 'A New Tenant Has Linked To You On N4R';
					$alt_message = 'A new tenant has linked to you on network4rentals.com';
					$this->load->model('special/send_email');
					$this->send_email->sendEmail($email, $message, $subject, $alt_message = null);
					
					
				}
				
				//Add Activity To Landlord
				$this->load->model('renters/user_model');
				$renterName = $this->user_model->getUsersFullName($this->session->userdata('user_id'));
				
				$data = array('action'=>'New Tenant Linked To You<br><b><small>'.$renterName.'</small></b>', 'user_id'=>$link_id, 'type'=>'landlords', 'action_id'=>$added);
				$this->user_model->add_activity($data);
				//Add Activity To Renter
				$data = array('action'=>'New Landlord Added', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>'');
				$this->user_model->add_activity($data);
				
				$this->session->set_flashdata('success', 'Landlord Added Successfully');
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			}		
			redirect('renters/my-history');
			exit;
		}
		$this->load->model('renters/landlord_handler');
		$this->load->view('renters/add-past-landlord');
	}
	
	function request_registration_email() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$this->load->model('renters/user_model');
		$details = $this->user_model->request_registration_landlord($id);
		$message = '<h3>'.$details['tenant_name'].' Has Invited You To N4R</h3>';
		$message .= '<p>Network4Rentals.com America’s best website for improving communications between landlords, tenants, and contractors.</p>';
		$message .= '<h4>Why Use N4R</h4>';
		$message .= '<ul><li>Easy access to your tenants at all hours</li><li>Additional source of easy record keeping by property including; what services have been requested in the past and how many times the same type of service has been requested, current phone numbers and email addresses provided by the tenant themselves.</li><li>Ability to hold tenants accountable for improper or frivolous service requests.</li><li>Conformation of receipt by tenant of any communication sent through the site.</li><li>Ability to attach pictures, documents, and copies of lease agreements to communication sent to tenant.</li><li>Ability to receive service requests with detailed descriptions, and pictures of the issue.</li><li>Less additional repair costs and secondary damages caused by improper contractors and/or misunderstandings of the issue.</li><li>Less hesitation by tenants to submit service requests and causing small issues to become more costly.</li><li>Ability to simply forward a service request to a contractor of your choice.</li><li>Easy reference to a contractor that can perform the service, but no commitment to any certain contractor.</li></ul>';
		$message .= '<h4><a href="https://network4rentals.com">Sign Up Today, Its Free</a></h4>';
		$subject = $details['tenant_name'].' Has Invited You To N4R';
		$email = $details['email'];
		$alt_message = $details['tenant_name'].' has requested that you register for an account at http://network4rentals.com';
		$this->load->model('special/send_email');
		$emailed = $this->send_email->sendEmail($email, $message, $subject, $alt_message);
		if($emailed) {
			$this->session->set_flashdata('success', 'An Email Has Been Emailed To This Landlord Requesting Them To Join N4R');
		} else {
			$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
		}
		redirect('renters/my-history');
		exit;
	}
	
	function view_payment_history() 
	{
		$this->session->unset_userdata('edit_rental_id', '');
		
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->load->model('renters/user_model');
	
		$this->form_validation->set_rules('amount', 'Amount', 'required|trim|max_length[10]|xss_clean|');
		$this->form_validation->set_rules('paid_on', 'Paid On', 'required|trim|max_length[10]|xss_clean');
		$this->form_validation->set_rules('payment_type', 'Payment Type', 'required|trim|max_length[60]|xss_clean');
		$this->form_validation->set_rules('notes', 'Notes', 'trim|max_length[1000]|xss_clean');
		
		$data['payments'] = $this->user_model->view_rental_payments($this->uri->segment('3'));
		$data['address'] = $this->user_model->show_rental_address($this->uri->segment('3'));

		$id = (int)$this->uri->segment(3);
		if(!empty($id)){
			$this->session->set_userdata('ref_id', $id);
		} elseif($this->session->userdata('ref_id') == '') {
			$this->session->set_flashdata('error', 'Your Address Was Not Found, Try Again');
			redirect('renters/my-history');
			exit;
		}
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			$payment = array(
				'amount'=>$amount, 
				'paid_on'=>$paid_on, 
				'payment_type'=>$payment_type, 
				'reason'=>$notes,
				'ref_id'=> $this->session->userdata('ref_id'),
				'landlord_id'=> $data['address']['link_id'],
			);
			$added = $this->user_model->add_payment($payment);
		
			if($added != false) {
				$this->session->set_flashdata('success', 'Your Payment Has Been Added');	
				
				$link = base_url().'landlords/view-tenant-info/'.$this->session->userdata('ref_id');
				
				/*SMS landlord if set
				$this->load->model('renters/sms_handler');
				$msg = 'One of your tenants have logged a payment on Network4Rentals.com: '.$link;
				$this->sms_handler->send_sms($data['address']['link_id'], $msg);
				*/
				
				// Send to the renter feed
				$inform = array('action'=>'Added A Rent Payment', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>$added['insert_id']);
				$this->user_model->add_activity($inform);
				
				// Send to the landlords feed
				$inform = array('action'=>'Renter Paid Offline Payment', 'user_id'=>$added['landlord_id'], 'type'=>'landlords', 'action_id'=>$added['ref_id'], 'group_id'=>$added['group_id']);
				$this->user_model->add_activity($inform);
				
				/*
				$email = $added['landlord_email'];
				$message = '<h3>Tenant Added Offline Payment</h3>
					Your tenant at '.$added['address'].' added an off-line payment to their account. You can view the details of this payment by logging into your account.
				';
				$subject = 'Tenant Added Offline Payment';
				$alt_message = 'Tenant Added Offline Payment network4rentals.com';
				$this->load->model('special/send_email');
				$this->send_email->sendEmail($email, $message, $subject, $alt_message = null);
				*/
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			}
			redirect('renters/view-payment-history/'.$added['ref_id']);
			exit;
		}
		
		if(empty($data['address'])) {
			redirect('renters/my-history');
			exit;
		}
		
		if(empty($data['payments'])) {
			$data['none'] = 'You never added any payments for this address. You can add them by clicking the add payment button in the upper right corner of this page.';
		}

		$this->output->set_template('logged-in');
		$this->load->css('assets/themes/default/css/datepicker.css');
		$this->load->js('assets/themes/default/js/custom.js');
		
		$this->load->view('renters/view_payment_history', $data);
	}
	
	function rental_history_pdf()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('blank');
		$this->load->helper(array('dompdf', 'file'));
		// page info here, db calls, etc.
		
		$this->load->model('renters/user_model');
		$results = $this->user_model->show_my_rental_history();
		$user = $this->user_model->get_users_details();
		$data['info'] = $results;
		$data['user'] = $user;
		
		$html = $this->load->view('renters/rental-history-pdf', $data, true); // Add Argument true after data
		pdf_create($html, 'rental-history-N4R'); 
	}
	
	function view_payment_details() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->load->js('assets/themes/default/js/renters/payment-notes.js');
		$this->load->js('assets/themes/default/js/notify.min.js');
		$id = (int)$this->uri->segment(3);
		
		$this->load->model('renters/payment_details');
		$info = $this->payment_details->show_payment_details($id);
		$data['landlord_info'] = $this->payment_details->get_payment_landlord_info($id);
		if($info == false) {
			$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			redirect('renters/view-payment-history/'.$id);
			exit;
		} else {
			$data['info'] = $info;
		}
		
		$data['cancel_id'] = $data['info'][1]['id'];
		
		
		$this->output->set_template('logged-in');
		$this->load->view('renters/view-payment-details', $data);
	}
	
	function edit_rental_details() 
	{
        $this->load->js('assets/themes/default/js/jquery.formatCurrency-1.4.0.min.js');
        $this->load->js('assets/themes/default/js/custom.js');

		$this->check_if_loggedin(); // Checks if user is logged in
		
		$id = (int)$this->uri->segment(3);
		if(!empty($id)) {
			$this->session->set_userdata('edit_rental_id', $id);
		}
		if(isset($_FILES)) {
			if(!empty($_FILES['file']['name'])) {
				$config['upload_path'] = './lease-uploads/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|PNG|JPG|JPEG|docx|doc';
				$config['max_size'] = '5000KB';
				$this->load->library('upload', $config);
				
				$file = "file";
				
				if($this->upload->do_upload($file)) {
					$upload = $this->upload->data();
					$file = $upload['file_name'];
				} else {
					$error = array('error' => $this->upload->display_errors());
					$file = '';
				}
			} else {
				$file = '';
			}
		}
		
		if(empty($error)) {
			//Form Validations
			$this->form_validation->set_rules('bName', 'Business Name', 'trim|max_length[100]|xss_clean');
			$this->form_validation->set_rules('landlord_name', 'Landlord/Contact Name', 'trim|max_length[100]|xss_clean');
			$this->form_validation->set_rules('landlord_email', 'Landlord/Contact Email', 'trim|max_length[200]|xss_clean|valid_email');
			$this->form_validation->set_rules('landlord_phone', 'Landlord/Contact Phone', 'trim|max_length[20]|xss_clean');
			$this->form_validation->set_rules('landlord_address', 'Landlord Address', 'trim|max_length[100]|xss_clean');
			$this->form_validation->set_rules('landlord_city', 'Landlord City', 'trim|max_length[60]|xss_clean');
			$this->form_validation->set_rules('state', 'Landlord State', 'trim|max_length[2]|xss_clean');
			$this->form_validation->set_rules('zip', 'Landlord Zip', 'trim|max_length[10]|xss_clean|alpha_dash');
			$this->form_validation->set_rules('link_id', '', 'trim|max_length[11]|xss_clean|numeric');
			$this->form_validation->set_rules('current', 'Current Residence', 'trim|max_length[1]|xss_clean|alpha');
			
			$this->form_validation->set_rules('payments', 'Rent', 'trim|max_length[60]|xss_clean|required');
			$this->form_validation->set_rules('lease', '', 'trim|max_length[60]|xss_clean|required');
			$this->form_validation->set_rules('deposit', 'Deposit', 'trim|max_length[10]|xss_clean|required|');
			
			$this->form_validation->set_rules('rental_address', 'Rental Address', 'required|trim|max_length[100]|xss_clean');
			$this->form_validation->set_rules('rental_city', 'Rental City', 'required|trim|max_length[60]|xss_clean');
			$this->form_validation->set_rules('rental_state', 'Rental State', 'required|trim|max_length[2]|xss_clean');
			$this->form_validation->set_rules('rental_zip', 'Rental Zip', 'required|trim|max_length[10]|xss_clean|alpha_dash');
			$this->form_validation->set_rules('move_in', 'Move In Date', 'required|trim|max_length[15]|xss_clean');
			$this->form_validation->set_rules('move_out', 'Move Out Date', 'trim|max_length[15]|xss_clean');
			
			$this->form_validation->set_rules('day_rent_due', 'Day The Rent Is Due', 'trim|max_length[2]|xss_clean|integer|required');

			if($this->form_validation->run() == TRUE) {
				extract($_POST);
				
				if(empty($current)) {
					$current = 'n';
				}
				$form_error = false;
				if(empty($bName) && empty($landlord_name)) {
					$this->session->set_flashdata('error', 'Business Name Or Landlord Name For The Landlord Is Required');
					$form_error = true;
				}
				if(empty($landlord_phone) && empty($landlord_email)) {
					$this->session->set_flashdata('error', 'Your Landlords Phone Or Email Is Required');
					$form_error = true;
				} 
				if(!empty($landlord_phone)) {
					$landlord_phone = preg_replace("/[^0-9]/", '', $landlord_phone);
					if(strlen($landlord_phone) != 10) {
						$this->session->set_flashdata('error', 'Invalid Phone Number, Must Be 10 Digits Long');
						$form_error = true;
					}
				}
				if(!empty($landlord_phone) && empty($landlord_email) && $form_error == false) {
					if(strlen($landlord_phone) == 10) {
						$link = 'http://www.carrierlookup.com/index.php/api/lookup?key=66ed44b57ad050d6b2d5eb14c366871dd0afb5d6&number='.$landlord_phone;
						$return = file_get_contents($link);
						$object = json_decode($return, true);
						
						$response_carriers = array('boost_cdma' => '@myboostmobile.com', 'sprint' => '@messaging.sprintpcs.com', 'AT&T' => '@cingularme.com', 'verizon' => 'vtext.com', 't-mobile' => 'tmomail.net');  
						foreach($response_carriers as $key => $val) {
							if($key == $object['Response']['carrier']) {
								$to = $phone.$val;
								$found = true;
								break;
							}
						}
						if($found == true) {
							$landlord_email = $to;
						}
					}
				}	
				if($form_error == false) {
					
					$move_in = date('Y-m-d', strtotime($move_in));
					
					if(!empty($move_out)) {
						$move_out = date('Y-m-d', strtotime($move_out));
					}
					$landlord_email = strtolower($landlord_email);
					
					if(!empty($move_out)) {
						$current = 'n';
					} else {
						$current = 'y';
					}
					$form_input = array('bName' => $bName, 'deposit'=>$deposit, 'landlord_name' => $landlord_name, 'landlord_phone' => $landlord_phone, 'landlord_email' => $landlord_email, 'landlord_address' => $landlord_address, 'landlord_city' => $landlord_city, 'state' => $state, 'zip' => $zip, 'link_id' => $link_id, 'rental_address' => $rental_address, 'rental_city' => $rental_city, 'rental_state' => $rental_state, 'rental_zip' => $rental_zip, 'move_in' => $move_in, 'move_out' => $move_out, 'tenant_id' => $this->session->userdata('user_id'), 'payments' => $payments, 'lease' => $lease, 'current_residence' => $current, 'lease_upload' => $file, 'day_rent_due' =>$day_rent_due);
	
					$this->load->model('renters/renter_history_handler');
			
					$added = $this->renter_history_handler->edit_landlord($form_input);
					
					if($added == 'created') {
						$this->session->set_flashdata('success', 'Rental Details Have Been Updated');
						redirect('renters/my-history');
						exit;
					} else {
						$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
					}
				}
			}
		} else {
			$er = '';
			foreach($error as $val) {
				$er .= $val;
			}
			$this->session->set_flashdata('error', $er);
			redirect('renters/edit-rental-details');
			exit;
		}
		
		$this->load->model('renters/user_model');
		$data['tenant_info'] = $this->user_model->get_rental_history_details($this->session->userdata('edit_rental_id'));
		if(empty($data['tenant_info'])) {
			$this->session->set_flashdata('error', 'Invalid Landlord, Try Clicking On Edit Details Again');
			redirect('renters/my-history');
			exit;
		}
		$data['landlord_info'] = $this->user_model->get_landlords_info($data['tenant_info']->link_id);
		
		
		$this->output->set_template('logged-in');
		$this->load->view('renters/edit-rental-history-details', $data);
	}
	
	function create_new_message() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$rental_id = $this->session->userdata('rental_id');
		if(empty($rental_id)) {
			redirect('renters/my-history');
			exit;
		}
		
		$this->form_validation->set_rules('subject', 'Subject', 'trim|max_length[45]|xss_clean|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|max_length[1250]|xss_clean|required');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);	
			if(isset($_FILES)) {
				if(!empty($_FILES['attachment']['name'])) {
					$config['upload_path'] = './message-uploads/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|PNG|JPG|JPEG';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					
					$file = "attachment";
					
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				} else {
					$file = '';
				}
			}
			
			if(empty($error)) {
				$hashMail = md5($this->session->userdata('user_id').rand(100, 9999999).date('Y-m-d'));
				$today = date('Y-m-d h:i:s');

				$data = array('attachment' => $file, 'rental_id' => $rental_id, 'message' => $message, 'tenant_id' => $this->session->userdata('user_id'), 'hash_mail' => $hashMail, 'sent_by' => '0', 'subject' => $subject, 'tenant_viewed' => $today);
				
				$this->load->model('renters/message_user'); 
				$message_id = $this->message_user->add_message($data); //returns insert id of message

				if($message_id>0) {
					// Send Email To Landlord
					$landlord_info = $this->message_user->get_landlord_email($message_id);
	
					$email = $landlord_info['email'];
					$message = '<h3>One Of Your Tenants Has Sent You A Message</h3>
						<p>One of your tenants has sent you a message through Network 4 Rentals. You can view the message without an account but if you create an account you will be able to respond to message and much more. Click the link below to view the message.</p>
						<a href="'.base_url().'renters/view-message-landlord/'.$hashMail.'">View Message</a>
					';
					$subject = 'New Message From Your Tenant On N4R';
					$alt_message = 'One of your tenants sent you a message on Network 4 Rentals';
					$this->load->model('special/send_email');
					$this->send_email->sendEmail($email, $message, $subject, $alt_message);
					
					
					//Add Activity To Renter
					$this->load->model('renters/user_model');
					$data = array('action'=>'Sent Landlord A New Message', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>$landlord_info['id']);
					$this->user_model->add_activity($data);
					
					//Add Activity To Landlord
					$data = array('action'=>'Message Received From Tenant', 'user_id'=>$landlord_info['id'], 'type'=>'landlords', 'action_id'=>$message_id);
					$this->user_model->add_activity($data);
					
					$this->session->set_flashdata('success', 'Message Sent To Your Landlord, Once They Read It You Will Receive An Email');
					$action_id = $this->session->userdata('rental_id');

					
					redirect('renters/message-landlord');
					exit;
				} else {
					$this->session->set_flashdata('error', 'Message Not Sent, Try Again');
					redirect('renters/message-landlord');
					exit;
				}
			} else {
				$er = '';
				foreach($error as $val) {
					$er .= $val;
				}
				$this->session->set_flashdata('error', $er);
				redirect('renters/message-landlord');
				exit;
			}
		} else {
			redirect('renters/message-landlord');
			exit;
		}
	}

	function replied_message() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$rental_id = $this->session->userdata('rental_id');
		if(empty($rental_id)) {
			redirect('renters/my-history');
			exit;
		}
				
		$this->form_validation->set_rules('message', 'Message', 'trim|max_length[1250]|xss_clean|required');
		$this->form_validation->set_rules('parent_id', '', 'trim|max_length[11]|xss_clean|numeric');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);	
			if(isset($_FILES)) {
				if(!empty($_FILES['attachment']['name'])) {
					$config['upload_path'] = './message-uploads/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					
					$file = "attachment";
					
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				} else {
					$file = '';
				}
			}
			
			if(empty($error)) {			
				$hashMail = md5($this->session->userdata('user_id').rand(100, 9999999).date('Y-m-d'));
				$today = date('Y-m-d h:i:s');
				$data = array('attachment' => $file, 'rental_id' => $rental_id, 'message' => $message, 'tenant_id' => $this->session->userdata('user_id'), 'hash_mail' => $hashMail, 'sent_by' => '0', 'parent_id' => $parent_id, 'tenant_viewed' => $today);
				$this->load->model('renters/message_user');
				$message_id = $this->message_user->message_reply($data);
				if($message_id>0) {
					// Send Email To Landlord
					$landlord_info = $this->message_user->get_landlord_email($message_id);
					$email = $landlord_info['email'];

					$message = '<h3>One Of Your Tenants Has Replied To Your Message</h3>
						<p>One of your tenants has to your message through Network 4 Rentals. You can view the message without an account but if you create an account you will be able to respond to message and much more. Click the link below to view the message.</p>
						<a href="'.base_url().'renters/view-message-landlord/'.$hashMail.'/">View Message</a>
					';
					$subject = 'New Message From Your Tenant On N4R';
					$alt_message = 'One of your tenants replied to your message on Network 4 Rentals';
					$this->load->model('special/send_email');
					$this->send_email->sendEmail($email, $message, $subject, $alt_message);
					
					//Add Activity To Renter
					$this->load->model('renters/user_model');
					$data = array('action'=>'Replied To Landlords Message', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>$landlord_info['id']);
					$this->user_model->add_activity($data);
					//Add Activity To Landlord
					$data = array('action'=>'Message Received From Tenant', 'user_id'=>$landlord_info['id'], 'type'=>'landlords', 'action_id'=>$landlord_info['id']);
					$this->user_model->add_activity($data);
					
					$this->session->set_flashdata('success', 'Message Sent To Your Landlord, Once They Read It You Will Receive An Email');
					redirect('renters/message-landlord');
					exit;
				} else {
					$this->session->set_flashdata('error', 'Message Not Sent, Try Again');
					redirect('renters/message-landlord');
					exit;
				}
			} else {
				$er = '';
				foreach($error as $val) {
					$er .= $val;
				}
				$this->session->set_flashdata('error', $er);
				redirect('renters/message-landlord');
				exit;
			}
		} else {
			redirect('renters/message-landlord');
			exit;
		}
	}
	
	function print_message()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		if(empty($id)) {
			redirect('renters/message-landlord');
			exit;
		}
		
		$this->output->set_template('blank');
		$this->load->helper(array('dompdf', 'file'));
		// page info here, db calls, etc.		
		$this->load->model('renters/message_user');
		$this->load->model('renters/user_model');
		$check_id = $this->session->userdata('rental_id');
		$data['messages'] = $this->message_user->show_parent_messages();
		$data['landlord_info'] = $this->message_user->get_rental_info($check_id);
		$data['user_info'] = $this->user_model->get_users_details();
	
				
		$html = $this->load->view('renters/print-message-pdf', $data, TRUE); // Add Argument true after data
		pdf_create($html, 'Landlord Message Print Off'); 
	}
	
	function submit_request() // Submit service request
	{
		$this->check_if_loggedin();
		$this->load->model('renters/user_model');
		
		$data['user'] = $this->user_model->get_users_details();
		if(!empty($data['user'][1]['group_id'])) {
			$group_id = $data['user'][1]['group_id'];
		}
		
		$data['landlord'] = $this->user_model->get_current_landlord_info();
		$this->form_validation->set_rules('phone2', 'Scheduling Phone','required|trim|min_length[10]|max_length[16]|xss_clean');
		$this->form_validation->set_rules('serviceType', 'Service Type','required|trim|min_length[1]|max_length[2]|xss_clean');
		$this->form_validation->set_rules('Permission', 'Permission To Enter','required|trim|min_length[2]|max_length[5]|xss_clean');
		$this->form_validation->set_rules('desc', 'Description','required|trim|min_length[5]|max_length[700]|xss_clean');
		$this->form_validation->set_rules('address', 'Address','required|trim|min_length[1]|max_length[150]|xss_clean');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			$phone2 = preg_replace("/[^0-9]/", '', $phone2);
			if(strlen($phone2) == 10) {
				
				if(isset($_FILES)) {
					if(!empty($_FILES['file']['name'])) {
						$config['upload_path'] = './service-uploads/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg';
						$config['max_size'] = '5000KB';
						$this->load->library('upload', $config);
						
						$file = "file";
						
						if($this->upload->do_upload($file)) {
							$upload = $this->upload->data();
							$file = $upload['file_name'];
							
							// Resize The Image
							$config['image_library'] = 'GD2';
							$config['source_image']	= FCPATH.'service-uploads/'.$file;
							$config['maintain_ratio'] = TRUE;
							$config['width']	 = 450;
							$config['height']	= 400;

							$this->load->library('image_lib', $config);
							
							$this->image_lib->resize();
							
							// Watermark The Image
							$config['source_image']	= FCPATH.'service-uploads/'.$file;
							$config['wm_text'] = 'Copyright '.date('Y').' - Network 4 Rentals';
							$config['wm_type'] = 'text';
							$config['wm_font_path'] = './system/fonts/texb.ttf';
							$config['wm_font_size']	= '8';
							$config['wm_font_color'] = 'ffffff';
							$config['wm_vrt_alignment'] = 'bottom';
							$config['wm_hor_alignment'] = 'center';

							$this->image_lib->initialize($config); 

							$this->image_lib->watermark();
							
						
						} else {
							$error = array('error' => $this->upload->display_errors());
							$file = '';
						}
					} else {
						$file = '';
					}
				}
				
				if(empty($error)) {	
					$this->load->model('renters/service_request_handler');
					
					$landlord_id = $data['landlord']['id'];
					$landlord_email = $data['landlord']['email'];
					$save_request = array(
						'service_type' => $serviceType,
						'enter_permission' => $Permission,
						'description' => $desc,
						'schedule_phone' => $phone2,
						'attachment' => $file,
						'rental_id' => $rental_id,
					);
					
					$submit = $this->service_request_handler->new_service_request($save_request);
					if(!empty($submit)) {
						// Send Action To Tenant Activity Feed
						$data = array('action'=>'Submitted Service Request', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>$submit['last_id'], 'group_id'=>$submit['group_id']);
						$this->user_model->add_activity($data); 
						$request_info = $this->service_request_handler->get_request_hash($submit['last_id']);
						if(!empty($request_info['email_hash'])) {
							$email = $landlord_email;
							$link = base_url().'renters/viewing-service-request/'.$request_info['email_hash'];
							$subject = 'One Of Your Tenants Has Submitted A Service Request on Network 4 Rentals';
							$alt_message = 'One of your tenants has submitted a service request on Network 4 Rentals. '.$link;
							$message = '<h3>One Of Your Tenants Have Requested A Repair At One Of Your Properties</h3>
								<p>Click the link below to view the service request that has been sent you.
								If you don\'t already have an account with us create and account and login to manage these service request along with all the other benefits of N4R.</p>
								<p>Click the link below to view the request. </br> <a href="'.$link.'">'.$link.'</a></p>
							';
							$this->load->model('special/send_email');
							$this->send_email->sendEmail($email, $message, $subject, $alt_message);
							
							$forwarding_email = $this->service_request_handler->get_forwarding_email($landlord_id);
							if(!empty($forwarding_email)) {
								$this->send_email->sendEmail($forwarding_email, $message, $subject);
							}
							
							//Add Activity To Landlord
							$data = array('action'=>'Received New Service Request<br><b><small>'.$address.'</small></b>', 'user_id'=>$request_info['landlord_id'], 'type'=>'landlords', 'action_id'=>$submit['last_id'], 'group_id'=>$submit['group_id']);
							$data = array('action'=>'Received New Service Request<br>'.$address, 'user_id'=>$request_info['landlord_id'], 'type'=>'landlords', 'action_id'=>$submit['last_id'], 'group_id'=>$submit['group_id']);
							$this->user_model->add_activity($data);
							$this->session->set_flashdata('success', 'Your Service Request Has Been Submitted.');
							
							//Check to see if sms is set on the landlord
							$this->load->model('renters/sms_handler');
							$msg = 'New Service Request: '.$link;
							$this->sms_handler->send_sms($request_info['landlord_id'], $msg);
							
							$this->sms_handler->send_Forwarding_SMS($request_info['landlord_id'], $msg);
						} else {
							$this->session->set_flashdata('success', 'Your Service Request Has Been Submitted, But The Email Did Not Send. <a href="'.base_url().'renters/resend-service-request/'.$request_info['id'].' To Your Landlord" class="btn btn-warning btn-sm btn-block">Click Here To Resend Request</a>');
						}
						
						redirect('renters/activity');
						exit;
					} else {
						$this->session->set_flashdata('error', 'Something went wrong, try again.');
					}
				} else {
					$er = '';
					foreach($error as $val) {
						$er .= $val;
					}
					$this->session->set_flashdata('error', $er);
					redirect('renters/submit-request');
					exit;
				}
			} else {
				$this->session->set_flashdata('error', 'Schedule Phone Number Must Be 10 Digits Long');
				redirect('renters/submit-request');
				exit;
			}
		}
		
		$this->output->set_template('logged-in');
		$this->load->view('renters/submit_request', $data);
	}
	
	function current_landlord() 
	{
		$this->check_if_loggedin();
		$this->load->model('renters/user_model');
		$this->output->set_template('logged-in');
		$data['landlord_info'] = $this->user_model->get_current_landlord_info();
		$data['tenant_info'] = $this->user_model->get_users_details();
		$id = $data['tenant_info'][1]['id'];
		$data['disputed_payments'] = $this->user_model->disputed_payments($id);
		$data['total'] = $this->user_model->get_payment_totals($id);
		$this->load->view('renters/current-landlord', $data);
	}
	
	function addNoteToRequest()
	{
		$this->form_validation->set_rules('id', 'id','required|trim|min_length[1]|max_length[13]|xss_clean|interger');
		$this->form_validation->set_rules('note', 'Note','required|trim|min_length[1]|max_length[255]|xss_clean');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			
			$this->load->model('renters/service_request_handler');
			$note = $this->service_request_handler->addNote($note, $id);
			if($note === true) {
				$this->session->set_flashdata('success','Your note has been added to the request');
			} else {
				$this->session->set_flashdata('error', $note);
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	function view_service_request() //Grouped By All Landlords
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in');
		
		$this->load->model('renters/service_request_handler');
		$data['requests'] = $this->service_request_handler->group_service_request_by_landlord();

		$this->session->unset_userdata('landlord_id', '');
		$this->load->view('renters/view-all-request', $data);
	}
	
	function view_requests() //Grouped By Single Landlord
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in');
		
		$id = (int)$this->uri->segment(3);
		
		$this->load->model('renters/service_request_handler');
		$data['requests'] = $this->service_request_handler->service_request_by_landlord($id);
		$data['notes'] = $this->service_request_handler->getRenterNotes($id);
		
		$this->load->view('renters/view-service-request', $data);
	}
	
	function view_request() // Show request
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in');
		
		$id = (int)$this->uri->segment(3);
		$this->load->model('renters/service_request_handler');
		$this->load->model('renters/user_model');
		
		$data['requests'] = $this->service_request_handler->view_service_request($id);
		
		
		$data['user'] = $this->user_model->get_users_details();
		
		if(!empty($data['requests'][0]['group_id'])) {
			$get_bname = $this->service_request_handler->get_business_name_of_group($data['requests']['0']['group_id']);
			$data['landlord'] = $this->user_model->get_landlords_info($get_bname['sub_admins']);
		} else {
			$data['landlord'] = $this->user_model->get_landlords_info($data['user'][1]['link_id']);
		}
		
		$data['rental'] = $this->user_model->show_rental_address($data['requests'][0]['rental_id']);		
		$data['notes'] = $this->service_request_handler->getRenterNotes($id);

		$this->load->view('renters/view-request', $data);
	}
	
	function service_request_complete($id)
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		
		$this->load->model('renters/service_request_handler');
		
		$updated = $this->service_request_handler->mark_as_complete($id);
		
		if($updated == true) {
			$this->session->set_flashdata('success', 'Service Request Marked As Complete');
		} else {
			$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
		}
		
		redirect('renters/view-request/'.$id);
		exit;
	}
	
	function status_update($id)
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);

		$this->load->model('renters/service_request_handler');
		$this->load->model('renters/user_model');
		
		$user_info = $this->user_model->get_users_details();
		$request_info = $this->service_request_handler->view_service_request($id);
		
		if(!empty($request_info['group_id'])) {
			$g_id = $this->service_request_handler->get_sub_admin_id($request_info['group_id']);
			if($g_id != false) {
				$request_info['landlord_id'] = $g_id;
			}
		}
		
		$landlords_info = $this->user_model->get_landlords_info($request_info['landlord_id']);
	
		$hash = random_string('unique');
		$this->service_request_handler->update_hash_for_email($id, $hash);
		if(!empty($landlords_info['forwarding_email'])) {
			$email = $landlords_info['forwarding_email'];
		} else {
			$email = $landlords_info['email'];
		}
		
		$message = '<h3>Your Tenant Has Requested The Status Of Their Service Request</h3>
			<p>'.$user_info[0]['name'].' sent you a service request and wanted to know what the status of the request is.</p>
			<p>If you have already updated the status disregard this message otherwise login to your account and update the status and a message will be sent to them letting them know about the changes.</p>
			
			<p><em><b>Note:</b> You will only be able to view this service request once without logging into your account. Once you view it, it will no longer be accessible through this link.</em></p>
			<a href="'.base_url().'renters/viewing_service_request/'.$hash.'">View Service Request</a>';
		$subject = 'Status Update For Service Request From N4R';

		$link = base_url().'renters/viewing-service-request/'.$hash;
		$alt_message = $user_info[0]['name'].' sent you a service request from N4R.com click the link to view the request. '.$link;
		$this->load->model('special/send_email');
		$sent = $this->send_email->sendEmail($email, $message, $subject, $alt_message);
		if($sent == true) {
			
			$this->load->model('special/add_activity');
			$action = 'Requested status update on service request<br><b><small>'.$request_info['rental_address'].' '.$request_info['rental_city'].', '.$request_info['rental_state'].'</small></b>';
			$this->add_activity->add_new_activity($action, $request_info['landlord_id'], 'landlords', $id);
			$this->add_activity->add_new_activity($action, $this->session->userdata('user_id'), 'renters', $id);
			$this->session->set_flashdata('success', 'Sent Request To Update Status To '.$email);
			
			$this->db->insert('service_request_notes', array('note'=>'Tenant requested a status update', 'visibility'=>'1', 'landlord_id'=> $request_info['landlord_id'],'ref_id'=>$id));
			
			$this->load->model('renters/sms_handler');
			
			$msg = 'One of your tenants has request a status update on their service request. '.base_url('landlords/view-service-request/'.$id);
			$this->sms_handler->send_sms($landlords_info['id'], $msg);
		} else {
			$this->session->set_flashdata('error', 'Email Not Sent, Try Again');
		}
		redirect('renters/view-request/'.$id);
		exit;
	}
	
	function resend_service_request($id) 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$this->load->model('renters/service_request_handler');
		$this->load->model('renters/user_model');
		
		$user_info = $this->user_model->get_users_details();

		$request_info = $this->service_request_handler->view_service_request($id);

		if(!empty($request_info[0]['group_id'])) {
			$admin_info = $this->service_request_handler->get_business_name_of_group($request_info[0]['group_id']);
			$landlords_info = $this->user_model->get_landlords_info($admin_info['sub_admins']);
		} else {
			$landlords_info = $this->user_model->get_landlords_info($request_info[0]['landlord_id']);
		}
		
		
		$hash = random_string('unique');
		$this->service_request_handler->update_hash_for_email($id, $hash);
		$email = $landlords_info['email'];
		
		$message = '<h3>You Have A New Service Request</h3>
			<p>'.$user_info[0]['name'].' has request that your property needs service. Click the link below to view the service request he has sent you.</p>
			<p>If you don\'t already have an account with us create and account and login to manage these service request along with all the other benefits of N4R.</p>
			<a href="'.base_url().'renters/viewing-service-request/'.$hash.'">View Service Request</a>
		
		';
		$subject = 'New Service Request From N4R';
		$alt_message = $user_info[0]['name'].' set you a service request from N4R.com click the link to view the request. http://therentguard.com/renters/renters/viewing_request?hash='.$hash;
		$this->load->model('special/send_email');
		$sent = $this->send_email->sendEmail($email, $message, $subject, $alt_message);
		
		$forwarding_email = $this->service_request_handler->get_forwarding_email($request_info[0]['landlord_id']);
		if(!empty($forwarding_email)) {
			$this->send_email->sendEmail($forwarding_email, $message, $subject);
		}
		
		
		if($sent == true) {
			$this->session->set_flashdata('success', 'This Service Request Has Been Re-Sent To '.$email);
		} else {
			$this->session->set_flashdata('error', 'Email Not Sent, Try Again');
		}
		redirect('renters/view-request/'.$id);
		exit;
	}
	
	function save_service_request() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);		
		
		if(empty($id)) {
			redirect('renters/view-request/'.$id);
			exit;
		}
		
		$this->output->set_template('blank');
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('renters/service_request_handler');
		$this->load->model('renters/user_model');
		
		$service_request = $this->service_request_handler->view_service_request($id);
		
		$data['user'] = $this->user_model->get_users_details();
		
		if(!empty($data['requests'][0]['group_id'])) {
			$get_bname = $this->service_request_handler->get_business_name_of_group($data['requests']['0']['group_id']);
			$data['landlord'] = $this->user_model->get_landlords_info($get_bname['sub_admins']);
		} else {
			$data['landlord'] = $this->user_model->get_landlords_info($data['user'][1]['link_id']);
		}
		
		$data['rental'] = $this->user_model->show_rental_address($data['requests'][0]['rental_id']);		
		$data['requests'] = $service_request;
		
		$html = $this->load->view('renters/print-service-request', $data, true); // Add Argument true after data
		pdf_create($html, 'Service_Reqeust_'.$data['landlord']['name']); 
	}
	
	public function printServiceRequest()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);		
		
		if(empty($id)) {
			redirect('landlords/view-service-request/'.$id);
			exit;
		}
	
		$this->output->set_template('blank');
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('special/ad_handler');
		$this->load->model('special/service_request_handler');
		$data = $this->service_request_handler->build_service_request($id);
		
		$ad_specs = array('service'=>$data['request']->service_type, 'zip'=>$data['rental']->zip, 'current_ads'=>$data['request']->ad_ids, 'request_id'=>$data['request']->id);
		$data['ad_post'] = $this->ad_handler->get_service_request_ads($ad_specs);	
		$data['suppliers'] = $this->ad_handler->getSupplyHouses($data['rental']->rental_zip, $data['request']->service_type); 
		
		$this->load->view('prints/service-request', $data); // Add Argument true after data
		$html = $this->load->view('prints/service-request', $data, true); // Add Argument true after data
		pdf_create($html, 'Service_Reqeust_'.$data['rental']->address);
	}
	
	
	
	function viewing_service_request($hash) 
	{
		$hash = $this->uri->segment(3);
		if(!empty($hash)) {
			$this->output->set_template('landlord-not-logged-in');
			
			$this->load->model('renters/service_request_handler');
			$this->load->model('renters/user_model');
			
			$data['requests'] = $this->service_request_handler->show_service_request_via_email($hash);
			
			if(!empty($data['requests'])) {
				$data['landlord'] = $this->user_model->get_landlords_info($data['requests']['landlord_id']);
				if(!empty($data['landlord'])) {
					if($data['requests']['viewed'] == '0000-00-00 00:00:00') {
						$tenant_info = $this->user_model->get_users_details($data['requests']['tenant_id']);
						$email = $data['requests']['email'];
						$subject = 'Your Landlord Has Viewed Your Service Request';
						$alt_message = 'Your Landlord Has Viewed Your Request On Network4Rentals.com';
						$message = '<h3>Your Landlord Viewed Your Request</h3>
										<p>You should be receiving a call or communication regarding this service request from your landlord.</p>
										<p>Click the link below to view the request.</br><a href="'.base_url().'renters/view-request/'.$data['requests']['id'].'">View Service Request</a></p>
									';
						$this->load->model('special/send_email');
						$this->send_email->sendEmail($email, $message, $subject, $alt_message = null);
						
						$activity = array('action'=>'Landlord Viewed Your Service Request', 'user_id'=>$data['requests']['tenant_id'], 'type'=>'renters', 'action_id'=>$data['requests']['id']);
						$this->user_model->add_activity($activity);	
					}
		
				}
			}
			
			if($this->session->userdata('side_logged_in') == '203020320389822') {
				redirect('contractors/view-service-request/'.$data['requests']['id']);
				exit;
			}
			
			$logedIn = $this->session->userdata('user_id');
			if(!empty($logedIn)) {
				redirect('landlords/view-service-request/'.$data['requests']['id']);
				exit;
			}

			$ad_specs = array('service'=>$data['requests']['service_type'], 'zip'=>$data['requests']['rental_zip']);
			$data['ad_post'] = $this->service_request_handler->get_service_request_ads($ad_specs);
			
			if(empty($data['requests'])) {
				$this->session->set_flashdata('error', 'You Can Only View The Message Once Before You Must Create An Account or Login. If You Decide To Create An Account Please Use The Same Email Address You Have Been Receiving These Links To In Order To Retain All That Data');
				redirect('landlords/login');
				exit;
			}		
			$this->load->view('renters/view-request-email', $data);
		} else {
			redirect('landlords/login');
			exit;
		}
	}
	
	function checklist_form() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$renter_history_row = (int)$this->uri->segment(3);
		if(!empty($renter_history_row)) {
			$this->session->set_userdata('renter_history_row', $renter_history_row);
		}

		$this->load->model('renters/checklist_handler');
		
		$row_info = $this->checklist_handler->get_renter_row_data($this->session->userdata('renter_history_row'));
		if($row_info == false) {
			$this->session->set_flashdata('error','Landlord Not Found, Try Again');
			redirect("renters/my-history");
			exit;	
		}
		
		if($this->session->userdata('renter_history_row') == '') {
			$this->session->set_flashdata('error','Landlord Not Found, Try Again');
			redirect("renters/my-history");
			exit;	
		}
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			
			foreach($_POST as $key => $val) {
				$data[$key] = $val;
			}
	
			$data['timeStamp'] = date('Y-m-d H:i:s');
			$data['tenant'] = $this->session->userdata('user_id');
			$data['landlord'] = $row_info['link_id'];
			$id = $this->checklist_handler->add_new_checklist($data);
			
			
			if(!empty($id)) {
				$this->session->set_flashdata('success','Check List Completed And Sent To Your Landlord');
				$this->load->model('renters/user_model');
				
				$landlord_info = $this->user_model->get_landlords_info($data['landlord']); // Landlord Id
				$email = $landlord_info['email'];
				
				$message = '
					<h3>One Of Your Tenants Completed Their Move-In Check List</h3>
					
					<p>Click the link below to view the check list<br> 
					<a href="'.base_url().'landlords/view-tenant-checklist/'.$id.'">'.base_url().'landlords/view-tenant-checklist/'.$id.'</a>
					</p>
				';
				$subject = 'One Of Your Tenants Completed A Move-In Check-List';
				$alt_message = 'One of your tenants have completed a check-list on Network 4 Rentals';
				$this->load->model('special/send_email');
				$this->send_email->sendEmail($email, $message, $subject, $alt_message = null);
				
				$info = array(
					'action' => 'Completed Rental Check-list',
					'user_id' => $this->session->userdata('user_id'),
					'type' => 'renters',
					'action_id' => $id
				);
				$this->user_model->add_activity($info);
				$info = array(
					'action' => 'Completed Rental Check-list',
					'user_id' => $row_info['link_id'],
					'type' => 'landlords',
					'action_id' => $id
				);
				$this->user_model->add_activity($info);
			} else {
				$this->session->set_flashdata('error','Something Went Wrong, Try Again');
			}
			
			
			
			
			$this->session->unset_userdata('renter_history_row', '');
			redirect("renters/activity");
			exit;
		} else {
			$check = $this->checklist_handler->check_for_submission($row_info['id']);
			if($check == 0) {
				$this->session->set_flashdata('error', 'No Landlord Found For That Check List');
				redirect('renters/my-history');
				exit;
			} elseif($check==1){
				$this->session->set_flashdata('error', 'This Landlord Is Not Set As Your Current Residence');
				redirect('renters/my-history');
				exit;
			} elseif($check==2){
				$this->session->set_flashdata('error', 'You Have Already Submitted A Check List For This Landlord');
				redirect('renters/my-history');
				exit;
			}
			
			$this->output->set_template('logged-in');
			$this->load->view('renters/checklist-form');
		}
	}
	
	function view_checklist() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->load->model('renters/checklist_handler');
		
		$checklist_id = (int)$this->uri->segment(3);
		$data['checklist_data'] = $this->checklist_handler->view_checklist($checklist_id);
		if($data['checklist_data'] == false) 
		{
			$this->session->set_flashdata('error', 'Could Not Find Checklist, Try Again');
			redirect('renters/my-history');
			exit;
		}
		
		$this->output->set_template('logged-in');
		$this->load->view('renters/view-checklist', $data);
	}
	
	function pay_rent()
	{	
		$this->load->js('assets/themes/default/js/jquery.validate.min.js');
		$this->load->js('assets/themes/default/js/additional-methods.min.js');
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->js('assets/themes/default/js/jquery.formatCurrency-1.4.0.min.js');
		$this->load->js('assets/themes/default/js/renters/payment.js');
		
		$this->load->js('assets/themes/default/js/jquery.bank.js');
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->load->model('renters/payment_handler');
		$this->load->model('renters/user_model');
		
		$data['landlord_info'] = $this->user_model->get_current_landlord_info();
		$data['renter_info'] = $this->user_model->get_users_details();
		if($data['renter_info'][1]['auto_pay'] == 'y') {
			$data['autopayment_info'] = $this->user_model->subscription_details($data['landlord_info']['id'], $data['renter_info'][1]['id']);
		}
		
		if($data['landlord_info'] == false) {
			$this->session->set_flashdata('error', 'None Of Your Landlords Are Set To Current Residence. Add Your Landlord Or Edit An Existing Landlord');
			redirect('renters/my-history');
			exit;
		}
		
		$data['payment_settings'] = $this->payment_handler->check_payment_settings($data['landlord_info']['id'], $data['landlord_info']['groupId']);
		
		$this->output->set_template('logged-in');
	
		$this->load->view('renters/add-landlord-payment', $data);
	}
	
	function pay_rent_by_check()
	{
		$this->form_validation->set_rules('name', 'Name','required|trim|min_length[2]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('route_num', 'Routing Number','required|trim|min_length[9]|max_length[9]|xss_clean|numeric');
		$this->form_validation->set_rules('account_num', 'Account Number','required|trim|min_length[3]|max_length[20]|xss_clean|numeric');
		$this->form_validation->set_rules('amount', 'Rent Amount','required|trim|min_length[1]|max_length[8]|xss_clean');
		$this->form_validation->set_rules('bank_name', 'Bank Name','required|trim|min_length[2]|max_length[70]|xss_clean');
		$this->form_validation->set_rules('payment_type', 'Account Payment Type','required|trim|min_length[1]|max_length[1]|xss_clean|integer');
		$this->form_validation->set_rules('autopay', 'Bank Name','trim|max_length[1]|xss_clean|alpha');
		if($_POST['autopay'] == 'y') {
			$this->form_validation->set_rules('understand', 'Understand How Auto Pay Works','trim|max_length[1]|xss_clean|alpha|required');
			$this->form_validation->set_rules('start_date', 'Payments Start Date','trim|max_length[10]|max_length[10]|xss_clean|required');
		}
		
		if($this->form_validation->run() == TRUE) {
	
			extract($_POST);

			if($payment_type == '1') {
				$payment_type = 'checking';
			} else {
				$payment_type = 'savings';
			}
			
			if($autopay == 'y') {
				$auto = TRUE;
			} else {
				$auto = FALSE;
				$autopay = 'n';
			}
			
			$this->load->model('renters/payment_handler');
			
			$payment_settings = $this->payment_handler->get_landlords_payment_data();
			
			$this->load->model('renters/payment_handler');
			$this->load->model('renters/user_model');
			
			$this->load->library('encrypt');
			
			$net_api = $this->encrypt->decode($payment_settings->net_api);
			$net_key = $this->encrypt->decode($payment_settings->net_key);
			
			$user_details = $this->user_model->get_users_details();
			
			if(!empty($net_api) || !empty($net_key)) {
				$name_array = explode(' ', $name);
				$amount = $amount+5;
				if($autopay == 'n') { //If autopay has not been selected
					$action = 'Made One Time Rent Payment';
					$payment_values = array(
						'x_amount' => number_format($amount, 2),
						'x_method' => 'echeck',
						'x_bank_aba_code' => $route_num,
						'x_bank_acct_num' => $account_num,
						'x_bank_acct_type' => $payment_type,
						'x_bank_name' => $bank_name,
						'x_bank_acct_name' => $name,
						'x_echeck_type' => 'WEB',
						'x_login' => $net_api,
						'x_tran_key' => $net_key,
						'x_recurring_billing' => $auto,
						'x_delim_data' => TRUE,
						'x_relay_response' =>FALSE,
						'x_first_name'			=> $name_array[0],
						'x_last_name'			=> end($name_array),
						'x_address'				=> $user_details[1]['rental_address'],
						'x_city'				=> $user_details[1]['rental_city'],
						'x_state'				=> $user_details[1]['rental_state'],
						'x_zip'					=> $user_details[1]['rental_zip'],
						'x_country'				=> 'US',
						'x_customer_ip'			=> $_SERVER['SERVER_ADDR'],
					);
				
					$payment_data = $this->payment_handler->create_one_time_payment($payment_values);
					
				} else { // Autopay selected use ARB instead of sim		
					$action = 'Set-up Auto Rent Payments';
					$auth_data = array('id'=>$net_api, 'key' => $net_key);
					$payment_values = array(
							'amount' => number_format($amount, 2),
							'routingNumber' => $route_num,
							'accountNumber' => $account_num,
							'accountType' => $payment_type,
							'bankName' => $bank_name,
							'nameOnAccount' => $name,
							'echeckType' => 'WEB',
							'address' => $user_details[1]['rental_address'],
							'city' => $user_details[1]['rental_city'],
							'state' => $user_details[1]['rental_state'],
							'zip' => $user_details[1]['rental_zip'],
							'startDate' => date('Y-m-d', strtotime($start_date)),
					);
					
					$payment_data = $this->payment_handler->create_auto_payment($payment_values, $auth_data);
		
					
				}
				
			
				if(isset($payment_data['success']))
				{
					//Payment Recorded into payment history
					$info = array(
						'amount' 		=> number_format($amount, 2),
						'status' 		=> 'Pending',
						'ref_id' 		=> $user_details[1]['id'],
						'tenant_id' 	=> $this->session->userdata('user_id'),
						'payment_type' 	=> 'E-Check',
						'entered_by'	=> '1',
						'landlord_id' 	=> $user_details[1]['link_id'],
						'trans_id'		=> $payment_data['success'],
						'created'		=> date('Y-m-d'),
						'paid_on'		=> date('Y-m-d'),
						'routing_num'   => $this->encrypt->encode($route_num),
						'bank_name'		=> $this->encrypt->encode($bank_name), 
						'sub_id'		=> $payment_data['success'],
						'recurring_payment' => $autopay,
						'auto_paid'		=> $autopay,
						'start_date'		=> date('Y-m-d', strtotime($start_date)),
					);
					$log_id = $this->payment_handler->record_payment($info);
					
					//Email Both All People
					$user_email = $user_details[0]['email']; //SEND DIFFERENT EMAIL TO TENANT
					$landlord_details = $this->user_model->get_landlords_info($user_details[1]['link_id']);
					$landlord_email = $landlord_details['email'];	 //MAIN LANDLORDS EMAIL
					$activity_id = $landlord_details['id']; //SAVE LANDLORDS IDS INTO ARRAY TO INSERT THE PAYMENT INTO THE ACTIVITY
					if(!empty($user_details[1]['group_id'])) {
						$sub_amdin = $this->user_model->get_sub_admin_id($user_details[1]['group_id']);
						$sub_admin_details = $this->user_model->get_landlords_info($sub_amdin);
						$landlord_email = $sub_admin_details['email'];
						$activity_id = $sub_admin_details['id']; //SAVE MANAGER ID INTO ARRAY TO INSERT THE PAYMENT INTO THEIR ACTIVITY
					}		
					if($autopay =='y') {
						$start_date = '<tr><td><b>Payment Scheduled:</b></td><td>'.$start_date.'</td></tr>';
					}
					$subject = 'Successfully Submitted Your Rent Using N4R';
					$message = '<h3>Your Rent Payment Has Been Submitted On-line With N4R</h3>
						<p><b>'.$user_details[0]['name'].'</b></p>
						<p> Thanks for using N4R to pay your rent on-line using our e-check feature. Keep this email for your records to show proof of your payment submission. </p>
						<table frame="box">
							<tr>
								<td><b>Name On Check:</b></td>
								<td>'.$name.'</td>
							<tr>
							<tr>
								<td><b>Transaction Id:</b></td>
								<td>'.$payment_data['success'].'</td>
							</tr>
							<tr>
								<td><b>Address:</b></td>
								<td>'.$user_details[1]['rental_address'].' '.$user_details[1]['rental_city'].' '.$user_details[1]['rental_state'].' '.$user_details[1]['rental_zip'].'</td>
							</tr>
							<tr>
								<td><b>Submitted On:</b></td>
								<td>'.date('m-d-Y').'</td>
							</tr>
							'.$start_date.'
							<tr>
								<td><b>Amount:</b></td>
								<td>$'.number_format($amount, 2).'</td>
							</tr>
							<tr>
								<td><b>Status:</b></td>
								<td>Pending</td>
							</tr>
						</table>
						<p>
							Login to you account to view the details of this transaction.<br><a href="https://network4rentals.com/network/renters/login">Login Now</a>
						</p>
					';	
					$this->load->model('special/send_email');
					$this->send_email->sendEmail($user_email, $message, $subject);
					
					//Attempt to notify landlord of payment by sms
					/*	$this->load->model('renters/sms_handler');
						$link = base_url.'landlords/view-tenant-info/'.$user_details[1]['id'];
						$msg = 'One of your tenants has made a rent payment to you: '.$link;
						$this->sms_handler->send_sms($user_details[1]['link_id'], $msg);
					*/
				
					/* Send Action To Tenant Activity Feed */
					$data = array('action'=>$action, 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>$log_id, 'group_id'=>$user_details[1]['group_id']);
					$this->user_model->add_activity($data); 
				
					$data = array('action'=>'Tenant '.$action, 'user_id'=>$activity_id, 'type'=>'landlords', 'action_id'=>$log_id, 'group_id'=>$user_details[1]['group_id']);
					$this->user_model->add_activity($data); 
					
					$this->session->set_flashdata('success', 'Your Payment Has Processed, You Will Receive A Confirmation Email Along With Your Landlord Of Your Payment.');
					redirect('renters/view-payment-history/'.$user_details[1]['id']);
					exit;
				}
				else
				{
					$this->session->set_flashdata('error', $payment_data['error']);
				}
			} else {
				$this->session->set_flashdata('error', 'Your Landlord Has Not Fully Set Up There Account With The Proper Settings, Contact Them And Let Them Know About This Error');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		
		redirect('renters/pay-rent');
		exit;
	}
	
	function view_message_landlord()
	{
		$this->output->set_template('landlord-not-logged-in');
		$this->session->unset_userdata('temp_id');
		$hash = $this->uri->segment(3);
		$hash = preg_replace("/[^ \w]+/", "", $hash);
		if(!empty($hash)) {
			$this->load->model('special/messaging_handler');
			
			$data['chat'] = $this->messaging_handler->get_hashed_message($hash);

			if(!empty($data['chat'])) {
				if($data['chat']['landlord_viewed'] == '0000-00-00 00:00:00') {
					$this->messaging_handler->update_timestamp_landlord_viewed($data['chat']['id']);
					if(!empty($data['chat']['tenants_email'])) {
					
						$subject = 'Your Landlord Has Viewed One Of Your Messages';
						$alt_message = 'Your Landlord Has Viewed One Of Your Messages';
						$message = '<h3>Your Landlord Has Viewed One Of Your Messages</h3>
							<p></p>
							<p>Click the link below to view the Message. </br> <a href="'.base_url().'renters/view-messages/'.$data['chat']['rental_id'].'">View Message</a></p>
						';
						$this->load->model('special/send_email');
						$this->send_email->sendEmail($data['chat']['tenants_email'], $message, $subject, $alt_message = null);
						
					}
					//Add Activity To Landlord
					$datas = array(
						'action' => 'Landlord Viewed Your Message', 
						'user_id' => $data['chat']['tenant_id'], 
						'type'=>'renters', 
						'action_id' => $data['chat']['rental_id']
					);
					$this->load->model('renters/user_model');
					$this->user_model->add_activity($datas);
				}
				
				$check = $this->session->userdata('user_id');
				if(!empty($check)) { 
					redirect('landlords/message-tenant/'.$data['chat']['rental_id']);
					exit;
				}
				
				$this->load->view('renters/view-message-landlord', $data);
			} else {
				$this->session->set_flashdata('error', 'Please login to your account to view your message.');
				redirect('landlords/login');
				exit;
			}
		} else {
			$this->session->set_flashdata('error', 'You have already viewed this message, if you would like to view the message again you have to create a free account with the email address that you received the email to.');
			redirect('landlords/login');
			exit;
		}
	}
	
	function view_message_email()
	{
		$this->output->set_template('tenants/not-logged-in');
		$hash = $this->uri->segment(3);
		$hash = preg_replace("/[^ \w]+/", "", $hash);
		if(!empty($hash)) {
			$this->load->model('special/messaging_handler');
			$data['chat'] = $this->messaging_handler->get_hashed_message($hash, 'y');
			$this->output->enable_profiler(TRUE);

			$side = $this->session->userdata('side_logged_in');
		
			if($side === '898465406540564') {
				redirect('renters/view-messages/'.$data['chat']['rental_id']);
				exit;
			}
				
			if(!empty($data['chat'])) {
				if($data['chat']['landlord_viewed'] == '0000-00-00 00:00:00') {
					$this->load->model('special/send_email');
					$this->messaging_handler->update_timestamp_landlord_viewed($data['chat']['id']);
					if(!empty($data['chat']['tenants_email'])) {
					
						$subject = 'Your Landlord Has Viewed One Of Your Messages';
						$alt_message = 'Your Landlord Has Viewed One Of Your Messages';
						$message = '<h3>Your Landlord Has Viewed One Of Your Messages</h3>
							<p></p>
							<p>Click the link below to view the Message. </br> <a href="'.base_url().'renters/view-messages/'.$data['chat']['rental_id'].'">View Message</a></p>
						';
						$this->send_email->sendEmail($data['chat']['tenants_email'], $message, $subject, $alt_message = null);
						
					}
					//Add Activity To Landlord
					$datas = array(
						'action' => 'Landlord Viewed Your Message', 
						'user_id' => $data['chat']['tenant_id'], 
						'type'=>'renters', 
						'action_id' => $data['chat']['rental_id']
					);
					$this->load->model('renters/user_model');
					$this->user_model->add_activity($datas);
				}
				
				
				
				$this->load->view('renters/view-message-renters', $data);
			} else {
				$this->session->set_flashdata('error', 'Please login to your account to view your message.');
				redirect('renters/login');
				exit;
			}
		} else {
			$this->session->set_flashdata('error', 'You have already viewed this message, if you would like to view the message again you have to create a free account with the email address that you received the email to.');
			redirect('renters/login');
			exit;
		}
	}	
	
	function terms_of_service()
	{
		$this->output->set_template('landlord-not-logged-in');
		$this->load->view('landlords/terms-of-service.php');
	}
	
	function view_messages() 
	{
		$this->output->set_template('logged-in');
		$this->check_if_loggedin();
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->session->set_userdata('date_to_msg', $_POST['date_to']);
			$this->session->set_userdata('date_from_msg', $_POST['date_from']);
		}
		
		$args = array(
			'rental_id' => (int)$this->uri->segment(3),
			'type' 		=> 'renter',
			'offset' 	=> (int)$this->uri->segment(4)
		);
		
		$this->load->model('special/messaging_modal');
		$data = $this->messaging_modal->show_messages($args);
		if(empty($data['message_to'])) {
			redirect('renters/message-landlords');
			exit;
		}
		
		$this->load->view('renters/view-messages', $data);
	}
	
	function send_new_message()
	{
	
		$this->check_if_loggedin();
		$this->form_validation->set_rules('message', 'Message', 'trim|max_length[700]|xss_clean|required');
		if($this->form_validation->run() == TRUE) {
			$this->load->model('special/messaging_modal');
			
			$args = array(
				'rental_id' => (int)$this->uri->segment(3),
				'type' 		=> 'renter',
				'msg' 		=> $_POST['message'],
				'file' 		=> $_FILES['file'] 
			);
			$this->messaging_modal->build_message($args);
		} else {
			$this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
		}
		redirect('renters/view-messages/'.$args['rental_id']);
		exit;
	}

	public function reset_dates_msg($id) // Resets Dates In The Activity Page
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->session->unset_userdata('date_to_msg');
		$this->session->unset_userdata('date_from_msg');
		$this->session->set_flashdata('reset', '<div class="alert alert-success"><p><b>Success:</b> Dates have been reset</p></div>');
		redirect('renters/view-messages/'.$id);
		exit; 
	}	
		
	function print_messages() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$rental_id = (int)$this->uri->segment(3);
		$this->load->model('special/messaging_handler');
		$data['results'] = $this->messaging_handler->print_messages($rental_id);
		
		$data['user_data'] = $this->messaging_handler->user_data($rental_id);
		
		$this->output->set_template('blank');
		$this->load->helper(array('dompdf', 'file'));
		$html = $this->load->view('renters/print-messages', $data, true); // Add Argument true after data
		pdf_create($html, 'Printed Messages From N4R'); 
	}	
	
	public function send_sms($data)
    {
        $this->load->library('plivo');
        $sms_data = array(
            'src' => '13525593099',
            'dst' => '1'.$data['phone_to'],
            'text' => $data['message'],
            'type' => 'sms', 
            'url' => base_url().'landlords/'.$data['page'].'/',
            'method' => 'POST',
        );

        $response_array = $this->plivo->send_sms($sms_data);
        if ($response_array[0] == '200' || $response_array[0] == '202') {
            $data["response"] = json_decode($response_array[1], TRUE);
			return true;
			exit;
        } else {
			return false;
        }
    }
	
	public function text_code_conformation()
	{
		$this->form_validation->set_rules('text_code', 'Text Message Code', 'required|trim|min_length[4]|max_length[15]|xss_clean|alphanumeric');
		if($this->form_validation->run() == TRUE) 
		{
			extract($_POST);
			$this->load->model('renters/create_user_model');
			$tenant_id = $this->create_user_model->check_text_code($text_code);
			if($tenant_id>0) {
						
				//////////////NEW 4.15.2015//////////////////
				$data = $this->create_user_model->sms_verification_data($tenant_id);
		
				$message = '<h3>'.$data->name.'</h3>';
				$message .= 'A new tenant has linked to your account on Network 4 Rentals. Once you login go to my tenants and you will see the tenants details.<br><a href="'.base_url().'landlords/login">Login</a>';
				$subject = "New Tenant Linked To You On N4R";
				$this->sendEmail($data->email, $message, $subject, $message);
				
				$this->load->model('renters/user_model');	

				$renterName = $this->user_model->getUsersFullName($this->session->userdata('user_id'));						
				$data = array('action'=>'New Tenant Linked To You<br><b><small>'.$renterName.'</small></b>', 'user_id'=>$data->link_id, 'type'=>'landlords', 'action_id'=>$data->id, 'group_id'=>$data->group_id);
				$this->user_model->add_activity($data);
				 
				//Attempt to notify landlord of link by sms
				$this->load->model('renters/sms_handler');
				$link = base_url().'landlords/view-tenant-info/'.$data->id;
				$msg = 'New tenant linked to you on N4R: '.$link;
				$this->sms_handler->send_sms($data->link_id, $msg);
				///////////////////NEW/////////////////////
				
				
				
				$this->session->set_flashdata('success', 'You account has been verified and you can now login');
				redirect('renters/login');
				exit;
			} else {
				$this->session->set_flashdata('error', 'Invalid Code, Try Again');
				redirect('renters/account-created');
				exit;
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
			redirect('renters/account-created');
			exit;
		}
	}
	
	public function my_landlord() 
	{
		$this->output->set_template('tenants/create-account-template');
		$unique_name = $this->uri->segment(3);
		$this->load->model('renters/create_user_model');
		
		$data['landlord'] = $this->create_user_model->capture_landlord_details($unique_name);
		
		if(empty($data['landlord'])) {
			$this->session->set_flashdata('error', 'Landlord not found, check the link and try again. If you need help search <a href="http://n4r.rentals/">here</a> and click the link to landlord button once you find them.</a>');
		}
		
		$data['subs'] = $this->create_user_model->getMangerAccounts($data['landlord']->landlord_id);
		
		$this->load->view('renters/create-account-simplified-with-landlord', $data);
	}
	
	function create_account()
	{	
		$this->load->helper('cookie');
		$cookie = $this->input->cookie('logged_in');
		if($cookie==1) {
			redirect('renters/activity');
			exit;
		}
		if($this->session->userdata('logged_in'))
		{
			redirect('renters/activity');
		}
	
		$this->output->set_template('default');
		$this->load->js('assets/themes/default/js/notify.min.js');
		$this->load->js('assets/themes/default/js/search-landlords.js');
		$this->load->js('assets/themes/default/js/custom.js');
	


		//Form Validations
		$this->form_validation->set_rules('bName', 'Business Name', 'trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('landlord_name', 'Landlord/Contact Name', 'trim|max_length[100]|xss_clean|required');
		$this->form_validation->set_rules('landlord_email', 'Landlord/Contact Email', 'trim|max_length[200]|xss_clean|valid_email');
		$this->form_validation->set_rules('landlord_phone', 'Landlord/Contact Phone', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('landlord_cell', 'Landlord Cell Phone', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('landlord_address', 'Landlord Address', 'trim|max_length[150]|xss_clean');
		$this->form_validation->set_rules('landlord_city', 'Landlord City', 'trim|max_length[60]|xss_clean|required');	
		$this->form_validation->set_rules('landlord_state', 'Landlord State', 'trim|max_length[2]|xss_clean|required');
		$this->form_validation->set_rules('landlord_zip', 'Landlord Zip', 'trim|max_length[10]|xss_clean|numeric|required');
		$this->form_validation->set_rules('link_id', '', 'trim|max_length[11]|xss_clean|numeric');
		$this->form_validation->set_rules('group_id', 'Select Your Landlord Again', 'trim|max_length[15]|xss_clean');
		
		$this->form_validation->set_rules('payments', 'Rent Per Month', 'trim|max_length[60]|xss_clean|numeric|required');
		$this->form_validation->set_rules('lease', '', 'trim|max_length[60]|xss_clean|required');
		$this->form_validation->set_rules('rental_address', 'Rental Address', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('rental_city', 'Rental City', 'required|trim|max_length[60]|xss_clean');
		$this->form_validation->set_rules('rental_state', 'Rental State', 'required|trim|max_length[2]|xss_clean');
		$this->form_validation->set_rules('rental_zip', 'Rental Zip', 'required|trim|max_length[10]|xss_clean|alpha_dash');
		$this->form_validation->set_rules('move_in', 'Move In Date', 'required|trim|max_length[15]|xss_clean');
		
		$this->form_validation->set_rules('fullname', 'Full Name', 'trim|max_length[50]|xss_clean|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[50]|xss_clean|required|valid_email');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[16]|xss_clean|required');
		$this->form_validation->set_rules('hear', 'How you heard about us', 'trim|max_length[40]|xss_clean|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|max_length[40]|xss_clean|required|is_unique[renters.user]');
		$this->form_validation->set_rules('password', 'Password', 'trim|max_length[35]|xss_clean|required|matches[password1]');
		$this->form_validation->set_rules('terms', 'Agree To Terms', 'trim|max_length[3]|xss_clean|required');
		$this->form_validation->set_rules('deposit', 'Deposit', 'trim|max_length[5]|xss_clean|required|integer');
		
		$this->form_validation->set_rules('day_rent_due', 'Rents Due On', 'trim|max_length[2]|xss_clean|required|integer');
		
		$this->form_validation->set_rules('sms_msgs', 'SMS Messages', 'trim|max_length[1]|xss_clean|required');
		$this->form_validation->set_rules('cell_phone', 'Cell Phone', 'trim|max_length[15]|xss_clean');
	
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			$form_error = false;
			
			if(empty($bName) && empty($landlord_name)) {
				$this->session->set_flashdata('error', 'Business Name Or Landlord Name For The Landlord Is Required');
				$form_error = true;
			}
			if(empty($landlord_email) && empty($landlord_phone)) {
				$this->session->set_flashdata('error', 'Landlords Phone Or Landlords Email Is Required');
				$form_error = true;
			}
			if(!empty($landlord_phone) && empty($landlord_email)) {
				$landlord_phone = preg_replace("/[^0-9]/", '', $landlord_phone);
				if(strlen($landlord_phone) == 10) {
					$check = $this->turn_phone_into_email($landlord_phone);
					if($check != false) {
						$landlord_email = $check;
					}
				} else {
					$this->session->set_flashdata('error', 'Landlords Phone Must Be 10 Digits Long');
					$form_error = true;
				}
			}
			if(!empty($landlord_cell) && empty($landlord_email)) {
				$landlord_cell = preg_replace("/[^0-9]/", '', $landlord_cell);
				if(strlen($landlord_phone) == 10) {
					$check = $this->turn_phone_into_email($landlord_cell);
					if($check != false) {
						$landlord_cell = $check;
					}
				} else {
					$this->session->set_flashdata('error', 'Landlords Phone Must Be 10 Digits Long');
					$form_error = true;
				}
			}
			
			if($form_error == true) {
				redirect('renters/add-landlord');
				exit;
			}
			$move_in = date('Y-m-d', strtotime($move_in));
			
			$landlord_email = strtolower($landlord_email);
			$landlord_name = ucwords(strtolower($landlord_name));
			$bName = ucwords(strtolower($bName));
			$fullname = ucwords(strtolower($fullname));
			
			$phone = preg_replace("/[^0-9,.]/", "", $phone);
			$landlord_phone = preg_replace("/[^0-9,.]/", "", $landlord_phone);
			$landlord_cell = preg_replace("/[^0-9,.]/", "", $landlord_cell);
			$cell_phone = preg_replace("/[^0-9,.]/", "", $cell_phone);
	
			$browser_info = '';
			
			// TEXT MESSAGE CODE
			$code = '';
			if($sms_msgs == 'y') {
				$code = substr(md5('492fajdfa49'.$username), 0, rand(5, 8));
				$sms['phone_to'] = $cell_phone;
				$sms['message'] = 'Network4Rentals verification code: '.$code.'. http://network4rentals.com/network/renters/account-created';
				$sms['page'] = 'create_user_account';
				
				$this->session->set_userdata('cell', $cell_phone);
				$this->session->set_userdata('sms', $sms_msgs);
				$this->session->set_userdata('sms_msg', $sms['message']);
				
				$this->send_sms($sms);
			}
			$email_hash = md5($_SERVER['REMOTE_ADDR'].$username);
			$this->session->set_userdata('hash', $email_hash);
			
			$user_account = array(
				'user'			=>$username,
				'pwd'			=>md5($password),
				'email'		=>$email,
				'loginHash'	=> $email_hash, 
				'ip'			=>$_SERVER['REMOTE_ADDR'],
				'name'		=> $fullname,
				'phone'		=> $phone,
				'terms'		=> 'y',
				'sign_up'	=> date('Y-m-d h:i:s'),
				'hear'			=> $hear,
				'browser_info'	=> $browser_info,
				'cell_phone' => $cell_phone,
				'sms_msgs' => $sms_msgs,
				'text_msg_code' => $code
			);
			
			$landlord_data = array(
				'name' => $landlord_name, 
				'phone' => $landlord_phone, 
				'email' => $landlord_email, 
				'address' => $landlord_address, 
				'city' => $landlord_city, 
				'zip' => $landlord_zip, 
				'bName' => $bName,
				'cell'=>$landlord_cell,
				'state'=>$landlord_state
			);

			$rental_data = array(
					'deposit'=>$deposit, 
					'rental_state' => $rental_state, 
					'link_id' => $link_id, 
					'rental_address' => ucwords(strtolower($rental_address)), 
					'rental_city' => $rental_city,
					'rental_zip' => $rental_zip, 
					'move_in' => $move_in,
					'payments' => $payments, 
					'lease' => $lease, 
					'current_residence' => 'y', 
					'lease_upload' => $file, 
					'group_id' => $group_id, 
					'created' => date('Y-m-d h:i:s'),
					'day_rent_due' => $day_rent_due
			);
			
			// Boolean values to check if data was created in the right tables
			$rentalCreated = true;
			$landlordCrearted = true;
			$userCreated = true;
			
			$this->load->model('renters/create_user_model');
			// Created user will return false if not inserted and a user id if inserted
			$createdUser = $this->create_user_model->create_account($user_account);
			if($createdUser>0) {
				// if link id is empty create landlord account place-holder
				if(empty($link_id)) {
					$landlordInfo = $this->create_user_model->add_landlord_hold($landlord_data);
					if($landlordInfo>0) {
						// add landlord id to the rental property
						$rental_data['link_id'] = $landlordInfo;
					} else {
						$landlordCrearted = false;
					}
				}
				if($landlordCreated === false) {
					$rentalCreated = false;
				} else {
					$rental_data['tenant_id']=$createdUser;
					// rentalInfo will be a boolean value checking for inserted or not
					$rentalInfo = $this->create_user_model->add_rental_details($rental_data);
				}
			} else {
				$userCreated = false;
			}
			
			
			if($userCreated) {
				$this->load->model('special/send_email');
				if($landlordCrearted) {
					if($rentalCreated) {
						$this->session->set_flashdata('success', 'You are almost done, now confirm your account and your ready to use N4R');
						$message = '<h3>'.$fullname.'</h3><p>Your account has been created, click <a href="'.base_url().'renters/account_verified/'.md5($_SERVER['REMOTE_ADDR'].$username) .'">here</a> to verify your email address.</p>';
						$subject = "N4R | Account Created";
						
						$this->session->set_userdata('user_email', $user_account['email']);
						$this->session->set_userdata('message', $message);
						$this->session->set_userdata('subject', $subject);
						$alt_message = '';
						
						$this->send_email->sendEmail($user_account['email'], $message, $subject, $alt_message);
						redirect('renters/account-created');
						exit;
					} else {
						$this->session->set_flashdata('warning', 'Your account was setup but linking to your landlord didn\'t work. After you confirm your email login and ad your landlord through "My Rental History"');
						$this->load->view('renters/account-created');
						$message = '<h3>'.$fullname.'</h3><p>Your account has been created, click <a href="'.base_url().'renters/account_verified/'.md5($_SERVER['REMOTE_ADDR'].$username) .'">here</a> to verify your email address.</p>';
						$subject = "N4R | Account Created";
						
						$this->session->set_userdata('user_email', $user_account['email']);
						$this->session->set_userdata('message', $message);
						$this->session->set_userdata('subject', $subject);
						$alt_message = '';
						$this->send_email->sendEmail($user_account['email'], $message, $subject, $alt_message);
						redirect('renters/account-created');
						exit;
					}
				} else {
					$this->session->set_flashdata('warning', 'Your account was setup but linking to your landlord didn\'t work. After you confirm your email login and ad your landlord through "My Rental History"');
					$message = '<h3>'.$fullname.'</h3><p>Your account has been created, click <a href="'.base_url().'renters/account_verified/'.md5($_SERVER['REMOTE_ADDR'].$username) .'">here</a> to verify your email address.</p>';
					$subject = "N4R | Account Created";
					
					$this->session->set_userdata('user_email', $user_account['email']);
					$this->session->set_userdata('message', $message);
					$this->session->set_userdata('subject', $subject);
					$alt_message = '';
					$this->send_email->sendEmail($user_account['email'], $message, $subject, $alt_message);
					redirect('renters/account-created');
					exit;
				}
			} else {
				$this->session->set_flashdata('error', 'Account creation failed, try again');
			}
		}
		
		$this->load->view('renters/add-landlord-steps');
	}
	
	function link_landlord_rentals()
	{
		$name = (string)$this->uri->segment(3);
		$cookie = array(
			'name'   => 'rental',
			'value'  => $name,
			'expire' => '86500',
			'domain' => '.network4rentals.com',
			'path'   => '/',
			'secure' => TRUE
		);
		
		$this->input->set_cookie($cookie);		
		$this->output->set_template('logged-in');
		
		$side = $this->session->userdata('side_logged_in');
		
		if($side === '898465406540564') {
			
			if(empty($name)) {
				$this->session->set_flashdata('error', 'Landlord Not Found, Try Searching For The Landlord In The Search Box Below');
				redirect('renters/add-landlord');
				exit;
			} else {
				$this->check_if_loggedin();
				$this->load->model('renters/landlord_handler');
				$data['landlord_info'] = $this->landlord_handler->check_link_name($name);
				if($data['landlord_info']!=false) {
					$data['properties'] = $this->landlord_handler->get_landlord_properties($data['landlord_info']->id);
					$this->load->view('renters/link-to-landlord', $data);
				} else {
					$this->session->set_flashdata('error', 'Landlord Not Found, Try Searching For The Landlord In The Search Box Below');
					redirect('renters/add-landlord');
					exit;
				}
			}
			
		} else {
			$this->session->set_flashdata('error', 'You must login or create an account before you can add your landlord. Once you create your account your landlord details will auto populate');
			redirect('renters/login');
			exit;
		}
	}
	
	function videos()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		$this->load->view('renters/videos');
	}
	
	function cancel_auto_pay()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		
		$payment_id = (int)$this->uri->segment(3);
		if(!empty($payment_id)) {
			$this->load->model('renters/payment_handler');
			$return = $this->payment_handler->cancel_rent_auto_pay($payment_id);
			$this->session->set_flashdata($return);
			redirect('renters/view-payment-details/'.$payment_id);
			exit;
		} else {
			$this->session->set_flashdata('error', 'Cancel payments failed, try again');
			redirect($_SERVER["HTTP_REFERER"]);
			exit;
		}
	}
	
	function link_submitted_landlord()
	{
		$unique_name = (string)$this->uri->segment(3);
		$this->check_if_loggedin(); // Checks if user is logged in
		
		$this->form_validation->set_rules('check_for_sub', 'Landlord', 'required|trim|max_length[11]|xss_clean|required|numeric');
		$this->form_validation->set_rules('rental_id', 'Rental', 'required|trim|max_length[11]|xss_clean|numeric');
		$this->form_validation->set_rules('rental_address', 'Rental Address', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('rental_city', 'Rental City', 'required|trim|max_length[60]|xss_clean');
		$this->form_validation->set_rules('rental_state', 'Rental State', 'required|trim|max_length[2]|xss_clean');
		$this->form_validation->set_rules('rental_zip', 'Rental Zip', 'required|trim|max_length[10]|xss_clean|alpha_dash');
		$this->form_validation->set_rules('move_in', 'Move In Date', 'required|trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('move_out', 'Move Out Date', 'trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('deposit', 'Deposit', 'trim|max_length[5]|xss_clean|required|integer');
		$this->form_validation->set_rules('lease', '', 'trim|max_length[60]|xss_clean|required');
		$this->form_validation->set_rules('payments', 'Rent Per Month', 'trim|max_length[60]|xss_clean|numeric|required');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			$landlord_id = $check_for_sub;
			$form_error = false;
			if(isset($_FILES)) { // File Upload Handler
				if(!empty($_FILES['file']['name'])) {
					$config['upload_path'] = './lease-uploads/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|PNG|JPG|JPEG|docx';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					 
					$file = "file";
					
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
					} else {
						$form_error = true;
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				} else {
					$file = '';
				}
			} 
			
			if($form_error == true) {
				redirect('renters/link-landlord-rentals/'.$unique_name);
				exit;
			}
			$move_in = date('Y-m-d', strtotime($move_in));
			if(!empty($move_out)) {
				$move_out = date('Y-m-d', strtotime($move_out));
			}

			
			$this->load->model('renters/landlord_handler');
			$landlord_info = $this->landlord_handler->check_link_name($unique_name);
			if($landlord_info!=false) {
				$this->load->model('renters/renter_history_handler');
				
				
				
				$user_id = $this->session->userdata('user_id');
				
				$form_input = array(
					'bName' => $landlord_info->bName, 
					'deposit'=>$deposit, 
					'landlord_name' => $landlord_info->name, 
					'landlord_phone' => $landlord_info->phone, 
					'landlord_email' => $landlord_info->email, 
					'landlord_address' => $landlord_info->address, 
					'landlord_city' => $landlord_info->city, 
					'rental_state' => $rental_state, 
					'zip' => $landlord_info->zip, 
					'link_id' => $landlord_id, 
					'rental_address' => ucwords(strtolower($rental_address)), 
					'rental_city' => $rental_city, 
					'rental_zip' => $rental_zip, 
					'move_in' => $move_in, 
					'move_out' => $move_out, 
					'tenant_id' => $user_id, 
					'payments' => $payments, 
					'lease' => $lease, 
					'current_residence' => 'y', 
					'lease_upload' => $file,
					'timestamp'=>date('Y-m-d h:i:s'),
					'address_locked' => 0
				);
	
				$added = $this->renter_history_handler->add_landlord($form_input);
				if($added == true) {	
					if($form_input['current_residence'] == 'y') {
						$this->load->model('special/send_email');
						$email = $form_input['landlord_email'];
						$message = '<h3>New Tenant Linked To You</h3>
							One of your tenants has linked to you account and ready to communicate with you through our network.
						';
						$message .= '<p>You can verify their info by visiting the link and clicking "verify tenant".</p><p>
						<a href="'.base_url().'landlords/view-tenant-info/'.$added.'">View Tenant Info</a></p>';
						$subject = 'A New Tenant Has Linked To You On N4R';
						$alt_message = 'A new tenant has linked to you on network4rentals.com';
						$this->send_email->sendEmail($email, $message, $subject, $alt_message = null);
						
						
					}
					
					$this->load->model('renters/user_model');
					$renterName = $this->user_model->getUsersFullName($this->session->userdata('user_id'));
					
					//Add Activity To Landlord
					$this->load->model('renters/user_model');
					$data = array('action'=>'New Tenant Linked To You<br><b><small>'.$renterName.'</small></b>', 'user_id'=>$form_input['link_id'], 'type'=>'landlords', 'action_id'=>$added);
					$this->user_model->add_activity($data);
					//Add Activity To Renter
					$data = array('action'=>'New Landlord Added', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>'');
					$this->user_model->add_activity($data);
					
					$this->session->set_flashdata('success', 'Landlord Added Successfully');
					redirect('renters/my-history');
					exit;
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
					redirect('renters/link-landlord-rentals/'.$unique_name);
					exit;
				}		
			} else {
				redirect('renters/link-landlord-rentals/'.$unique_name);
				exit;
			}
		}
	}
	
	
	public function in_search_of() 
	{
		$this->load->js('assets/themes/default/js/textarea-tags/jquery.tagsinput.js');

		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		
		$this->load->model('renters/iso');
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data['feedback'] = $this->iso->validate_iso_data();
		} else {
			$success = $this->session->flashdata('success');
			$error = $this->session->flashdata('error');
			if(!empty($error)) {
				$data['feedback'] = array('error'=>'You have not added any In Search Of data yet');
			}
			if(!empty($success)) {
				$data['feedback'] = array('success'=>'Your In Search Of has been deleted');
			}
		}
		
		$userData = $this->iso->get_isos();
		$data['table'] = $userData['table'];
		$data['info'] = $userData['data'];
		
		$this->load->view('renters/in-search-of', $data);
	}
	
	public function delete_iso() 
	{
		$this->load->model('renters/iso');
		$this->check_if_loggedin(); // Checks if user is logged in

		if($this->iso->delete_iso()) {
			$this->session->set_flashdata('success', 'Your "In Search Of" has been deleted');
		} else {
			$this->session->set_flashdata('error', 'Something went wrong trying to delete ISO, try again');
		}
		redirect('renters/in-search-of');
		exit;
		
	}
	
	public function rent_receipt() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		$this->load->model('renters/user_model');
		$this->load->model('renters/payment_handler');
		$data['landlord_info'] = $this->user_model->get_current_landlord_info();
		$data['renter_info'] = $this->user_model->get_users_details();
		if($data['renter_info'][1]['auto_pay'] == 'y') {
			$data['autopayment_info'] = $this->user_model->subscription_details($data['landlord_info']['id'], $data['renter_info'][1]['id']);
		}
		
		if($data['landlord_info'] == false) {
			$this->session->set_flashdata('error', 'None Of Your Landlords Are Set To Current Residence. Add Your Landlord Or Edit An Existing Landlord');
			redirect('renters/my-history');
			exit;
		}
		
		$data['payment_settings'] = $this->payment_handler->check_payment_settings($data['landlord_info']['id'], $data['landlord_info']['groupId']);
		$data['payments'] = $this->user_model->view_rental_payments($data['renter_info'][1]['id']);
		
		$this->load->view('renters/rent-receipt', $data);
	}
	
	public function request_online_payments()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in');
		
		$this->load->model('renters/landlord_handler');
		$data['sent'] = $this->landlord_handler->request_online_payments();
			
		$this->load->view('renters/request-online-payments', $data);
	}
	
	public function messaging_tester() 
	{ 
		$this->check_if_loggedin();
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->session->set_userdata('date_to_msg', $_POST['date_to']);
			$this->session->set_userdata('date_from_msg', $_POST['date_from']);
		}
		
		$args = array(
			'rental_id' => (int)$this->uri->segment(3),
			'type' 		=> 'renter',
			'offset' 	=> (int)$this->uri->segment(4)
		);
		
		$this->load->model('special/messaging_modal');
		$data = $this->messaging_modal->show_messages($args);
		if(empty($data['message_to'])) {
			echo 'EMPTY OUTTA HERE'; //REDIRECT THEM BACK TO SELECT A LANDLORD
		}
		$this->load->view('renters/message-landlords-test-delete', $data);
	}
	
	public function send_new_messages()
	{
		$this->check_if_loggedin();
		$this->form_validation->set_rules('message', 'Message', 'trim|max_length[700]|xss_clean|required');
		if($this->form_validation->run() == TRUE) {
			$this->load->model('special/messaging_modal');
			
			$args = array(
				'rental_id' => (int)$this->uri->segment(3),
				'type' 		=> 'renter',
				'msg' 		=> $_POST['message'],
				'file' 		=> $_FILES['file']
			);
			$this->messaging_modal->build_message($args);
		} else {
			$this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
		}
		redirect('renters/messaging_tester/'.$args['rental_id']);
		exit;
	}

	
	public function create_renter_account()
	{
		if($this->uri->segment(3)) {
			$this->session->set_userdata('affiliate_id', $this->uri->segment(3));
			redirect('renters/create-renter-account');
			exit;
		}
	
		$this->output->set_template('tenants/create-account-template');	
		
		$this->load->js('assets/themes/default/js/renters/create-new-account.js');
		$this->load->view('renters/create-account-simplified');
	}

	
}
