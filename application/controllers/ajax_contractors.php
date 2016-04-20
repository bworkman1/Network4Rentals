<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class ajax_contractors extends CI_Controller {
		function __construct()
		{
			parent::__construct();
		}
		
		function check_if_loggedin() 
		{
			$test = true;
			if($this->session->userdata('logged_in') !== true)
			{
				$test = false;
			}
			if($this->session->userdata('side_logged_in') != '203020320389822') {
				$test = false;
			}

			$user_id = $this->session->userdata('user_id');
			if(empty($user_id)) {
				$test = false;
			}

			if( $test == false) {
				exit;
			}
		}
		
		function update_page_stack()
		{
			if ($this->input->is_ajax_request()) {
				$this->load->model('contractors/public_page_handler');
				$positions = json_decode($_POST['jsonData']);
				
				foreach($positions as $key => $val) {  // KEY IS THE ID OF THE MEMBER AND VAL IS THE POSITION
					$data = array(
						'id'=>$key,
						'stack_order'=>$val
					);
					$this->public_page_handler->reorder_pages($data);
				}
			}
		}
		
		public function submit_new_account()
		{
			$this->output->set_template('json');
			
			$feedback = array();
			
			//Personal Details
			$this->form_validation->set_rules('company_name', 'Company Name', 'min_length[3]|max_length[70]|xss_clean');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[50]|alpha|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[50]|alpha|xss_clean');
			
			$this->form_validation->set_rules('cc_fname', 'Credit Card First Name', 'required|min_length[2]|max_length[50]|alpha|xss_clean');
			$this->form_validation->set_rules('cc_lname', 'Credit Card Last Name', 'required|min_length[2]|max_length[50]|alpha|xss_clean');
			
			$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[19]|max_length[19]|xss_clean');
			$this->form_validation->set_rules('ccv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean');
			$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[3]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('credit_card_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
			$this->form_validation->set_rules('zip', 'Zip', 'required|min_length[5]|max_length[5]|numeric|xss_clean');
			
			$this->form_validation->set_rules('email', 'Email', 'required|min_length[3]|max_length[60]|valid_email|xss_clean|is_unique[contractors.email]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('amount', 'Amount', 'required|min_length[4]|max_length[6]|xss_clean');
			$this->form_validation->set_rules('promo', 'Promo Code', 'min_length[2]|max_length[15]|xss_clean');
			
			$this->form_validation->set_message('is_unique', 'The email address you entered is already in use');

			if ($this->form_validation->run() == FALSE) {
				$feedback = array('error' => validation_errors());
			} else { 
				extract($_POST);
			
				/*
				$company_name = 'EMF Web Solutions';
				$first_name = 'Brian';
				$last_name = 'Workman';
				
				$credit_card = '4111111111111111';
				$credit_card_month = '06';
				$credit_card_year = '2016';
				$ccv = '494';
				$zip = '43055';
				$email = 'brian@semf-websolutions.com';
				$password = 'password12';
				$amount = '155.00';
				$promo = 'n4r';
				*/
				$this->load->model('advertisers/process_payment');
				
				$this->process_payment->setAmount($amount);
				if(!empty($promo)) {
					$this->process_payment->setPromoCode($promo);
				}
				$this->process_payment->calculateNewTotal();
				$this->process_payment->authName = 'N4R Contractor Account '.ucwords($this->process_payment->billing_name).' Payment';
				
				$credit_card = preg_replace("/[^0-9,.]/", "", $credit_card);
				
				$creditCardDetails = array(
					'x_card_num'			=> $credit_card, // Visa
					'x_exp_date'			=> $credit_card_month.'/'.ltrim($credit_card_year,'20'),
					'x_card_code'			=> $ccv,
					'x_description'			=> 'N4R Contracctor Account '.ucwords($this->process_payment->billing_name).' Payment',
					'x_amount'				=> $this->process_payment->total,
					'x_first_name'			=> $cc_fname,
					'x_last_name'			=> $cc_lname,
					'x_zip'					=> $zip,
					'x_country'				=> 'US',
					'x_email'				=> $email,
					'x_customer_ip'			=> $this->input->ip_address(),
				);
				
				if($this->process_payment->total > 0) {
					if(!$this->process_payment->processInitialPayment($creditCardDetails)) {
						$feedback = array('error' => $this->process_payment->paymentError);
					} else {
						$subscription = true;
						$arb_setup = $this->process_payment->setUpSubscription($creditCardDetails);
						if($arb_setup !== true) {
							$subscription = false;
						}
						
						$user = array(
							'user' => $email,
							'password' => md5($password),
							'email' => $email,
							'zip' => $zip,
							'f_name' => $first_name,
							'l_name' => $last_name,
							'sub_id' => $this->process_payment->subscription_id,
							'bName' => $company_name,
							'active' => 'y',
							'category' => $category,
							'affiliate_id' => $this->session->userdata('affiliate_id')
						);
						
						$this->load->model('contractors/create_user_handler');
						$user_id = $this->create_user_handler->createUserAccount($user);
					
						$user['amount'] = $this->process_payment->amount;
						$user['freq'] = $this->process_payment->freq;
						$user['subscription_id'] = $this->process_payment->subscription_id;
						$user['total'] = $this->process_payment->total;
						
						$details = array(
							'id' 				=> $user_id,
							'payment_amount'	=> $user['amount'],
							'type'				=> 'contractor',
							'payment_id'		=> $this->process_payment->trans_id,
							'options'			=> '',
							'frequency'			=> $user['freq'],
							'sub_id'			=> $this->process_payment->subscription_id,
							'affiliate_id' 		=> $this->session->userdata('affiliate_id'),
							'renewal'			=> 'n',
							'expires' => date('Y-m-d', strtotime($credit_card_month.'/01/'.$credit_card_year)),
							'payment_type' => 'auto'
						);
				
						$this->process_payment->log_payment($details);
						
						$this->create_user_handler->sendWelcomeEmail($user);
						
						$this->session->set_userdata('logged_in', true);
						$this->session->set_userdata('user_id', $user_id);
						$this->session->set_userdata('email', $email);
						$this->session->set_userdata('side_logged_in', 'local-partner');
						$feedback = array('success' => 'Your account has been created successfully');
						
						if($subscription) {
							
							$this->load->model('contractor/login');
							$results = $this->login->check_creditials(array('user'=>$user['user'], 'password'=>$user['password']));
							$this->session->set_userdata('side', 'Contractor');
							
							$this->session->set_flashdata('success', 'Your account has been created successfully. Now add the zip codes you service and the type of services you offer.');
						} else {
							$this->session->set_flashdata('success', 'Your first year payment successfully processed but your yearly subscription didn\'t process due to an error. Please contact support to sort this out.<br><b>Reason: </b>'.$arb_setup);
						}					
					}
				} else {
					$feedback = array('error' => 'Invalid payment plan selected');
				}
							
			}
			
			echo json_encode($feedback);
		}
		
		function create_account()
		{
			
			if ($this->input->is_ajax_request()) {	
				//Personal Details
				$this->form_validation->set_rules('bName', 'Business Name', 'required|min_length[3]|max_length[70]|xss_clean|trim');
				$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[3]|max_length[50]|alpha|xss_clean|trim');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[3]|max_length[50]|alpha|xss_clean|trim');
				$this->form_validation->set_rules('address', 'Address', 'required|min_length[3]|max_length[70]|xss_clean|trim');
				$this->form_validation->set_rules('baddress', 'Billing Address', 'required|min_length[3]|max_length[70]|xss_clean|trim');
				$this->form_validation->set_rules('bstate', 'Billing State', 'required|min_length[2]|max_length[2]|alpha|xss_clean|trim');
				$this->form_validation->set_rules('state', 'State', 'required|min_length[2]|max_length[2]|alpha|xss_clean|trim');
				$this->form_validation->set_rules('bzip', 'Billing Zip', 'required|min_length[5]|max_length[5]|numeric|xss_clean|trim');
				$this->form_validation->set_rules('zip', 'Zip', 'required|min_length[5]|max_length[5]|numeric|xss_clean|trim');
				$this->form_validation->set_rules('city', 'City', 'required|min_length[3]|max_length[70]|xss_clean|trim');
				$this->form_validation->set_rules('bcity', 'Billing City', 'required|min_length[3]|max_length[70]|xss_clean|trim');
				$this->form_validation->set_rules('phone', 'Phone', 'required|min_length[14]|max_length[18]|xss_clean|trim');
				$this->form_validation->set_rules('fax', 'Fax', 'max_length[18]|xss_clean|trim');
				$this->form_validation->set_rules('cell', 'Cell', 'max_length[18]|xss_clean|trim');
				//Payment Info
				$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[3]|max_length[20]|xss_clean|trim');
				$this->form_validation->set_rules('exp_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean|trim');
				$this->form_validation->set_rules('exp_year', 'Expiration Year', 'required|min_length[4]|max_length[4]|numeric|xss_clean|trim');
				$this->form_validation->set_rules('ccv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean|trim');
				$this->form_validation->set_rules('name_on_card', 'Name On Credit Card', 'required|min_length[5]|max_length[70]|xss_clean|trim');
				//User Account 
				$this->form_validation->set_rules('email', 'Email', 'required|min_length[3]|max_length[60]|valid_email|is_unique[contractors.email]|xss_clean|trim');
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|max_length[20]|matches[password2]|xss_clean|trim');
				$this->form_validation->set_rules('password2', 'Password', 'required|min_length[3]|max_length[20]|matches[password]|xss_clean|trim');
				$this->form_validation->set_rules('terms', 'Terms Of Service', 'required|min_length[1]|max_length[1]|xss_clean|trim');		
				if ($this->form_validation->run() == FALSE) {
					
					$feed = array('error'=>validation_errors());
					
				} else {
					extract($_POST);
					$this->load->helper('string');

					
					$phone = preg_replace("/[^0-9]/", '', $phone);
					$fax = preg_replace("/[^0-9]/", '', $fax);
					$cell = preg_replace("/[^0-9]/", '', $cell);
					$credit_card = preg_replace("/[^0-9]/", '', $credit_card);
					$affiliateId = $this->session->userdata('affiliate_id');
					
					$data = array('bName'=> $bName, 'first_name'=> $first_name, 'last_name'	=> $last_name,'address'=>$address, 	'baddress'=>$baddress, 'bstate'=>$bstate, 'bcity'=>$bcity, 'state'=>$state,'bzip'=>$bzip,'zip'=>$zip, 'city'=>$city, 'phone'=>$phone, 'fax'=>$fax, 'email'=>strtolower($email), 'cell'=>$cell, 'user'=>strtolower($email), 'password'=>$password,'terms'=>$terms,'credit_card'	=> $credit_card, 'exp_month'=> $exp_month, 'exp_year'=> $exp_year, 'ccv'=>$ccv, 'name_on_card'=>$name_on_card, 'hash'=>random_string('sha1', 25), 'affiliate_id'=>$affiliateId);
					
					//$data = array('bName'=> 'test', 'first_name'=> 'brian', 'last_name'	=> 'workman','address'=>'494 Garfeild ave', 	'baddress'=>'494 Garfiled Ave', 'bstate'=>'OH', 'bcity'=>'Newark', 'state'=>'OH','bzip'=>'43055','zip'=>'43055', 'city'=>'Newark', 'phone'=>'7406611411', 'fax'=>'7406611411', 'email'=>strtolower('brian@emf-websolutiosns.com'), 'cell'=>'', 'user'=>strtolower('brian@emf-websolutions.com'), 'password'=>'password','terms'=>'y','credit_card'	=> '4111111111111111', 'exp_month'=> '04', 'exp_year'=> '2015', 'ccv'=>'152', 'name_on_card'=>'Brian Workman', 'hash'=>random_string('sha1', 25));
					
					$this->load->model('contractor/create_account_handler');
					$feed = $this->create_account_handler->account_builder($data);
				}
				echo json_encode($feed);
				
			}
		}
		
		public function show_available_zips()
        {
			/*
				Account creation ajax function for when a user searches for zip codes to be in
			*/
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$this->output->set_content_type('application/json');
				$zip = (int)$this->uri->segment(3);
				$serviceType = $this->uri->segment(4);
			
                if (strlen($zip) == 5) {
                    $this->load->model('contractor/create_account');
					echo $this->create_account->available_zips($zip, $serviceType);
                } else {
					echo '1';	
				}
            }
			
		}
		
		function check_username() 
		{	
			$this->output->set_template('json');
			if ($this->input->is_ajax_request()) {
				if(isset($_POST["username"])) {
					$username =  trim($_POST["username"]);
					$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
					$this->load->model('contractors/create_user_handler');
					echo $this->create_user_handler->check_username_avaliable($username);
				}
			}
		}
		
		function check_email() 
		{	
			$this->output->set_template('json');
			if ($this->input->is_ajax_request()) {
				if(isset($_POST["email"])) {
					$email =  strtolower(trim($_POST["email"]));
					$email = filter_var($email, FILTER_SANITIZE_EMAIL);
					$this->load->model('contractors/create_user_handler');
					echo $this->create_user_handler->check_email_avaliable($email);
				}
			}
		}
		
		public function add_zip()
		{
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$data['zip'] = (int)$this->uri->segment(3);
				$data['service'] = (int)$this->uri->segment(4);
				$data['price'] = (int)$this->uri->segment(5);
				if(!empty($data['zip']) or !empty($data['service'])) {
					$this->load->model('contractor/ad_handler');
					$results = $this->ad_handler->check_zip($data);
					echo json_encode($results);
				} else {
					$results = array('error', 'Not Found');
					echo json_encode($results);
				}
			}
		}
		
		public function add_zip_create_account()
		{
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$data['zip'] = (int)$this->uri->segment(3);
				$data['service'] = (int)$this->uri->segment(4);
				$data['price'] = (int)$this->uri->segment(5);
				if(!empty($data['zip']) or !empty($data['service'])) {
					$this->load->model('contractor/ad_handler');
					$results = $this->ad_handler->check_zip_create_account($data);
					echo json_encode($results);
				} else {
					$results = array('error', 'Not Found');
					echo json_encode($results);
				}
			}
		}
		
		public function add_zips_to_account()
		{
			$this->check_if_loggedin();
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$data['zip'] = (int)$this->uri->segment(3);
				$data['service'] = (int)$this->uri->segment(4);
				if(!empty($data['zip']) or !empty($data['service'])) {
					$this->load->model('contractor/manage_zip_codes');
					$results = $this->manage_zip_codes->addZip($data);
					echo json_encode($results);
				} else {
					$results = array('error', 'Not Found');
					echo json_encode($results);
				}
			}
			
		}
		
		public function remove_zip_account()
		{
			if ($this->input->is_ajax_request()) {
				$this->check_if_loggedin();
				$this->form_validation->set_rules('id', 'service', 'required|min_length[1]|max_length[20]|numeric|xss_clean');
				$this->form_validation->set_rules('service', 'service', 'required|min_length[1]|max_length[20]|numeric|xss_clean');
				if ($this->form_validation->run() == FALSE) {
					$feed = array('error'=>'Invalid selection, try again');
				} else {
					extract($_POST);
					$data = array('id'=>$id, 'service_type'=>$service);
					$this->load->model('contractor/manage_zip_codes');
					$results = $this->manage_zip_codes->remove_zip_code($data);
					if($results) {
						$feed = array('success'=>'Zip code has been deleted');
					} else {
						$feed = array('error'=>'Invalid selection, try again');
					}
				}
				echo json_encode($feed);
			}
		}
		
		function delete_public_image()
		{
			if ($this->input->is_ajax_request()) {
				$this->check_if_loggedin();
				$this->form_validation->set_rules('id', 'id', 'required|min_length[1]|max_length[20]|numeric|xss_clean');
				if ($this->form_validation->run() == FALSE) {
					$feed = array('error'=>validation_errors());
				} else {
					extract($_POST);
					$this->load->model('landlords/public_page_handler');
					$deleted = $this->public_page_handler->delete_public_image($id);
					if($deleted) {
						$feed = array('success'=>'Image has been deleted, you can now add a new image');
					} else {
						$feed = array('error'=>'Image Not Found, Try Again');
					}
				}
				echo json_encode($feed);	
			}
		}		
		
		function remove_zip()
		{
			$this->check_if_loggedin();
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$this->output->set_content_type('application/json');
				$data['zip'] = (int)$this->uri->segment(3);
				$data['service'] = (int)$this->uri->segment(4);
				if(!empty($data['zip']) or !empty($data['service'])) {
					$this->load->model('contractor/ad_handler');
					$results = $this->ad_handler->remove_zip_code_cart($data);
					echo $results;
				} else {
					echo 'fail';
				}
			}
		}
		
		public function show_available_service_zips() //Account creation search
        {
			$this->output->set_template('json');
			$this->output->set_content_type('application/json');
            $zip = (int)$this->uri->segment(3);
            $serviceType = $this->uri->segment(4);
			if ($this->input->is_ajax_request()) {
                if (strlen($zip) == 5) {
                    $this->load->model('contractor/create_account');
					echo $this->create_account->available_zips($zip, $serviceType);
                } else {
					echo '1';	
				}
            } else {
				return false;
			}
			
		}
		
		public function remove_service_zip()
		{
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$this->output->set_content_type('application/json');
				$data['zip'] = (int)$this->uri->segment(3);
				$data['service'] = (int)$this->uri->segment(4);
				if(!empty($data['zip']) or !empty($data['service'])) {
					$this->load->model('contractors/create_account');
					$results = $this->create_account->remove_zip_code_cart($data);
					echo $results;
				} else {
					echo 'fail';
				}
			}
		}
		
		public function remove_service_zip_create_account()
		{
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$this->output->set_content_type('application/json');
				$data['zip'] = (int)$this->uri->segment(3);
				$data['service'] = (int)$this->uri->segment(4);
				if(!empty($data['zip']) or !empty($data['service'])) {
					$this->load->model('contractor/create_account');
					$results = $this->create_account->remove_zip_code_cart($data);
					echo $results;
				} else {
					echo 'fail';
				}
			}
		}
		
		public function show_available_adspaces()
        {
			$this->check_if_loggedin();
            $zip = (int)$this->uri->segment(3);
            $serviceType = $this->uri->segment(4);
			if ($this->input->is_ajax_request()) {
                if (strlen($zip) == 5) {
                    $this->load->model('contractor/ad_handler');
					$data = $this->ad_handler->available_zips($zip, $serviceType);
					echo json_encode($data);
                } else {
					echo '1';	
				}
            } else {
				return false;
			}
		}
		
		public function purchase_ads()
		{ 
			if ($this->input->is_ajax_request()) {
				$this->check_if_loggedin();
				
				$this->form_validation->set_rules('baddress', 'Billing Address', 'required|min_length[5]|max_length[50]|xss_clean|trim');
				$this->form_validation->set_rules('bcity', 'Billing City', 'required|min_length[5]|max_length[30]|xss_clean|trim');
				$this->form_validation->set_rules('bstate', 'Billing State', 'required|min_length[2]|max_length[2]|xss_clean|trim');
				$this->form_validation->set_rules('bzip', 'Billing Zip', 'required|min_length[5]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('ccv', 'Credit Card CCV', 'required|min_length[3]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('credit_card', 'Credit Card Number', 'required|min_length[19]|max_length[19]|xss_clean|trim');
				$this->form_validation->set_rules('exp_month', 'Experation Month', 'required|min_length[2]|max_length[2]|xss_clean|trim');
				$this->form_validation->set_rules('exp_year', 'Experation Year', 'required|min_length[4]|max_length[4]|xss_clean|trim');
				$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[20]|xss_clean|trim');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[20]|xss_clean|trim');
				$this->form_validation->set_rules('name_on_card', 'Name On Card', 'required|min_length[2]|max_length[20]|xss_clean|trim');
				$this->form_validation->set_rules('total', 'Total', 'required|min_length[2]|max_length[10]|xss_clean|trim');
				
				if ($this->form_validation->run() == FALSE) {
					$feed = array('error'=>validation_errors());
				} else {
					$this->load->model('contractor/purchase_zips');
					$feed = $this->purchase_zips->purchase($_POST);
				}
				echo json_encode($feed);
			}
		}
		
		public function grab_stats()
		{
			if ($this->input->is_ajax_request()) {
				$this->check_if_loggedin();
				$this->load->model('contractor/stats_handler');
				$data = $this->stats_handler->stats_data();
				echo json_encode($data);
			}
		}
		
		public function update_payment()
		{
			if ($this->input->is_ajax_request()) {
				$this->check_if_loggedin();
				$this->form_validation->set_rules('ccv', 'Credit Card CCV', 'required|min_length[3]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('credit_card', 'Credit Card Number', 'required|min_length[19]|max_length[19]|xss_clean|trim');
				$this->form_validation->set_rules('exp_month', 'Experation Month', 'required|min_length[2]|max_length[2]|xss_clean|trim');
				$this->form_validation->set_rules('exp_year', 'Experation Year', 'required|min_length[4]|max_length[4]|xss_clean|trim');
				$this->form_validation->set_rules('name_on_card', 'Name On Card', 'required|min_length[2]|max_length[20]|xss_clean|trim');
		
				if ($this->form_validation->run() == FALSE) {
					$feed = array('error'=>validation_errors());
				} else {
					extract($_POST); 
					$ccv = '111';
					$credit_card = '4111111111111111';
					$exp_month = '01';
					$exp_year = '2016';
					$data = array('ccv'=>$ccv, 'credit_card'=>$credit_card, 'exp_month'=>$exp_month, 'exp_year'=>$exp_year, 'name_on_card'=>$name_on_card);
					$this->load->model('contractor/update_payment_details');
					$feed = $this->update_payment_details->update_payment($data);
					
				}
				
				echo json_encode($feed);
			}
		}
		
		function summernote_image_uploader()
		{
			
			if ($this->input->is_ajax_request()) {
			
				$path = "./uploads/";

				$year_folder = $path . date("Y");
				$month_folder = $year_folder . '/' . date("m");

				!file_exists($year_folder) && mkdir($year_folder , 0777);
				!file_exists($month_folder) && mkdir($month_folder, 0777);

				$path = $month_folder . '/' . $new_file_name;
				
				
				$config['upload_path'] = './uploads/'.date('Y').'/'.date('m').'/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '200';
				$config['max_width']  = '1500';
				$config['max_height']  = '1500';
				
				$this->load->library('upload', $config);
				$file = 'file';
				if (!$this->upload->do_upload($file)) {
					$error = array('error'=>$this->upload->display_errors('', ''));
					echo json_encode($error);
				} else {
					$data = array('upload_data' => $this->upload->data());
					$img = base_url().'uploads/'.date('Y').'/'.date('m').'/'.$data['upload_data']['file_name'];
					echo json_encode(array('success'=>$img));
				}
			} 
		}
		
		
		function getEmployeeInfo()
		{
			if ($this->input->is_ajax_request()) {
				$id = (int)$_POST['id'];
				if($id>0) {
					$this->load->model('contractor/employees');
					echo json_encode($this->employees->get_single_employee($id));
				}
			}
		}
		
		public function getCalendarEvents()
		{
			if ($this->input->is_ajax_request()) {
				$id = $this->session->userdata('user_id');
				if($id>0) {
					$this->load->model('special/schedule_calendar');
					$dates = array('start'=>$_POST['start'], 'end'=>$_POST['end']);
					$data = $this->schedule_calendar->loadEvents('contractor', $dates);
					echo json_encode($data);
				}
			}
		}
		
		public function addCalendarEvent()
		{
			if ($this->input->is_ajax_request()) {
				
				$this->form_validation->set_rules('employee_id', 'Employee', 'required|min_length[1]|max_length[20]|xss_clean|trim|integer');
				
				$this->form_validation->set_rules('startDate', 'Start Date', 'required|min_length[10]|max_length[10]|xss_clean|trim');
				$this->form_validation->set_rules('startTime', 'Start Time', 'required|min_length[5]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('endDate', 'End Date', 'required|min_length[10]|max_length[10]|xss_clean|trim');
				$this->form_validation->set_rules('endTime', 'End Time', 'required|min_length[5]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('allDay', 'All Day Event', 'required|min_length[4]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('title', 'Title', 'required|min_length[2]|max_length[150]|xss_clean|trim');
				
				$this->form_validation->set_rules('endAm', 'Start Date am/pm', 'required|min_length[2]|max_length[2]|alpha');
				$this->form_validation->set_rules('startAm', 'End Date am/pm', 'required|min_length[2]|max_length[2]|alpha');
				
				$this->form_validation->set_rules('link', 'Link', 'min_length[2]|max_length[255]|prep_url');
				$this->form_validation->set_rules('service_id', 'Service Id', 'min_length[1]|max_length[12]|int');
				
				if ($this->form_validation->run() == FALSE) {
					echo json_encode(array('error'=>validation_errors(' ', ' ')));
				} else {
					extract($_POST); 
			
					if($endAm == 'pm') {
						$endTime_array = explode(':', $endTime);
						$endTimeHour = 24-(12-$endTime_array[0]);
						$endTime = $endTimeHour.':'.$endTime_array[1];
					}
					if($startAm == 'pm') {
						$startTime_array = explode(':', $startTime);
						$startTimeHour = 24-(12-$startTime_array[0]);
						$startTime = $startTimeHour.':'.$startTime_array[1];
					}
					
					$start = $startDate.' '.$startTime;
					$end = $endDate.' '.$endTime;
					
					$this->load->model('special/schedule_calendar');
					$data = array(
						'user_id' => $this->session->userdata('user_id'),
						'employee_id'=>$employee_id,
						'start'=> date('Y-m-d\ H:i', strtotime($start)),
						'end'=> date('Y-m-d\ H:i', strtotime($end)),
						'allDay'=>$allDay,
						'title'=>$title,
						'user_type'=>'contractor',
						'service_request_id' => $service_id
					);
					if(!empty($link)) {
						$data['link'] = $link;
					}
					$submitted = $this->schedule_calendar->addEvent($data, 'contractor');
					if($submitted != false) {
						echo json_encode(array('success'=>$submitted));
					} else {
						echo json_encode(array('error'=>'Something went wrong, try again'));
					}
					
				}
			}
			
		}
		
		public function deleteCalendarEvent()
		{
			$this->form_validation->set_rules('id', 'id', 'required|min_length[1]|max_length[20]|xss_clean|required|trim|integer');
		
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(array('error'=>validation_errors(' ', ' ')));
			} else {
				extract($_POST); 
				$this->load->model('special/schedule_calendar');
				if($this->schedule_calendar->deteleEvent($id)>0) {
					echo json_encode(array('success'=> 'Event Deleted'));
				} else {
					echo json_encode(array('error'=> 'Something went wrong, try again '.$id));
				}
				
			}
			
		}
		
		public function updateDroppedEvent()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('start', 'Start Date', 'required|min_length[10]|max_length[100]|xss_clean|trim');
				$this->form_validation->set_rules('end', 'End Date', 'min_length[5]|max_length[100]|xss_clean|trim');
				$this->form_validation->set_rules('allDay', 'All Day Event', 'required|min_length[4]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('id', 'id', 'required|min_length[1]|max_length[40]|xss_clean|trim|integer');
				
				if ($this->form_validation->run() == FALSE) {
					echo json_encode(array('error'=>validation_errors('', '')));
				} else {
					extract($_POST); 
					
					if($end=='') {
						$end = $start;
					}
					
					$start_array = explode('(', $start);
					$end_array = explode('(', $end);
					
					if($allDay != 'true') {
						$allDay = 'false';
					}
					
					$data = array(
						'user_id' => $this->session->userdata('user_id'),
						'start'=> date('Y-m-d\ H:i', strtotime(substr($start_array[0], 0, -9))),
						'end'=> date('Y-m-d\ H:i', strtotime(substr($end_array[0], 0, -9))),
						'allDay'=>$allDay,
						'user_type'=>'contractor',
					);
					
					
					$this->load->model('special/schedule_calendar');
					
					$updated = $this->schedule_calendar->updateEvent($data, $id);
					if($updated) {
						echo json_encode(array('success'=>$submitted));
					} else {
						echo json_encode(array('error'=>'Something went wrong, try again'));
					}
					
				}
			}
			
		}
		
		public function updateCalendarEvent()
		{
			
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('employee_id', 'Employee', 'required|min_length[1]|max_length[20]|xss_clean|trim|integer');
				
				$this->form_validation->set_rules('startDate', 'Start Date', 'required|min_length[10]|max_length[10]|xss_clean|trim');
				$this->form_validation->set_rules('startTime', 'Start Time', 'required|min_length[5]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('endDate', 'End Date', 'required|min_length[10]|max_length[10]|xss_clean|trim');
				$this->form_validation->set_rules('endTime', 'End Time', 'required|min_length[5]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('allDay', 'All Day Event', 'required|min_length[4]|max_length[5]|xss_clean|trim');
				$this->form_validation->set_rules('title', 'Title', 'required|min_length[2]|max_length[150]|xss_clean|trim');
				$this->form_validation->set_rules('id', 'id', 'required|min_length[1]|max_length[40]|xss_clean|trim|integer');
				
				$this->form_validation->set_rules('editEndAm', 'End Time of day', 'required|min_length[2]|max_length[2]|alpha');
				$this->form_validation->set_rules('editStartAm', 'Start Time of day', 'required|min_length[2]|max_length[2]|alpha');
				
				if ($this->form_validation->run() == FALSE) {
					echo json_encode(array('error'=>validation_errors('', '')));
				} else {
					extract($_POST); 
										
					if($editEndAm == 'pm') {
						$endTime_array = explode(':', $endTime);
						if($endTime_array[0]!=12) {
							$endTimeHour = 24-(12-$endTime_array[0]);
							$endTime = $endTimeHour.':'.$endTime_array[1];
						}
					}
					
					if($editStartAm == 'pm') {
						$startTime_array = explode(':', $startTime);
						if($startTime_array[0]!=12) {
							$startTimeHour = 24-(12-$startTime_array[0]);
							$startTime = $startTimeHour.':'.$startTime_array[1];
						}
					}
					
					$start = $startDate.' '.$startTime;
					$end = $endDate.' '.$endTime;
					
					if($allDay != 'true') {
						$allDay = 'false';
					}
				
					
					$data = array(
						'user_id' => $this->session->userdata('user_id'),
						'employee_id'=>$employee_id,
						'start'=> date('Y-m-d\ H:i', strtotime($start)),
						'end'=> date('Y-m-d\ H:i', strtotime($end)),
						'allDay'=>$allDay,
						'title'=>$title,
						'user_type'=>'contractor',
					);
					
					
					
					
					$this->load->model('special/schedule_calendar');
					
					$updated = $this->schedule_calendar->updateEvent($data, $id);
					if($updated) {
						echo json_encode(array('success'=>$submitted));
					} else {
						echo json_encode(array('error'=>'Something went wrong, try again'));
					}
					
				}
			} 
			
		}
		
		public function add_service_request()
		{
			if ($this->input->is_ajax_request()) {
				$this->check_if_loggedin();
				
				$this->form_validation->set_rules('contactEmail', 'Contact Email', 'min_length[3]|max_length[50]|valid_email|xss_clean|trim');
				$this->form_validation->set_rules('contactName', 'Contact Name', 'min_length[2]|max_length[60]|xss_clean|trim');
				$this->form_validation->set_rules('contactPhone', 'Contact Phone', 'min_length[14]|max_length[14]|xss_clean|trim');
				
				$this->form_validation->set_rules('serviceAddress', 'Service Address', 'min_length[5]|max_length[40]|xss_clean|trim|required');
				$this->form_validation->set_rules('serviceCity', 'Service City', 'min_length[2]|max_length[40]|xss_clean|trim|required');
				$this->form_validation->set_rules('serviceState', 'Service State', 'min_length[2]|max_length[2]|xss_clean|trim|required');
				$this->form_validation->set_rules('serviceType', 'Service Type', 'min_length[1]|max_length[2]|xss_clean|trim|required|integer');
				$this->form_validation->set_rules('serviceZip', 'Service Zip', 'min_length[5]|max_length[5]|xss_clean|trim|required|integer');
				$this->form_validation->set_rules('description', 'Service Description', 'min_length[5]|max_length[500]|xss_clean|trim|required');
				
				if($_POST['schedule'] == 'y') {
					$this->form_validation->set_rules('createEndTask', 'End Date', 'required|min_length[10]|max_length[10]|xss_clean|trim');
					$this->form_validation->set_rules('createStartTask', 'Start Date', 'required|min_length[10]|max_length[10]|xss_clean|trim');
					$this->form_validation->set_rules('createTaskEndTime', 'End Time', 'required|min_length[5]|max_length[5]|xss_clean|trim');
					$this->form_validation->set_rules('createTaskStartTime', 'Start Time', 'required|min_length[5]|max_length[5]|xss_clean|trim');
					
					$this->form_validation->set_rules('endAm', 'End AM/PM', 'required|min_length[2]|max_length[2]|alpha');
					$this->form_validation->set_rules('startAm', 'Start AM/PM', 'required|min_length[2]|max_length[2]|alpha');
				}
								
				if ($this->form_validation->run() == FALSE) {
					echo json_encode(array('error'=>validation_errors('', '')));
					exit;
				} else {
					extract($_POST); 
					
					$isValidDates = true;
					if($schedule == 'y') {
						$startDateValid = $this->validDate($createStartTask, $createTaskStartTime, $startAm);
						$endDateValid = $this->validDate($createEndTask, $createTaskEndTime, $endAm);
						if($startDateValid === false || $endDateValid === false) {
							$isValidDates = false;
						}
					}

					if($isValidDates) {
						$img = '';
						if(!empty($_FILES)) {
							$path = "./uploads/";

							$year_folder = $path . date("Y");
							$month_folder = $year_folder . '/' . date("m");

							!file_exists($year_folder) && mkdir($year_folder , 0777);
							!file_exists($month_folder) && mkdir($month_folder, 0777);

							$path = $month_folder . '/' . $new_file_name;
							
							$config['upload_path'] = './uploads/'.date('Y').'/'.date('m').'/';
							$config['allowed_types'] = 'gif|jpg|png';
							$config['max_width']  = '3000';
							$config['max_height']  = '3000';
							
							
							$this->load->library('upload', $config);
							$file = 'file';
							if (!$this->upload->do_upload($file)) {
								echo json_encode(array('error'=>$this->upload->display_errors('', '')));
								exit;
							} else {
								$data = array('upload_data' => $this->upload->data());
								$img = base_url().'uploads/'.date('Y').'/'.date('m').'/'.$data['upload_data']['file_name'];
							}
						}
						
						
						$this->load->model('contractor/service_request');
						
						if(!empty($contactPhone)) {
							$contactPhone = preg_replace("/[^0-9,.]/", "",  $contactPhone);
						}
						
						$data = array(
							'service_type' => $serviceType,
							'description' => $description,
							'schedule_phone' => $contactPhone,
							'attachment' => $img,
							'submitted' => date('Y-m-d H:i:s'),
							'contractor_received' => date('Y-m-d H:i:s'),
							'contractor_id' => $this->session->userdata('user_id'),
							'address' => $serviceAddress.' '.$serviceCity.', '.$serviceState.' '.$serviceZip,
							'page_submit' => 'y',
							'email' => $contactEmail,
							'name' => $contactName,
						);
						$serviceId = $this->service_request->add_service_request($data);
						if($serviceId>0) {
							if($schedule == 'y') {
								$event = array(
									'user_id' => $this->session->userdata('user_id'),
									'employee_id'=>$this->session->userdata('user_id'),
									'start'=> $startDateValid,
									'end'=> $endDateValid,
									'allDay'=>'false',
									'title'=>'SR: '.$data['address'],
									'user_type'=>'contractor',
									'link' => 'https://network4rentals.com/network/contractor/view-service-request/'.$serviceId,
								);
								
								$this->load->model('special/schedule_calendar');
								$this->schedule_calendar->addEvent($event);
							}
							$this->session->set_flashdata('success', 'Service request added successfully');
							
							echo json_encode(array('success'=>'Service request added'));
							exit;
							
						} else {
							echo json_encode(array('error'=>'Add service request failed, try again'));
							exit;
						}
					} else {
						echo json_encode(array('error'=>'There is an invalid date scheduled, fix it and try again'));
						exit;
					}
					
				
				}
			}
		}
		
		private function validDate($date, $time, $ampm)
		{
			$time_array = explode(':', $time);
			$date_array = explode('/', $date);
			
			
			if($date_array[0]>12 || $date_array[0]<1) {
				return false;
			}
			if($date_array[1] > date('t', strtotime($date))) {
				return false;
			}
			if( $date_array[2]< date('Y') && $date_array[2]>(date('Y')+4)) {
				return false;
			}
			
			if($time_array[0]>12 && $time_array[1]>=0) {
				return false;
			}
			if($time_array[1]>59 && $time_array[1]>=0) {
				return false;
			}
			
			if($ampm == 'pm') {
				
				$time_array[0] = $time_array[0]+12;
			}
			
			return date('Y-m-d H:i', strtotime($date.' '.$time_array[0].':'.$time_array[1]));
		}
		
	} //ENDS CLASS
