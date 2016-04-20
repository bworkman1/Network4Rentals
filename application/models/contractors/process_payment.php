<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
    class Process_payment extends CI_Model {
		
		/*Error codes Submit Payment: 
			** 1 = 'Payment Failed To Process'
			** 2 = 'User Failed To Insert Into contractors table'
			** 3 = 'Failed To Add Zips To The contractor_zips Table'
			** 4 = 'Failed To Log The Payment into the payments Table'
		*/
		
        public function __construct() 
		{
            parent::__construct();
        }

		public function submit_payment($data) 
		{			
			$payment_amount = $this->calculate_total($data['frequency']); //Payment amount calculated through the session cookies
			if($payment_amount != false) {
				$this->load->library('authorize_arb');
				$this->authorize_arb->startData('create');
				$this->authorize_arb->addData('refId', 'my_reference_id');
				$subscription_data = array(
					'name' => 'Network 4 Rentals Contractor Ads',
					'paymentSchedule' => array(
						'interval' => array(
							'length' => $data['frequency'],
							'unit' => 'months',
							),
						'startDate' => date('Y-m-d'),
						'totalOccurrences' => 9999, // Unlimited
						),
					'amount' => $payment_amount,
					'payment' => array(
						'creditCard' => array(
							'cardNumber' => $data['credit_card'],
							'expirationDate' => $data['exp_year'].'-'.$data['exp_month'],
							'cardCode' => $data['ccv'],
							),
						),
					'billTo' => array(
						'firstName' => $data['first_name'],
						'lastName' => $data['last_name'],
						'address' => $data['baddress'],
						'city' => $data['bcity'],
						'state' => $data['bstate'],
						'zip' => $data['bzip'],
						'country' => 'US',
						),
				);
				
		
				
				$this->authorize_arb->addData('subscription', $subscription_data);
				$this->authorize_arb->send();
			
				$error = $this->authorize_arb->getError();
				$subscription_id = $this->authorize_arb->getId();

				if (empty($error)) {
					if(!empty($subscription_id)) {
							// Loaded for random string for email hash
						//Create user account data
						$user_data = array(
							'bName'			=> $data['bName'],
							'user' 			=> $data['user'],
							'password' 		=> md5($data['password']),
							'email'			=> $data['email'],
							'email_hash' 	=> $data['hash'],
							'address'		=> $data['address'],
							'city'			=> $data['city'],
							'state'			=> $data['state'],
							'zip'			=> $data['zip'],
							'f_name'		=> $data['first_name'],
							'l_name'		=> $data['last_name'],
							'fax'			=> $data['fax'],
							'phone'			=> $data['phone'],
							'baddress'		=> $data['baddress'],
							'bcity'			=> $data['bcity'],
							'bstate'		=> $data['bstate'],
							'bzip'			=> $data['bzip'],
							'active'		=> $data['terms'],
							'sub_id'		=> $subscription_id,
							'ip'			=> $_SERVER['REMOTE_ADDR'],
							'created'	=> date('Y-m-d h:i:s')
						);
						
						if(isset($data['promo'])) {
							$user_data['promo'] = $data['promo'];
						}
					
						$this->db->insert('contractors', $user_data); 
						$contractor_id = $this->db->insert_id();
						
						if($contractor_id>0) { //check if insert was successful 
							$this->load->library('encrypt');
							$user_data['id'] = $contractor_id;
							$user_data['payment_amount'] = $payment_amount;
							$user_data['type'] = 'contractor';
							$user_data['frequency'] = $data['frequency'];
							$user_data['payment_id'] = random_string('sha1', 55);
							$user_data['last_4'] = substr($data['credit_card'], -4);
							$expires_on = $data['exp_month'].'/01/'.$data['exp_year'];
							
							$user_data['expires'] = date('Y-m-d', strtotime($expires_on));
							
							// If payment was successful add the zips to the contractor zips table
							$info = array('id'=>$user_data['id']);
							$results = $this->add_contractor_zips($info);
							
							if($results) {
								$zips = $this->session->userdata('zips');
								$service = $this->session->userdata('service');
								$options = '';
								for($i=0;$i<count($zips);$i++) {
									$options .= $zips[$i].'-'.$service[$i].'|';
								}
								$options = rtrim($options, '|');
								$user_data['options'] = $options;
								$user_data['sub_id'] = $subscription_id;
								$logged = $this->log_payment($user_data);
								if($logged) {
									$this->session->set_userdata('user_id', $user_data['id']);
									$this->session->set_userdata('side_logged_in', '203020320389822');
									$this->session->set_userdata('logged_in', true);							
									return '89';
								} else {
									log_message('error', 'Failed To Log Payment To The payments table contractors/models/process_payment.php Line 172');
									return '4';
								}
								
							} else {
								log_message('error', 'Failed To Add Zips To The contractor_zips Table contractors/models/process_payment.php Line 88');
								return '3';
							}
						} else {
							log_message('error', 'User Failed To Insert Into contractors table contractors/models/process_payment.php Line 76');
							return '2';
						}
					} else {
						log_message('error', 'Payment Failed To Process Through Authorize even though there were no errors in the transaction in contractors/models/process_payment.php');
						return '1';
					}
				} else {
					log_message('error', 'Payment Failed To Process Through Authorize in contractors/models/process_payment.php : Reason: '.$error);
					return '1'; 
				}
			} else {
				log_message('error', 'Calculating the payment failed due to the zips not matching the services contractors/models/process_payment.php');
				return '5';
			}
		}

		public function calculate_total($frequency)
		{
			/*
				calculates a total according to the zip codes in their session cookie and grabs the actual price from the database
			*/
			
			$zips = $this->session->userdata('zips');
			$service = $this->session->userdata('service');
			if(count($zips)==count($service)) {
				$total = 0;
				foreach($zips as $val) {
					$this->db->select('contractor_price');
					$results = $this->db->get_where('zips', array('zipCode'=>$val));
					if($results->num_rows()>0) {
						$row = $results->row();
						$total = $total+$row->contractor_price;
					}
				}
				
				if($total>0) {
					$total = $total*$frequency;
					return $total;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
			
		public function add_contractor_zips($data)
		{
			/* 
				required: data['id'] <-- Insert Id Of Contractor Account
				used array just in case more data was needed later
			*/
			$zips = $this->session->userdata('zips');
			$service = $this->session->userdata('service');
			for($i=0;$i<count($zips);$i++) {
				$this->db->insert('contractor_zips', array('contractor_id'=>$data['id'], 'zip_purchased'=>$zips[$i], 'service_purchased'=>$service[$i]));
			}
			
			if($i != count($zips)){ // Check to see if count matched the amount of zips they purchased
				// Email order details
				return false;
			} else {
				return true;
			}
		}
		
		public function log_payment($data)
		{
			$log = array(
				'user_id' 			=> $data['id'],
				'amount'			=> $data['payment_amount'],
				'type'				=> $data['type'],
				'payment_id'		=> $data['payment_id'],
				'options'			=> $data['options'],
				'payment_frequency'	=> $data['frequency'],
				'sub_id'			=> $data['sub_id'],
				'expires'			=> $data['expires'],
				'last_4'			=> $data['last_4']
			);
			$query = $this->db->insert('payments', $log);

			if($this->db->insert_id()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function cancel_payment()
		{
			$this->db->select('sub_id');
			$results = $this->db->get_where('payments', array('user_id'=>$this->session->userdata('user_id'), 'active'=>'y','type'=>'contractor'));
			if($results->num_rows()>0) {
				$row = $results->row();
				$subId = $row->sub_id;
				// Load the ARB lib
				$this->load->library('authorize_arb');
				// Start with a cancel object
				$this->authorize_arb->startData('cancel');
				// Locally-defined reference ID (can't be longer than 20 chars)
				$refId = substr(md5( microtime() . 'ref' ), 0, 20);
				$this->authorize_arb->addData('refId', $refId);
				
				// The subscription ID that we're canceling
				$this->authorize_arb->addData('subscriptionId', $subId);
				
				// Send request
				if($this->authorize_arb->send()) {
					$refId = $this->authorize_arb->getRefId();
					if(!empty($refId)) {
						$results = $this->db->get_where('contractor_zips', array('contractor_id' => $this->session->userdata('user_id'))); 
						foreach ($results->result() as $row) {
							$this->db->delete('contractor_ads', array('ref_id' => $row->id)); 
						}
						$this->db->delete('contractor_zips', array('contractor_id' => $this->session->userdata('user_id'))); 
						
						$this->db->where('id', $this->session->userdata('user_id'));
						$this->db->update('contractors', array('sub_id'=>''));
						
						$this->db->where('id', $this->session->userdata('user_id'));
						$this->db->update('contractors', array('sub_id'=>''));
						
						$this->db->where('sub_id', $subId);
						$this->db->update('payments', array('cancel_date'=>date('Y-m-d'), 'active'=>'n'));
						
						$this->db->where('landlord_id', $this->session->userdata('user_id'));
						$this->db->where('type', 'contractor');
						$this->db->update('landlord_page_settings', array('active'=>'n', 'unique_name'=>''));
						
						return true;
					} else {
						return false;
					}
				} else {
					log_message('error', $this->authorize_arb->getError());
				}

			} else {
				log_message('error', 'No User Found For ');
			}
			
		}
		
		function get_unactive_users_cron()  //cron job that grabs all unverified users and cancels their subscription before they are charged
		{
			$data = array();
			$this->db->select('sub_id, promo');
			$this->db->where('active', 'n');
			$this->db->where('email_hash !=', '');
			$this->db->where('sub_id !=', '');
			$results = $this->db->get('contractors');
			if($results->num_rows()>0) {
				foreach($results->result() as $row) {
					if($row->promo !=='y') {
						$this->cancel_payment_cron($row->sub_id);
						$data[] = $row->sub_id;
					}
				}
			}
			$this->db->select('sub_id');
			$this->db->where('active', 'n');
			$this->db->where('email_hash !=', '');
			$this->db->where('sub_id !=', '');
			$results = $this->db->get('advertisers');
			if($results->num_rows()>0) {
				foreach($results->result() as $row) {
					$this->cancel_payment_cron_advertisers($row->sub_id);
					$data[] = $row->sub_id;
				}
				return $data;
			}
		}

		function cancel_payment_cron_advertisers($sub_id)
		{
			$this->db->select('user_id, sub_id');
			$results = $this->db->get_where('payments', array('active'=>'y','type'=>'advertiser', 'sub_id'=>$sub_id));
			if($results->num_rows()>0) {
				$row = $results->row();
				$subId = $row->sub_id;
				$user_id = $row->user_id;
				// Load the ARB lib
				$this->load->library('authorize_arb');
				// Start with a cancel object
				$this->authorize_arb->startData('cancel');
				// Locally-defined reference ID (can't be longer than 20 chars)
				$refId = substr(md5( microtime() . 'ref' ), 0, 10);
				$this->authorize_arb->addData('refId', $refId);
				
				// The subscription ID that we're canceling
				$this->authorize_arb->addData('subscriptionId', $subId);
				
				// Send request
				if($this->authorize_arb->send()) {
					$refId = $this->authorize_arb->getRefId();
					if(!empty($refId)) {
						$results = $this->db->get_where('advertiser_zips', array('advertiser_id' => $user_id)); 
						foreach ($results->result() as $row) {
							$this->db->delete('advertiser_ads', array('ref_id' => $user_id)); 
						}
						$this->db->delete('advertiser_zips', array('advertiser_id' => $user_id)); 
						
						$this->db->where('id', $user_id);
						$this->db->update('advertisers', array('sub_id'=>''));
						
						$this->db->where('sub_id', $subId);
						$this->db->update('payments', array('cancel_date'=>date('Y-m-d'), 'active'=>'n'));
						
						$this->db->where('landlord_id', $user_id);
						$this->db->where('type', 'advertiser');
						$this->db->update('landlord_page_settings', array('active'=>'n', 'unique_name'=>''));
						
						$this->db->delete('advertisers', array('id'=>$user_id));
						
						
						log_message('info', 'User Subscription Was Cancelled For Subscription Id '.$sub_id);
					} else {
						log_message('error', 'No Subscription Found In Authorize With A Subscription Id '.$sub_id);
					}
				} else {
					log_message('error', $this->authorize_arb->getError());
				}

			} else {
				log_message('error', 'No User Found With Subscription Id Of '.$sub_id);
			}
		}
		
		function cancel_payment_cron($sub_id)
		{
			$this->db->select('user_id, sub_id');
			$results = $this->db->get_where('payments', array('active'=>'y','type'=>'contractor', 'sub_id'=>$sub_id));
			if($results->num_rows()>0) {
				$row = $results->row();
				$subId = $row->sub_id;
				$user_id = $row->user_id;
				// Load the ARB lib
				$this->load->library('authorize_arb');
				// Start with a cancel object
				$this->authorize_arb->startData('cancel');
				// Locally-defined reference ID (can't be longer than 20 chars)
				$refId = substr(md5( microtime() . 'ref' ), 0, 10);
				$this->authorize_arb->addData('refId', $refId);
				
				// The subscription ID that we're canceling
				$this->authorize_arb->addData('subscriptionId', $subId);
				
				// Send request
				if($this->authorize_arb->send()) {
					$refId = $this->authorize_arb->getRefId();
					if(!empty($refId)) {
						$results = $this->db->get_where('contractor_zips', array('contractor_id' => $user_id)); 
						foreach ($results->result() as $row) {
							$this->db->delete('contractor_ads', array('ref_id' => $user_id)); 
						}
						$this->db->delete('contractor_zips', array('contractor_id' => $user_id)); 
						
						$this->db->where('id', $user_id);
						$this->db->update('contractors', array('sub_id'=>''));
						
						$this->db->where('sub_id', $subId);
						$this->db->update('payments', array('cancel_date'=>date('Y-m-d'), 'active'=>'n'));
						
						$this->db->where('landlord_id', $user_id);
						$this->db->where('type', 'contractor');
						$this->db->update('landlord_page_settings', array('active'=>'n', 'unique_name'=>''));
						
						$this->db->delete('contractors', array('id'=>$user_id));
						
						
						log_message('info', 'User Subscription Was Cancelled For Subscription Id '.$sub_id);
					} else {
						log_message('error', 'No Subscription Found In Authorize With A Subscription Id '.$sub_id);
					}
				} else {
					log_message('error', $this->authorize_arb->getError());
				}

			} else {
				log_message('error', 'No User Found With Subscription Id Of '.$sub_id);
			}
		}
		
		function add_zips_after_account($data) 
		{
			// Load authroize Library
			$this->load->library('authorize_arb');
			
			//Calculate Total Based On Session Cookies
			$amount = $this->calculate_total($data['payment_data']['frequency']);

			//Check to see if the user already has an active subscription
			$results = $this->db->get_where('payments', array('user_id'=>$this->session->userdata('user_id'), 'type'=>'contractor', 'active'=>'y'));
			if($results->num_rows()>0) {
				$data = array('error'=>'You Already Have An Active Subscription With An Ongoing Charge. Cancel Your Current Subscription Before Trying To Add Another.');
				return $data;
			} else { // No active subscription found
				$paymentFrequency = $data['payment_data']['frequency'];
				$names = explode(' ', $data['payment_data']['name_on_card']);
				$this->authorize_arb->startData('create');
				$this->authorize_arb->addData('refId', 'my_reference_id');
				$subscription_data = array(
					'name' => 'Network 4 Rentals Contractor Ads',
					'paymentSchedule' => array(
						'interval' => array(
							'length' => $data['payment_data']['frequency'],
							'unit' => 'months',
							),
						'startDate' => date('Y-m-d'),
						'totalOccurrences' => 9999, // Unlimited
						),
					'amount' => $amount,
					'payment' => array(
						'creditCard' => array(
							'cardNumber' => $data['payment_data']['credit_card'],
							'expirationDate' => $data['payment_data']['exp_year'].'-'.$data['payment_data']['exp_month'],
							'cardCode' => $data['payment_data']['ccv'],
							),
						),
					'billTo' => array(
						'firstName' => $names[0],
						'lastName' => end($names),
						'address' => $data['personnal_info']['baddress'],
						'city' => $data['personnal_info']['bcity'],
						'state' => $data['personnal_info']['bstate'],
						'zip' => $data['personnal_info']['bzip'],
						'country' => 'US'
						),
				);

				$this->authorize_arb->addData('subscription', $subscription_data);
				$this->authorize_arb->send();
			
				$error = $this->authorize_arb->getError();
				$subscription_id = $this->authorize_arb->getId();
				if(empty($error)) { //no errors processed payment
					//log payment data
					$zips = $this->session->userdata('zips');
					$service = $this->session->userdata('service');
					$options = '';
					for($i=0;$i<count($zips);$i++) {
						$options .= $zips[$i].'-'.$service[$i].'|';
					}
					$options = rtrim($options, '|');
					$expires_on = date('Y-m-d', strtotime($data['payment_data']['exp_month'].'/01/'.$data['payment_data']['exp_year']));
					$log = array(
						'id' 				=> $this->session->userdata('user_id'),
						'payment_amount'	=> $amount,
						'type'				=> 'contractor',
						'payment_id'		=> random_string('sha1', 55),
						'options'			=> $options,
						'frequency'	        => $data['payment_data']['frequency'],
						'sub_id'			=> $subscription_id,
						'last_4' 			=> substr($data['payment_data']['credit_card'], -4),
						'expires'			=> $expires_on
					);
					
					$this->log_payment($log); // Log the payment data
					
					//Add The Zips To The Database
					$info = array('id'=>$this->session->userdata('user_id'));
					$results = $this->add_contractor_zips($info);
					if($results) {
						$this->db->where('landlord_id', $this->session->userdata('user_id'));
						$this->db->where('type', 'contractor');
						$this->db->update('landlord_page_settings', array('active'=>'y'));
						$this->update_billing_info($data['personnal_info']);
						$this->session->set_flashdata('success', 'Your Payment Was Successful And You Can Now Create Your Posts Below');
						return true;
					} else {
						$this->session->set_flashdata('error', 'Your Payment Was Successful But There Was A Problem Creating Adding The Posts You Purchased To Your Account. Please Contact Us Right Away by going to https://network4rentals.com/help-support/');
						return false;
					}
				} else {
					$this->session->set_flashdata('error', $error);
					return false;	
				}
			}
		}
		
		function update_billing_info($data)
		{
	
			$this->db->where('id', $this->session->userdata('user_id'));
			$query = $this->db->update('contractors', $data);
			if($query) {
				return true;
			} else {
				return false;
			}
		}	
		
		function get_subscriber_email()
		{
			$this->db->select('email');
			$this->db->where('id', $this->session->userdata('user_id'));
			$query = $this->db->get('contractors');
			if($query->num_rows>0) {
				$row = $query->row();
				return $row->email;
			} else {
				return false;
			}
		}
		
		function update_credit_card($data) 
		{	
			$query = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
			if($query->num_rows()>0) {
				$personal_data = $query->row();
				
				$query = $this->db->get_where('payments', array('user_id'=>$this->session->userdata('user_id'), 'type'=>'contractor', 'active'=>'y'));
				if($query->num_rows()>0) {
					$payment_data = $query->row();
					// Load the ARB lib
					$this->load->library('authorize_arb');	
					$this->authorize_arb->startData('update');

					// Locally-defined reference ID (can't be longer than 20 chars)
					$refId = substr(md5( microtime() . 'ref' ), 0, 20);
					$this->authorize_arb->addData('refId', $refId);
					
					$this->authorize_arb->addData('subscriptionId', $payment_data->sub_id);

					$subscription_data = array(
						'name' => 'Network 4 Rentals Contractor Ads',
						'paymentSchedule' => array(
							'totalOccurrences' => 9999,
							'trialOccurrences' => 0,
							),
						'amount' => $payment_data->amount,
						'payment' => array(
							'creditCard' => array(
								'cardNumber' => $data['credit_card'],
								'expirationDate' => $data['exp_year'].'-'.$data['exp_month'],
								'cardCode' => $data['ccv'],
								),
							),
						'customer' => array(
							'id' => $personal_data->id,
							'email' => $personal_data->email
							),
						'billTo' => array(
							'firstName' => $data['f_name'],
							'lastName' => $data['l_name'],
							'address' => $personal_data->baddress,
							'city' => $personal_data->bcity,
							'state' => $personal_data->bstate,
							'zip' => $personal_data->bzip,
							'country' => 'US',
							),
						);
					print_r( $subscription_data);
					$this->authorize_arb->addData('subscription', $subscription_data);
					// Send request
					if( $this->authorize_arb->send()) {		
						echo $this->authorize_arb->getError();
						echo '<br>';
						echo  $this->authorize_arb->getId();
						echo '<br>';
						exit;
						$exp = $data['exp_year'].'-'.$data['exp_month'].'-01';
						$last_4 = substr($data['credit_card'], -4);
						$this->db->where('type', 'contractor');
						$this->db->where('user_id', $this->session->userdata('user_id'));
						$this->db->where('active', 'y');
						$this->db->update('payments', array('expires'=>$exp, 'last_4'=>$last_4));
						return $status = array('ref_id'=>$this->authorize_arb->getRefId());
					} else {
						return $status = array('error'=>$this->authorize_arb->getError());
					}
				} else {
					return false; //no payment info found
				}
			} else {
				return false; // no contractor found with this id
			}
		}
		
		
		
		
		
		
		
    }


