<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Create_user_model extends CI_Model {
		function Create_model()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function create_user_account($username, $password, $fullname, $email, $phone, $hear)
		{			
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$date = date('Y-m-d h:i:s'); 
			$data['sign_up'] = $date; 
			$data['loginHash'] = md5($username.$date);
			$data['terms'] = 'y';
			$data['user'] = $username;
			$data['pwd'] = $password;
			$data['email'] = $email;
			$data['phone'] = $phone;
			$data['name'] = $fullname;
			$data['hear'] = $hear;

			$result = $this->db->insert('renters', $data);
			
			if($this->db->insert_id())
			{
				$this->session->set_userdata('email_hash', $data['loginHash']);
				return $data['loginHash'];
			}
			else 
			{
				return FALSE;
			}
		}
		
		public function check_if_registered($email)
		{
			$result = $this->db->get_where('renters', array('email'=>$email, 'confirmed'=>'n'));
			if($result->num_rows()>0) {
				$row = $result->row();
				$this->session->set_userdata('confirm-code', $row->text_msg_code);	
				$this->session->set_userdata('confirm-email', $row->email);
				
				$this->send_confirmation_email_simple();
				
				if(!empty($row->cell_phone)) {
					$this->session->set_userdata('confirm-cell', $row->cell_phone);
					$this->send_confirmation_text_simple();
				}
				return true;
			}
			return false;
		}
		
		function verify_account($hash) {
			if(!empty($hash)) {
				$this->db->select('id');
				$results = $this->db->get_where('renters', array('loginHash'=>$hash, 'confirmed'=>'n'));
				if($results->num_rows() > 0) {
					$row = $results->row();
					$tenant_id = $row->id;
					$this->db->where('loginHash', $hash);
					$this->db->update('renters', array('confirmed'=>'y', 'loginHash'=>''));
					if($this->db->affected_rows()>0) {
						$this->db->select('link_id, group_id, id');
						$this->db->limit('1');
						$results = $this->db->get_where('renter_history', array('tenant_id'=>$row->id, 'current_residence'=>'y'));
						if($results->num_rows()>0) {
							$row = $results->row();
							$rental_id = $row->id;
							$group_id = $row->group_id;
							if($row->group_id >0) {
								$results = $this->db->get_where('admin_groups', array('id'=>$row->group_id));
								if($results->num_rows()>0) {
									$ids = $results->row();
									$landlord_id = $ids->sub_admins;
								} else {
									return false;
								}
							}  else {
								$landlord_id = $row->link_id;
							}
							$this->db->select('email, name');
							$results = $this->db->get_where('landlords', array('id'=>$landlord_id));
							if($results->num_rows()>0) {
								$row = $results->row();
								$data = array(
									'name'=>$row->name,
									'tenant_id'=>$tenant_id,
									'landlord_email'=>$row->email,
									'group_id'=>$group_id,
									'rental_id'=>$rental_id,
									'landlord_id'=>$landlord_id
								);
								return $data;
							} else {
								return false;
							}
						}
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		function verify_account_no_landlord($hash) {
			if(!empty($hash)) {
				$this->db->select('id');
				$results = $this->db->get_where('renters', array('loginHash'=>$hash, 'confirmed'=>'n'));
				if($results->num_rows() > 0) {
					$row = $results->row();
					$tenant_id = $row->id;
					$this->db->where('loginHash', $hash);
					$this->db->update('renters', array('confirmed'=>'y', 'loginHash'=>''));
					if($this->db->affected_rows()>0) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		function search_business($search_for) 
		{
			$query_str = "SELECT bName FROM landlords WHERE bName LIKE '%".$search_for."%' AND confirmed = 'Y' LIMIT 7";
			$result = $this->db->query($query_str);
			if($result->num_rows() > 0) 
			{
			   foreach ($result->result() as $row)
			   {
				  $values = '<li>'.$row->b_name.'</li>';
			   }
			}
			return $values;
		}
		
		/* New Account Creation For The Steps Process */
		function create_account($data) 
		{
			$results = $this->db->insert('renters', $data);
			if($this->db->affected_rows() > 0) {
				return $this->db->insert_id();;
			} else {
				return false;
			}
		}
		
		/* New Account Creating to add the users rental details to the db */
		function add_rental_details($data) 
		{
			$results = $this->db->insert('renter_history', $data);
			if($this->db->affected_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		function add_landlord_hold($data) 
		{
			$this->db->select('id, email');
			$results = $this->db->get_where('landlords', array('email'=>$data['landlord_email']));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row->id;
			} else {
				$query = $this->db->insert('landlords', $data);
				if($this->db->affected_rows() > 0) {
					return $this->db->insert_id();
				} else {
					return false;
				}
			}
		}
		
		/* Ajax function that searches for registered landlord on renter account creation */
		function search_for_registered_landlord_email($email) 
		{
			$this->db->select('id, name, bName, email, city, state, zip, phone');
			$results = $this->db->get_where('landlords', array('email'=>$email));
			if($results->num_rows()>0) {
				return $results->row();
			}
		}
		
		/* Ajax function that searches for registered landlord on renter account creation */
		function search_for_registered_landlord_phone($phone) 
		{
			$this->db->select('id, name, bName, email, city, state, zip, phone');
			$results = $this->db->get_where('landlords', array('phone'=>$phone));
			if($results->num_rows()>0) {
				return $results->row();
			}
		}
		
		/* Ajax function that adds landlords properties when on renter account creation */
		function getLandlordProperties($id, $group_id)
		{
			$this->db->select('address, city, stateAbv, zipCode, id');
			if(!empty($group_id)) {
				$results = $this->db->get_where('listings', array('contact_id'=>$group_id));
				if($results->num_rows()>0) {
					return $results->result();
				}
			} else {
				$results = $this->db->get_where('listings', array('owner'=>$id));
				if($results->num_rows()>0) {
					return $results->result();
				}
			}
		}
		
		// check username from the account creation
		function check_username($user) 
		{	
			$this->db->select('id');
			$results = $this->db->get_where('renters', array('user'=>$user));
			if($results->num_rows()>0) {
				return true;
			} else {
				return false;
			}
		}		
		
		// check username from the account creation
		function check_unique_email($email) 
		{	
			$this->db->select('id');
			$results = $this->db->get_where('renters', array('email'=>$email));
			if($results->num_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function check_text_code($code)
		{
			$this->db->select('id');
			$this->db->limit(1);
			$results = $this->db->get_where('renters', array('text_msg_code'=>$code));
			if($results->num_rows()>0) {
				$row = $results->row();
				
				$data = array('loginHash'=>'', 'text_msg_code'=>'', 'confirmed'=>'y');
				
				$this->db->limit(1);
				$this->db->where('id', $row->id);
				$results = $this->db->update('renters', $data);
				if($this->db->affected_rows()>0) {
					return $row->id;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		//Adds text message feature when the user creates the account
		function update_text_message_option($data) {
			$this->db->limit(1);
			$this->db->where('loginHash', $this->session->userdata('hash'));
			$results = $this->db->update('renters', $data);
		
			return true;
		}
		
		function capture_landlord_details($unique_link) 
		{
			if(preg_match('/^([a-z0-9]+-)*[a-z0-9]+$/i',$unique_link)) {
				$query = $this->db->get_where('landlord_page_settings', array('unique_name'=>$unique_link));
				return $query->row();
			}
			return false;
		}
		
		public function getMangerAccounts($landlord_id)
		{
			$query = $this->db->get_where('admin_groups', array('main_admin_id' => $landlord_id));
			return $query->result();
		}
		
		function sms_verification_data($tenant_id) 
		{
			$this->db->select('link_id, group_id, id');
			$this->db->limit(1);
			$results = $this->db->get_where('renter_history', array('tenant_id'=>$tenant_id));
			if($results->num_rows()>0) {
				$row = $results->row();				
				
				if($row->group_id>0) {
					$this->db->select('sub_admins');
					$r = $this->db->get_where('admin_groups', array('id'=>$row->group_id));
					if($r->num_rows()>0) {
						$d = $r->row();
						$row->link_id = $d->sub_admins;
						
						$this->db->select('email, name');
						$r = $this->db->get_where('landlords', array('id'=>$row->link_id));
						$dat = $r->row();
						$row->email = $dat->email;
						$row->name = $dat->name;
						
					
					} else {
						$this->db->select('email, name');
						$r = $this->db->get_where('landlords', array('id'=>$row->link_id));
						$dat = $r->row();
						$row->email = $dat->email;
						$row->name = $dat->name;
					
					}
				} else {
					$this->db->select('email, name');
					$r = $this->db->get_where('landlords', array('id'=>$row->link_id));
					$dat = $r->row();
					$row->email = $dat->email;
					$row->name = $dat->name;
					$row->group_id = NULL;
					
				}
				
				return $row;
			} else {
				return false;
			}
		}
	
		function add_no_landlord_user($data) 
		{
	
			$code = '';
			if($data['sms_msgs'] == 'y') {
				$code = substr(md5('492fajdfa49'.$data['username']), 0, rand(5, 8));
				$sms['phone_to'] = $data['cell_phone'];
				$sms['message'] = 'Network4Rentals verification code: '.$code.'. http://network4rentals.com/network/renters/account-created';
				$sms['page'] = 'create_user_account';
				
				$this->session->set_userdata('cell', $data['cell_phone']);
				$this->session->set_userdata('sms', $data['sms_msgs']);
				$this->session->set_userdata('sms_msg', $sms['message']);
				
				$this->send_sms($sms);
			}
			$data['text_msg_code'] = $code;

			$this->db->insert('renters', $data);
			
			if($this->db->insert_id()>0) {
			
				$this->session->set_userdata('hash', $data['loginHash']);
				$this->session->set_flashdata('success', 'You are almost done, now confirm your account and your ready to use N4R');
				$message = '<h3>'.$data['fullname'].'</h3><p>Your account has been created, click <a href="https://network4rentals.com/network/renters/account_verified_no_landlord/'.$data['loginHash'] .'">here</a> to verify your email address.</p>';
				$subject = "N4R | Account Created";
				
				$this->session->set_userdata('user_email', $user_account['email']);
				$this->session->set_userdata('message', $message);
				$this->session->set_userdata('subject', $subject);
				$alt_message = '';
				$this->load->model('special/send_email');
				$this->send_email->sendEmail($data['email'], $message, $subject, $alt_message);
				
				return array('success'=>'Your account has been created');
			} else {
				return array('error'=>'Something went wrong, try again');
			}
		}
		
		public function send_sms($data)
		{			
			$this->load->library('plivo');
			$sms_data = array(
				'src' => '13525593099',
				'dst' => '1'.$data['phone_to'],
				'text' => $data['message'],
				'type' => 'sms',
				'method' => 'POST',
			);

			$response_array = $this->plivo->send_sms($sms_data);
			if ($response_array[0] == '200' || $response_array[0] == '202') {
				//$data["response"] = json_decode($response_array[1], TRUE);
				return true;
			} else {
				return false;
			}
		}
		
		function sendEmail($email, $message, $subject, $alt_message = null)
		{
			$this->load->library('email');
			$config['mailtype'] = 'html';	
			$this->email->initialize($config);
			$this->email->from('no-reply@network4rentals.com', 'Network4Rentals');
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
								<a href="https://network4rentals.com"><img src="https://network4rentals.com/wp-content/uploads/2013/07/network_logo.png" width="300" alt="Network 4 Rentals"></a>
							</center>
						</td>
						<td width="400px" align="center">
							<FONT COLOR="#fff"><p><b>Improving Landlord &amp; Tenant Relations Nationwide</b></p></FONT>
						</td>
					</tr>
				</table>
				<table cellpadding="10" bgcolor="#ffffff">
					<tr>
						<td valign="top" align="left">
							'.$message.'<br><br><h3>Unsure Of What To Do Next?</h3><p>If at any point you find yourself lost or unsure of what to do next please check out the many resources available on the <a href="https://network4rentals.com/fas/">faqs</a> or <a href="https://network4rentals.com/blog/">blog</a> page or contact us <a href="http://network4rentals.com/help-support/">here</a>.</p>
						</td>
					</tr>
				</table>
			</center>
			</body>
			</html>';
			return $email_body;
		}			

		
		public function create_simple_user($data)
		{
			if(!empty($data['cell_phone'])) {
				$data['cell_phone'] = preg_replace("/[^0-9]/","",$data['cell_phone']);
				$data['sms_msgs'] = 'y';
			}
			
			$this->db->insert('renters', $data);
			if($this->db->insert_id()>0) {
			
				$this->session->set_userdata('confirm-code', $data['text_msg_code']);	
				$this->session->set_userdata('confirm-email', $data['email']);
				
				$this->send_confirmation_email_simple();
				
				if(!empty($data['cell_phone'])) {
					$this->session->set_userdata('confirm-cell', $data['cell_phone']);
					$this->send_confirmation_text_simple();
				}
				
				return true;
			}
			
			return false;
		}
		
		public function send_confirmation_email_simple()
		{
			$message = '<p>Your account has been created at Network4Rentals. Confirm you account by typing this <b>'.$this->session->userdata('confirm-code').'</b> into the code box on your previous screen. If you have any questions or concerns contact us at <a href="https://network4rentals.com/help-support/">https://network4rentals.com/help-support/</a></p>';
			$subject = "N4R | Account Created";
			$this->load->model('special/send_email');
			$this->send_email->sendEmail($this->session->userdata('confirm-email'), $message, $subject, $alt='');
		}
		
		public function send_confirmation_text_simple()
		{	
			$msg = 'Network4Rentals confirmation code: '.$this->session->userdata('confirm-code');
			$data = array('phone_to' => $this->session->userdata('confirm-cell'), 'message'=>$msg);
			$this->send_sms($data);
		}
		
		public function confirmation_code_check($code)
		{ 	
			$results = $this->db->get_where('renters', array('text_msg_code'=>$code));
			if($results->num_rows()>0) {
				$row = $results->row();
				
				$array_items = array('confirm-code' => '', 'confirm-email' => '', 'confirm-cell' => '');
				$this->session->unset_userdata($array_items);
				
				$this->session->set_userdata('side_logged_in', '898465406540564');
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('logged_in', TRUE);
				$this->session->set_userdata('username', $row->email);
				$this->session->set_flashdata('guide_user', true);
				
				
				
				$this->db->where('id', $row->id);
				$this->db->update('renters', array('text_msg_code'=>'', 'confirmed'=>'y'));
				
				return true;
			}
			return false;
			
		}
		
	}
?>