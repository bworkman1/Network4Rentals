<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
    class Create_account_handler extends CI_Model {
		
        public function __construct() 
		{
            parent::__construct();
        }
		
		public function account_builder($data)
		{
			/*
				This function receives all the form data and sends it off to the places it needs to go
			*/
			$result = $this->check_zips_selection();
			
			if($result) {
				$first_result = $this->submit_initial_payment_details($data);
				if(isset($first_result['error'])) {
					return $first_result;
				} else {
					$result = $this->submit_arb_payment($data);
					if(isset($result['error'])) { //if error sending payment return the error to the controller
						return $result;
					} else {
						//$result['success'] holds a value of the sub id from authorize
						$data['sub_id'] = $result['success'];
						$result = $this->create_user_account($data);
					
						if(isset($result['error'])) { //if error sending payment return the error to the controller
							return $result;
						} else {	
							//$result['success'] holds the contractors insert id/user id
							$this->session->set_userdata('user_id', $result['success']);
							$result = $this->add_zips_to_account($result['success'], $data['sub_id']);
							if($result) {
								//Log Payment
								$d = array(
									'user_id'=>$this->session->userdata('user_id'),
									'amount'=>'299.99',
									'type'=>'contractor',
									'sub_id'=>$data['sub_id'],
									'payment_date' => date('Y-m-d'),
									'payment_frequency' => '12',
									'active'  => 'y',
									'expires' => $data['exp_year'].'-'.$data['exp_month'].'-'.date('t', strtotime($data['exp_month'].'/01/'.$data['exp_year'])),
									'last_4' => substr($data['credit_card'], -4)
								);
								$this->logPayment($d);
								
								$this->session->set_flashdata('success', 'Your account has been created, now set-up your profile so that landlords can find you below.');
							} else {
								$this->session->set_userdata('success', 'Your account has been created but you will need to add your zip codes again. Next set-up your profile so that landlords can find you below');
								
							}
							$this->email_contractor($data); 
							$this->session->set_userdata('side_logged_in', '203020320389822');
							$this->session->set_userdata('logged_in', true);
							return array('success'=>'Your account has been created');
						}
					}
				}
			} else {
				return array('error'=>'There was an error with your selected zip codes, try removing selected zips and adding them again');
			}
		}
		
		private function submit_initial_payment_details($data) 
		{
			$this->load->library('auth/authorize_net');
			$auth_net = array(
				'x_card_num'			=> $data['credit_card'],
				'x_exp_date'			=> $data['exp_month'].'/'.$data['exp_year'],
				'x_card_code'			=> $data['ccv'],
				'x_description'			=> 'Network4Rentals Contractor Subscription',
				'x_amount'				=> '299.99',
				'x_first_name'			=> $data['first_name'],
				'x_last_name'			=> $data['last_name'],
				'x_address'				=> $data['baddress'],
				'x_city'				=> $data['bcity'],
				'x_state'				=> $data['bstate'],
				'x_zip'					=> $data['bzip'],
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
		
		public function submit_arb_payment($data)
		{			
			
			/*
				This function will process all the payment data and return and array with a key of error or success upon completion
			*/
			$payment_amount = '299.99';
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
						'startDate' => date("Y-m-d"),
						'totalOccurrences' => 9999, // Unlimited
						'trialOccurrences'=>1,
						),
					'amount' => $payment_amount,
					'trialAmount' => 0.00,
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
						return array('success'=>$subscription_id);
					} else {
						return array('error'=>'Failed to create subscription');
					}
				} else {
					return array('error'=>$error);
				}
			}
		}
		
		public function create_user_account($data)
		{
			/*
				This function creates the users account if the payment was successful
			*/
			$user_data = array('bName'=> $data['bName'], 'user'=>$data['user'], 'password'=> md5($data['password']), 'email'=>$data['email'], 'email_hash'=>$data['hash'], 'address'=>$data['address'], 'city'=>$data['city'], 'state'=>$data['state'],		'zip'=> $data['zip'], 'f_name'=>$data['first_name'], 'l_name'=>$data['last_name'], 'cell'=>$data['cell'], 'fax'=>$data['fax'], 'phone'=>$data['phone'], 'baddress'=>$data['baddress'], 'bcity'=>$data['bcity'], 'bstate'=>$data['bstate'], 'bzip'=>$data['bzip'],	'active'=>$data['terms'], 'sub_id'=>$data['sub_id'], 'ip'=>$_SERVER['REMOTE_ADDR'], 'created'=>date('Y-m-d h:i:s'));
						
			if(isset($data['promo'])) {
				$user_data['promo'] = $data['promo'];
			}
			
			$this->db->insert('contractors', $user_data); 
			$contractor_id = $this->db->insert_id();
			if($contractor_id>0) {
				return array('success'=>$contractor_id);
			} else {
				return array('error'=>'Your payment processed but account creation failed, please contact support with your email and user name you would like to use so we can create your account manually');
			}
		}
		
		function check_zips_selection()
		{
			/*
				Checks the session cookies the user selected they wanted to advertise in and makes sure its an number and makes sure the zip is 5 digits long and the service type is not more then 2 digits long
			*/
			$zips = $this->session->userdata('zips');
			$service = $this->session->userdata('service');
			if(count($zips)>0 AND count($service)>0) {
				foreach($zips as $val) {
					$c = strlen((int)$val);
					if($c != 5) {
						echo $c;
						return false;
					}
				}
				
				foreach($service as $val) {
					$c = strlen((int)$val);
					if($c>2) {
						return false;
					}
				}
				return true;
			} else {
				return false;
			}
			
		}
		
		function email_contractor($data)
		{
			$subject = 'Your Contractor Account Has Been Created';
								
			$zips = $this->session->userdata('zips');
			$service = $this->session->userdata('service');	
			$price = $this->session->userdata('price');	
			$city = $this->session->userdata('city');	
			$state = $this->session->userdata('state');
		
			$today = date('Y-m-d');
			$next_due_date = strtotime($today.' + 12 Months');
			$message = '<h3>'.$data['bName'].'</h3>
						<p>Thank you for becoming a sponsored Network4Rentals contractor. This is to confirm that your credit card payment for your account has been authorized and processed. The details of the transaction are below.</p>
						<h4>Business Info</h4>
						<table cellpadding="4" width="60%" align="left">
							<tr>
								<td><b>Business Name:</b></td>
								<td>'.$data['bName'].'</td>
							</tr>
							<tr>
								<td><b>Contact Name:</b></td>
								<td>'.$data['first_name'].' '.$data['last_name'].'</td>
							</tr>
							<tr>
								<td valign="top"><b>Address:</b></td>
								<td>'.$data['address'].' '.$data['city'].', '.$data['state'].'. '.$data['zip'].'</td>
							</tr>
							<tr>
								<td valign="top"><b>Billing Address:</b></td>
								<td>'.$data['baddress'].' '.$data['bcity'].', '.$data['bstate'].'. '.$data['bzip'].'</td>
							</tr>
							<tr>
								<td><b>Phone:</b></td>
								<td>('.substr($data['phone'], 0, 3).') '.substr($data['phone'], 3, 3).'-'.substr($data['phone'],6).'</td>
							</tr>
							<tr>
								<td><b>Email:</b></td>
								<td>'.$data['email'].'</td>
							</tr>
							<tr>
								<td><b>Date:</b></td>
								<td>'.date('m-d-Y').'</td>
							</tr>
						</table>	
						<table cellpadding="4" width="39%" align="right">
							<tr>
								<td align="right"><b>Selected Zips:</b></td>
								<td>'.count($zips).'</td>
							</tr>
							<tr>
								<td align="right"><b>Next Billing Date:</b></td>
								<td>'.date('m-d-Y', $next_due_date).'</td>
							</tr>
							<tr>
								<td align="right"><b>Subscription Term:</b></td>
								<td>1 Year</td>
							</tr>
							<tr>
								<td align="right"><b>Subscription Total Cost:</b></td>
								<td>$299.99</td>
							</tr>
						</table>		
						<table width="100%">
						<tr>
							<td><h3>Build Your Post</h3>
						<p>You may have already followed the websites request and created your public web page and created your post so that start displaying on landlords service request. If not please do so as soon as possible so that you are taking full advantage of your sponsored space. To edit your public web page go to <a href="https://network4rentals.com/network/contractors/public-page-settings">https://network4rentals.com/network/contractors/public-page-settings</a> once you add all the details there go to <a href="https://network4rentals.com/network/contractors/my-zips">https://network4rentals.com/network/contractors/my-zips</a> to edit your posts so that they are showing your information to the landlords.</p>
							</td>
						</tr>
						</table>
						<table cellpadding="4" width="100%">
							<tr>
								<th align="left">Zips</th>
								<th align="left">Service Type</th>
								<th align="left">City</th>
								<th align="left">State</th>
							</tr>';
							
			$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
			for($i=0;$i<count($zips);$i++) {
				if ($i%2===0){ 
					$message .= '<tr bgcolor="#DFDFDF">';
				} else {
					$message .= '<tr>';
				}
				$message .= '
					<td>'.$zips[$i].'</td>
					<td>'.$services_array[$service[$i]].'</td>
					<td>'.$city[$i].'</td>
					<td>'.$state[$i].'</td>
				</tr>';
			}
			$message .= '</table>';
			$message .= '<br><br><small>"Network4Rentals.com operates on a 1 year subscription basis. All accounts not cancelled in writing at least 30 days prior to the subscription renewal date will have their subscription automatically renewed and be billed as per their previously selected billing terms."</small>';
			
			
			$this->sendEmail($data['email'], $message, $subject);
			
			$this->session->unset_userdata('city');
			$this->session->unset_userdata('state');
			$this->session->unset_userdata('zips');
			$this->session->unset_userdata('service');
			
			$this->session->set_userdata('fresh', true);
							
		}
		
		function sendEmail($email, $message, $subject)
		{
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from('no-reply@network4rentals.com', 'No Reply');
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
		
		function add_zips_to_account($contractor_id, $sub_id)
		{
			$zips = $this->session->userdata('zips');
			$service = $this->session->userdata('service');
		
			for($i=0;$i<count($zips);$i++) {
				$this->db->insert('contractor_zip_codes', array('zip'=>$zips[$i], 'contractor_id'=>$contractor_id, 'service_type'=>$service[$i], 'sub_id'=>$sub_id));
				if($this->db->insert_id()==0) {
					return false;
				}
			}
			return true;
		}

		private function logPayment($data)
		{		
			$this->db->insert('payments',  $data);
		}
		
	}//ENDS CLASS