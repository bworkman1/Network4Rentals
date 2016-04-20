<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class landlord_Associations extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->_init();
	}
	
	private function _init()
	{
		$title = 'Landlord Associations | Network 4 Rentals';
		$description = 'Looking for homes for rent?, Our rental listings is easy to use and is nationwide.';
		$keywords = 'Homes For Rent, Rentals, For Rent, Rental Homes';
		$this->output->set_common_meta($title, $description, $keywords);
		$this->output->set_template('associations/landlord-associations');
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/js/bootstrap.min.js');
		$this->load->css('assets/themes/default/css/associations/landlord-associations.css');
		$this->load->js('assets/themes/default/js/assoc/login.js');
		$this->load->js('assets/themes/default/js/assoc/common.js');
	}
	
	public function index()
	{
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/classie.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/modalEffects.js');
		$this->load->css('assets/themes/default/js/ModalWindowEffects/css/component.css');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/cssParser.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/css-filters-polyfill.js');
		$this->load->js('https://www.google.com/recaptcha/api.js');
		
		if($this->session->userdata('logged_in'))
		{
			redirect('landlord_associations/home');
		}
		
		$this->load->view('landlord-associations/home');
	}
	
	public function create_account()
	{
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/classie.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/modalEffects.js');
		$this->load->css('assets/themes/default/js/ModalWindowEffects/css/component.css');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/cssParser.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/css-filters-polyfill.js');
		$this->load->js('https://www.google.com/recaptcha/api.js');
		$datas = array();
		$this->load->js('assets/themes/default/js/assoc/jquery.tagsinput.min.js');
		$this->load->js('assets/themes/default/js/assoc/create-account.js');
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->css('assets/themes/default/css/associations/jquery.tagsinput.css');

		if($this->session->userdata('logged_in'))
		{
			redirect('landlord-associations/home');
		}
		$this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[6]|max_length[20]|xss_clean|is_unique[landlord_assoc.username]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[100]|xss_clean|is_unique[landlord_assoc.email]|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[200]|matches[password2]|xss_clean|md5');
		$this->form_validation->set_rules('password2', 'Confirm Password', 'required|trim|max_length[200]|xss_clean');
		
		$this->form_validation->set_rules('title', 'Association Title', 'required|trim|min_length[2]|max_length[100]|xss_clean');
		$this->form_validation->set_rules('name', 'Full Name', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[14]|max_length[14]|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[50]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'required|trim|max_length[30]|xss_clean');
		$this->form_validation->set_rules('state', 'State', 'required|trim|min_length[2]|max_length[2]|xss_clean|alpha');
		$this->form_validation->set_rules('zip', 'Zip', 'required|trim|min_length[5]|max_length[5]|xss_clean|numeric');
		$this->form_validation->set_rules('referrer', 'Heard About Us', 'required|trim|min_length[3]|max_length[20]|xss_clean');
		
		$this->form_validation->set_rules('service_zips', 'Service Area Is Required', 'required|trim|min_length[5]|max_length[500]|xss_clean');
		$this->form_validation->set_rules('terms', 'Terms Of Service', 'required|trim|min_length[1]|max_length[1]|xss_clean|alpha');

		$this->form_validation->set_rules('coupon', 'Coupon', 'trim|min_length[1]|max_length[10]|xss_clean');
		if(empty($_POST['coupon'])) {
			$this->form_validation->set_rules('cc_name', 'Name on credit card', 'required|trim|min_length[1]|max_length[30]|xss_clean|');
			$this->form_validation->set_rules('cc_number', 'Credit Card Number', 'required|trim|min_length[1]|max_length[19]|xss_clean|');
			$this->form_validation->set_rules('exp_month', 'Expiration Month', 'required|trim|min_length[2]|max_length[2]|xss_clean|numeric');
			$this->form_validation->set_rules('exp_yy', 'Expiration Year', 'required|trim|min_length[4]|max_length[4]|xss_clean|numeric');
			$this->form_validation->set_rules('cv_code', 'CV Number', 'required|trim|min_length[1]|max_length[4]|xss_clean|numeric');
		}

		$this->form_validation->set_message('is_unique', '%s is already being used, try logging into your account by using the forgot password link <a href="'.base_url().'landlord-associations/forgot_password">here</a>.');
		
	
		if($this->form_validation->run() == FALSE) {
			
		} else {
			$_POST['title'] = ucwords(strtolower($_POST['title']));
			$_POST['name'] = ucwords(strtolower($_POST['name']));
			$_POST['address'] = ucwords(strtolower($_POST['address']));
			$_POST['city'] = ucwords(strtolower($_POST['city']));
			$_POST['state'] = strtoupper(strtolower($_POST['state']));
		
			//remove the second variable from the $_post array
			unset($_POST['password2']);
			$this->load->model('landlord-assoc/account_handler');
			
			extract($_POST);
			$coupon_codes = array('free4year');
			
			if(!in_array($coupon, $coupon_codes)) { //Check to see if coupon is valid
				//if its not valid, charge them	  
				$data = array(
					'credit_card'=>preg_replace("/[^0-9,.]/", "", $_POST['cc_number']),
					'exp_year'=>$_POST['exp_yy'], 
					'exp_month'=>$_POST['exp_month'],
					'cardCode'=>$_POST['cv_code'],
					'nameOnCard' => $_POST['cc_name']
				);
				$payment_success = $this->account_handler->process_payment($data);
			} else {
				$payment_success = array('success'=>'coupon-'.$coupon); 
			}
			
			if(!empty($payment_success['success'])) {
				$_POST['sub_id'] = $payment_success['success'];
				$data = $this->account_handler->create_account($_POST); //create account
				if(!empty($data)) {
					// Load email handler to email the user details on how to login
					$this->load->model('landlord-assoc/email_handler');					
					$subject = 'Landlord Association Account Created | Network4Rentals';
					$message = '<h2>'.$data['name'].'</h2>';				
					$message .= '<p>Your account has been created and there is only one more step to finishing up creating account. All we need for you do do now is to confirm your email by clicking the link below. If the link does not work copy and paste it into your browsers URL box.</p>';	
					$message .= '<a href="'.base_url().'landlord-associations/verify-email/?email='.$data['email'].'&hash='.$data['hash'].'">'.base_url().'landlord-associations/verify-email/?email='.$data['email'].'&hash='.$data['hash'].'</a>';
					
					if($this->email_handler->sendEmail($data['email'], $message, $subject)) {
						$this->session->set_userdata('create_email', $data['email']);
						$this->session->set_userdata('create_name', $data['name']);
						$this->session->set_userdata('create_hash', $data['hash']);
						redirect('landlord-associations/verify-email');
						exit;
					} else {
						$datas['error'] = 'Your account was created but we failed to email you at '.$data['email'].', please contact support <a href="https://network4rentals.com/help-support/">here</a>';
					}	
				} else {
					$datas['error'] = 'There was an error processing your account, try submitting your information again.';
				}
			} else {	
				$datas['error'] = $payment_success['error'];
			}
		}
		
		$this->load->view('landlord-associations/create-account', $datas);
	}
	
	public function create_association_account()
	{
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/classie.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/modalEffects.js');
		$this->load->css('assets/themes/default/js/ModalWindowEffects/css/component.css');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/cssParser.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/css-filters-polyfill.js');
		$this->load->js('assets/themes/default/js/assoc/create-account-simplified.js');
		$this->load->js('assets/themes/default/js/masked-input.js');
		
		$this->load->model('landlord-assoc/account_handler');
		$this->account_handler->create_account_simplified();
		
		$this->load->view('landlord-associations/create-account-simplified', $datas);
	}
	
	public function reset_password() 
	{
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/classie.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/modalEffects.js');
		$this->load->css('assets/themes/default/js/ModalWindowEffects/css/component.css');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/cssParser.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/css-filters-polyfill.js');
		$this->load->js('https://www.google.com/recaptcha/api.js');
		$datas = array();
		$this->load->js('assets/themes/default/js/assoc/jquery.tagsinput.min.js');
		$this->load->js('assets/themes/default/js/assoc/create-account.js');
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->css('assets/themes/default/css/associations/jquery.tagsinput.css');
		
		$encrypt = $this->uri->segment(3);
		if(!empty($encrypt)) {
			$this->load->view('landlord-associations/forgot-password'); 
			$this->form_validation->set_rules('pwd', 'Password', 'trim|min_length[7]|max_length[30]|matches[pwd_confirm]|xss_clean');
			$this->form_validation->set_rules('pwd_confirm', 'Password Confirm', 'trim|min_length[7]|max_length[30]|xss_clean|required|matches[pwd]');
			$this->form_validation->set_rules('hash', 'Email hash', 'trim|max_length[60]|xss_clean|required');
			if($this->form_validation->run() == FALSE) {
				if($_SERVER['REQUEST_METHOD'] == 'POST') { 
					$this->session->set_flashdata('error', validation_errors());  
					redirect('landlord-associations/reset-password/'.$encrypt);
				}
			} else {
				$failed = $this->session->userdata('failed_password_attempts');
				if($failed<10) {
					extract($_POST);
					$data = array('pwd'=>$pwd, 'hash'=>$hash);
					$this->load->model('landlord-assoc/reset_password');
					$result = $this->reset_password->update_password($data);
					$this->session->set_flashdata($result);
				} else {
					$this->session->set_flashdata('error', 'Too many failed login attempts, contact us to login');
				}
				redirect('landlord-associations/reset-password/'.$encrypt);
			}
		} else {
			echo 'Empty'.$encrypt;
			//redirect('landlord-associations/');
			exit;
		}
	}
	
	public function verify_email()	
	{
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/classie.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/modalEffects.js');
		$this->load->css('assets/themes/default/js/ModalWindowEffects/css/component.css');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/cssParser.js');
		$this->load->js('assets/themes/default/js/ModalWindowEffects/js/css-filters-polyfill.js');
		$this->load->js('https://www.google.com/recaptcha/api.js');
		$datas = array();
		$this->load->js('assets/themes/default/js/assoc/jquery.tagsinput.min.js');
		$this->load->js('assets/themes/default/js/assoc/create-account.js');
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->css('assets/themes/default/css/associations/jquery.tagsinput.css');
		$this->load->js('assets/themes/default/js/assoc/login.js');
		if(isset($_GET['email']) || isset($_GET['hash'])) {
			$valid = true;
			if(!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
				$valid = false;
			} else {
				if (!preg_match('/^[A-Z0-9]{4,64}$/i', $_GET['hash'])) {
					$valid = false;
				}
			}
			if($valid) {
				$data = array(
					'email'=>$_GET['email'],
					'hash'=>$_GET['hash']
				);
				$this->load->model('landlord-assoc/account_handler');
				$verified = $this->account_handler->verify_account_email($data);
				if(!empty($verified)) {
					$this->load->view('landlord-associations/account-verified');
				} else {
					$this->load->view('landlord-associations/account-verified'); // NEEDS AN ERROR PAGE OR SOMETHING
					//$this->load->view('landlord-associations/verify-email');
				}
			}
		} else {
			$this->load->view('landlord-associations/verify-email');
		}
	}
	
	public function resend_account_email() //Link to resend email for user to verify their email address
	{
		$name = $this->session->userdata('create_name');
		$email = $this->session->userdata('create_email');
		$hash = $this->session->userdata('create_hash');
		if(!empty($name) || !empty($email) || !empty($hash)) {
			$this->load->model('landlord-assoc/email_handler');					
			$subject = 'Landlord Association Account Created | Network4Rentals';
			$message = '<h2>'.$name.'</h2>';				
			$message .= '<p>Your account has been created and there is only one more step to finishing up creating account. All we need for you do do now is to confirm your email by clicking the link below. If the link does not work copy and paste it into your browsers URL box.</p>';	
			$message .= '<a href="'.base_url().'landlord-associations/verify-email/?email='.$email.'&hash='.$hash.'">'.base_url().'landlord-associations/verify-email/?email='.$email.'&hash='.$hash.'</a>';
			
			if($this->email_handler->sendEmail($email, $message, $subject)) {
				$this->session->set_flashdata('success', 'Email re-sent to <b>'.$email.'</b>, check your email and make sure you look in your spam folder.');
			} else {
				$this->session->set_flashdata('error', 'Email not sent to '.$email.', try again.');
			}
			redirect('landlord-associations/verify-email');
			exit;
		} else {
			redirect('landlord-associations/create-account');
			exit;
		}	
	}
	
	private function check_login($checkPayment = null)
	{
		$this->output->set_template('associations/landlord-associations-logged-in');
		$logged_in = $this->session->userdata('logged_in');
		$user_id = $this->session->userdata('user_id');
		if(!$logged_in) {
			$this->session->sess_destroy();
			redirect('landlord-associations');
			exit;
		}
		if($this->session->userdata('side_logged_in')!=='54986544688') {
			$this->session->sess_destroy();
			redirect('landlord-associations');
			exit;
		}
		if($user_id<1) {
			$this->session->sess_destroy();
			redirect('landlord-associations');
			exit;
		}
		if(empty($checkPayment)) {
			if($this->session->userdata('expiredSub')) {
				redirect('landlord-associations/update-payment-settings');
				exit;
			}
		}
	}
	
	public function home()
	{
		$this->check_login();
		$this->load->js('assets/themes/default/js/assoc/common.js');
		$temp = array ( //Calendar Settings
		   'start_day'      => 'sunday',
		   'month_type'     => 'long',
		   'day_type'       => 'short',
		   'show_next_prev' => TRUE,
		   'next_prev_url'  => site_url('landlord-associations/home/')
		); 
		$this->load->library('calendar', $temp);
		
		$this->load->model('landlord-assoc/account_handler');
		
		$data['test'] = $this->account_handler->check_payment_details($this->session->userdata('user_id'));
		
		$data['memebers_total'] = $this->account_handler->total_members();
		$data['public_page'] = $this->account_handler->public_page_data();
		$data['stats'] = $this->account_handler->load_stats();
		$this->load->view('landlord-associations/home-loggedin', $data);
	} 
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('landlord-associations');
		exit;
	}
	
	public function edit_account()
	{
		$this->check_login();
		$this->load->js('assets/themes/default/js/assoc/common.js');
		$this->load->js('assets/themes/default/js/assoc/jquery.tagsinput.min.js');
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->js('assets/themes/default/js/assoc/edit-account.js');
		$this->load->css('assets/themes/default/css/associations/jquery.tagsinput.css');
		
		$this->load->model('landlord-assoc/account_handler');
		$data['user'] = $this->account_handler->get_account_details();
		if($data['user']===false) {
			redirect('landlord-assocations/logout');
			exit;
		}
		
		
		
		if(!empty($_POST['password'])) {
			$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[20]|matches[password1]|xss_clean');
			$this->form_validation->set_rules('password1', 'Confirm Password', 'required|trim|max_length[20]|xss_clean');
		}
		
		$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[100]|xss_clean|valid_email');
		$this->form_validation->set_rules('title', 'Association Title', 'required|trim|min_length[2]|max_length[100]|xss_clean');
		$this->form_validation->set_rules('name', 'Full Name', 'required|trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[14]|max_length[14]|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[50]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'required|trim|max_length[30]|xss_clean');
		$this->form_validation->set_rules('state', 'State', 'required|trim|min_length[2]|max_length[2]|xss_clean|alpha');
		$this->form_validation->set_rules('zip', 'Zip', 'required|trim|min_length[5]|max_length[5]|xss_clean|numeric');

		$this->form_validation->set_rules('service_zips', 'Service Area Is Required', 'required|trim|min_length[5]|max_length[500]|xss_clean');		
	
		if($this->form_validation->run() == FALSE) {
			
		} else {
			extract($_POST);
			
			$title = ucwords(strtolower($title));
			$name = ucwords(strtolower($name));
			$address = ucwords(strtolower($address));
			$city = ucwords(strtolower($city));
			$state = strtoupper(strtolower($state));
			$inputs = array(
				'title' => $title,
				'name' => $name,
				'address' => $address,
				'city' => $city,
				'state' => $state,
				'state' => $state,
				'phone' => preg_replace("/[^0-9]/","",$phone),
				'zip' => $zip,
				'email' => $email,
				'email' => $email,
				'service_zips' => $service_zips
			);
		
			if(!empty($password)) {
				$inputs['password'] = $password;
			}
			
			$this->load->model('landlord-assoc/account_handler');	
			$results = $this->account_handler->edit_account_details($inputs);
			if($results) {
				$this->session->set_flashdata('success', 'Account details have been saved');
			} else {
				$this->session->set_flashdata('error', 'No details were changed');
			}
			redirect('landlord-associations/edit-account');
			exit;
			
		}
		
		
		$this->load->view('landlord-associations/edit-account', $data);
	}
	
	public function calendar()
	{
		$this->check_login();
		$this->load->js('assets/themes/default/js/assoc/common.js');
		$this->load->js('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
		$this->load->js('assets/themes/default/js/assoc/bootstrap-datetimepicker.min.js');
		$this->load->js('assets/themes/default/js/notify.min.js');
		$this->load->css('assets/themes/default/css/associations/bootstrap-datetimepicker.min.css');
		$this->load->js('assets/themes/default/js/assoc/calendar.js');
	
		$year = $this->uri->segment(3);
		$month = $this->uri->segment(4);
		if(empty($year)) {
			$year = date('Y');
		}
		if(empty($month)) {
			$month = date('m');
		}
		
		$prefs['template'] = '
			{table_open}<table class="calendar table table-stripes">{/table_open}
			{week_day_cell}<th class="day_header">{week_day}</th>{/week_day_cell}
			{heading_previous_cell}<th><a class="btn btn-primary pull-left" href="{previous_url}"><<</a></th>{/heading_previous_cell}
			{heading_next_cell}<th><a class="btn btn-primary pull-right" href="{next_url}">>></a></th>{/heading_next_cell}
			{cal_cell_content}<span class="day_listing">{day}</span><ul class="cal-event-list">{content}</ul>{/cal_cell_content}
			
			{cal_cell_content_today}<div class="today"><span class="day_listing">{day}</span><ul class="cal-event-list">{content}</ul></div>{/cal_cell_content_today}
			{cal_cell_no_content}<span class="day_listing">{day}</span>&nbsp;{/cal_cell_no_content}
			{cal_cell_no_content_today}<div class="today"><span class="day_listing">{day}</span></div>{/cal_cell_no_content_today}
		'; 
		
		$prefs['start_day'] = 'sunday';
		$prefs['month_type'] = 'long';
		$prefs['day_type'] = 'short';
		$prefs['day_type'] = 'short';
		$prefs['show_next_prev'] = TRUE;
		$prefs['next_prev_url'] = base_url().'landlord-associations/calendar/'; 
		
		$this->load->model('landlord-assoc/calendar_handler');
		$data['events'] = $this->calendar_handler->load_calendar_events($year, $month, $this->session->userdata('user_id'));
		
		
		
		$this->load->library('calendar', $prefs); 
		$this->load->view('landlord-associations/calendar', $data);
	}
	
	public function add_new_event()
	{
		$this->check_login();
	
		$this->load->js('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
		$this->load->js('assets/themes/default/js/assoc/bootstrap-datetimepicker.min.js');
		$this->load->css('assets/themes/default/css/associations/bootstrap-datetimepicker.min.css');
		
		$this->load->css('assets/themes/default/js/summernote/summernote.css');
		$this->load->css('assets/themes/default/js/summernote/summernote-bs3.css');
		$this->load->js('assets/themes/default/js/summernote/summernote.min.js');
		$this->load->js('assets/themes/default/js/notify.min.js'); 
		$this->load->js('assets/themes/default/js/assoc/wysiwyg.js');
		$this->load->js('assets/themes/default/js/assoc/calendar.js');
		
				
					
		$this->form_validation->set_rules('starts', 'Starts', 'required|trim|min_length[10]|max_length[20]|xss_clean|required');
		$this->form_validation->set_rules('ends', 'ends', 'required|trim|min_length[10]|max_length[20]|xss_clean|required');
		$this->form_validation->set_rules('what', 'Event Title', 'required|trim|min_length[3]|max_length[60]|xss_clean|required');
		$this->form_validation->set_rules('where', 'Venue Name', 'required|trim|min_length[2]|max_length[60]|xss_clean');
		$this->form_validation->set_rules('details', 'Details', 'trim|min_length[2]|max_length[5000]');
		
		$this->form_validation->set_rules('map', 'Google Map', 'trim|min_length[1]|max_length[1]|xss_clean|alpha');
		$this->form_validation->set_rules('address', 'Address', 'trim|min_length[2]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('public', 'Public', 'trim|min_length[1]|max_length[1]|xss_clean|alpha');
				
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			if(empty($map)) {
				$map = 'n';
			}
			if(empty($public)) {
				$public = 'n';
			}
			$what = ucwords(strtolower($what));
			$where = ucwords(strtolower($where));
			
			$this->load->helper('htmlpurifier');
			$details = html_purify($details);
			
			$data = array(
				'start' => date('Y-m-d H:i:s', strtotime($starts)),
				'end' => date('Y-m-d H:i:s', strtotime($ends)),
				'what' => $what,
				'where' => $where,
				'map' => $map,
				'address' => $address,
				'public' => $public,
				'user_id' => $this->session->userdata('user_id'),
				'details' => $details
			);
			
			$this->load->model('landlord-assoc/calendar_handler');
			$results = $this->calendar_handler->add_event($data);
			if($results>0) {
				$this->session->set_flashdata('success', 'Event added successfully');
				redirect('landlord-associations/calendar');
			} else {
				$data['errors'] = 'Event failed to add try again'; // event failed to add
			} 
		} else {
			$data['errors'] = validation_errors();
		}
		
		$this->load->view('landlord-associations/add-event', $data);
	}
	
	public function members() 
	{
		$this->check_login();
		$this->load->model('landlord-assoc/member_handler');
		
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->js('assets/themes/default/js/assoc/common.js');
		$this->load->js('https://code.jquery.com/ui/1.11.2/jquery-ui.js');
		$this->load->css('https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
		$this->load->js('assets/themes/default/js/assoc/bootstrap-datetimepicker.min.js');
		$this->load->css('assets/themes/default/css/associations/bootstrap-datetimepicker.min.css');
		$this->load->js('assets/themes/default/js/jquery.maskMoney.js');
		$this->load->js('assets/themes/default/js/notify.min.js');
		$this->load->js('assets/themes/default/js/assoc/members.js');
		
		$this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[6]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[50]|xss_clean|valid_email');
		$this->form_validation->set_rules('position', 'Member Position', 'required|trim|min_length[2]|max_length[40]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|min_length[10]|max_length[15]|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[50]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|xss_clean');
		$this->form_validation->set_rules('state', 'State', 'required|trim|min_length[2]|max_length[2]|xss_clean|alpha');
		$this->form_validation->set_rules('zip', 'Zip', 'trim|min_length[5]|max_length[5]|xss_clean|numeric');
		$this->form_validation->set_rules('show_badge', 'Show Badge', 'required|trim|min_length[1]|max_length[1]|xss_clean|alpha');
		$this->form_validation->set_rules('due_date', 'Payment Date', 'required|trim|min_length[10]|max_length[10]|xss_clean');
		$this->form_validation->set_rules('landlord_id', 'Landlord Id', 'trim|max_length[12]|xss_clean|integer');
		$this->form_validation->set_rules('payment_amount', 'Payment Amount', 'trim|max_length[6]|xss_clean|required');
		$this->form_validation->set_rules('member_type', 'Member Type', 'trim|max_length[30]|xss_clean|required');
		$this->form_validation->set_error_delimiters('<span>* ', '</span> ');
		if($this->form_validation->run() == FALSE) {
			
		} else {
			$_POST['title'] = ucwords(strtolower($_POST['title']));
			$_POST['name'] = ucwords(strtolower($_POST['name']));
			$_POST['address'] = ucwords(strtolower($_POST['address']));
			$_POST['city'] = ucwords(strtolower($_POST['city']));
			$_POST['state'] = strtoupper(strtolower($_POST['state']));
			$_POST['position'] = ucwords(strtolower($_POST['position']));
			
			extract($_POST);
			$phone = preg_replace("/[^0-9,.]/", "", $phone);
			$insert_data = array(
				'name'=>$name,
				'email'=>$email,
				'position'=>$position,
				'phone'=>$phone,
				'address'=>$address,
				'city'=>$city,
				'state'=>$state,
				'zip'=>$zip,
				'show_badge'=>$show_badge,
				'due_date'=>date('Y-m-d', strtotime($due_date)+3600),
				'member_type' => $member_type,
				'payment_amount' => $payment_amount,
			);
			if(!empty($landlord_id)) {
				$insert_data['registered_landlord_id'] = $landlord_id;
			}
			$inserted = $this->member_handler->add_member($insert_data);
			if($inserted) {
				$this->session->set_flashdata('success', 'Member has been added to your association');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, try again');
			}
			redirect('landlord-associations/members');
			exit;
		}
		
		$data['categories'] = $this->member_handler->memberCategories();
		$data['members'] = $this->member_handler->get_members();
		
		$this->load->view('landlord-associations/members', $data);
	}
	
	public function edit_member()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[6]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[50]|xss_clean|valid_email');
		$this->form_validation->set_rules('position', 'Member Position', 'required|trim|min_length[2]|max_length[40]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|min_length[10]|max_length[16]|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[50]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|xss_clean');
		$this->form_validation->set_rules('state', 'State', 'trim|min_length[2]|max_length[2]|xss_clean|alpha');
		$this->form_validation->set_rules('zip', 'Zip', 'trim|min_length[5]|max_length[5]|xss_clean|numeric');
		$this->form_validation->set_rules('show_badge', 'Show Badge', 'required|trim|min_length[1]|max_length[1]|xss_clean|alpha');
		$this->form_validation->set_rules('due_date', 'Payment Date', 'required|trim|min_length[10]|max_length[10]|xss_clean');
		$this->form_validation->set_rules('member_id', 'Member Id', 'trim|max_length[12]|xss_clean|integer');
		
		$this->form_validation->set_rules('active', 'Active Member', 'trim|max_length[12]|xss_clean|alpha');
		$this->form_validation->set_rules('member_type', 'Member Category', 'trim|min_length[2]|max_length[30]|xss_clean|required');
		$this->form_validation->set_rules('payment_amount', 'Payment Amount', 'trim|max_length[12]|xss_clean');
		$this->form_validation->set_rules('custom_values', 'Custom Values', 'trim|max_length[12]|xss_clean|alpha');
	
		if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
		} else {
			extract($_POST);
			
			$phone = preg_replace("/[^0-9,.]/", "", $phone);
			
			$data = array(
				'id'=>$member_id,
				'name'=>$name,
				'email'=>$email,
				'position'=>$position,
				'phone'=>$phone,
				'address'=>$address,
				'city'=>$city,
				'state'=>$state,
				'zip'=>$zip,
				'show_badge'=>$show_badge,
				'due_date'=>date('Y-m-d', strtotime($due_date)),
				'member_id'=>$member_id,
				'active'=>$active,
				'member_type'=>$member_type,
				'payment_amount'=>$payment_amount,
				'custom_values'=>$custom_values,
			);
	
			$this->load->model('landlord-assoc/member_handler');
			$results = $this->member_handler->update_member_details($data);
			if($results) {
				$this->session->set_flashdata('success', 'Member details updated successful');
			} else {
				$this->session->set_flashdata('error', 'No changes were made to the member details');
			}
		}
		redirect('landlord-associations/members');
		exit;
	}
	
	public function delete_member()
	{
		$id = (int)$this->uri->segment(3);
		if(empty($id)) {
			$this->session->set_flashdata('error', 'No member found, try again');
		} else {
			$this->load->model('landlord-assoc/member_handler');
			$result = $this->member_handler->delete_member($id);
			if($result) {
				$this->session->set_flashdata('success', 'Member has been deleted');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, try again');
			}
		}
		redirect('landlord-associations/members');
		exit;
	}
	
	public function public_page()
	{
		$this->check_login();
		
		$this->load->js('assets/themes/default/js/notify.min.js');
		$this->load->js('assets/themes/default/js/jscolor/jscolor.js');
		$this->load->js('assets/themes/default/js/assoc/common.js');
		$this->load->js('assets/themes/default/js/assoc/public-page.js');
		$this->load->js('https://code.jquery.com/ui/1.11.2/jquery-ui.js');
		
		$this->load->css('https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
		
		$this->load->model('landlord-assoc/public_page_handler');
		
		$data['settings'] = $this->public_page_handler->get_page_settings();
		$data['pages'] = $this->public_page_handler->get_public_pages();
		
		if(isset($_POST)) {
			if($data['settings']->unique_name != $_POST['unique_name']) {
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
		$this->form_validation->set_rules('zip', 'Zip', 'trim|min_length[5]|max_length[5]|xss_clean');
		$this->form_validation->set_rules('show_vacant', 'Show Vacant Members', 'trim|min_length[1]|max_length[1]|xss_clean|required');

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
							$config['maintain_ratio'] = false;
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
						$config['height']	= 400;

						$this->load->library('image_lib', $config);
						$this->image_lib->resize($config);	

						
						// GENERATE THUMB
						//your desired config for the resize() function
						$config = array(
							'source_image'      => FCPATH.'public-images/'.$file, //path to the uploaded image
							'new_image'         => FCPATH.'public-images/assoc_thumbs', //path to
							'maintain_ratio'    => true,
							'width'             => 50,
							'height'            => 50
						);
					
						//this is the magic line that enables you generate multiple thumbnails
						//you have to call the initialize() function each time you call the resize()
						//otherwise it will not work and only generate one thumbnail
						$this->image_lib->initialize($config);
						$this->image_lib->resize();		
						
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				} 
			}
			
			$input['landlord_id'] = $this->session->userdata('user_id');
			$input['type'] = 'association';
		
			$results = $this->public_page_handler->update_public_page_details($input);
			redirect('landlord-associations/public-page');
			exit;
		} else {
			$data['errors'] = validation_errors();
		}
		//ENDS NEW
		
		$this->load->view('landlord-associations/public-page', $data);
		
	}
	
	public function web_posts()
	{
		$this->check_login();
		$this->load->js('assets/themes/default/js/assoc/common.js');
		
		$this->load->view('landlord-associations/web-post');
	}	
	
	public function edit_page()
	{
		$this->check_login();
		$this->load->model('landlord-assoc/public_page_handler');
		
		$id = $this->uri->segment(3);

		$this->load->css('assets/themes/default/js/summernote/summernote.css');
		$this->load->css('assets/themes/default/js/summernote/summernote-bs3.css');
		$this->load->js('assets/themes/default/js/summernote/summernote.min.js');
		$this->load->js('assets/themes/default/js/notify.min.js'); 
		$this->load->js('assets/themes/default/js/assoc/wysiwyg.js');

		$this->form_validation->set_rules('post', 'Post Is Required', 'required|trim|min_length[20]|max_length[5000]');
		$this->form_validation->set_rules('id', 'Page Id', 'required|trim|min_length[1]|max_length[11]|integer');
		$this->form_validation->set_rules('name', 'Page Name', 'required|trim|min_length[3]|max_length[30]');
		if($this->form_validation->run() == FALSE) {
			$data['errors'] = validation_errors();
		} else {
			extract($_POST);
			$this->load->helper('htmlpurifier');
			$post = html_purify($post);
			//$post = strip_tags($post, '<b><h1><h2><h3><h4><h5><h6><img><p><span><ol><li><ul><blockquote><a><h>');
			
			$results = $this->public_page_handler->edit_page($post, $id, $name);
			if($results) {
				$this->session->set_flashdata('success', 'Your page has been updated');
				redirect('landlord-associations/public-page');
				exit;
			} else {
				$data['errors'] = 'Either you didn\'t make any changes to the page or the page failed to update, try again.';
			}
		}

		
		$data['page'] = $this->public_page_handler->get_page_data($id);
		
		$this->load->view('landlord-associations/edit-page', $data);
	}
	
	public function new_web_post()
	{
		$this->check_login();
		$this->load->js('assets/themes/default/js/tinyeditor/packed.js');
		$this->load->js('assets/themes/default/js/assoc/edit-post-page.js');
		$this->load->js('assets/themes/default/js/notify.min.js');
		
		$this->form_validation->set_rules('title', 'Title', 'required|trim|min_length[2]|max_length[60]|xss_clean');
		$this->form_validation->set_rules('post', 'Post', 'required|trim|min_length[20]|max_length[5000]');
		if($this->form_validation->run() == FALSE) {
			
		} else {
			extract($_POST);
			$this->load->helper('htmlpurifier');
			$post = html_purify($post);
			$input = array(
				'title'=>ucwords(strtolower($title)),
				'post'=>$post,
				'created'=>date('Y-m-d H:i:s'),
				'user_id'=>$this->session->userdata('user_id'),
				'ip'=>$_SERVER['REMOTE_ADDR'],
			);
			$this->load->model('landlord-assoc/post_page_handler');
			$results = $this->post_page_handler->add_new_post($input);
			if($results) {
				$this->session->set_flashdata('success', 'Your new post has been created');
				redirect('landlord-associations/all-posts');
				exit;
			} else {
				
			}
		}	
		$this->load->view('landlord-associations/edit-post-page');
	}
	
	public function delete_page()
	{
		$page_id = (int)$this->uri->segment(3);
		if($page_id>0) {
			$this->load->model('landlord-assoc/public_page_handler');
			$results = $this->public_page_handler->delete_page($page_id);
			if($results) {
				$this->session->set_flashdata('success', 'The page has been deleted successfully');
			} else {
				$this->session->set_flashdata('error', 'Page not deleted, something went wrong. Try again');
			}
		} else {
			$this->session->set_flashdata('error', 'Page could not be deleted, try using the button below.');
		}
		redirect('landlord-associations/public-page');
		exit;
	}
	
	public function edit_post()
	{	
		//$this->load->helper('htmlpurifier');
		$id = $this->uri->segment(3);
		if($id>0) {
			$this->check_login();
			
			$this->load->model('landlord-assoc/post_page_handler');
			$data['post'] = $this->post_page_handler->get_single_posts($id);
			if(empty($data['post'])) {
				$this->session->set_flashdata('error', 'No post found, try again');
				redirect('landlord-associations/all-posts');
				exit;
			}
			
			$this->load->js('assets/themes/default/js/tinyeditor/packed.js');
			$this->load->css('assets/themes/default/js/tinyeditor/style.css');
			$this->load->js('assets/themes/default/js/assoc/edit-post-page.js');
			
			/* FORM SUBMIT SECTION */
			$this->form_validation->set_rules('title', 'Title Is Required', 'required|trim|min_length[2]|max_length[60]|xss_clean');
			$this->form_validation->set_rules('post', 'Post Is Required', 'required|trim|min_length[20]|max_length[5000]');
			if($this->form_validation->run() == FALSE) {
				
			} else {
				extract($_POST);
				$this->load->helper('htmlpurifier');
				$post = html_purify($post);
				//$post = strip_tags($post, '<b><h1><h2><h3><h4><h5><h6><img><p><span><ol><li><ul><blockquote><a><h>');
				$input = array(
					'title'=>ucwords(strtolower($title)),
					'post'=>$post,
					'user_id'=>$this->session->userdata('user_id'),
					'ip'=>$_SERVER['REMOTE_ADDR'],
					'id'=>$id
				);
				$this->load->model('landlord-assoc/post_page_handler');
				$results = $this->post_page_handler->edit_post($input);
				if($results) {
					$this->session->set_flashdata('success', 'Your post has been updated');
					redirect('landlord-associations/all-posts');
					exit;
				} else {
					
				}
			}	
			
			
			
			$this->load->view('landlord-associations/edit-post-page', $data);
		} else {
			$this->session->set_flashdata('error', 'No post found, try again');
			redirect('landlord-associations/all-posts');
			exit;
		}
	}
	
	public function all_posts()
	{
		$this->check_login();
		$this->load->js('assets/themes/default/js/assoc/common.js');
		
		$this->load->model('landlord-assoc/post_page_handler');
		$data['post'] = $this->post_page_handler->show_all_posts();
		
		
		$this->load->view('landlord-associations/all-posts', $data);
	}
	
	public function delete_post()
	{
		$this->check_login();
		$id = $this->uri->segment(3);
		$this->load->model('landlord-assoc/post_page_handler');
		$results = $this->post_page_handler->delete_post($id);
		if($results) {
			$this->session->set_flashdata('success', 'The post was deleted successfully');
		}else{
			$this->session->set_flashdata('error', 'The post failed to delete, try again');
		}
		redirect('landlord-associations/all-posts');
		exit;
	}	
	
	public function update_payment_settings() 
	{
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->js('assets/themes/default/js/assoc/payment-settings.js');
		$this->check_login(1);
		
		$this->form_validation->set_rules('card_holder_name', 'Card Holder Name', 'required|trim|min_length[2]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('card_number', 'Card Number', 'required|trim|min_length[19]|max_length[19]');
		$this->form_validation->set_rules('expiry_month', 'Expiration Month', 'required|trim|min_length[2]|max_length[2]|integer');
		$this->form_validation->set_rules('expiry_year', 'Expiration Year', 'required|trim|min_length[2]|max_length[2]|integer');
		$this->form_validation->set_rules('cvv', 'Card CVV', 'required|trim|min_length[2]|max_length[4]|integer');
		
		if($this->form_validation->run() == FALSE) {
			$data['error'] = validation_errors();
		} else {
			extract($_POST);
			$data = array(
				'nameOnCard'=>$card_holder_name,
				'credit_card'=> preg_replace("/[^0-9,.]/", "", $card_number),
				'exp_month' => $expiry_month,
				'exp_year' => $expiry_year,
				'cardCode' => $cvv
			);
			$this->load->modal('landlord-assoc/account_handler');
			$result = $this->account_handler->process_payment($data);
			if(isset($result['success'])) {
				$log = array(
					'user_id' => $this->session->userdata('user_id'),
					'amount' => $this->account_handler->returnSubAmount(),
					'type' => 'association',
					'payment_date' => date('Y-m-d H:i:s'),
					'payment_frequency' => '12',
					'sub_id' => $result['success'],
					'active' => 'y',
					'expires' =>  $data['exp_month'].'/'.$data['exp_year'],
					'last_4' =>  substr(preg_replace("/[^0-9,.]/", "", $data['credit_card']), -4)
				);
				
				$this->account_handler->logPaymentDetails($data);
				
				
				$this->session->set_flashdata('success', 'Your payment has been processed and you can now continue.');
				redirect('landlord-associations/home');
				exit;
			} else {
				$data['error'] = $result['error'];
			}
			
		}
		
		$this->load->view('landlord-associations/update-payment-settings', $data);
	}
	
	public function edit_event()
	{
		$this->check_login();
		
		$eventId = (int)$this->uri->segment(3);
		if(empty($eventId)) {
			redirect('landlord-associations/calendar');
			exit;
		}
		
		$this->load->js('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
		$this->load->js('assets/themes/default/js/assoc/bootstrap-datetimepicker.min.js');
		$this->load->css('assets/themes/default/css/associations/bootstrap-datetimepicker.min.css');
		
		$this->load->css('assets/themes/default/js/summernote/summernote.css');
		$this->load->css('assets/themes/default/js/summernote/summernote-bs3.css');
		$this->load->js('assets/themes/default/js/summernote/summernote.min.js');
		$this->load->js('assets/themes/default/js/notify.min.js'); 
		$this->load->js('assets/themes/default/js/assoc/wysiwyg.js');
		$this->load->js('assets/themes/default/js/assoc/calendar.js');
		
		$this->form_validation->set_rules('id', 'Event Id', 'required|trim|min_length[1]|max_length[20]|xss_clean|integer');
		$this->form_validation->set_rules('starts', 'Starts', 'required|trim|min_length[10]|max_length[20]|xss_clean|required');
		$this->form_validation->set_rules('ends', 'ends', 'required|trim|min_length[10]|max_length[20]|xss_clean|required');
		$this->form_validation->set_rules('what', 'Event Title', 'required|trim|min_length[3]|max_length[60]|xss_clean|required');
		$this->form_validation->set_rules('where', 'Venue Name', 'required|trim|min_length[2]|max_length[60]|xss_clean');
		$this->form_validation->set_rules('details', 'Details', 'trim|min_length[2]|max_length[5000]');
		$this->form_validation->set_rules('map', 'Google Map', 'trim|min_length[1]|max_length[1]|xss_clean|alpha');
		$this->form_validation->set_rules('address', 'Address', 'trim|min_length[2]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('public', 'Public', 'trim|min_length[1]|max_length[1]|xss_clean|alpha');
				
		$this->load->model('landlord-assoc/calendar_handler');
		
		if($this->form_validation->run() == TRUE) {
			extract($_POST);
			
			
			if(empty($map)) {
				$map = 'n';
			}
			if(empty($public)) {
				$public = 'n';
			}
			$what = ucwords(strtolower($what));
			$where = ucwords(strtolower($where));
			
			$this->load->helper('htmlpurifier');
			$details = html_purify($details);
			
			$data = array(
				'event_id' => $id,
				'start' => date('Y-m-d H:i:s', strtotime($starts)),
				'end' => date('Y-m-d H:i:s', strtotime($ends)),
				'what' => $what,
				'where' => $where,
				'map' => $map,
				'address' => $address,
				'public' => $public,
				'user_id' => $this->session->userdata('user_id'),
				'details' => $details
			);
			$results = $this->calendar_handler->edit_event($data);
			
			if($results) {
				$data['success'] = 'Event edited successfully';
			} else {
				$data['errors'] = 'Event failed to edit try again'; // event failed to add
			} 
			
		} else {
			$data['errors'] = validation_errors();
		}
		$data['event_details'] = $this->calendar_handler->event_details($eventId);

		$this->load->view('landlord-associations/edit-event', $data);
	}
	
	public function vacant_member_listings() 
	{
		$this->check_login();
		$this->load->model('landlord-assoc/vacant_rentals');
		
		$data = $this->vacant_rentals->buildVacantRentals();
		
		$this->load->view('landlord-associations/vacant-member-listings', $data);
	}
	
	public function event_details()
	{
		$this->check_login();
		$eventId = (int)$this->uri->segment(3);
		if(empty($eventId)) {
			redirect('landlord-associations/calendar');
			exit;
		}
		
		$this->load->view('landlord-associations/event-details', $data);
	}
	
}