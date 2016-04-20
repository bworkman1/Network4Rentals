<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class account_handler extends CI_Model {
	
		private $amount = '99.99';
		//private $startDate = date('Y-m-d');
		
		function Account_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		protected function encrypt_password($pwd)
		{
			return md5($pwd.'OpY9Awad7jsd1Sql5MTF9YK');
		}
	
		function create_account($data) 
		{
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$data['hash'] = md5($data['username'].$data['ip']);
			$data['phone'] =  preg_replace('/[^0-9.]+/', '', $data['phone']);
			
			$expDate = '';
			$cc = '';
			if(!empty($data['cc_number'])) {
				$cc = substr(preg_replace("/[^0-9,.]/", "", $data['cc_number']), -4);
				$expDate = $data['exp_yy'].'-'.$data['exp_month'].'-01';
				unset($data['exp_yy']);
				unset($data['exp_month']);
				unset($data['cc_number']);
				unset($data['cv_code']);
				unset($data['cc_name']);
			}
			
			$this->db->insert('landlord_assoc', $data);
			$id = $this->db->insert_id();
			if($id>0) {
				$data = array(
					'name' => $data['name'],
					'hash' => $data['hash'],
					'username'=>$data['username'],
					'email'=>$data['email'],
					'sub_id'=>$data['sub_id'],
					'id' => $id,
					'last_4'=>$cc,
					'expires' => $expDate
				);
				$this->logPaymentDetails($data);
				return $data;
			} else {
				return 0;
			}
		}
		
		function create_account_simplified()
		{
			$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[100]|xss_clean|is_unique[landlord_assoc.email]|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[200]|matches[password2]|xss_clean|md5');
			$this->form_validation->set_rules('password2', 'Confirm Password', 'required|trim|max_length[200]|xss_clean');
			$this->form_validation->set_rules('referrer', 'Heard About Us', 'required|trim|min_length[3]|max_length[20]|xss_clean');
			
			$this->form_validation->set_rules('coupon', 'Coupon', 'trim|min_length[1]|max_length[10]|xss_clean');
			
			$this->form_validation->set_rules('cc_name', 'Name on credit card', 'required|trim|min_length[1]|max_length[30]|xss_clean|');
			$this->form_validation->set_rules('cc_number', 'Credit Card Number', 'required|trim|min_length[1]|max_length[19]|xss_clean|');
			$this->form_validation->set_rules('exp_month', 'Expiration Month', 'required|trim|min_length[2]|max_length[2]|xss_clean|numeric');
			$this->form_validation->set_rules('exp_yy', 'Expiration Year', 'required|trim|min_length[4]|max_length[4]|xss_clean|numeric');
			$this->form_validation->set_rules('cv_code', 'CV Number', 'required|trim|min_length[1]|max_length[4]|xss_clean|numeric');
			
			$this->form_validation->set_rules('terms', 'Terms Of Service', 'required|trim|min_length[1]|max_length[1]|xss_clean|alpha');
			
			$this->form_validation->set_message('is_unique', '%s is already being used, try logging into your account by using the forgot password link <a href="'.base_url().'landlord-associations/forgot_password">here</a>.');
			
			if($this->form_validation->run() == FALSE) {
				return array('error'=>$this->validation_errors());
			} else {
				extract($_POST);
				$name_array = explode(' ', $cc_name);
				$coupon_array = $this->getCoupons();
				$cc_processed = array();
				$setTrial = true;
				$charged_data = array( //ONCE THE ACCOUNT IS CREATED AND ADD THE USER ID AND RUN IT TO THE LOG PAYMENT FUNCTION
					'user_id' 	=>	'',
					'amount'	=>  $this->amount,
					'type' 		=>  'association',
					'payment_id'	=> $cc_processed[0],
					'payment_date'  => date('Y-m-d H:i:s'), 
					'expires'		=> $exp_yy.'-'.$exp_month.'-01',
					'last_4'		=> substr($cc_number, -4),
				);
				
				if(!in_array($coupon, $coupon_array)) {
					$setTrial = false;
					//INVALID COUPON CHARGE CARD NOW
					$payment_data = array(
						'credit_card' 	=> $cc_number,
						'exp_month' 	=> $exp_month,
						'exp_year'  	=> $exp_yy,
						'email'     	=> $email,
						'ccv'	 		=> $cv_code,
						'first_name' 	=> $name_array[0],
						'last_name' 	=> $name_array[count($name_array)-1],
					);
					$cc_processed = $this->process_payment($payment_data);					
				}
				
				if(!isset($cc_processed['error'])) {
					//CARD HAS BEEN CHARGED SUCCESSFULLY
					if($setTrial) { //IF COUPON CODE WAS VALID SET A TRIAL 1 YEAR FROM NOW
						$payment_data = array(
							'credit_card' 	=> $cc_number,
							'exp_month' 	=> $exp_month,
							'exp_year'  	=> $exp_yy,
							'email'     	=> $email,
							'ccv'	 		=> $cv_code,
							'first_name' 	=> $name_array[0],
							'last_name' 	=> $name_array[count($name_array)-1],
						);
						$sub_process = $this->create_trial_subscription($payment_data);
						
					} else {
						$payment_data = array(
							'credit_card' 	=> $cc_number,
							'exp_month' 	=> $exp_month,
							'exp_year'  	=> $exp_yy,
							'email'     	=> $email,
							'ccv'	 		=> $cv_code,
							'first_name' 	=> $name_array[0],
							'last_name' 	=> $name_array[count($name_array)-1],
						);
						$sub_process = $this->account_create_subscription($payment_data);
					}
					
					if(isset($sub_process['success'])) {
						//SUBSCRIPTION SUCCESSFUL
						$user_data = array(
							'username' 	=> $email,
							'email' 	=> $email,
							'password' 	=> md5(),	
							'name'   	=> $cc_name,
							'terms'		=> $terms,
							'created' 	=> date('Y-m-d H:i:s'),
							'ip'		=> $this->input->ip_address(),
							'verified'	=> 'y',
							'referrer'	=> $referrer,
							'coupon'	=> $coupon,
							'sub_id'	=> '',
							'active'	=> 'y',
						);
						$results = $this->db->insert('landlord_assoc', array($user_data));
						$userId = $this->db->insert_id();
						if($userId>0) {
							
							
							
							return array('success', 'Account created successfully');
						} else {
							return array('error', 'Your credit card was charged but your account failed to created, please <a href="https://network4rentals.com/help-support/">contact support</a>');
						}
					} else {
						$data = $cc_processed;
					}
					
				} else {
					$data = $cc_processed;
				}
				
				return $data;
			}
		}
		
		private function process_payment()
		{
			$this->load->library('auth/authorize_net');
			$auth_net = array(
				'x_card_num'			=> $data['credit_card'],
				'x_exp_date'			=> $data['exp_month'].'/'.$data['exp_year'],
				'x_card_code'			=> $data['ccv'],
				'x_description'			=> 'Network4Rentals Landlord Association Subscription',
				'x_amount'				=> $this->amount,
				'x_first_name'			=> $data['first_name'],
				'x_last_name'			=> $data['last_name'],
				'x_country'				=> 'US',
				'x_email'				=> $data['email'],
				'x_customer_ip'			=> $this->input->ip_address()				
			);

			$this->authorize_net->setData($auth_net);
			// Try to AUTH_CAPTURE
			if( $this->authorize_net->authorizeAndCapture() ) {
				$tranId = $this->authorize_net->getTransactionId();
				$appCode = $this->authorize_net->getApprovalCode();
				return array($tranId, $appCode);
			} else {
				return array('error'=>$this->authorize_net->getError());
			}
		}
		
		private function create_trial_subscription($data)
		{
			/*
				This function will process all the payment data and return and array with a key of error or success upon completion
			*/
			if($payment_amount != false) {
				$this->load->library('authorize_arb');
				$this->authorize_arb->startData('create');
				$this->authorize_arb->addData('refId', 'my_reference_id');
				$subscription_data = array(
					'name' => 'Network 4 Rentals Landlord Association Account',
					'paymentSchedule' => array(
						'interval' => array(
							'length' => 12,
							'unit' => 'months',
							),
						'startDate' => date('Y-m-d'),
						'totalOccurrences' => 9999, // Unlimited
						'trialOccurrences'=>1,
						),
					'amount' => $this->amount,
					'trialAmount' => 0.00,
					'payment' => array(
						'creditCard' => array(
							'cardNumber' => $data['credit_card'],
							'expirationDate' => $data['exp_year'].'-'.$data['exp_month'],
							'cardCode' => $data['ccv'],
							),
						),
					'customer' => array(
						'email' => $data['email'],
					),	
					'billTo' => array(
						'firstName' => $data['first_name'],
						'lastName' => $data['last_name'],
						'country' => 'US',
					),
				);
				
		
				
				$this->authorize_arb->addData('subscription', $subscription_data);
				$this->authorize_arb->send();
			
				$error = $this->authorize_arb->getError();
				$subscription_id = $this->authorize_arb->getId();

				if (empty($error)) {
					if(!empty($subscription_id)) {
						return array('success'=>$subscription_id);
					} else {
						return array('error'=>'Failed to create subscription');
					}
				} else {
					return array('error'=>$error);
				}
			}			
		}
		
		public function account_create_subscription($data) 
		{			
			/*
				This function will process all the payment data and return and array with a key of error or success upon completion
			*/
			if($payment_amount != false) {
				$this->load->library('authorize_arb');
				$this->authorize_arb->startData('create');
				$this->authorize_arb->addData('refId', 'my_reference_id');
				$subscription_data = array(
					'name' => 'Network 4 Rentals Contractor Ads',
					'paymentSchedule' => array(
						'interval' => array(
							'length' => 12,
							'unit' => 'months',
							),
						'startDate' => date('Y-m-d'),
						'totalOccurrences' => 9999, // Unlimited
						),
					'amount' => $this->amount,
					'payment' => array(
						'creditCard' => array(
							'cardNumber' => $data['credit_card'],
							'expirationDate' => $data['exp_year'].'-'.$data['exp_month'],
							'cardCode' => $data['ccv'],
							),
						),
					'customer' => array(
						'email' => $data['email'],
					),	
					'billTo' => array(
						'firstName' => $data['first_name'],
						'lastName' => $data['last_name'],
						'country' => 'US',
						),
				);
				
		
				
				$this->authorize_arb->addData('subscription', $subscription_data);
				$this->authorize_arb->send();
			
				$error = $this->authorize_arb->getError();
				$subscription_id = $this->authorize_arb->getId();

				if (empty($error)) {
					if(!empty($subscription_id)) {
						return array('success'=>$subscription_id);
					} else {
						return array('error'=>'Failed to create subscription');
					}
				} else {
					return array('error'=>$error);
				}
			}
		}
		
		private function logPayment($data)
		{		
			//user_id	amount	type	payment_id	payment_date	options	payment_frequency	cancel_date	sub_id	active	expires	last_4
			$this->db->insert('payments',  $data);
		}
		
		function returnSubAmount() 
		{
			return $this->amount;
		}
		
		function logPaymentDetails($data) 
		{
			$this->load->library('encrypt');
			$insert = array(
				'user_id' => $data['id'],
				'amount' => $this->amount,
				'type' => 'association',
				'payment_date' => date('Y-m-d H:i:s'),
				'payment_frequency' => '12',
				'sub_id' => $data['sub_id'],
				'active' => 'y',
				'expires' =>  $data['expires'],
				'last_4' =>  $data['last_4']
			);
				
	
			$this->db->insert('payments', $insert);
		}
			
		function verify_account_email($data) 
		{
			$this->db->where('email', $data['email']);
			$this->db->where('hash', $data['hash']);
			$this->db->where('verified', 'n');
			$this->db->update('landlord_assoc', array('hash'=>'', 'verified'=>'y'));
			return $this->db->affected_rows();
		}
		
		function public_page_data() 
		{	
			$this->db->select('id, visits');
			$result = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'association'));
			if($result->num_rows()>0) {
				return $result->row();
			}
			
		}
		
		function getCoupons()
		{
			$coupon_codes = array('free4year');
			return $coupon_codes;
		}
		
		public function check_unique_username($user)
		{
			$this->db->limit(1);
			$results = $this->db->get_where('landlord_assoc', array('username'=>$user));
			if($results->num_rows()>0) {
				return 1; // Username is not unique
			} else {
				return 0; // Username is unique
			}
		}
		
		public function total_members()
		{
			$results = $this->db->get_where('landlord_assoc_members', array('assoc_id'=>$this->session->userdata('user_id')));
			return $results->num_rows();
		}
		
		public function check_unique_email($email)
		{
			$this->db->limit(1);
			$results = $this->db->get_where('landlord_assoc', array('email'=>$email));
			if($results->num_rows()>0) {
				return 1; // Email is not unique
			} else {
				return 0; // Email is unique
			}
		}

		public function check_unique_email_edit($email)
		{
			$this->db->limit(1);
			$results = $this->db->get_where('landlord_assoc', array('email'=>$email, 'id !='=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return 1; // Email is not unique
			} else {
				return 0; // Email is unique
			}
		}
		
		public function check_login_details($data) //Check login details
		{
			$data['password'] = md5($data['password']);
			$data['verified'] = 'y';
			$this->db->select('id');
			$this->db->limit(1);
	
			$results = $this->db->get_where('landlord_assoc', $data);
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->check_payment_details($row->id);
				return $row->id; // Username and password match
			} else {
				return 0; // username and password do not match
			}
		}
		
		public function check_payment_details($id) 
		{
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$results = $this->db->get_where('payments', array('user_id'=>$id));
			if($results->num_rows()>0) {
				// First check to see if the payment is about to expire
				$row = $results->row();
				  
				if(!empty($row->expires)) {
					if(strtotime($row->expires) < strtotime('+30 day')) { 
						if(strtotime($row->expires)>strtotime(date('Y-m-d'))) {
							$this->session->set_userdata('warning', '<i class="fa fa-exclamation-triangle"></i> The Credit card you have on file with us is about to expire in less than 30 days. <a href="'.base_url('landlord-associations/update-payment-settings').'">Update Payment Settings</a>');
							$this->session->unset_userdata('danger');
						} else {
							$this->session->set_userdata('danger', '<i class="fa fa-exclamation-triangle"></i> The Credit card you have on file with us has expired, if you don\'t update your payment details your subscription will expire on <b>'.date("m/d/Y", strtotime("12 months", strtotime($row->expires))).'<b>'); 
							$this->session->unset_userdata('warning');
						} 
					} else {
						$this->session->unset_userdata('expiredSub');
						$this->session->unset_userdata('warning');
						$this->session->unset_userdata('danger');
					}
					
					$this->session->set_userdata('expiredSub', true);    
				}
			}
		}
		
		public function get_account_details()
		{
			$results = $this->db->get_where('landlord_assoc', array('id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row;
			} else {
				return false;
			}
		}
		
		function edit_account_details($data) 
		{
			if(array_key_exists ('password', $data)) {
				$data['password'] = $this->encrypt_password($data['password']);
			} 
			$data['phone'] =  preg_replace('/[^0-9.]+/', '', $data['phone']);
			$userData = $this->get_account_details();
			
			if($userData->email == $data['email']) {
				unset($data['email']);
			}
			
			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->update('landlord_assoc', $data);
			$id = $this->db->affected_rows();
			return $id;
		}
		
		function load_stats()
		{
			$data['member_count'] = $this->count_assoc_members();   //returns an integer
			$data['page_posts'] = $this->count_blog_post();			//returns an integer
			$data['next_event'] = $this->next_calendar_event();	     //returns an object
			$data['public_page_views'] = $this->public_page_views(); //returns an integer
			
			return $data;
		}
		
		function count_assoc_members()
		{
			$results = $this->db->get_where('landlord_assoc', array('id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$row = $results->row();
				if(!empty($row->members)) {
					$members = explode('|', $row->members);
					return sizeof($members);
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		function count_blog_post() 
		{
			$results = $this->db->get_where('assoc_posts', array('user_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return $results->num_rows();
			} else {
				return 0;
			}
		}
		
		function next_calendar_event()
		{
			$now = date('Y-m-d h:i:s');
			$this->db->limit(1);
			$results = $this->db->get_where('assoc_events', array('user_id'=>$this->session->userdata('user_id'), 'start >'=>$now));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return NULL;
			}
		}
		
		function public_page_views()
		{	
			$this->db->select('visits');
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'landlord-assoc'));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row->visits;
			} else {
				return 0;
			}			
		}
		
		
		
		
	}
		
	