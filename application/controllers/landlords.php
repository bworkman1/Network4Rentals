<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landlords extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('cookie');
        $this->load->section('sidebar', 'advertiser-sidebar');
		$this->_init();
	}

	function check_if_loggedin() 
	{	
		// THIS WAS TO IMPLEMENT INTERCOM IO AND CAN BE DELETED
		$userEmailSet = $this->session->userdata('user_email');
		if(empty($userEmailSet)) {
			$this->session->set_flashdata('errror', 'You have been logged out for updates. Please log back in to access your account');
		}
		
		if($this->session->userdata('logged_in') == false)
		{
			$cookie = array(
				'name'   => 'logged_in',
				'domain' => '.network4rentals.com',
				'path'   => '/',
			);
			delete_cookie($cookie);
			redirect('landlords/login');
			exit;
		}
		if($this->session->userdata('side_logged_in') != '8468086465404') {
			$cookie = array(
				'name'   => 'logged_in',
				'domain' => '.network4rentals.com',
				'path'   => '/',
			);
			delete_cookie($cookie);
			$this->session->sess_destroy();
			redirect('landlords/login');
			exit;
		}
	}
	
	private function _init()
	{
		$this->load->model('special/ads_output');
		$data['result'] = $this->ads_output->get_ads_in_location();
		$this->load->vars($data);
		
		$this->output->set_template('landlord-not-logged-in');
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/js/bootstrap.min.js');
		$this->load->js('assets/themes/default/js/custom.js');
		$this->load->js('assets/themes/default/js/fitvids.js');
		$this->load->js('assets/themes/default/js/bootstrap-datepicker.js');
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->css('assets/themes/default/css/datepicker.css');

		$this->load->js('assets/themes/default/js/select2.min.js');
		$this->load->css('assets/themes/default/css/select2.css');
	
	}

	function email_format($message)
	{
		$email_body = '
		<html>
		<head>
		</head>
		<body>
		<center>
			<table width="100%" bgcolor="#428BCA" cellpadding="10">
				<tr>
					<td width="350px">
						<center>
							<a href="https://network4rentals.com"><img src="https://network4rentals.com/wp-content/uploads/2013/07/network_logo.png" border="0" width="300" alt="Network 4 Rentals"></a>
						</center>
					</td>
					<td width="400px" align="center">
						<FONT COLOR="#ffffff"><p><b>Improving Landlord &amp; Tenant Relations Nationwide</b></p></FONT>
					</td>
				</tr>
			</table>
			<table width="750px" cellpadding="10" bgcolor="#ffffff" align="left">
				<tr>
					<tdvalign="top">
						'.$message.'<br><br><h3>Unsure Of What To Do Next?</h3><p>If at any point you find yourself lost or unsure of what to do next there are plenty of resources available at your disposal our on <a href="https://network4rentals.com/fas/">faqs</a> page or our <a href="https://network4rentals.com/blog/">blog</a> page.</p>
					</td>
				</tr>
			</table>
		</center>
		</body>
		</html>';
		return $email_body;
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
	
	function sendEmail($email, $message, $subject, $alt_message = null)
	{
		
		$this->load->library('email');
		
		$config['mailtype'] = 'html';
		
		$this->email->initialize($config);
		
		$this->email->from('no-reply@network4rentals.com', 'No Reply');
		$this->email->to($email);   

		
		
		$mobile_check = $this->check_if_mobile($email);
		if($mobile_check == true) {
			$this->email->subject('');
			$this->email->message($alt_message);	
		} else {
			$this->email->subject($subject);
			$message = $this->email_format($message);
			$this->email->message($message);	
		}
		

		if($this->email->send()) {
			return true;
		} else {
			return false;
		}
		
	}	
	
	public function test_email()
	{
		$this->check_if_loggedin();
		$this->load->model('landlords/user_account_handler');
		$info = $this->user_account_handler->landlord_info();
		if(!empty($info['email'])) {
			$name = explode(' ', $info['name']);
			$message = '<h3>Hello '.$name[0].'</h3>';
			$message .= '<p>It looks like your email is working fine and you should be all set to start using the system.</p>';
			$subject = 'Test Email From Network4Rentals';
			$alt_msg = $subject;
			$this->load->model('special/send_email');
			if($this->send_email->sendEmail($info['email'], $message, $subject)) {
				if(!empty($info['forwarding_email'])) {
					$this->sendEmail($info['forwarding_email'], $message, $subject, $alt_msg);
					$this->session->set_flashdata('success', 'Your test email has been sent to '.$info['email'].' and '.$info['forwarding_email']);
				} else {
					$this->session->set_flashdata('success', 'Your test email has been sent to '.$info['email']);
				}
				
			} else {
				$this->session->set_flashdata('error', 'There was an error sending the email to '.$info['email']);
			}
		} else {
			$this->session->set_flashdata('error', 'There was an error finding your email address, check your account settings to make sure you have a valid email in the system.');
		}
		redirect('landlords/edit-account');
		exit;
	}
	
	public function index()
	{
		$cookie = $this->input->cookie('logged_in');
		if($cookie==1) {
			redirect('landlords/activity');
			exit;
		}
		if($this->session->userdata('logged_in'))
		{
			redirect('landlords/activity');
		} 
		
		$this->load->view('landlords/home');
	}

	public function login()
	{
		$cookie = $this->input->cookie('logged_in');
		if($cookie==1) {
			redirect('landlords/activity');
			exit;
		}
		if($this->session->userdata('logged_in'))
		{
			redirect('landlords/activity');
		}
		else 
		{
			$this->load->view('landlords/login');
		}
	
	}

	public function create_account()
	{	
		
		if($this->uri->segment(3)) {
			if (ctype_alnum($this->uri->segment(3))) {
				$this->session->set_userdata('affiliate_id', $this->uri->segment(3));
				redirect('landlords/create-account');
				exit;
			}
		}
		
		if(isset($_COOKIE['affiiliate'])) {
			if (ctype_alnum($_COOKIE['affiiliate'])) {
				$this->session->set_userdata('affiliate_id', $_COOKIE['affiiliate']);
			}
		}
		
		$this->output->set_template('landlords/nosidebars');
		$this->load->js('assets/themes/default/js/notify.min.js');
		$cookie = $this->input->cookie('logged_in');
		if($cookie==1) {
			redirect('landlords/activity');
			exit;
		}
		$this->session->sess_destroy();
		if($this->session->userdata('logged_in'))
		{
			redirect('landlords/activity');
		}
		else 
		{
			$this->load->view('landlords/create-account');
		}
	}

	public function password_reset()
	{
		$this->load->view('landlords/password-reset');
	}

	public function user() // Login Handler
	{
		$this->form_validation->set_rules('username', 'Username', 'required|trim|max_length[50]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|max_length[200]|xss_clean');
		
		if($this->form_validation->run() == FALSE) {
			$this->load->view('landlords/login');
		} else {
			// process user input and login the user
			extract($_POST);
			
			$this->load->model('landlords/login_handler');
			$userData = $this->login_handler->check_login($username, $password);
			if($userData === false) {
				//Login failed
				$this->session->set_flashdata('error', 'Username and/or password are incorrect');
				redirect('landlords/login');
			} else {
				if($userData->confirmed == 'y') {
					redirect('landlords/activity');
					exit;
				} else {
					// User needs to confirm their email
					$this->session->set_flashdata('error', 'You need to confirm your account before you can login, we sent a conformation email to the email address associated with this account. Please check your email for instructions on how to confirm your account.');
					redirect('landlords/login');
					exit;
				}
			} 
		}
	}
	
	public function text_code_conformation()
	{
		$this->form_validation->set_rules('text_code', 'Text Message Code', 'required|trim|min_length[5]|max_length[15]|xss_clean|alphanumeric');
		if($this->form_validation->run() == TRUE) 
		{
			extract($_POST);
			$this->load->model('landlords/create_user_model');
			$results = $this->create_user_model->check_text_code($text_code);
			if($results) {
				
				$this->session->set_flashdata('success', 'You account has been verified and you can now login');
				$this->session->set_userdata('firstTime', true);
				redirect('landlords/activity');
				exit;
			} else {
				$this->session->set_flashdata('error', 'Invalid Code, Try Again');
				redirect('landlords/account-created');
				exit;
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
			redirect('landlords/account-created');
			exit;
		}
	}
	
	public function create_user_account() 
	{
		if($this->session->userdata('logged_in'))
		{
			redirect('landlords/activity');
		}
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[200]|matches[password1]|xss_clean|md5');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[100]|xss_clean|is_unique[landlords.email]|valid_email');
		$this->form_validation->set_rules('password1', 'Confirm Password', 'required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[10]|max_length[14]|xss_clean');
		$this->form_validation->set_rules('cell_phone', 'Cell Phone', 'trim|min_length[10]|max_length[14]|xss_clean');
		$this->form_validation->set_rules('sms_msgs', 'Text Messages', 'trim|min_length[1]|max_length[1]|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('state', 'State', 'required|trim|min_length[2]|max_length[2]|xss_clean|alpha');
		$this->form_validation->set_rules('zip', 'Zip', 'required|trim|min_length[5]|max_length[5]|xss_clean');
		$this->form_validation->set_rules('terms', 'Terms Of Service', 'required|trim|min_length[1]|max_length[1]|xss_clean');
		$this->form_validation->set_rules('bName', 'Business Name', 'trim|min_length[1]|max_length[70]|xss_clean');
		$this->form_validation->set_rules('hear', 'Hear About Us', 'trim|min_length[1]|max_length[70]|xss_clean');
		$this->form_validation->set_rules('rental_units', 'Rental Units', 'trim|min_length[1]|max_length[70]|xss_clean|required|numeric');
		
		$this->form_validation->set_message('is_unique', '%s is already being used, try logging into your account by using the forgot password link <a href="'.base_url().'landlords/forgot_password">here</a>.');
		
		if($this->form_validation->run() == FALSE) 
		{
			
		}
		else 
		{
			// Form Is Valid
			extract($_POST);
			$this->load->model('landlords/create_user_model');
			
			$ip = $_SERVER['REMOTE_ADDR'];
			$date = date('Y-m-d h:i:s'); 
			$hash = md5($username.$date);
			$phone = preg_replace("/[^0-9]/", '', $phone);
			$cell_phone = preg_replace("/[^0-9]/", '', $cell_phone);
			$data = array(
				'user' 		=> $email,
				'pwd' 		=> $password,
				'name'		=> $fullname,
				'email'	 	=> $email,
				'phone' 	=> $phone,
				'address' 	=> $address,
				'city' 		=> $city,
				'state' 	=> $state,
				'zip' 		=> $zip,
				'loginHash' => $hash,
				'sign_up' 	=> $date,
				'ip'		=> $ip,
				'bName'		=> $bName,
				'hear'		=> $hear,
				'rental_units' => $rental_units,
				'cell_phone' => $cell_phone,
				'sms_msgs' => $sms_msgs
			);
			
			
			$affiliateId = $this->session->userdata('affiliate_id');
			$data['affiliate_id'] = $affiliateId;
			

			if($sms_msgs == 'y') {
				$data['text_msg_code'] =  substr($hash, 0, rand(5, 8));
			}
			$created = $this->create_user_model->create_user_account($data);
			
			$this->session->set_userdata('user_hash', $hash);
			
			if($sms_msgs == 'y') {
				$sms['phone_to'] = $cell_phone;
				$sms['message'] = 'Network4Rentals verification code: '.$data['text_msg_code'].'. http://network4rentals.com/network/landlords/account-created';
				$sms['page'] = 'create_user_account';
				
				$this->session->set_userdata('cell', $cell_phone);
				$this->session->set_userdata('sms', $sms_msgs);
				$this->session->set_userdata('sms_msg', $sms['message']);
				
				$this->send_sms($sms);
			}
			$this->load->model('special/send_email');
			switch ($created) {
				case 1:
					$message = '<h3>'.$fullname.'</h3><p>Your account has been created, click <a href="'.base_url().'landlords/account_verified/'.$hash.'">here</a> to verify your email address.</p>';
					$subject = "N4R | Account Created";
					
					$this->session->set_userdata('user_email', $email);
					$this->session->set_userdata('message', $message);
					$this->session->set_userdata('subject', $subject);
					$alt_message = '';
					
					$this->send_email->sendEmail($email, $message, $subject, $alt_message);
					redirect('landlords/account_created');
					exit;
					break;
				case 2:
					$message = '<h3>'.$fullname.'</h3><p>Your account has been created, click <a href="'.base_url().'landlords/account_verified/'.$hash.'">here</a> to verify your email address.</p>';
					$subject = "N4R | Account Created";
					
					$this->session->set_userdata('user_email', $email);
					$this->session->set_userdata('message', $message);
					$this->session->set_userdata('subject', $subject);
					$alt_message = '';
					$this->send_email->sendEmail($email, $message, $subject, $alt_message);
					redirect('landlords/account-created');
					exit;
					break;
				case 3:
					$data = array('error'=>'The email address is already in use, try our forgot password path <a href="'.base_url().'landlords/forgot_password">here</a>');
					break;
				case 4:
					$data = array('error'=>'The phone number that you have used is already in use. Please contact us to retrieve this account <a href="http://network4rentals.com/help-support/">here</a>');
					break;
			}
		}
		$this->load->view('landlords/create-account', $data);
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
			$this->load->model('landlords/create_user_model');
			$results = $this->create_user_model->update_text_message_option($data);
			if($results) {
				$this->session->set_userdata('cell', $cell_phone);
				$this->session->set_userdata('sms', $sms_msgs);
				
				$info = array(
					'phone_to' => $this->session->userdata('cell'),
					'message' => $this->session->userdata('sms_msg'),
					'page' => 'account-created'
				);
				$texted = $this->send_sms($info);
				if($texted) {
					$this->session->set_flashdata('resent', 'Text message sent, please enter your verification code into the box');
				} else {
					$this->session->set_flashdata('error', 'Text message failed to send, please contact support');
				}
				
			} else {
				$this->session->set_flashdata('error', 'There was a problem updating your text settings, try again');
			}
			redirect('landlords/account-created');
			exit;
		}
		
		$this->load->view('landlords/account-created');
	}
	
	public function account_verified() 
	{
		$hash = $this->uri->segment(3);
		if(empty($hash)) {
			$this->session->set_flashdata('error', 'Invalid Email Address, Try Again');
			redirect('landlords/create-account');
		} else {
			$this->load->model('landlords/create_user_model');
			$user_verified = $this->create_user_model->verify_account($hash);
			if($user_verified) {
				$this->session->set_userdata('firstTime', true);
				$this->session->set_flashdata('success', 'Your account has been verified');
				redirect('landlords/activity');
				exit;
			} else {
				$this->session->set_flashdata('error', 'Your account could not be verified, try logging in and we will send you another email with the verification link');
				$this->load->view('landlords/create-account');
			}	
		}	
	}
	
	public function activity()
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
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
		
		$data = $this->user_activity_page->activity('landlords', $offset);
		$data['date_to'] = $this->session->userdata('date_to');
		$data['date_from'] = $this->session->userdata('date_from');
		$data['reset'] = $this->session->flashdata('reset');
		
		$this->load->view('landlords/activity-new', $data);
	}
	
	public function activity_old_activity() 
	{
		$this->load->js('assets/themes/default/js/landlords/activity.js');
		$this->output->set_template('logged-in-landlord');
		$this->check_if_loggedin();
		
		$temp_id = $this->session->userdata('temp_id');
		if(empty($temp_id)) {
			$id = $this->session->userdata('user_id');
		} else {
			$id = $this->session->userdata('temp_id');
		}
		
		$this->form_validation->set_rules('date_to', 'Date To', 'required|trim|max_length[15]|xss_clean');
		$this->form_validation->set_rules('date_from', 'Date From', 'required|trim|max_length[15]|xss_clean');
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			$this->session->set_userdata('date_to', $date_to);
			$this->session->set_userdata('date_from', $date_from);
		} 
		
		
		$this->load->model('landlords/fetch_activity_model');
		$this->load->library('pagination');
		
		$config['base_url'] = base_url().'landlords/activity';
		
		$config['total_rows'] = $this->fetch_activity_model->record_count($id,  $this->session->userdata('date_to'), $this->session->userdata('date_from'));
		
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
		
		$data["results"] = $this->fetch_activity_model->fetch_recent_activity($config["per_page"], $page, $id, $this->session->userdata('date_to'), $this->session->userdata('date_from'));
		if($this->fetch_activity_model->landlord_check() == true) {
			$data['landlord_check'] = 1;
		} else {
			$data['landlord_check'] = 0;
		}
		
		$data['date_to'] = $this->session->userdata('date_to');
		$data['date_from'] = $this->session->userdata('date_from');
		$data['reset'] = $this->session->flashdata('reset');
		
		$data["links"] = $this->pagination->create_links();
		
		$this->load->model('landlords/admin_switch_handler');
		$data['switches'] = $this->admin_switch_handler->my_groups();
		$this->load->view('landlords/activity', $data);

	}

	public function turn_phone_into_email($phone) 
	{
		if(strlen($phone) == 10) {
			$link = 'http://www.carrierlookup.com/index.php/api/lookup?key=66ed44b57ad050d6b2d5eb14c366871dd0afb5d6&number='.$phone;
			$return = file_get_contents($link);
			$object = json_decode($return, true);

			$response_carriers = array('boost_cdma' => '@myboostmobile.com', 'sprint' => '@messaging.sprintpcs.com', 'AT&T' => '@cingularme.com', 'verizon' => '@vtext.com', 't-mobile' => '@tmomail.net');  
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
		$this->form_validation->set_rules('email', 'Email', 'required|trim|min_length[3]|max_length[100]|xss_clean');
		$this->form_validation->set_rules('captcha', "Captcha", 'required|callback_check_captcha');
		
		/* Get the user's entered captcha value from the form */
		if(!empty($_POST['captcha'])) {
			$userCaptcha = strtolower($_POST['captcha']);
		}
		
		/* Get the actual captcha value that we stored in the session (see below) */
		$word = $this->session->userdata('captchaWord');
		
		extract($_POST);
		
		$this->session->flashdata('userCaptcha', '$captcha');
		
		/* Check if form (and captcha) passed validation*/
		if ($this->form_validation->run() == TRUE && strcmp(strtoupper($userCaptcha),strtoupper($word)) == 0)
		{
			$this->session->unset_userdata('captchaWord');
		 	extract($_POST);
			$this->load->model('landlords/reset_password');
			$hash = $this->reset_password->check_user_email($email);
			if($hash != false) 
			{
				$this->load->model('special/send_email');
				// DB Updated with hash now need to email user that hash
				$message = '
					<h3>Reset Your Password</h3>
					<p>You have requested to reset your password. If you did not request to have your password reset you can ignore this email. Else click the link below to go through the steps to reset your password.</p><a href="'.base_url().'landlords/reset_password/'.$hash.'">Reset Password</a>
				';
				$subject = 'N4R | Password Reset Instructions';
				$this->send_email->sendEmail($email, $message, $subject);
				$data = array('email' => $email);
				$this->load->view('landlords/password-reset', $data);
			} 
			else
			{
				redirect('landlords/password-reset');
			}		  
	
		}
		else 
		{
		  /** Validation was not successful - Generate a captcha **/
		  $this->session->flashdata('captchas', 'Invalid captcha, try again');
		
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
		  $this->load->view('landlords/forgot-password', $captcha);
		}
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
			$this->load->model('landlords/reset_password');
			if($this->reset_password->check_token($token) == true) {
				$this->session->set_userdata('token', $token);
				$this->load->view('landlords/change-password');
			} else {
				$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
				redirect('landlords/forgot_password');
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
				$this->load->view('landlords/change-password');
			}
			else 
			{
				extract($_POST);
				$this->load->model('landlords/reset_password');
				if($this->reset_password->check_token($token) == true) {
					if($this->reset_password->change_password($token, $password) == true) {
						$this->session->set_userdata('token', '');
						$this->session->set_flashdata('success', 'Your Password Has Now Been Changed, You Can Login Now With Your New Password');
						redirect('landlords/login');
						exit;
					} else {
						$this->session->set_userdata('token', '');
						$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
						redirect('landlords/forgot_password');
						exit;
					}
				} else {
					$this->session->set_userdata('token', '');
					$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
					redirect('landlords/forgot_password');
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
		redirect('landlords/activity');
		exit; 
	}
	
	function logout() 
	{
		$cookie = array(
			'name'   => 'logged_in',
			'domain' => '.network4rentals.com',
			'path'   => '/',
		);
		delete_cookie($cookie);
		$this->session->sess_destroy();
		redirect('landlords/login');
	}
	
	public function edit_account() 
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
		$this->load->js('assets/themes/default/js/landlords/edit-account.js');
		$this->load->model('landlords/user_account_handler');
		$data['info'] = $this->user_account_handler->landlord_info();
		$data['public_page_setup'] = $this->user_account_handler->check_public_page();
		$this->load->model('landlords/admin_switch_handler');
		$data['switches'] = $this->admin_switch_handler->my_groups();
		
		$profile_img = $data['info']['profile_img']; 
		if(!empty($_POST)) {
			if($data['info']['email'] == $_POST['email']) {
				$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[100]|xss_clean|');
			} else {
				$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[100]|xss_clean|is_unique[landlords.email]');
			}
		}
		
		$this->form_validation->set_rules('name', 'Full Name', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('bName', 'Business Name', 'trim|max_length[100]|xss_clean');
		
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[14]|max_length[14]|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[200]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'required|trim|max_length[100]|xss_clean|alpha');
		$this->form_validation->set_rules('state', 'State', 'required|trim|min_length[2]|max_length[2]|xss_clean|alpha');
		$this->form_validation->set_rules('zip', 'Zip', 'required|trim|min_length[5]|max_length[5]|xss_clean');
		$this->form_validation->set_rules('alt_phone', 'Alternative Phone', 'trim|min_length[9]|max_length[18]|xss_clean');
		$this->form_validation->set_rules('sms_msgs', 'Alternative Phone', 'trim|min_length[1]|max_length[1]|xss_clean');
		$this->form_validation->set_rules('cell_phone', 'Alternative Phone', 'trim|min_length[12]|max_length[14]|xss_clean');
		
		
		
		if($this->form_validation->run() == FALSE) {
			
		} else {
			extract($_POST);
			
			if(isset($_FILES)) {
				if(!empty($_FILES['file']['name'])) {
					$config['upload_path'] = './public-images/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					
					$file = "file";
					
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
						
						// Resize The Image
						$config['image_library'] = 'GD2'; 
						$config['source_image']	= FCPATH.'public-images/'.$file;
						$config['maintain_ratio'] = TRUE;
						$config['width']	 = 450;
						$config['height']	= 400;

						$this->load->library('image_lib', $config);
						
						$this->image_lib->resize();
					
						$this->image_lib->initialize($config); 

						
					
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				} else {
					$file = '';
				}
			}
			
			$_POST['phone'] = preg_replace("/[^0-9]/", '', $_POST['phone']);
			$_POST['alt_phone'] = preg_replace("/[^0-9]/", '', $_POST['alt_phone']);
			$_POST['cell_phone'] = preg_replace("/[^0-9]/", '', $_POST['cell_phone']);
			if(!empty($file)) {
				if($file != $profile_img) {
					$_POST['profile_img'] = $file;
				}
			}
			
			if($data['info']['sms_msgs'] == 'n' && $_POST['sms_msgs'] == 'y') {
				$data = array('phone_to'=> $_POST['cell_phone'], 'message'=> 'You will now start to receive text messages when a service request or message is received', 'page'=>'landlords/edit-account');
				$this->send_sms($data);
			}
			
			$input = $_POST;
			$updated = $this->user_account_handler->update_landlord_info($input);
			if($updated) {
				$this->load->model('landlords/fetch_activity_model');
				$data = array('action' =>'Made Changes To Your Personal Info','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>'');
				$this->fetch_activity_model->add_activity_feed($data);				
				$this->session->set_flashdata('success', 'Account Info Updated');
			} else {
				$this->session->set_flashdata('error', 'Updating Account Info Failed, Try Again');
			}
			redirect('landlords/edit-account');
			exit;
		}

		$this->load->view('landlords/edit-account', $data);
	}
	
	public function update_password() 
	{
		$this->check_if_loggedin();
		$this->form_validation->set_rules('pwd1', 'Password','required|trim|min_length[6]|max_length[50]|xss_clean|matches[pwd2]');
		$this->form_validation->set_rules('pwd2', 'Password','required|trim|min_length[6]|max_length[50]|xss_clean|matches[pwd1]');
		if($this->form_validation->run() == FALSE) 
		{
			redirect('landlords/edit_account'); 
			exit;
		}
		else 
		{	
			extract($_POST);
			$this->load->model('landlords/user_account_handler');
			$pwd1 = md5($pwd1);
			$updated = $this->user_account_handler->update_password($pwd1);
			
			if($updated) {
				$this->session->set_flashdata('success', 'Your Password Has Been Updated');
				$this->load->model('landlords/fetch_activity_model');
				$data = array('action' =>'Changed Password','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>'');
				$this->fetch_activity_model->add_activity_feed($data);
				
				
				
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			}
			redirect('landlords/edit_account');
			exit();
		}
	}
	
	function add_forwarding_email() 
	{

		$this->check_if_loggedin();
		if(!empty($_POST['forwarding_email'])) {
			$this->form_validation->set_rules('forwarding_email', 'Forwarding Address Email','trim|max_length[100]|xss_clean|valid_email');
		}
		
		$this->form_validation->set_rules('forwarding_cell', 'Forwarding Cell Phone','trim|max_length[16]|xss_clean');
		$this->form_validation->set_rules('forwarding_sms_msgs', 'Accept Forwarding Text Messages','trim|max_length[1]|xss_clean|required');
		
		if($this->form_validation->run() == FALSE) 
		{
			$this->session->set_flashdata('error', validation_errors());
			redirect('landlords/edit-account'); 
			exit;
		}
		else 
		{
			extract($_POST);
			$forwarding_cell = preg_replace("/[^0-9,.]/", "", $forwarding_cell);
			$this->load->model('landlords/user_account_handler');
			$info = array('forwarding_email' => $forwarding_email, 'forwarding_cell'=>$forwarding_cell, 'forwarding_sms_msgs'=>$forwarding_sms_msgs);
	
			$result = $this->user_account_handler->edit_forwarding_address($info);
			if($result) {				
				$this->load->model('landlords/fetch_activity_model');
				$data = array('action' =>'Added A Forwarding Email','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>'');
				$this->fetch_activity_model->add_activity_feed($data);
				
				
				$this->session->set_flashdata('success', 'Forwarding Email Address Has Been Saved');
				redirect('landlords/edit_account');
				exit;
			} else {
				$this->session->set_flashdata('error', 'No Changes Where Made');
				redirect('landlords/edit_account');
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
			redirect('landlords/edit_account');
			exit;
		}
		else 
		{
			extract($_POST);	
			$this->load->model('landlords/edit_user_account');
			$result = $this->edit_user_account->update_password($password);
			if($result == true) {
				
				$this->session->set_flashdata('feedback_success', 'Your Password Has Been Changed');
				
				redirect('landlords/edit_account');
				exit;
			} else {
				$this->session->set_flashdata('feedback_error', 'Something Went Wrong, Try Again');
				redirect('landlords/edit_account');
				exit;
			}
		}
	}
	
	function remove_forwarding_email()
	{
		$this->load->model('landlords/user_account_handler');
		$result = $this->user_account_handler->remove_forwarding_email();
		if($result) {
			$this->session->set_flashdata('success', 'Forwarding Email Address Has Been Removed');
			redirect('landlords/edit_account');
			exit;
		} else {
			$this->session->set_flashdata('error', 'No Changes Where Made');
			redirect('landlords/edit_account');
			exit;
		}
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
			if($this->send_email->sendEmail($email, $message, $subject, $alt_message)) {
				$this->session->set_flashdata('resent', 'Verification Email Has Been Sent Again');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, try again');
			}
			redirect('landlords/account_created');
		} else {
			redirect('landlords/create_account');
			exit;
		}
	}
	
	function add_listing() 
	{	
		$this->check_if_loggedin();
		
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			$this->form_validation->set_rules('title', 'Listing Title', 'required|trim|max_length[70]|xss_clean');
			$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[50]|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'required|trim|max_length[50]|xss_clean');
			$this->form_validation->set_rules('stateAbv', 'State', 'required|trim|max_length[2]|xss_clean|alpha');
			$this->form_validation->set_rules('zipCode', 'Zip Code', 'required|trim|exact_length[5]|xss_clean');
			$this->form_validation->set_rules('bedrooms', 'Bedrooms', 'required|trim|max_length[3]|xss_clean|numeric');
			$this->form_validation->set_rules('bathrooms', 'Bathrooms', 'required|trim|max_length[5]|xss_clean|numeric');
			$this->form_validation->set_rules('price', 'Rent', 'required|trim|max_length[50]|xss_clean|numeric');
			$this->form_validation->set_rules('deposit', 'Deposit', 'required|trim|max_length[50]|xss_clean|numeric');
			$this->form_validation->set_rules('sqFeet', 'Square Feet', 'trim|max_length[10]|xss_clean|numeric');
			$this->form_validation->set_rules('details', 'Details', 'required|trim|max_length[700]|xss_clean');
			$this->form_validation->set_rules('map_correct', 'Is Map Correct', 'trim|max_length[1]|xss_clean');
			$this->form_validation->set_rules('featured_image', 'Featured Image', 'min_length[1]|trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('garage', 'Garage', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('pets', 'Pets', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('central_air', 'Central Air', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('laundry_hook_ups', 'Laundry Hook Ups', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('off_site_laundry', 'Off Site Laundry', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('on_site_laundry', 'On Site Laundry', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('parking', 'Off  Street Parking', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('basement', 'Basement', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('single_lvl', 'Single Level Floor Plan', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('shed', 'Shed', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('park', 'Near a Park', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('inside_city', 'Within City Limits', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('outside_city', 'Outside City Limits', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('deck_porch', 'Deck / Porch', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('large_yard', 'Large Yard', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('fenced_yard', 'Fenced Yard', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('partial_utilites', 'Some Utilities Included', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('all_utilities', 'Utilities Included', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('appliances', 'Appliances Included', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('furnished', 'Fully Furnished', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('pool', 'Pool', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('shopping', 'Shopping / Entertainment', 'trim|max_length[1]|xss_clean|alpha');
			
			$user_id = $this->session->userdata('user_id');
			if($user_id == 23) {
				$this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|max_length[50]|xss_clean');
				$this->form_validation->set_rules('contact_email', 'Contact Email', 'trim|max_length[50]|xss_clean');
				$this->form_validation->set_rules('contact_phone', 'Contact Phone', 'trim|max_length[16]|xss_clean');
			}
			if ($this->form_validation->run() == FALSE) {
		
				$data['error'] = validation_errors();	
			} else {
				extract($_POST);
				$white_list = array('title', 'address', 'city', 'zipCode', 'stateAbv', 'bedrooms', 'bathrooms', 'price', 'deposit', 'sqFeet', 'details', 'map_correct', 'image1', 'image2', 'image3', 'image4', 'image5', 'add-listings', 'laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'single_lvl', 'shed', 'park', 'outside_city', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool', 'inside_city', 'shopping', 'contact_name', 'contact_email', 'contact_phone', 'featured_image');
	
				$isValid = true;
				foreach($_POST as $key => $val) {
					if(!in_array($key, $white_list)) {
						echo '\''.$key.'\', ';
						$isValid = false;
					}
				}
				if($isValid) {
					
					$this->load->model('landlords/rental_listing_model');
					if($this->rental_listing_model->add_new_listing($_POST)) {
							
						$this->load->model('landlords/fetch_activity_model');
						$action = 'Added New Rental Listing<br><b><small>'.$address.' '.$city.', '.$stateAbv.'</small></b>';
						$data = array('action' =>$action,'user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>'');
						$this->fetch_activity_model->add_activity_feed($data);
						
						$this->session->set_flashdata('success', 'Listing added successfully');
						redirect('landlords/manage-listings');
						exit;
					} else {
						$data['error'] = 'Oops, something went wrong adding your listing. If the problem persist contact us and so we can look into the issue';
					}
				} else {
					$data['error'] = 'Invalid values add into the form, listing not created';
				}
				
			}
		}
		
		$this->load->js('assets/themes/default/js/landlords/croppic.min.js');
		$this->load->js('assets/themes/default/js/landlords/add-listing.js');
		
		$this->load->css('assets/themes/default/css/landlords/croppic.css');
		$this->output->set_template('logged-in-landlord');
		
		$this->load->view('landlords/add-new-listing', $data);
		
	}
	
	function manage_listings() 
	{ 
		$this->check_if_loggedin();
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->js('assets/themes/default/js/jquery.maskMoney.js');
		$this->load->js('https://code.jquery.com/ui/1.11.2/jquery-ui.min.js');
		$this->load->js('assets/themes/default/js/landlords/manage-listings.js');
		$this->load->library('pagination');
		$this->load->model('landlords/listings_handler');
		$config['base_url'] = base_url().'landlords/manage-listings';
		
		$config['total_rows'] = $this->listings_handler->listing_count();		

		$config['per_page'] = 21; 
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
		
		$data["results"] = $this->listings_handler->manage_all_listings($config["per_page"], $page);
		$data["link"] = $this->listings_handler->public_link_caller();
		$data['listingHeader'] = $this->listings_handler->manage_listing_header();
		$data["links"] = $this->pagination->create_links();
		if(empty($data['listingHeader'])) {
			$data['listingHeader'] = 'My Properties';
		}
		$this->output->set_template('logged-in-landlord');
		$this->load->view('landlords/manage-listings', $data); 
	}

	
	function add_property_expense()
	{
		$this->form_validation->set_rules('expense_type', 'Expense Type', 'trim|max_length[1]|xss_clean|numeric|required');
		$this->form_validation->set_rules('cost', 'Expense Cost', 'trim|max_length[10]|xss_clean|required');
		$this->form_validation->set_rules('date', 'Date', 'trim|max_length[10]|xss_clean|required');
		$this->form_validation->set_rules('property_id', 'Property Id', 'trim|max_length[12]|xss_clean|required|numeric');

		
		if ($this->form_validation->run() == FALSE) {
			$data['error'] = validation_errors();	
			echo validation_errors();
		} else {
			extract($_POST);
			
		
			$file = '';
			if(isset($_FILES)) {echo 'file';
				if(!empty($_FILES['file']['name'])) {
					
					$y = date('Y');
					$m = date('m');
					$dir = './public-images/expenses/'.$y;
					if (!is_dir($dir)) {
						mkdir($dir); 
						$newFileName = './public-images/expenses/'.$y.'/index.php';
						$newFileContent = '<?php echo "No access..."; ?>';
						file_put_contents($newFileName,$newFileContent);
					}
					
					$dir = './public-images/expenses/'.$y.'/'.$m;
					if (!is_dir($dir)) {
						mkdir($dir);
						$newFileName = './public-images/expenses/'.$y.'/'.$m.'/index.php';
						$newFileContent = '<?php echo "No access..."; ?>';
						file_put_contents($newFileName,$newFileContent);
					}
				
					$config['upload_path'] = $dir;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					
					$file = "file";
					/*
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
						$input['background'] = $background;
						// Resize The Image
						$config['image_library'] = 'GD2';
						$config['source_image']	= FCPATH.'public-images/expenses/'.$y.'/'.$m.'/'.$file;
						$config['maintain_ratio'] = TRUE;
						$config['width']	 = 'auto';
						$config['height']	= 500;

						$this->load->library('image_lib', $config);
						$this->image_lib->resize($config);					
					
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
					*/
				}
			} //File Upload ends
			
			$data = array('expense_type' => $expense_type, 'expense_cost' => $cost, 'expense_date' => $date, 'property_id' => $property_id,	'image' =>$file);
				
		}
	}
	
	function edit_listing() 
	{
		$this->check_if_loggedin();
		$id = (int)$this->uri->segment(3);
		if($id>0) {
			//STARTS
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				$this->form_validation->set_rules('title', 'Listing Title', 'required|trim|max_length[70]|xss_clean');
				$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[50]|xss_clean');
				$this->form_validation->set_rules('city', 'City', 'required|trim|max_length[50]|xss_clean');
				$this->form_validation->set_rules('stateAbv', 'State', 'required|trim|max_length[2]|xss_clean|alpha');
				$this->form_validation->set_rules('zipCode', 'Zip Code', 'required|trim|exact_length[5]|xss_clean');
				$this->form_validation->set_rules('bedrooms', 'Bedrooms', 'required|trim|max_length[3]|xss_clean|numeric');
				$this->form_validation->set_rules('bathrooms', 'Bathrooms', 'required|trim|max_length[5]|xss_clean|numeric');
				$this->form_validation->set_rules('price', 'Rent', 'required|trim|max_length[50]|xss_clean|numeric');
				$this->form_validation->set_rules('deposit', 'Deposit', 'required|trim|max_length[50]|xss_clean|numeric');
				$this->form_validation->set_rules('sqFeet', 'Square Feet', 'trim|max_length[10]|xss_clean|numeric');
				$this->form_validation->set_rules('details', 'Details', 'required|trim|max_length[700]|xss_clean');
				$this->form_validation->set_rules('map_correct', 'Is Map Correct', 'trim|max_length[1]|xss_clean');
				$this->form_validation->set_rules('featured_image', 'Featured Image', 'min_length[1]|trim|max_length[1]|xss_clean|numeric');
				
				$this->form_validation->set_rules('garage', 'Garage', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('pets', 'Pets', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('central_air', 'Central Air', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('laundry_hook_ups', 'Laundry Hook Ups', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('off_site_laundry', 'Off Site Laundry', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('on_site_laundry', 'On Site Laundry', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('parking', 'Off  Street Parking', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('basement', 'Basement', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('single_lvl', 'Single Level Floor Plan', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('shed', 'Shed', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('park', 'Near a Park', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('inside_city', 'Within City Limits', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('outside_city', 'Outside City Limits', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('deck_porch', 'Deck / Porch', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('large_yard', 'Large Yard', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('fenced_yard', 'Fenced Yard', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('partial_utilites', 'Some Utilities Included', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('all_utilities', 'Utilities Included', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('appliances', 'Appliances Included', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('furnished', 'Fully Furnished', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('pool', 'Pool', 'trim|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('shopping', 'Shopping / Entertainment', 'trim|max_length[1]|xss_clean|alpha');
			
				if ($this->form_validation->run() == FALSE) {
					$data['error'] = validation_errors();	
				} else {
					$white_list = array('title', 'address', 'city', 'zipCode', 'stateAbv', 'bedrooms', 'bathrooms', 'price', 'deposit', 'sqFeet', 'details', 'map_correct', 'image1', 'image2', 'image3', 'image4', 'image5', 'add-listings', 'laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'single_lvl', 'shed', 'park', 'outside_city', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool', 'inside_city', 'shopping', 'owner', 'contact_name', 'contact_phone', 'contact_email', 'featured_image');
					$isValid = true;
					
					foreach($_POST as $key => $val) {
						if(!in_array($key, $white_list)) {
							$isValid = false;
						}  else {
							$data[$key] = $val;
						}
					}
				
					$aminities_array = array('laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'single_lvl', 'shed', 'park', 'outside_city', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool', 'inside_city', 'shopping');
					foreach($aminities_array as $val) {
						if(!isset($_POST[$val])) {
							$data[$val] = 'n';
						}
					}
				
					if($isValid) {
						$this->load->model('landlords/rental_listing_model');
						if($this->rental_listing_model->edit_rental_listing($data, $id)) {
							$this->session->set_flashdata('success', 'Listing saved successfully');
							redirect('landlords/edit-listing/'.$id);
							exit;
						} else {
							$data['error'] = 'Oops, something went wrong editing your listing. It might be that you didn\'t change anything. If the problem persist contact us and so we can look into the issue';
						}
					} else {
						$data['error'] = 'Invalid values add into the form, listing not saved';
					}
					
				}
			}
			
			//WITHOUT POST
			$this->load->model('landlords/rental_listing_model');
			$data['details'] = $this->rental_listing_model->retrieve_listing_details($id);
			if(empty($data['details'])) {
				$this->session->set_flashdata('error', 'Invalid listing selection, try again');
				redirect('landlords/manage-listings');
				exit;
			}
			
			$this->load->js('assets/themes/default/js/landlords/croppic.min.js');
			$this->load->js('assets/themes/default/js/landlords/edit-listing.js');
		
			$this->load->css('assets/themes/default/css/landlords/croppic.css');
			$this->output->set_template('logged-in-landlord');

 			$this->load->view('landlords/edit-rental-listing', $data);
		} else {
			$this->session->set_flashdata('error', 'Invalid listing selection, try again');
			redirect('landlords/manage-listings');
			exit;
		}
	}
	
	function delete_listing()
	{
		$this->check_if_loggedin();
		$this->form_validation->set_rules('delete_id', 'Listing Id', 'required|trim|max_length[11]|xss_clean');
		
		if($this->form_validation->run() == true) {
			extract($_POST);
			
			$this->load->model('landlords/listings_handler');
			$deleted = $this->listings_handler->delete_listing($delete_id);
			if($deleted) {
				$this->session->set_flashdata('success', 'Your Listing Has Been Deleted');
				redirect('landlords/manage-listings');
				exit();
			} else {
				$this->session->set_flashdata('error', 'No Listing Found, Try Again');
				redirect('landlords/manage-listings');
				exit();
			}
		} else {
			$this->session->set_flashdata('error', 'No Listing Found, Try Again');
			redirect('landlords/manage-listings');
			exit();
		}
	}
	
	function my_admins() 
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
		
		$this->load->model('landlords/landlord_admin_handler');
		$data['results'] = $this->landlord_admin_handler->show_my_admins();
		
		$this->load->view('landlords/my-admins', $data);	

	}
	
	public function add_admin() 
	{
		$this->check_if_loggedin();
		
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[50]|min_length[6]|xss_clean|required');
		$this->form_validation->set_rules('sub_b_name', 'Sub Business Name', 'trim|max_length[50]|xss_clean|required');
		
		if($this->form_validation->run() == true) {
			extract($_POST);
			
			$this->load->model('landlords/landlord_admin_handler');
			$data = array('email'=>$email, 'sub_b_name' => $sub_b_name);
			$added_admin = $this->landlord_admin_handler->add_admin($data);
			switch($added_admin) {
				case 1:
					$this->session->set_flashdata('error', 'You Can\'t Add Yourself To Your Group, Your Already The Admin. Try Again');
					break;
				case 2:
					$this->session->set_flashdata('error', 'No User Found With That User Name');
					break;
				case 3:
					$this->session->set_flashdata('error', 'This User Is Already An Admin Under Your Account');
					break;
				default:
					$this->session->set_flashdata('success', 'Sub Business Added Successfully To Your Account And The Manager Has Been Notified');
					$this->load->model('landlords/fetch_activity_model');
					$action = 'Added '.$email.' As Manager';
					$data = array('action' =>$action,'user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>'');
					$this->fetch_activity_model->add_activity_feed($data);
					if (filter_var($added_admin, FILTER_VALIDATE_EMAIL)) {
						$message = '<h3>You have been added as a manager of an account</h3><p>Login to your account and click on the my accounts page to see what accounts you can manage in your account.</p>';
						$subject = 'You have been added as an Manager';
						$alt_message = 'You have been added as a manager of someone\'s account. Please login to your account on Network4Rentals.com to view the details of this info.';
						$this->load->model('special/send_email');
						$this->send_email->sendEmail($added_admin, $message, $subject, $alt_message = null); //Uncomment To Send Email
					}
			}
			redirect('landlords/my-admins');
			exit();
		}
	}
	
	public function public_page_settings() 
	{
		$this->check_if_loggedin();
		
		$this->load->model('landlords/public_page_handler');

		$page_settings = $this->public_page_handler->get_public_page_info();
		if($page_settings == false) {
			$data['settings'] = $this->public_page_handler->landlord_details();
		} else {
			$data['settings'] = $page_settings;
		}
		if(isset($_POST)) {
			if(empty($page_settings->unique_name)) {
				$this->form_validation->set_rules('unique_name', 'Unique Name', 'trim|max_length[100]|xss_clean|required|is_unique[landlord_page_settings.unique_name]');
			} else {
				$this->form_validation->set_rules('unique_name', 'Unique Name', 'trim|max_length[100]|xss_clean|required');
			}
		}
		
		$this->form_validation->set_rules('bName', 'Business Name', 'trim|max_length[50]|xss_clean|required');
		$this->form_validation->set_rules('desc', 'Business Description', 'trim|max_length[500]|xss_clean|required');
		
		$this->form_validation->set_rules('facebook', 'Facebook Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('google', 'Google Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('twitter', 'Twitter Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('linkedin', 'Linkedin Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('youtube', 'Youtube Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('web_link', 'Website Link', 'trim|max_length[100]|xss_clean|prep_url');
		$this->form_validation->set_rules('link_title', 'Link Title', 'trim|max_length[30]|xss_clean');
		
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[50]|xss_clean|required');
		$this->form_validation->set_rules('state', 'State', 'trim|max_length[2]|xss_clean|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('admin_redirect', 'Admin Redirect', 'trim|max_length[1]|xss_clean');
		if($this->form_validation->run() == true) {
			foreach($_POST as $key => $val) {
				if($key == 'unique_name') {
					$val = str_replace('-', ' ', $val);
					$val = preg_replace('/[^\da-z ]/i', '', $val);
					$val = str_replace(' ', '-', $val);
				}
				
				if($key != 'background_select') {
					$input[$key] = $val;
				}
			}
			extract($_POST);
			
			//Image Upload

			if($background_select=="na") {
				if(isset($_FILES)) {
					if(!empty($_FILES['background']['name'])) {
						$config['upload_path'] = './public-images/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg';
						$config['max_size'] = '5000KB';
						$this->load->library('upload', $config);
						
						$background = "background";
						
						if($this->upload->do_upload($background)) {
							$upload = $this->upload->data();
							$background = $upload['file_name'];
							$input['background'] = $background;
							// Resize The Image
							$config['image_library'] = 'GD2';
							$config['source_image']	= FCPATH.'public-images/'.$background;
							$config['maintain_ratio'] = TRUE;
							$config['width']	 = 1140;
							$config['height']	= 370;

							$this->load->library('image_lib', $config);
							$this->image_lib->resize($config);					
						
						} else {
							$error = array('error' => $this->upload->display_errors());
							$background = '';
						}
					}
				}
	
			} elseif($background_select>0) {			
				switch($background_select) {
					case '1':
						$background_select = 'default-1-bkg.jpg';
						break;
					case '2':
						$background_select = 'default-2-bkg.jpg';
						break;
					case '3':
						$background_select = 'default-3-bkg.jpg';
						break;
				}
				
				$background = $background_select;
				$input['background'] = $background;
			}
			
			if(isset($_FILES)) {
				if(!empty($_FILES['file']['name'])) {
					$config['upload_path'] = './public-images/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					
					$file = "file";
					
					if($this->upload->do_upload($file)) {
				
						$upload = $this->upload->data();
						$file = $upload['file_name'];
						$input['image'] = $file;
						// Resize The Image
						$config['image_library'] = 'GD2';
						$config['source_image']	= FCPATH.'public-images/'.$file;
						$config['maintain_ratio'] = TRUE;
						$config['width']	 = 400;
						$config['height']	= 600;

						$this->load->library('image_lib', $config);
						$this->image_lib->resize($config);					
					} else {
						$error = array('error' => $this->upload->display_errors());
					
						$file = '';
					}
				} 
			}
			$input['type'] = 'landlord';
			$input['phone'] = preg_replace("/[^0-9,.]/", "", $input['phone']);
			
			$updated = $this->public_page_handler->update_settings($input);
			if($updated) {
				$this->load->model('landlords/fetch_activity_model');
				$data = array('action' =>'Updated Public Page Setting','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>$this->session->userdata('user_id'));
				$this->fetch_activity_model->add_activity_feed($data);
				$this->session->set_flashdata('success', 'Public Page Settings Added Successfully');
				redirect('landlords/public-page-settings');
				exit();
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
				redirect('landlords/public-page-settings');
				exit();
			}
		}
		
		
		
		$this->output->set_template('logged-in-landlord');
		
		$this->load->library('pagination');
		$this->load->model('landlords/listings_handler');
		$config['base_url'] = base_url().'landlords/public-page-settings';
		
		$config['total_rows'] = $this->listings_handler->listing_avaliable_count();		
		
		$config['per_page'] = 15; 
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
		
		$data["results"] = $this->listings_handler->manage_all_listings_avaliable($config["per_page"], $page);
		
		$data["links"] = $this->pagination->create_links();
		
		$this->load->view('landlords/public-page-settings', $data);	
	}
	
	public function public_page_view() 
	{
		$this->check_if_loggedin();
		$this->load->model('landlords/public_page_handler');
		$data['info'] = $this->public_page_handler->get_public_page_info();
		
		$this->load->library('pagination');
		$this->load->model('landlords/listings_handler');
		$config['base_url'] = base_url().'landlords/public-page-settings';
		
		$config['total_rows'] = $this->listings_handler->listing_count();		
		
		$config['per_page'] = 15; 
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
		
		$data["results"] = $this->listings_handler->manage_all_listings($config["per_page"], $page);
		
		$data["links"] = $this->pagination->create_links();
		$this->output->set_template('logged-in-landlord');
		$this->load->view('landlords/public-page-view', $data);	
	}
	
	public function send_public_link()
	{
		$this->check_if_loggedin();
		$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[70]|required|xss_clean');
		if($this->form_validation->run() == true) {
			extract($_POST);
			
			$this->load->model('landlords/public_page_handler');
			$page_settings = $this->public_page_handler->get_public_page_info();
			
			$link = 'http://n4r.rentals/'.$page_settings->unique_name;
			
			if(!empty($page_settings->bName)) {
				$who = $page_settings->bName;
			} else {
				$who = $page_settings->name;
			}
			$message = '	
				<h3>'.$who.' Has Sent Your Their Rental Listings</h3>
				<p>Here is our public link on Network 4 Rentals that includes our available rental listings as well as our contact information. If you find something you like feel free to contact us to see the house.</p>
				<a href="'.$link.'">Our Listings</a>
			';
			$subject = $who.' Sent Your Their Public Rentals Link';
			$alt_message = $who.' sent you their public link go to '.$link;
			$this->load->model('special/send_email');
			if($this->send_email->sendEmail($email, $message, $subject, $alt_message = null)) {
				$this->session->set_flashdata('success', 'Your Public Link Has Been Emailed To '.$email);
				redirect('landlords/public-page-settings');
				exit;
			} else {
				$this->session->set_flashdata('error', 'Email Not Sent, Try Again');
				redirect('landlords/public-page-settings');
				exit;
			}
		}
		
	} 
	
	public function search_requests()
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('serviceType', 'Service Type', 'trim|max_length[20]|xss_clean');

		if($this->form_validation->run() == true) {
			extract($_POST);
			$search = array('start_date'=>$start_date, 'end_date'=>$end_date, 'address'=>$address, 'service_type'=>$serviceType);
			$this->load->model('landlords/service_request_handler');
			$data['results'] = $this->service_request_handler->search($search);
			$data['my_properties'] = $this->service_request_handler->get_user_properties();
			
			$this->load->view('landlords/search-requests', $data);
		} else {
			$data = '';
			$this->load->view('landlords/search-requests', $data);
		}
	}
	
	public function service_request()
	{	
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
		$this->load->model('landlords/service_request_handler');
		$data['new_requests'] = $this->service_request_handler->unread_service_request();
		$data['my_properties'] = $this->service_request_handler->get_user_properties();
		$this->load->view('landlords/service-request', $data);	
	}
	
	public function my_tenants() 
	{
		$this->check_if_loggedin();
		
		$this->form_validation->set_rules('tenants', 'Sort Tenants', 'required|trim|max_length[1]|min_length[1]|required|xss_clean|alpha');
		if($this->form_validation->run() == true) {
			extract($_POST);
			$this->session->set_userdata('current_residence', $tenants);
			redirect('landlords/my-tenants');
			exit;
		}
		$this->load->model('landlords/user_account_handler');
		$this->load->model('landlords/admin_switch_handler');
		$data['public_page_setup'] = $this->user_account_handler->check_public_page();
		$data['switches'] = $this->admin_switch_handler->my_groups();
		
		$this->load->library('pagination');
		$this->load->model('landlords/tenants_handler');
		$config['base_url'] = base_url().'landlords/my-tenants';
		if($this->session->userdata('current_residence') == '') {
			$this->session->set_userdata('current_residence', 'y');
		}
		$config['total_rows'] = $this->tenants_handler->tenant_count();		
		$config['per_page'] = 30; 
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
		
		$data["results"] = $this->tenants_handler->show_tenants($config["per_page"], $page);
		
		$data["links"] = $this->pagination->create_links();		
		$this->output->set_template('logged-in-landlord');
		$this->load->view('landlords/my-tenants', $data);	
	}
	
	public function all_service_requests() 
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
		
		$this->load->library('pagination');
		$this->load->model('landlords/service_request_handler');
	
		$config['base_url'] = base_url().'landlords/all-service-requests';
		
		$config['total_rows'] = $this->service_request_handler->service_request_counter();		
		
		$config['per_page'] = 30; 
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
		
		$data["results"] = $this->service_request_handler->show_all_service_request($config["per_page"], $page);
		
		$data["links"] = $this->pagination->create_links();		
		$data['my_properties'] = $this->service_request_handler->get_user_properties();
		$this->load->view('landlords/view-all-requests', $data);	
	}
	
	public function all_complete_service_requests() 
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
		
		$this->load->library('pagination');
		$this->load->model('landlords/service_request_handler');
	
		$config['base_url'] = base_url().'landlords/all-service-requests';
		
		$config['total_rows'] = $this->service_request_handler->complete_service_request_counter();		
		
		$config['per_page'] = 30; 
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
		
		$data["results"] = $this->service_request_handler->show_all_service_request($config["per_page"], $page, $status='y');
		
		$data["links"] = $this->pagination->create_links();		
		
		$data['my_properties'] = $this->service_request_handler->get_user_properties();
		$data['sorted'] = true;
		$this->load->view('landlords/view-all-requests', $data);	
	}
	
	public function all_incomplete_service_requests() 
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
		
		$this->load->library('pagination');
		$this->load->model('landlords/service_request_handler');
	
		$config['base_url'] = base_url().'landlords/all-service-requests';
		
		$config['total_rows'] = $this->service_request_handler->service_request_counter();		
		
		$config['per_page'] = 30; 
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
		
		$data["results"] = $this->service_request_handler->show_all_service_request($config["per_page"], $page, $status='n');
		$data['sorted'] = true;
		$data["links"] = $this->pagination->create_links();		
		$data['my_properties'] = $this->service_request_handler->get_user_properties();
		$this->load->view('landlords/view-all-requests', $data);	
	}	
	
	public function switch_admin_group()
	{
		$this->check_if_loggedin();
		$this->form_validation->set_rules('admin', 'Admin Id', 'trim|max_length[20]|xss_clean|required|integer');
		if($this->form_validation->run() == true) {
			extract($_POST);
			if($admin==0) {
				$this->session->unset_userdata('temp_id');
				header('location: '.$_SERVER['HTTP_REFERER']);
				exit;
			}
			$this->load->model('landlords/landlord_admin_handler');
			$switch = $this->landlord_admin_handler->switch_account($admin);
		} else {
			header('location: '.$_SERVER['HTTP_REFERER']);
			exit;
		}
		if($switch) {
			header('location: '.$_SERVER['HTTP_REFERER']);
			exit;
		} else {
			$this->session->set_flashdata('error', 'You are not listed as an admin of that account');
			header('location: '.$_SERVER['HTTP_REFERER']);
			exit;
		}
	}
	
	public function new_service_requests()
	{	
		$this->check_if_loggedin();
		
		$this->load->model('landlords/admin_switch_handler');
		$this->load->model('landlords/service_request_handler');
		
		$this->load->library('pagination');
		$config['base_url'] = base_url().'landlords/new-service-requests';
		
		$config['total_rows'] = $this->service_request_handler->new_service_request_counter();		
		
		$config['per_page'] = 10; 
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
		
		$data["results"] = $this->service_request_handler->show_all_new_service_request($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();		
		$data['switches'] = $this->admin_switch_handler->my_groups();
		
		$this->output->set_template('logged-in-landlord');
		$this->load->view('landlords/new-service-requests', $data);
	}
	
	public function view_service_request()
	{
		$this->check_if_loggedin();	
		
		$this->load->js('assets/themes/default/js/lightboxdistrib.min.js');
		$this->load->css('assets/themes/default/css/easybox.min.css');
		
		$id = (int)$this->uri->segment(3);
		
		$this->load->js('assets/themes/default/js/landlords/service-request.js');
		
		$this->load->model('landlords/service_request_handler');
		$data['details'] = $this->service_request_handler->view_service_requests($id);
		
		$data['suppliers'] = $this->service_request_handler->getSupplyHouses($data['details']['zip'], $data['details']['service_type']);
		
		
		$ad_specs = array('service'=>$data['details']['service_type'], 'zip'=>$data['details']['zip'], 'current_ads'=>$data['details']['ad_ids'], 'request_id'=>$data['details']['id']);
		$data['ad_post'] = $this->service_request_handler->get_service_request_ads($ad_specs);

		if($data['details'] == false) {
			$this->session->set_flashdata('error', 'Service Request Not Found, Try Again');
			redirect('landlords/all-service-requests');
			exit;
		}
	
		$this->output->set_template('logged-in-landlord');
		
		$this->load->view('landlords/view-request', $data);
	}
	
	public function mark_request_incomplete()
	{
		$id = $this->uri->segment(3);
		if(empty($id)) {
			$this->session->set_flashdata('error', 'Something went wrong, try again');
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
		
		$this->load->model('landlords/service_request_handler');
		if($this->service_request_handler->markRequestIncomplete($id)) {
			$this->session->set_flashdata('success', 'Service request marked as incomplete');
		} else {
			$this->session->set_flashdata('error', 'Something went wrong, try again');
		}
		redirect($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	function service_request_complete()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$this->form_validation->set_rules('cost', 'Cost', 'trim|max_length[20]|xss_clean|greater_than[0]');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[80]|xss_clean|');
		if($this->form_validation->run() == true) {
			extract($_POST);
			$this->load->model('landlords/service_request_handler');
			$data = array('id'=>$id, 'cost'=>$cost, 'complete'=>'y', 'completed' => date('Y-m-d H:i'), 'email_hash' => '');
			$updated = $this->service_request_handler->mark_as_complete($data);
		}
		
		
		if($updated == true) {
			$query = $this->db->get_where('all_service_request', array('id'=>$id));	
			if ($query->num_rows() > 0) {
				$row = $query->row_array();
				$tenant_id = $row['tenant_id'];
				$query = $this->db->get_where('renters', array('id'=>$row['tenant_id']));	
				if ($query->num_rows() > 0) {
					$row = $query->row_array();
					$this->load->model('special/send_email');
					$email = $row['email'];
					$message = '<h2>Service Request Complete</h2>
					<p>Your service request has been completed.... more info here..... To view the service request click the link below.</p>
					<p><a href="'.base_url().'renters/view-request/'.$id.'">View Service Request</a></p>';
					$subject = 'Your Landlord Has Marked Your Service Request As Complete';
					$this->send_email->sendEmail($email, $message, $subject);$email = $row['email'];
					
					if(!empty($row['forwarding_email'])) {
						$email = $row['forwarding_email'];
						$message = '<h2>Service Request Complete</h2>
						<p>Your service request has been completed.... more info here..... To view the service request click the link below.</p>
						<p><a href="'.base_url().'renters/view-request/'.$id.'">View Service Request</a></p>';
						$subject = 'Your Landlord Has Marked Your Service Request As Complete';
						$this->send_email->sendEmail($email, $message, $subject);
					}
					
					$this->load->model('landlords/fetch_activity_model');
					$data = array('action' =>'Marked Service Request Complete','user_id' =>$tenant_id,'type'=>'renters','action_id' =>$id);
					$this->fetch_activity_model->add_activity_feed($data);
					$data = array('action' =>'Marked Service Request Complete<br><b><small>'.$address.'</small></b>','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>$id);
					$this->fetch_activity_model->add_activity_feed($data);
					
					$note = 'Marked service request complete '.date('d-m-Y H:i a', strtotime('+1 hours'));
					$data = array('note' => $note, 'visibility' => 1, 'ref_id' => $id, 'landlord_id' => $this->session->userdata('user_id'));
					$added = $this->service_request_handler->add_note($data);
				}
			}
			
			$this->session->set_flashdata('success', 'Service Request Marked As Complete');
		} else {
			$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
		}
		
		redirect('landlords/view-service-request/'.$id);
		exit;
	}
	
	function forward_service_request() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[70]|xss_clean|required');
		$this->form_validation->set_rules('note', 'Note', 'trim|max_length[500]|xss_clean');
		
		if($this->form_validation->run() == true) {
			extract($_POST);
			
			$this->load->model('landlords/service_request_handler');
			$service_details = $this->service_request_handler->view_service_requests($id);
			$temp_id = $this->session->userdata('temp_id');
			if(empty($temp_id)) {
				$ids = $this->session->userdata('user_id');
			} else {
				$ids = $this->session->userdata('temp_id');
			}
			if(empty($service_details['email_hash'])) {
				
				$service_details['email_hash'] = md5($_SERVER['REMOTE_ADDR'].date('Y-m-d H:s'));
				$data = array('id'=>$id, 'email_hash'=>$emailHash, 'landlord_id'=>$ids);
				
				$this->service_request_handler->set_email_hash($data);
			}
			
			if(empty($service_details['bName'])) {
				$service_details['bName'] = $service_details['landlord_name'];
			}
			
			$subject = 'Service Request Forwarded From N4R';
			$phone = "(".substr($service_details['landlord_phone'], 0, 3).") ".substr($service_details['landlord_phone'], 3, 3)."-".substr($service_details['landlord_phone'],6); ucwords($service_details['landlord_phone']);
			$message = '
				<h3>'.$service_details['bName'].' Sent You A Service Request</h3>
				<p>At Network 4 Rentals our landlords have the option of forwarding a service request they received from one of their tenants to someone that can take care of the problem. '.$service_details['bName'].' has choose to forward the request to you to view and take action. To view the request click the link below which will have all the information you need.</p>
				<p>
					<b>Landlord:</b> '.$service_details['bName'].'<br>
					<b>Contact Name:</b> '.$service_details['landlord_name'].'<br>
					<b>Email:</b> '.$service_details['landlord_email'].'<br>
					<b>Phone:</b> '.$phone.'<br>
					<b>Notes:</b> '.$note.'<br>
				</p>
				
				<h4>Notes From Landlord:</h4><p>'.$note.'</p>
				<p><b>Link To Service Request:</b> <br><a href="'.base_url().'renters/viewing_service_request/'.$service_details['email_hash'].'">View Service Request</a></p>
			';
			$this->load->model('special/send_email');
			$sendMail = $this->send_email->sendEmail($email, $message, $subject, $alt_message = null);
			if($sendMail) {
				// Add Activity To Landlord	
				$this->load->model('landlords/fetch_activity_model');
				$action = 'Forwarded Service Request To - '.$email;
				$data = array('action' =>$action,'user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>$id);
				$this->fetch_activity_model->add_activity_feed($data);
				$this->session->set_flashdata('success', 'Service Request Has Been Submitted To The Email You Provided');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, try again');
			}
			
			
			// Add A Note About Sending The Forward Request To Someone
			$this->load->model('landlords/service_request_handler');
			$contractor_id = $this->service_request_handler->check_for_contractor($email,$id, $note);
			if($contractor_id>0) {
				//Add activity to contractor 
				$this->load->model('landlords/fetch_activity_model');
				$action = 'Received New Service Request <br> From '.$this->session->userdata('full_name');
				$data = array('action' =>$action,'user_id' =>$contractor_id,'type'=>'contractor','action_id' =>$id);
				$this->fetch_activity_model->forwardToActivity($data);
			}
			$note .= ' Request Has Been Forwarded To '.$email;
			$data = array('note' => $note, 'visibility' => 1, 'ref_id' => $id, 'landlord_id' => $this->session->userdata('user_id'));
			
			$this->service_request_handler->addContractorDate($id);
			
			$added = $this->service_request_handler->add_note($data);
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect('landlords/view-service-request/'.$id);
		exit;
	}	
	 
	function print_service_request()  
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);		
		
		if(empty($id)) {
			redirect('landlords/view-service-request/'.$id);
			exit;
		}
		
		$this->output->set_template('blank');
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('landlords/service_request_handler');
		
		$data['details'] = $this->service_request_handler->view_maintenance_requests($id);
		$ad_specs = array('service'=>$data['details']['service_type'], 'zip'=>$data['details']['zip'], 'current_ads'=>$data['details']['ad_ids'], 'request_id'=>$data['details']['id']);
		$data['ad_post'] = $this->service_request_handler->get_service_request_ads($ad_specs);	
		$data['suppliers'] = $this->service_request_handler->getSupplyHouses($data['details']['zip'], $data['details']['service_type']);
		$this->load->view('landlords/print-service-request', $data); // Add Argument true after data
		$html = $this->load->view('landlords/print-service-request', $data, true); // Add Argument true after data
		pdf_create($html, 'Service_Reqeust_'.$data['details']['address']);  
	}
	
	function add_note_to_request() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$redirect = $this->uri->segment(4);
		
		$this->form_validation->set_rules('visibility', 'Visibility', 'trim|max_length[20]|xss_clean|integer|required');
		$this->form_validation->set_rules('note', 'Note', 'trim|max_length[500]|xss_clean|required');
		if($this->form_validation->run() == true) {
			extract($_POST);
			if(isset($_FILES)) {
				if(!empty($_FILES['img']['name'])) {
					$config['upload_path'] = './service-uploads/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size'] = '0';
					$this->load->library('upload', $config);
					
					$file = "img";
					
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
						$input['file'] = $file;
						// Resize The Image
						$config['image_library'] = 'GD2';
						$config['source_image']	= FCPATH.'public-images/'.$file;
						$config['maintain_ratio'] = TRUE;
						$config['width']	 = 500;
						$config['height']	= 500;

						$this->load->library('image_lib', $config);
						$this->image_lib->resize($config);					
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				}
			}
			$data = array('note' => $note, 'visibility' => $visibility, 'ref_id' => $id, 'landlord_id' => $this->session->userdata('user_id'), 'contractor_image'=>$file);
			$this->load->model('landlords/service_request_handler');
			$added = $this->service_request_handler->add_note($data);
			if($added) {
				if($visibility==1) {
					// Add action to contractors activity page
					$this->db->select('contractor_id');
					$result = $this->db->get_where('all_service_request', array('id'=>$id));
					$d = $result->row();
					if($d->contractor_id>0) {
						$this->load->model('landlords/fetch_activity_model');
						$action = 'Landlord Added New Note To Request <br>'.$this->session->userdata('full_name');
						$data = array('action' =>$action,'user_id' =>$d->contractor_id,'type'=>'contractor','action_id' =>$id);
						$this->fetch_activity_model->add_activity_feed($data);
					}
				}
				
				$this->session->set_flashdata('success', 'Note Added To Service Request');
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			}
		}

		if(empty($redirect)) {
			redirect('landlords/view-service-request/'.$id);
			exit();
		} else {
			redirect('landlords/view-preventive-maintenance/'.$id);
			exit();
		}
	}
	
	function property_report()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		
		$this->load->model('landlords/service_request_handler');
		$data['details'] = $this->service_request_handler->property_report($id);
		
		$this->output->set_template('logged-in-landlord');
		$this->load->view('landlords/property-report', $data);
	}
	
	function add_service_request() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		
		$this->load->model('landlords/admin_switch_handler');
		$data['switches'] = $this->admin_switch_handler->my_groups();
		$data['properties'] = $this->admin_switch_handler->show_properties();
		
		$this->form_validation->set_rules('serviceType', 'Service Type', 'trim|max_length[50]|xss_clean|required');
		$this->form_validation->set_rules('desc', 'Description', 'trim|max_length[500]|xss_clean|required');
		$this->form_validation->set_rules('rental_id', 'Required Field Missing', 'trim|max_length[20]|xss_clean|required');
		$this->form_validation->set_rules('group_id', 'ID', 'trim|max_length[20]|xss_clean|integer');
		
		if($_POST['reoccurring'] == 'y') {
			$this->form_validation->set_rules('reoccurring', 'Reoccurring PM', 'required|trim|min_length[1]|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('interval', 'How Often', 'required|trim|min_length[1]|max_length[2]|xss_clean|numeric');
			$this->form_validation->set_rules('reoccurring_date', 'Reoccurring Date', 'required|trim|min_length[10]|max_length[10]|xss_clean');
		}
		
		if($this->form_validation->run() == true) {
			
			extract($_POST);
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
			
			if(empty($group_id)) {
				$group_id = NULL;
			}
			
			$datas = array(
				'service_type'=>$serviceType,
				'enter_permission'=>'Call First',
				'description'=>$desc,
				'schedule_phone'=>'na',
				'attachment'=>$file,
				'tenant_id'=>'0',
				'complete'=>'n',
				'submitted'=>date('Y-m-d H:s'),
				'email_hash'=> md5($_SERVER['REMOTE_ADDR'].date('Y-m-d H:s')),
				'listing_id' => $rental_id,
				'group_id' => $group_id,
				'reminder_sent' => 'y'
			);		
			if($reoccurring == 'y') {
				$datas['reoccurring_date'] = date('Y-m-d', strtotime($reoccurring_date));
				$datas['interval'] = $interval;
				$datas['reoccurring'] = $reoccurring;
			}
			
			
			$this->load->model('landlords/service_request_handler');
			$result_id = $this->service_request_handler->add_service_request($datas); //returns insert id
			if($result_id>0) {
				
				$property = $this->service_request_handler->getPropertyById($rental_id, 'listings');
			
				$this->load->model('landlords/fetch_activity_model');
				$data = array('action' =>'Added A Service Request<br><b><small>'.$property->address.' '.$property->city.', '.$property->stateAbv.'</small></b>','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>$result_id);
				$this->fetch_activity_model->add_activity_feed($data);
				
				$this->load->model('landlords/user_account_handler');
				
				if(empty($group_id)) {
					$user_data = $this->user_account_handler->landlord_info();
				} else {
					
				}
				
				$rental_id = '1';
				
				$message = '
					<h3>Service Request Created</h3>
					<p>Your service request for <b></b> has been created and logged into the system.</p>
					<p>
						<b>Address:</b> '.$service_details['bName'].'<br>
						<b>Type Of Servce:</b> '.$service_details['landlord_name'].'<br>
						<b>Description:</b> '.$service_details['landlord_email'].'<br>
					</p>
					<p><b>Link To Service Request:</b> <br><a href="'.base_url().'renters/viewing_service_request/'.$service_details['email_hash'].'">View Service Request</a></p>
				';
				
				$this->session->set_flashdata('success', 'Your service request has been added, if you need to forward the request to someone you can do so by clicking the forward button below.');
				redirect('landlords/view-service-request/'.$result_id);
				exit;
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
				redirect('landlords/add-service-request');
				exit;
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		
		$this->output->set_template('logged-in-landlord');
		$this->load->view('landlords/create-request', $data);
	}
	
	function delete_pm_service()
	{
		$this->check_if_loggedin(); 
		$id = $this->uri->segment(3);
		$this->output->set_template('logged-in-landlord');
		$this->load->model('landlords/service_request_handler');
		$result = $this->service_request_handler->delete_pmr($id);
		if($result) {
			redirect('landlords/reoccurring-preventive-maintenance');
			exit;
		} else {
			redirect('landlords/view-preventive-maintenance/'.$id);
			exit;
		}
	}
	
	function reoccurring_preventive_maintenance() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
	
		$year = $this->uri->segment(3);
		$month = $this->uri->segment(4);
		if(empty($year)) {
			$year = date('Y');
		}
		if(empty($month)) {
			$month = date('m');
		}
	
		$this->output->set_template('logged-in-landlord');
		$this->load->model('landlords/service_request_handler');

		$data["results"] = $this->service_request_handler->reoccuring_pms($year, $month);
		$data['calendar'] = $this->calendar->generate($year, $month, $data['results']);
		$this->load->view('landlords/view-all-pms', $data);	
		
	}
	
	function view_preventive_maintenance()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
				
		$id = (int)$this->uri->segment(3);
		$this->load->model('landlords/admin_switch_handler');
		
		$data['switches'] = $this->admin_switch_handler->my_groups();
		$this->load->model('landlords/service_request_handler');
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$result = $this->service_request_handler->updatePM($id);
			redirect('landlords/view-preventive-maintenance/'.$id);
			exit;
		}
		
		$this->load->js('assets/themes/default/js/landlords/service-request.js');
		
		$data['details'] = $this->service_request_handler->view_maintenance_requests($id, $reoccurring = 'y');
		
		$ad_specs = array('service'=>$data['details']['service_type'], 'zip'=>$data['details']['zip'], 'current_ads'=>$data['details']['ad_ids'], 'request_id'=>$data['details']['id']);
		$data['ad_post'] = $this->service_request_handler->get_service_request_ads($ad_specs);

		if($data['details'] == false) {
			$this->session->set_flashdata('error', 'Service Request Not Found, Try Again');
			redirect('landlords/reoccurring-preventive-maintenance');
			exit;
		}
	
		$this->output->set_template('logged-in-landlord');
		$this->load->view('landlords/view-maintenance-request', $data);
	}
	
	function accounts()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$this->output->set_template('logged-in-landlord');
		$this->load->model('landlords/landlord_admin_handler');
		$data['results'] = $this->landlord_admin_handler->user_admin_accounts();
		$this->load->view('landlords/accounts', $data);
	}
	
	function add_note_payment() 
	{
		$id = (int)$this->uri->segment(3);
		$this->check_if_loggedin(); // Checks if user is logged in
		
		$this->form_validation->set_rules('payment_note', 'Payment Note', 'trim|min_length[5]|max_length[1000]|xss_clean|required');
		
		if ($this->form_validation->run() == TRUE) {
			extract($_POST);
			$this->load->model('landlords/payment_handler');
			$data = array('sent_by'=>'landlord', 'payment_id'=>$id, 'note'=>$payment_note);
			$note = $this->payment_handler->add_payment_note($data);
			if($note) {
				$this->session->set_flashdata('success', 'Your Note Has Been Added To The Payment');
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong While Adding The Note To This Payment, Try Again');
			}
			
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect('landlords/view-tenant-info/'.$this->session->userdata('tenant_info_id'));
		exit;
	}
	
	function view_tenant_info() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$this->load->js('assets/themes/default/js/notify.min.js');
		$this->load->js('assets/themes/default/js/jquery.formatCurrency-1.4.0.min.js');
		$this->load->js('assets/themes/default/js/landlords/view-tenant-info.js');
		$this->session->set_userdata('tenant_info_id', $id);
		$this->load->model('landlords/tenants_handler');
		$this->load->model('landlords/listings_handler');
		
		$data['tenant_info'] = $this->tenants_handler->tenants_info($id);
		$data['home_items'] = $this->listings_handler->get_items_at_property($data['tenant_info']['listing_id']);
		$data['payments_set'] = $this->tenants_handler->check_landlord_payment_settings();
		
		$tenant_info = array(
			'ref_id'=>$id,
			'landlord_id'=>$this->session->userdata('user_id'), 
			'tenant_id'=>$data['tenant_info']['tenant_id']
		);
		$data['rent_payments'] = $this->tenants_handler->rental_payments($tenant_info); 
		if(empty($data['tenant_info'])) {
			$this->session->set_flashdata('error', 'No Tenant Found, Try Selecting Another Tenant.');
			redirect('landlords/my-tenants');
			exit;
		}

		$this->output->set_template('logged-in-landlord');
		
		$this->load->view('landlords/view-tenant-info', $data);		
	}
	
	function reassign_listing()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('group_id', 'Group', 'trim|min_length[1]|max_length[12]|xss_clean|required|integer');
		$this->form_validation->set_rules('listing_id', 'Listing Id', 'trim|min_length[1]|max_length[12]|xss_clean|required|integer');
		if ($this->form_validation->run() == TRUE) {	
			extract($_POST);
			$this->load->model('landlords/listings_handler');
			$data = $this->listings_handler->update_listing_group_id($group_id, $listing_id);
			$this->session->set_flashdata($data);
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect('landlords/edit-listing/'.$_POST['listing_id']);
		exit;
		
	}
	
	function tenant_rental_settings()
	{
		$this->form_validation->set_rules('payments_allowed', 'Payments Allowed', 'trim|min_length[1]|max_length[1]|xss_clean|required|alpha');
		$this->form_validation->set_rules('partial_payments', 'Partial Payment', 'trim|min_length[1]|max_length[1]|xss_clean|required|alpha');
		$this->form_validation->set_rules('rental_id', 'Rental Id', 'trim|min_length[1]|max_length[12]|xss_clean|required|integer');
		
		if ($this->form_validation->run() == TRUE) {
			extract($_POST);
			
			if($rental_id>0) {
				$data = array(
					'payments_allowed' => $payments_allowed,
					'partial_payments' => $partial_payments,
					'rental_id' => $rental_id
				);
			
				$this->load->model('landlords/tenants_handler');
				$results = $this->tenants_handler->tenant_settings($data);
				if($results) {
					$this->session->set_flashdata('success', 'Tenant settings saved');
				} else {
					$this->session->set_flashdata('error', 'Something went wrong, try again');	
				}
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, try again');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect('landlords/view-tenant-info/'.$this->session->userdata('tenant_info_id'));
		exit;
	}
	
	function link_rental_property()
	{
		$id = (int)$this->uri->segment(3);
		$this->check_if_loggedin(); // Checks if user is logged in	
		if(!empty($_POST['existing'])) {
			$this->form_validation->set_rules('existing', 'Existing Address', 'trim|max_length[15]|xss_clean|numeric');
		} else {
			$this->form_validation->set_rules('title', 'Title', 'trim|max_length[70]|xss_clean|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|max_length[70]|xss_clean|required');
			$this->form_validation->set_rules('city', 'City', 'trim|max_length[70]|xss_clean|required');
			$this->form_validation->set_rules('stateAbv', 'State', 'trim|max_length[2]|xss_clean|required');
			$this->form_validation->set_rules('zipCode', 'Zip', 'trim|max_length[10]|xss_clean|required');
			$this->form_validation->set_rules('bathrooms', 'Bathrooms', 'trim|max_length[4]|xss_clean|required');
			$this->form_validation->set_rules('bedrooms', 'Bedrooms', 'trim|max_length[4]|xss_clean|required');
			$this->form_validation->set_rules('sqFeet', 'sqFeet', 'trim|max_length[5]|xss_clean|numeric');
			$this->form_validation->set_rules('price', 'Rent', 'trim|max_length[10]|xss_clean|required');
			$this->form_validation->set_rules('deposit', 'Deposit', 'trim|max_length[10]|xss_clean|required');
			$this->form_validation->set_rules('map', 'Google Map', 'trim|xss_clean|max_length[1]');
			$this->form_validation->set_rules('desc', 'Description', 'trim|max_length[1000]|xss_clean'); 
			$this->form_validation->set_rules('featured_image', 'Featured Image', 'trim|max_length[2]|xss_clean'); 
			
			$this->form_validation->set_rules('garage', 'Garage', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('pets', 'Pets', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('central_air', 'Central Air', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('laundry_hook_ups', 'Laundry Hook Ups', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('off_site_laundry', 'Off Site Laundry', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('on_site_laundry', 'On Site Laundry', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('parking', 'Off  Street Parking', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('basement', 'Basement', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('single_lvl', 'Single Level Floor Plan', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('shed', 'Shed', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('park', 'Near a Park', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('inside_city', 'Within City Limits', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('outside_city', 'Outside City Limits', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('deck_porch', 'Deck / Porch', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('large_yard', 'Large Yard', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('fenced_yard', 'Fenced Yard', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('partial_utilites', 'Some Utilities Included', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('all_utilities', 'Utilities Included', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('appliances', 'Appliances Included', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('furnished', 'Fully Furnished', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('pool', 'Pool', 'trim|max_length[1]|xss_clean|alpha');
			$this->form_validation->set_rules('shopping', 'Shopping / Entertainment', 'trim|max_length[1]|xss_clean|alpha');
		}
			
		$map = '';
		$title = '';
		$bedrooms = '';
		$deposit = '';
		$sqFeet = '';
		$bathrooms = '';
		$desc = '';
		
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
		} else {
			if(!isset($featured_image)) {
				$featured_image = 0;
			}
			extract($_POST);
			
			$file_names = array();
			
			$config['upload_path'] = './listing-images/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '5000KB';
			$this->load->library('upload', $config);
			
			foreach($_FILES as $field => $file)
			{
				if($file['error'] == 0)
				{
					if ($this->upload->do_upload($field))
					{	
						$this->load->library('image_lib');
						$upload = $this->upload->data();
						
						// Resize The Image
						$configSize['image_library'] = 'GD2';
						$configSize['source_image']	= FCPATH.'listing-images/'.$upload['file_name'];
						$configSize['maintain_ratio'] = TRUE;
						$configSize['width']	 = 650;
						$configSize['height']	= 600;
						
						$this->image_lib->initialize($configSize);
						$this->image_lib->resize();
						unset($configSize);
						
						// Watermark The Image
						$configMark['source_image']	= FCPATH.'listing-images/'.$upload['file_name'];
						$configMark['wm_text'] = 'Copyright '.date('Y').' - Network 4 Rentals';
						$configMark['wm_type'] = 'text';
						$configMark['wm_font_path'] = './system/fonts/texb.ttf';
						$configMark['wm_font_size']	= '8';
						$configMark['wm_font_color'] = 'ffffff';
						$configMark['wm_vrt_alignment'] = 'bottom';
						$configMark['wm_hor_alignment'] = 'center';
						
						$this->image_lib->initialize($configMark);
						$this->image_lib->watermark();
						
						unset($configMark);
						$file_names[] = $upload['file_name'];
					}
					else
					{
						$errors = $this->upload->display_errors();
					}
				}
			}
			if(empty($map)) {
				$map = 'n';
			}
			$data = array(
				'title'=>$title,
				'details'=>$desc,
				'bedrooms'=>$bedrooms,
				'price'=>$price,
				'active'=>'n',
				'deposit'=>$deposit,
				'address'=>$address,
				'zipCode'=>$zipCode,
				'stateAbv'=>$stateAbv,
				'city'=>$city,
				'sqFeet'=>$sqFeet,
				'bathrooms'=>$bathrooms,
				'map_correct'=>$map,
				'details'=>$desc,
				'existing'=>$existing, 
				'rental_id' => $id,
				'images' => $file_names,
				'featured_image' => $featured_image,
				
				'garage' => $garage, 
				'pets' => $pets, 
				'central_air' => $central_air, 
				'laundry_hook_ups' => $laundry_hook_ups, 
				'off_site_laundry' => $off_site_laundry, 
				'on_site_laundry' => $on_site_laundry, 
				'parking' => $parking, 
				'basement' => $basement, 
				'single_lvl' => $single_lvl, 
				'shed' => $shed, 
				'park' => $park, 
				'inside_city' => $inside_city, 
				'outside_city' => $outside_city,
				'deck_porch' => $deck_porch,
				'large_yard' => $large_yard,
				'fenced_yard' => $fenced_yard,
				'partial_utilites' => $partial_utilites,
				'all_utilities' => $all_utilities,
				'appliances' => $appliances, 
				'furnished' => $furnished,
				'pool' => $pool,
				'shopping' => $shopping,
			);
	
			$this->load->model('landlords/listings_handler');
			$created = $this->listings_handler->add_tenant_property($data);
			
			$this->listings_handler->set_default_payment_settings($data['rental_id']);

			if($created) {
				$this->db->select('tenant_id, listing_id');
				$query = $this->db->get_where('renter_history', array('id'=>$id));
				if($query->num_rows()>0) {
					$row = $query->row();
					$this->db->where('tenant_id', $row->tenant_id);
					$this->db->where('rental_id', $data['rental_id']);
					$query = $this->db->update('all_service_request', array('listing_id'=>$row->listing_id));
				}
			
				$this->session->set_flashdata('success', 'Rental Property Has Been Added To Your Properties');
				$query = $this->db->get_where('renter_history', array('id'=>$id));
				if ($query->num_rows() > 0) {
					$row = $query->row_array(); 
					$query = $this->db->get_where('renters', array('id'=>$row['tenant_id']));
					if ($query->num_rows() > 0) {
						$row = $query->row_array(); 
					}
				}
				$email = $row['email'];
				$subject = 'Landlord Verified Your Info';
				$message = '<h3>Landlord Verified</h3><p>Your landlord has verified your address and possibly made minor changes to your current address with them. Login and check if any changes have been made to your address on record.</p><p>Login to you account by clicking the link or copying the link below.</p><br><a href="'.base_url().'renters/login">'.base_url().'renters/login</a>';
				$this->load->model('special/send_email');
				$this->send_email->sendEmail($email, $message, $subject);
				
				if(!empty($row['forwarding_email'])) {
					$email = $row['forwarding_email'];
					$subject = 'Landlord Verified Your Info';
					$message = '<h3>Landlord Verified</h3><p>Your landlord has verified your address and possibly made minor changes to your current link with them. There is nothing further to do....more... here ???';
					$this->send_email->sendEmail($email, $message, $subject);
				}
				
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			}
		}
		
		redirect('landlords/view-tenant-info/'.$id);
		exit();
	}
	
	function invite_tenant() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[80]|min_length[3]|xss_clean|required');
		$this->form_validation->set_rules('email2', 'Confirm Email', 'trim|max_length[80]|min_length[3]|xss_clean|required|matches[email]');
		$this->form_validation->set_rules('behalf', 'Behalf', 'trim|max_length[12]|xss_clean|integer');
		if($this->form_validation->run() == true) {
			extract($_POST);
			$this->load->model('landlords/user_account_handler');
			if(empty($behalf)) {
				$info = $this->user_account_handler->landlord_info();
				$bname = $info['bName'];
				if(empty($bname)) {
					$bname = $info['name'];
				}
			} else {
				$info = $this->user_account_handler->get_current_landlord_info($behalf);
				$bname = $info['sub_b_name'];
			}

			$unique_name = $info['unique_name'];
			$message = '<h2>'.$bname.' Has Sent You An Invite</h2>
						<p>We have signed up with Network 4 Rentals to help us become more effective while communicating with our tenants. Using Network 4 Rentals allows you to communicate with us day or night, at your convenience. We ask that you create a tenant account at <a href="https://www.network4rentals.com">N4R</a> to allow us to communicate online. Once you create an account go to "My Rental History" and click on "Add Landlord". Once there you will see a box that says "Search For Your Landlord". In that box if you search for <b>"'.$bname.'"</b> you will see our name pop up. Click our name and fill out the rest of the form to connect with us.</p>
						<p>If you have any questions contact us via the message system inside your account.</p>
						<br><br>
						<a href="'.base_url().'renters/link-landlord-rentals/'.$unique_name.'">Create Account</a>
				';
			$subject = $bname.' Has Invited You To Join N4R';
			
			// Add Activity To Landlord
			$this->load->model('landlords/fetch_activity_model');
			$action = 'Invited Tenant To Join - '.$email;
			$data = array('action' =>$action,'user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>'');
			$this->fetch_activity_model->add_activity_feed($data);
			$this->load->model('special/send_email');			
			if($this->send_email->sendEmail($email, $message, $subject, $alt_message = null)) { 
				$this->session->set_flashdata('success', 'Your Invitation Has Been Sent');
			} else {
				$this->session->set_flashdata('error', 'There Was An Error Sending Your Invitation, Try Again');
			}
		}
		redirect('landlords/edit-account');
		exit;
	}
	
	function edit_rental_info()
	{
		$id = (int)$this->uri->segment(3);
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[70]|xss_clean|required'); 
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[70]|xss_clean|required'); 
		$this->form_validation->set_rules('state', 'State', 'trim|min_length[2]|max_length[2]|xss_clean|required'); 
		$this->form_validation->set_rules('zip', 'Zip', 'trim|min_length[5]|max_length[70]|xss_clean|required'); 
		$this->form_validation->set_rules('move_in', 'Move In', 'trim|max_length[10]|xss_clean|required'); 
		$this->form_validation->set_rules('move_out', 'Move Out', 'trim|max_length[10]|xss_clean');   
		$this->form_validation->set_rules('lease', 'Lease Length', 'trim|max_length[70]|xss_clean|required'); 
		$this->form_validation->set_rules('day_rent_due', 'Rent Due Date', 'trim|max_length[2]|xss_clean|required|integer'); 
		$this->form_validation->set_rules('payments', 'Rent Per Month', 'trim|max_length[70]|xss_clean|required|numeric'); 
		$this->form_validation->set_rules('group_id', 'Reassign Manager', 'trim|max_length[12]|xss_clean|numeric'); 
		$this->form_validation->set_rules('deposit', 'Deposit', 'trim|max_length[8]|xss_clean|required|numeric'); 
		 
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
		} else {
			extract($_POST);

			if(isset($_FILES)) { // File Upload Handler
				//print_r($_FILES);
				if(!empty($_FILES['lease']['name'])) {
					$config['upload_path'] = './lease-uploads/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|PNG|JPG|JPEG|docx|vnd.oasis.opendocument.text';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					 
					$file = "lease";
					
					if($this->upload->do_upload($file)) {
						$upload = $this->upload->data();
						$file = $upload['file_name'];
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				}
			} 
			if(empty($error)) {
				if(!empty($file)) {
					$data = array('address'=>$address, 'city'=>$city, 'state'=>$state, 'zip'=>$zip, 'move_in'=>$move_in, 'move_out'=>$move_out, 'lease'=>$lease, 'group_id' => $group_id, 'payments'=>$payments, 'lease_upload'=>$file, 'deposit'=>$deposit, 'day_rent_due'=>$day_rent_due);
				} else {
					$data = array('address'=>$address, 'city'=>$city, 'state'=>$state, 'zip'=>$zip, 'move_in'=>$move_in, 'move_out'=>$move_out, 'lease'=>$lease, 'group_id'=>$group_id, 'payments'=>$payments, 'deposit'=>$deposit, 'day_rent_due'=>$day_rent_due);
				}
			
				$this->load->model('landlords/listings_handler');
				$results = $this->listings_handler->edit_rental_info($data, $id);
				if($results) {
					$this->session->set_flashdata('success', 'This Rental Property Has Been Updated');
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
				}
			} else {	
				$str = '';
				if(is_array($error)) {
					foreach($error as $val) {
						$str .= $val.'<br>';
					}
					$str = substr($str, 0, -4); 
					$this->session->set_flashdata('error', $str);
				} else {
					$this->session->set_flashdata('error', $error);
				}
				
			}
		}
		redirect('landlords/view-tenant-info/'.$id);
		exit;
	}
	
	function add_rental_item()  
	{	
		$id = (int)$this->uri->segment(3);
		$page = $this->uri->segment(4);
		
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('desc', 'Item Name', 'trim|max_length[70]|xss_clean|required'); 
		$this->form_validation->set_rules('modal_num', 'Model Number', 'trim|max_length[70]|xss_clean'); 
		$this->form_validation->set_rules('serial', 'Serial', 'trim|max_length[70]|xss_clean'); 
		$this->form_validation->set_rules('brand', 'Brand', 'trim|max_length[70]|xss_clean'); 
		$this->form_validation->set_rules('service_type', 'Service Type', 'trim|max_length[70]|xss_clean|required'); 
		if(empty($id)) {
			$this->form_validation->set_rules('id', 'Rental Id', 'trim|max_length[70]|xss_clean|required|integer'); 
		}
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
		} else {
			extract($_POST);
			$data = array('desc'=>$desc, 'modal_num'=>$modal_num, 'serial'=>$serial, 'brand'=>$brand, 'service_type'=>$service_type, 'renter_id'=>$id);
			if(!empty($page)) {
				$data['listing_id'] = $id;
			}
			$this->load->model('landlords/listings_handler');
			$this->load->model('special/user_uploads');
			
			if(!empty($_FILES['img']['name'])) {
				$image = $this->user_uploads->upload_image($_FILES['img'], 'img');
				if(isset($image['success'])) {
					$data['image'] = $image['success']['system_path'];
				}
			}

			$results = $this->listings_handler->add_rental_item($data);

			if($results) {
				$this->session->set_flashdata('success', 'This Rental Property Has Been Updated');
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			}
		}

		redirect($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	function delete_rental_item()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$delete_id = (int)$this->uri->segment(4);
		$this->load->model('landlords/listings_handler');
		$results = $this->listings_handler->delete_rental_item($delete_id);
		if($results) {
			$this->session->set_flashdata('success', 'Item Deleted From Rental Property');
		} else {
			$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
		}
		redirect($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	function view_tenant_checklist()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$id = (int)$this->uri->segment(3);
		$this->output->set_template('logged-in-landlord');
		$this->load->model('landlords/tenants_handler');
		$data['checklist_data'] = $this->tenants_handler->show_checklist_details($id);
		if($data['checklist_data'] == false) {
			$this->session->set_flashdata('error', 'Check List Not Found For That User, Try Again');
			$back = $_SERVER['HTTP_REFERER'];
			redirect($back);
			exit;
		}
		$this->load->view('landlords/view-checklist', $data);
	}
	
	public function property_items()
	{		
		$this->output->set_template('logged-in-landlord');
		$this->load->js('assets/themes/default/js/notify.min.js');
		$this->load->js('assets/themes/default/js/lightboxdistrib.min.js');
		$this->load->css('assets/themes/default/css/alertify.core.css');
		$this->load->css('assets/themes/default/css/easybox.min.css');
		$this->check_if_loggedin(); // Checks if user is logged in
		
		if($_SERVER['REQUEST_METHOD']=='POST') {
			$this->form_validation->set_rules('desc', 'Item Name', 'trim|max_length[70]|xss_clean|required'); 
			$this->form_validation->set_rules('modal_num', 'Model Number', 'trim|max_length[70]|xss_clean'); 
			$this->form_validation->set_rules('serial', 'Serial', 'trim|max_length[70]|xss_clean'); 
			$this->form_validation->set_rules('brand', 'Brand', 'trim|max_length[70]|xss_clean'); 
			$this->form_validation->set_rules('service_type', 'Service Type', 'trim|max_length[70]|xss_clean|required'); 
			$this->form_validation->set_rules('id', 'Item Id', 'trim|max_length[15]|xss_clean|required|integer'); 
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				
				extract($_POST);
				$data = array('desc'=>$desc, 'modal_num'=>$modal_num, 'serial'=>$serial, 'brand'=>$brand, 'service_type'=>$service_type, 'id'=>$id);
				if(!empty($page)) {
					$data['listing_id'] = $id;
				}
				$this->load->model('landlords/listings_handler');
				$this->load->model('special/user_uploads');
				
				if(!empty($_FILES['img']['name'])) {
					$image = $this->user_uploads->upload_image($_FILES['img'], 'img', true);
					if(isset($image['success'])) {
						$data['image'] = $image['success']['system_path'];
					}
				}
				
			
				$results = $this->listings_handler->edit_rental_item($data);

				if($results) {
					$this->session->set_flashdata('success', 'This Rental Property Has Been Updated');
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
				}
			}
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
		
		$listing_id = (int)$this->uri->segment(3);
		if(empty($listing_id)) {
			redirect('landlords/manage-listings');
			exit;
		}
		$this->load->model('landlords/listings_handler');
		$data['rental'] = $this->listings_handler->get_listing_info($listing_id);
		$data['items'] = $this->listings_handler->get_items_at_property($listing_id);
		
		$this->load->view('landlords/property-items', $data);
	}
	
	function send_group_message()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('message', 'Message', 'trim|max_length[700]|xss_clean|required'); 
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
		} else {
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
			} // end files
			
			if(empty($error)) {
				$this->load->model('special/messaging_handler');
				
				$temp_id = $this->session->userdata('temp_id');
				if(!empty($temp_id)) {
					$id = $this->messaging_handler->get_sub_admin_id($this->session->userdata('temp_id'));
				} else {
					$id = $this->session->userdata('user_id');
				}
				
				// GET ALL CURRENT TENANTS
				$this->load->model('landlords/user_account_handler');
				$tenantInfo = $this->user_account_handler->get_current_tenants($id);

				$count = 0;
				if(!empty($tenantInfo)) {
					foreach($tenantInfo as $val) {
						$data = array(
							'rental_id' 			=> $val['id'],
							'message' 				=> $message,
							'tenant_id' 			=> $val['tenant_id'],
							'hash_mail' 			=> md5($this->session->userdata('user_id').date('Y-m-d H:i:m')),
							'attachment'			=> $file,
							'sent_by'				=> '1',
							'landlord_id'			=> $id,
							'actual_landlord_sent'  => $this->session->userdata('user_id')
						);
	
						$sent = $this->messaging_handler->send_message($data);
						if($sent) {
							$count++;
						}
					}
				}
				if($count>0) {
					$this->session->set_flashdata('success', 'Your Message Was Sent Out To '.$count.' Of Your Tenants');
					redirect('landlords/my-tenants');
					exit;
				} else {
					$this->session->set_flashdata('error', 'Message Not Sent, Try Again');
					redirect('landlords/my-tenants');
					exit;
				}
			} // end $error	
		}
	}
	
	function my_messages()
	{
	
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->load->model('landlords/admin_switch_handler');
		$data['switches'] = $this->admin_switch_handler->my_groups();
		
		$this->load->model('landlords/internal_email_handler');
		$this->load->library('pagination');
		
		$config['base_url'] = base_url().'landlords/my-messages';
		
		$config['total_rows'] = $this->internal_email_handler->email_count();
		
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
		
		$data["results"] = $this->internal_email_handler->fetch_parent_emails($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
	
		$this->output->set_template('logged-in-landlord');
		
		$this->load->view('landlords/my-messages', $data);

	}
	
	function reply_messages() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('hidden-id', 'hidden-id', 'trim|max_length[40]|xss_clean|required|integer'); 
		$this->form_validation->set_rules('message', 'Message', 'trim|max_length[700]|xss_clean|required'); 
		
		if ($this->form_validation->run() == FALSE) {	
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
				extract($_POST);
				$data = array('message'=>$message, 'hidden_id'=>$hidden_id, 'attachment'=>$file, 'actual_landlord_sent'=>$this->session->userdata('user_id'));
				$this->load->model('landlords/internal_email_handler');
				$results = $this->internal_email_handler->reply_message($data);

				if(!empty($results)) {
					$query = $this->db->get_where('renters', array('id'=>$results['tenant_id']));
					if ($query->num_rows() > 0) {
						$row = $query->row_array();
						$email = $row['email'];
						$message = '<h2>New Message From Landlord</h2>
						<p>Your landlord has sent you a new message. Login if you have not done so already and view you message from them....more stuff here .. Click the link below to go to your messages.</p>
						<p><a href="'.base_url().'renters/message-landlord/'.$results['rental_id'].'">View Messages</a></p>';
						$subject = 'New Message From Landlord On N4R';
						$this->load->model('special/send_email');
						$this->send_email->sendEmail($email, $message, $subject);
						if(!empty($row['forwarding_email'])) {
							$email = $row['forwarding_email'];
							$this->send_email->sendEmail($email, $message, $subject);
						}
						$this->load->model('landlords/fetch_activity_model');
						// Add Activity To Tenants
						$data = array('action' =>'New Message From Landlord','user_id' =>$results['tenant_id'],'type'=>'renters','action_id' =>$results['rental_id']);
						$this->fetch_activity_model->add_activity_feed($data);	
						// Add Activity To Landlord
						$data = array('action' =>'Replied To Tenants Message','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>$results['message_id']);
						$this->fetch_activity_model->add_activity_feed($data);	
					}
					$this->session->set_flashdata('success', 'Message Sent To The Tenant');
				} else {
					$this->session->set_flashdata('error', 'Message Not Sent, Try Again');
				}
			} else {
				$this->session->set_flashdata('error', 'Message Not Sent, <br>'.$error);
			}
			
		}
		
		redirect('landlords/my-messages');
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
		$html = $this->load->view('landlords/print-messages', $data, true); // Add Argument true after data
		pdf_create($html, 'Printed Messages From N4R'); 
	}
	
	function send_new_messager()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('admin', 'Admin Id', 'trim|max_length[15]|xss_clean|integer|required');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|max_length[70]|xss_clean|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|max_length[700]|xss_clean|required');
		
		if($this->form_validation->run() == TRUE) {
		
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

			
			extract($_POST);
			$this->load->model('landlords/internal_email_handler');
			if($admin == 0) {
				$admin = $this->session->userdata('user_id');
			}
	
			$count = 0;
			foreach($tenants as $val) {
				$tenant_id = $this->internal_email_handler->get_tenant_id($val);
				if($tenant_id) {
					$data = array(
						'message' => $message,
						'tenant_id' => $tenant_id,
						'rental_id' => $val,
						'landlord_viewed' => date("Y-m-d H:i:s"),
						'hash_mail' => md5($_SERVER['REMOTE_ADDR'].date('Y-m-d h:i')),
						'attachment' => $file,
						'sent_by' => '1',
						'parent_id' => NULL,
						'subject' => $subject,
						'landlord_id' => $admin,
						'actual_landlord_sent' => $this->session->userdata('user_id')
					);
					$sent = $this->internal_email_handler->add_new_message($data);
					if($sent) {
						$count++;
					}
				}
				
				$query = $this->db->get_where('renters', array('id'=>$tenant_id));
				if ($query->num_rows() > 0) {
					$row = $query->row_array();
				}
				
				$email = $row['email'];
				$email_message = '<h2>New Message From Landlord</h2>
				<p>Your landlord has sent you a new message. Login if you have not done so already and view you message from them....more stuff here .. Click the link below to go to your messages.</p>
				<p><a href="'.base_url().'renters/message-landlord/'.$val.'">View Messages</a></p>';
				$email_subject = 'New Message From Landlord On N4R';
				$this->load->model('special/send_email');
				$this->send_email->sendEmail($email, $email_message, $email_subject);				
				if(!empty($row['forwarding_email'])) {
					$email = $row['forwarding_email'];
					$email_message = '<h2>New Message From Landlord</h2>
					<p>Your service request has been completed.... more info here..... To view the service request click the link below.</p>
					<p><a href="'.base_url().'renters/view-request/'.$val.'">View Messages</a></p>';
					$email_subject = 'New Message From Landlord On N4R';
					$this->send_email->sendEmail($email, $email_message, $email_subject);
				}
				
				$this->load->model('landlords/fetch_activity_model');
				$data = array('action' =>'New Message From Landlord','user_id' =>$tenant_id,'type'=>'renters','action_id' =>$val);
				$this->fetch_activity_model->add_activity_feed($data);				
				unset($data);
			}
		
			$this->load->model('landlords/fetch_activity_model');
			$data = array('action' =>'Sent ('.$count.') Tenant A Message','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>'');
			$this->fetch_activity_model->add_activity_feed($data);
			
			
			
			$this->session->set_flashdata('success', 'Message Has Been Sent To '.$count.' Tenants You Selected.');
		} else {
			$this->session->set_flashdata('error', 'Something Went Wrong,<br>'.validation_errors());
		}
		redirect('landlords/my-messages');
		exit;
	}
	
	function remove_admin_from_account()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->form_validation->set_rules('sub_b_name', 'Sub Business Name', 'trim|max_length[50]|min_length[3]|xss_clean');
		$this->form_validation->set_rules('sub_group_id', 'Sub Group Id', 'trim|max_length[12]|xss_clean|integer|required');
		$this->form_validation->set_rules('new_email', 'Email', 'trim|max_length[50]|xss_clean|valid_email');
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			$this->load->model('landlords/landlord_admin_handler');
			$results = $this->landlord_admin_handler->remove_admin_accounts($sub_b_name, $sub_group_id, $new_email);
		}	
		$errors = validation_errors();
		if(!empty($errors)) {
			$this->session->set_flashdata('error', $errors);
		}
		redirect('landlords/my-admins');
		exit;
	}
	
	function payment_settings()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');
		$this->load->js('assets/themes/default/js/jquery.formatCurrency-1.4.0.min.js');
		$this->load->model('landlords/payment_settings');
		$this->load->model('landlords/user_account_handler');
		$landlord_data = $this->user_account_handler->landlord_info();
		
		$this->load->library('encrypt');
		
		$data['settings'] = $this->payment_settings->check_settings();
		$data['groups'] = $this->payment_settings->get_group_admins();
		$silent_link = $this->encrypt->encode($landlord_data['id'].'|'.$landlord_data['user']);
		$data['silent_link'] = str_replace(array('+', '/', '='), array('-', '_', '~'), $silent_link);;
		//decode the api keys
		$data['settings']['net_key'] = $this->encrypt->decode($data['settings']['net_key']);
		$data['settings']['net_hash'] = $this->encrypt->decode($data['settings']['net_hash']);
		$data['settings']['net_api'] = $this->encrypt->decode($data['settings']['net_api']);
		$data['user_settings'] = $landlord_data;
		
		$this->form_validation->set_rules('net_key', 'Transaction key', 'trim|max_length[255]|xss_clean');
		$this->form_validation->set_rules('net_hash', 'MD5 Hash', 'trim|max_length[255]|xss_clean');
		$this->form_validation->set_rules('net_api', 'API Login id', 'trim|max_length[255]|xss_clean');
		$this->form_validation->set_rules('allow_payments', 'Allow Payments Online', 'trim|max_length[1]|xss_clean|required');
		$this->form_validation->set_rules('checks', 'Accept E-Checks', 'trim|max_length[1]|xss_clean');
		$this->form_validation->set_rules('credit_card', 'Accept Credit Cards', 'trim|max_length[1]|xss_clean');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			//encode the api keys
			if(empty($net_key) OR empty($net_api)) {
				$allow_payments = 'n';
			} else {
				$net_key = $this->encrypt->encode($net_key);
				$net_hash = $this->encrypt->encode($net_hash);
				$net_api = $this->encrypt->encode($net_api);
			}
			
			if(empty($checks)) {
				$checks = 'n';
			}
			if(empty($credit_card)) {
				$credit_card = 'n';
			}
			
			$settings = array('net_key'=> $net_key,'net_hash'=> $net_hash,'net_api'=> $net_api,'allow_payments'=> $allow_payments, 'accept_cc'=>$credit_card, 'accept_echeck'=>$checks, 'landlord_id'=>$this->session->userdata('user_id'));
			
			$results = $this->payment_settings->update_payment_settings($settings);
			if($results) {
				$this->session->set_flashdata('success', 'Your Settings Have Been Saved');
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
			}
			redirect('landlords/payment-settings');
			exit;
		}

		$this->load->view('landlords/payment-settings', $data);
	}
	
	public function save_default_payment_settings() 
	{ 
		$this->form_validation->set_rules('default_partial_payments', 'Partial Payments', 'trim|min_length[1]|max_length[1]|xss_clean|required|alpha');
		$this->form_validation->set_rules('default_min_payment', 'Min Payment', 'trim|min_length[1]|max_length[8]|xss_clean|required');
		$this->form_validation->set_rules('default_auto_pay_discount', 'Auto Pay Discount', 'trim|min_length[1]|max_length[8]|xss_clean|required');
		$this->form_validation->set_rules('online_payment_discount', 'Online Payment Discount', 'trim|min_length[1]|max_length[8]|xss_clean|required');
		if($this->form_validation->run() == TRUE) {
			$this->load->model('landlords/user_account_handler');
			$feedback = $this->user_account_handler->update_landlord_info($_POST);
			if($feedback) {
				$this->session->set_flashdata('success', 'Default payment settings saved');
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
		} else {
			$this->session->set_flashdata('error', validation_errors());
		}
		redirect('landlords/payment-settings');
		exit;
	}
	
	function group_payment_selection()
	{
		$this->form_validation->set_rules('payment_group', 'Group Payment', 'trim|min_length[1]|max_length[255]|xss_clean|required|numeric');
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			if($payment_group==0) {
				$this->session->unset_userdata('payment_group');
			} else {
				$query = $this->db->get_where('admin_groups', array('main_admin_id'=>$this->session->userdata('user_id'), 'id'=>$payment_group));
				if($query->num_rows()>0) {
					$this->session->set_userdata('payment_group', $payment_group);
				} else {
					$this->session->set_flashdata('error', 'Invalid Group Selection, Try Again');
				}
			}
		}
		redirect('landlords/payment_settings');
		exit;
	}
	
	function authorize_details()
	{
		$this->load->model('landlords/payment_settings');
		$this->payment_settings->have_details();
		redirect('landlords/payment-settings');
		exit;
	}
	
	function search_service_requests() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		
		$this->form_validation->set_rules('reassign', 'Reassign To', 'trim|max_length[12]|xss_clean|integer');
		$this->form_validation->set_rules('admin_id', 'Admin', 'trim|max_length[12]|xss_clean|integer');
		$this->form_validation->set_rules('admin_id', 'Admin', 'trim|max_length[12]|xss_clean|integer');
		$this->form_validation->set_rules('admin_id', 'Admin', 'trim|max_length[12]|xss_clean|integer');
		
		if($this->form_validation->run() == TRUE) {
			/*
				Date From:
				Date To:
				Address:
				Type Of Service:
			*/
		} else {
			
		}
	}
	
	function terms_of_service()
	{
		$this->output->set_template('landlord-not-logged-in');
		$this->load->view('landlords/terms-of-service');
	}
	
	function view_message() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');
		$id = (int)$this->uri->segment(3);
		
		$this->load->model('landlords/internal_email_handler');
		$data['results'] = $this->internal_email_handler->get_emails_nojson($id);
		
		if(!empty($data['results'])) {
			
		} else {
		
		}
		$this->load->view('landlords/view-message', $data);
	}

	function message_tenant() 
	{
		$this->output->set_template('logged-in-landlord');
		$this->check_if_loggedin(); // Checks if user is logged in
		
		/* NEW */
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->session->set_userdata('date_to_msg', $_POST['date_to']);
			$this->session->set_userdata('date_from_msg', $_POST['date_from']);
		}
		
		$args = array(
			'rental_id' => (int)$this->uri->segment(3),
			'type' 		=> 'landlord',
			'offset' 	=> (int)$this->uri->segment(4)
		);
		
		$this->load->model('special/messaging_modal');
		$data = $this->messaging_modal->show_messages($args); 
		
		if(empty($data['message_to'])) {
			redirect('landlords/my-tenants');
			exit;
		}
		
		$this->load->view('landlords/view-messages', $data);
	}
	
	function send_new_message()
	{
		$this->check_if_loggedin();
		$this->form_validation->set_rules('message', 'Message', 'trim|max_length[1500]|xss_clean|required');
		if($this->form_validation->run() == TRUE) {
			$this->load->model('special/messaging_modal');
			
			$args = array(
				'rental_id' => (int)$this->uri->segment(3),
				'type' 		=> 'landlord',
				'msg' 		=> $_POST['message'],
				'file' 		=> $_FILES['file']
			);

			$this->messaging_modal->build_message($args);
	
		} else {
			$this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
		}
		redirect('landlords/message-tenant/'.$args['rental_id']);
		exit;
	}
	
	public function reset_dates_msg($id) // Resets Dates In The Activity Page
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->session->unset_userdata('date_to_msg');
		$this->session->unset_userdata('date_from_msg');
		$this->session->set_flashdata('reset', '<div class="alert alert-success"><p><b>Success:</b> Dates have been reset</p></div>');
		redirect('landlords/message-tenant/'.$id);
		exit; 
	}	
	
	public function view_landlord()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');
		$landlord_id = (int)$this->uri->segment(3);
		$this->load->model('landlords/user_account_handler');
		$data['info'] = $this->user_account_handler->find_landlord_info($landlord_id);
		$this->load->view('landlords/view-landlord', $data);
	}
	
	public function videos() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');
		$this->load->view('landlords/videos');
	}
	
	function record_keeping()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');		
		$this->load->css('assets/themes/default/css/admins.css');
		$this->load->css('assets/themes/default/css/jquery.cleditor.css');
		$this->load->js('assets/themes/default/js/jquery.cleditor.min.js');
		$this->load->js('assets/themes/default/js/admin-only.js');
		//checks to see if admin is here
		$admins = array('23', '73', '156', '80');
		if(!in_array($this->session->userdata('user_id'), $admins)) {
			redirect('landlords/logout');
			exit;
		}
		 
		$this->load->model('landlords/admins_only');
		$data['tenants_sign_ups'] = $this->admins_only->get_tenants_signup_dates();
		$data['landlords_sign_ups'] = $this->admins_only->get_landlords_signup_dates();
		$data['total_tenants'] = $this->admins_only->count_total_tenants();
		$data['total_landlords'] = $this->admins_only->count_total_landlords();
		$data['tenants_by_zip'] = $this->admins_only->get_tenants_by_zip();
		$data['landlords_by_zip'] = $this->admins_only->get_landlords_by_zip();
		$data['inactive_landlords'] = $this->admins_only->get_inactive_landlords();
		$data['inactive_tenants'] = $this->admins_only->get_inactive_tenants();
		
		$this->load->view('landlords/admins-only', $data);	
		
	}
	
	function assign_tenant_group_admin()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$rental_id = (int)$this->uri->segment(3);
		$this->form_validation->set_rules('admin', 'Assign Manager', 'trim|max_length[11]|xss_clean|required|numeric|min_length[1]'); 
		if ($this->form_validation->run() == FALSE) {
			
		} else {
			extract($_POST);
			if(!empty($rental_id)) {
				$data = array('rental_id'=>$rental_id, 'admin_id'=>$admin);
				$this->load->model('landlords/landlord_admin_handler');
				$results = $this->landlord_admin_handler->assign_admin($data);
				if($results) {
					$this->session->set_flashdata('success', 'Tenant Has Been Assigned To Your Admin');
					$this->load->model('landlords/fetch_activity_model');
					$data = array('action' =>'Assigned Manager To Tenant','user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>$rental_id);
					$this->fetch_activity_model->add_activity_feed($data);
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Try Again 1');
				}
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong, Try Again 2 '.$rental_id.' '.$admin);
			}
			
		}
		redirect('landlords/view-tenant-info/'.$rental_id);
		exit;
	}
	
	function send_inactive_email()
	{
		extract($_POST);
		$email = $email_email;
		$message = $email_body;
		if($this->sendEmailWithReply($email, $message, $subject)) {
			$this->session->set_flashdata('success', 'Email Sent To '.$email);
		} else {
			$this->session->set_flashdata('success', 'Email Sent To '.$email);
		}
		redirect('landlords/record-keeping');
		exit;
	}
	
	function sendEmailWithReply($email, $message, $subject)
	{
		
		$this->load->library('email');
		
		$config['mailtype'] = 'html';
		
		$this->email->initialize($config);
		
		$this->email->from('ron.palmer@network4rentals.com', 'Ron Palmer');
		$this->email->to($email);   

		$this->email->subject($subject);
		$message = $this->email_format($message);
		$this->email->message($message);	
		
		if($this->email->send()) {
			return true;
		} else {
			return false;
		}
		
	}	
	
	function contractor_click()
	{
		$unique_name = $this->uri->segment(4); //unique name
		$ad_id = $this->uri->segment(3); //id
		
		$this->db->select('clicks');
		$results = $this->db->get_where('contractor_zip_codes', array('id'=>$ad_id));
		if($results->num_rows()>0) {
			$row = $results->row();
			$clicks = (int)$row->clicks;
			if(empty($clicks)) {
				$clicks = 1;
			} else {
				$clicks = $clicks+1;
			}
			$this->db->where('id', $ad_id);
			$this->db->update('contractor_zip_codes', array('clicks'=>$clicks));
			
			header('location: http://n4r.rentals/'.$unique_name);
			exit;			
		} else {
			$this->session->set_flashdata('error', 'No Website Found For That Business, Try Again');
			redirect('landlord/all-service-requests');
			exit;
		}
	}
	
	public function resources()
	{
		$this->output->set_template('logged-in-landlord');
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->load->view('landlords/resources');
	}
	
	public function contractor_search()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');		
		$this->form_validation->set_rules('zip', 'Zip Code', 'required|trim|min_length[5]|max_length[5]|xss_clean');
		$this->form_validation->set_rules('radius', 'Radius', 'required|trim|min-length[1]|max_length[2]|xss_clean');
		$this->form_validation->set_rules('serviceType', 'Service Type', 'required|trim|min-length[6]|max_length[35]|xss_clean');
	
		if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('landlords/resources');
			exit;
		} else {
			extract($_POST);
			$data['searched'] = array('zip'=>$zip, 'radius'=>$radius, 'service'=>$serviceType);
			$this->load->model('landlords/resources_handler');
			$data['results'] = $this->resources_handler->search_contractors($data['searched']);
			
			$this->load->view('landlords/contractor-search', $data);
		}
		
	}
	
	function send_admin_test_email()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$admins = array('23', '73', '75', '80');
		if(!in_array($this->session->userdata('user_id'), $admins)) {
			redirect('landlords/logout');
			exit;
		}
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from('no-reply@network4rentals.com', 'No Reply');
		$subject = 'Testing Emails';
		$list = array('ron.network4rentals@gmail.com', 'TESTN87@YAHOO.COM', 'BRIAN.WORKMAN@ROCKETMAIL.COM', 'n4rlandlord@gmail.com', 'n4rtest@outlook.com', 'mmidwaytap@midohio.twbc.com', '7404037661@tmomail.net', 'palmer9810@roadrunner.com');
		$this->email->to($list); 
		$message = '<h2>Test Emails</h2><p>This is a test sent from the admin section of Network4Rentals to see if emails are routing correctly.</p>';
		
		$this->email->subject($subject);
		$message = $this->email_format($message);
		$this->email->message($message);	
		
		

		if($this->email->send()) {
			$this->session->set_flashdata('success', 'Test Emails Sent To Ron\'s Emails');
		} else {
			$this->session->set_flashdata('error', 'Test Emails Not Sent, Try Again');
		}
		redirect('landlords/record-keeping');
		exit;
	}	
		
	function online_rent_payment_setup()
	{
		$this->output->set_template('landlords/no-menu-page');
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->load->view('landlords/authorize-signup');
	}
	
	function payment_data()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');
		
		$year = $this->uri->segment(3);
		$month = $this->uri->segment(4);
		if(empty($month)) {
			$month = date('m');
		}	
		if(empty($year)) {
			$year = date('Y');
		}
		
		$prefs['template'] = '
			{table_open}<table class="calendar table table-responsive table-stripes text-center">{/table_open}
			{week_day_cell}<th class="day_header">{week_day}</th>{/week_day_cell}
			{heading_previous_cell}<th><a class="btn btn-primary pull-left" href="{previous_url}"><< Previous </a></th>{/heading_previous_cell}
			{heading_next_cell}<th><a class="btn btn-primary pull-right" href="{next_url}">Next >></a></th>{/heading_next_cell}
			{cal_cell_content}<span class="day_listing">{day}</span><ul class="cal-event-list">{content}</ul>{/cal_cell_content}
			
			{cal_cell_content_today}<div class="today"><span class="day_listing">{day}</span><ul class="cal-event-list">{content}</ul></div>{/cal_cell_content_today}
			{cal_cell_no_content}<span class="day_listing">{day}</span>&nbsp;{/cal_cell_no_content}
			{cal_cell_no_content_today}<div class="today"><span class="day_listing">{day}</span></div>{/cal_cell_no_content_today}
		'; 
		
		$prefs['show_next_prev'] = true;
		$prefs['next_prev_url'] = base_url().'landlords/payment-data/'; 
		$this->load->library('calendar', $prefs);
		
		$this->load->model('landlords/payment_handler');
		$params = array('month'=>$month, 'year'=>$year);
		$data['events'] = $this->payment_handler->payment_data($params);

		$data['cal'] = $this->calendar->generate($year, $month, $data['events']);
		
		$this->load->view('landlords/payment-data', $data);
	}
	
	function view_daily_payments()
	{
		$this->load->js('assets/themes/default/js/notify.min.js');
		$this->load->js('assets/themes/default/js/landlords/payment-notes.js');
		$this->load->js('assets/themes/default/js/landlords/daily-payments.js');
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');
		
		$year = $this->uri->segment(3);
		$month = $this->uri->segment(4);
		$day = $this->uri->segment(5);
		
		$this->load->model('landlords/payment_handler');
		$details = $this->payment_handler->retrieve_payments_by_day($year, $month, $day);
		
		if(!empty($details)) {
			$data['table'] = $details;
		} else {
			//NO DATA FOUND KICK THEM BACK TO CALENDAR PAGE
			$this->session->set_flashdata('error', 'No Payments Found For That Date');
			redirect('landlords/payment-data/'.$year.'/'.$month);
			exit;
		}
		
		$data['date'] = array(
			'year'=>$year,
			'month'=>$month,
			'day'=>$day
		);
		
		
		
		$this->load->view('landlords/view-payments-by-day', $data);
	}
	
	public function create_landlord_account()
	{
		$this->output->set_template('landlords/create-landlord-account');
		$this->load->js('assets/themes/default/js/notify.min.js');
		$cookie = $this->input->cookie('logged_in');
		if($cookie==1) {
			redirect('landlords/activity');
			exit;
		}
		$this->session->sess_destroy();
		if($this->session->userdata('logged_in'))
		{
			redirect('landlords/activity');
		}
		else 
		{
			$this->load->view('landlords/create-account');
		}
	}
	
	public function cancel_auto_payments() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$backToPage = $_SERVER['HTTP_REFERER'];
		
		$tenant_id = (int)$this->uri->segment(3);
		$sub_id = (int)$this->uri->segment(4);
		if(!empty($tenant_id) && !empty($sub_id)) {
			$this->load->model('landlords/payment_handler');
			$this->payment_handler->cancel_subscription($tenant_id, $sub_id);			
		} else {
			$this->session->set_flashdata('error', 'There was an error processing your request, try again.');
		}
				
		redirect($backToPage);
		exit;
	}
	
	public function silent_post_url() {
		
		$this->output->set_template('json');
		//CHECK WHO THE INCOMING TRANSACTIONS ARE COMING FROM
		$this->load->library('encrypt');
		$hash = $this->uri->segment(3); //ENCRYPTED USER ID AND USERNAME

		$this->load->model('landlords/payment_handler');		
		
		
		
		if(!empty($hash)) { // MAKE SURE HASH IS NOT EMPTY
			$hash = str_replace(array('-', '_', '~'), array('+', '/', '='), $hash); //CONVERTED TO UNFRIENDLY URL FROM FRIENDLY URL
			$un_encrypt = $this->encrypt->decode($hash); 
			$hash_array = explode('|', $un_encrypt); // $hash_array[0] == id // $hash_array[1] == username
	
			if($hash_array[0]>0 && !empty($hash_array[1])) { //CHECK TO MAKE SURE BOTH HOLD PROPER VALUES
				if($this->payment_handler->check_silent_post_link($hash_array)) { //IF USERNAME AND ID MATCH UP IN DATABASE
					echo json_encode($_POST);
					$this->payment_handler->log_auth_data($_POST);
					if(!empty($_POST)) { //IF POST VARIABLES ARE PRESENT
						foreach($_POST as $key => $val) {
							$data[$key] = $val;
						}
						$data['landlord_id'] = $hash_array[0];
						$this->payment_handler->log_auth_data($data);
						$autopay = 'n';
						if($_POST['x_response_code'] == 1) {
							$update_data['status'] = 'Complete';
							$autopay = 'y';
						} elseif($_POST['x_response_code'] == 2) {
							$update_data['status'] = 'Payment Declined';
						} elseif($_POST['x_response_code'] == 3) {
							$update_data['status'] = 'Payment Expired';
						} elseif($_POST['x_response_code'] == 4) { 
							$update_data['status'] = 'Held For Review';
							$autopay = 'y';
						} else {
							$update_data['status'] = 'Unknown';
						}
						
						$update_data['name'] = $_POST['x_first_name'].' '.$_POST['x_last_name'];
						$update_data['trans_id'] = $_POST['x_trans_id'];
						$update_data['id'] = $hash_array[0];
						$update_data['amount'] = $_POST['x_amount'];
						$update_data['landlord_id'] = $hash_array[0];
						$update_data['recurring_payment'] = $autopay;
						$update_data['sub_id'] = $_POST['x_subscription_id'];
						
						if(!empty($update_data)) {
							$this->payment_handler->update_payment_history($update_data);
						}
						
					}
				} else {
					redirect('landlords/logout');
				exit;
				}
			} else {
				redirect('landlords/logout');
				exit;
			}
		}
		
	}
	
	public function view_association_events() 
	{
		$assoc_id = (int)$this->uri->segment(3);
		if($assoc_id>0) {
			$this->db->select('unique_name');
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$assoc_id, 'type'=>'association'));
			$row = $results->row();
			if(!empty($row->unique_name)) {
				redirect('http://n4r.rentals/'.$row->unique_name);
				exit;
			}
		}
		$this->session->set_flashdata('error', 'Landlord association not found');
		redirect('landlords/activity');
		exit;
		
	}
	
	
	public function assoc_invite() 
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$accepted = (int)$this->uri->segment(3); // if value is 2 (accepted) else 1 (declined)
		$id = (int)$this->uri->segment(4);
		if($accepted>0 && $id>0) {
			$this->load->model('landlords/associations');
			$result = $this->associations->acceptInvite($id, $accepted);
			if($result) {
				$this->session->set_flashdata('success', 'Invite Accepted');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, try again');
			}
		} else {
			$this->session->set_flashdata('error', 'Something went wrong, try again');
		}
		redirect('landlords/activity');
		exit;
	}
	
	public function delete_public_image()
	{
		$this->check_if_loggedin();
		$id = (int)$this->uri->segment(3);
		if(!empty($id)) {
			$this->load->model('landlords/public_page_handler');
			if($this->public_page_handler->delete_public_image($id)) {
				$this->session->set_flashdata('success', 'Background image deleted successfully');
			} else {
				$this->session->set_flashdata('error', 'Invalid selection, try again');
			}
		} else {
			$this->session->set_flashdata('error', 'Invalid selection, try again');
		}
		redirect('landlords/public-page-settings');
		exit;
	}
	
	public function my_calendar()
	{
		$this->check_if_loggedin();
		$this->output->set_template('logged-in-landlord');
		
		$this->load->js('assets/themes/default/js/fullcalendar.min.js');
		$this->load->css('assets/themes/default/css/fullcalendar.css');
		$this->load->js('assets/themes/default/js/jquery-ui.custom.min.js');
		$this->load->js('assets/themes/default/js/landlords/my-calendar.js');
		
		$this->load->view('landlords/my-calendar');
	}
	
	public function supply_house_search()
	{
		$this->check_if_loggedin(); // Checks if user is logged in
		$this->output->set_template('logged-in-landlord');		
		$this->form_validation->set_rules('zip', 'Zip Code', 'required|trim|min_length[5]|max_length[5]|xss_clean|integer');
		$this->form_validation->set_rules('radius', 'Radius', 'required|trim|min-length[1]|max_length[2]|xss_clean|integer');
		$this->form_validation->set_rules('serviceType', 'Service Type', 'trim|min-length[1]|max_length[2]|xss_clean|integer');
		
		$this->load->js('assets/themes/default/js/landlords/resource.js');
		
		if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('landlords/resources');
			exit;
		} else {
			extract($_POST);
			$data = array('zip'=>$zip, 'radius'=>$radius, 'service'=>$serviceType);
			$this->load->model('landlords/resources_handler');
			$data['results'] = $this->resources_handler->searchSupplyHouses($data);
			
			$this->load->view('landlords/supply-house-search', $data);
		}
	}
	
}
