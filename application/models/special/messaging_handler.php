<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Messaging_handler extends CI_Model {
	
		// Call the Model constructor
		function messaging_handler()
		{		
			parent::__construct();
		}
		 
		function get_admin_id($group_id) 
		{	
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				return $row->main_admin_id;
			}
		}
		
		function get_sub_admin_id($group_id) 
		{	
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				return $row->sub_admins;
			}
		}
		
		//Counts number of messages for pagination
		function count_records($id, $to, $from)
		{
			$this->db->from('messaging');
				
			if(!empty($to)) {
				$date_to_msg = date('Y-m-d', strtotime($to));
				$this->db->where('timestamp >=', $date_to_msg);
			}
			if(!empty($from)) {
				$from = date('Y-m-d', strtotime($from));
				$this->db->where('timestamp <=', $from);
			}
			$this->db->where('tenant_id', $id['tenant_id']);
			$this->db->where('rental_id', $id['rental_id']);
			
			$temp_id = $this->session->userdata('temp_id');
			if(empty($temp_id)) {
				$this->db->where('landlord_id', $this->session->userdata('user_id'));
			} else {
				$this->db->where('group_id', $temp_id);
			}
			
			
			$total = $this->db->count_all_results();
		
			return $total;
		}
		
		// inserts the message data to the database
		function send_message($data)
		{
		
			$query = $this->db->get_where('renter_history', array('id'=>$data['rental_id'], 'tenant_id'=>$data['tenant_id']));
			
			if ($query->num_rows() > 0) {
				if($data['sent_by'] == '0') {
					$data['tenant_viewed'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))+3600);
					$data['landlord_viewed'] = '0000-00-00 00:00:00';
				} else {
					$data['landlord_viewed'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))+3600);
					$data['tenant_viewed'] = '0000-00-00 00:00:00';
				} 
		
				$this->db->insert('messaging', $data);
				$msg_id = $this->db->insert_id();
				if($msg_id>0) { //if message sent
					if($data['sent_by'] == 0) {  //check to see who sent it (landlord or tenant)
						// Landlords Activity
						$info = array(
							'action'=> 		'Message Received From Tenant', 
							'user_id'=> 	$data['landlord_id'], 
							'type'=> 		'landlords', 
							'action_id'=> 	$data['rental_id'],
							'group_id'=>   	$data['group_id']
						);
						$this->add_activity_feed($info);
						
						// Tenants Activity
						$info = array(
							'action'=> 'Message Sent To Landlord', 
							'user_id'=> $data['tenant_id'], 
							'type'=> 'renters', 
							'action_id'=> $data['rental_id']
						);
						$this->add_activity_feed($info);
						
						//Send Email To Landlord About New Message
						
						if($data['group_id']>0) {
							$sub_id = $this->get_sub_admin_id($data['group_id']);
							$this->db->select('email');
							$q1 = $this->db->get_where('landlords', array('id'=>$sub_id));
						} else {
							$this->db->select('email');
							$q1 = $this->db->get_where('landlords', array('id'=>$data['landlord_id']));
						}
						$r = $q1->row();  
						$subject = 'New Message From Tenant On N4R';
						$message = '<h3>New Message Received</h3><p>You have received a new message from one of your tenants on Network 4 Rentals.</p><p>To view the message click the link below.<br><a href="'.base_url().'renters/view-message-landlord/'.$data['hash_mail'].'">View Message</a></p>';
						$alt_message = 'You have received a new message from one of your tenants on Network 4 Rentals';
						$email = (string)$r->email;
						$returnHash = $data['hash_mail'];
						$this->sendEmail($email, $message, $subject, $alt_message = null);
					} else {
						// Landlords Activity
						$info = array(
							'action'=> 'Message Sent To Tenant', 
							'user_id'=> $data['landlord_id'],  
							'type'=> 'landlords', 
							'action_id'=> $data['rental_id']
						);
						$this->add_activity_feed($info);
						
						// Tenants Activity
			
						$info = array(
							'action'=> 'New Message From Landlord', 
							'user_id'=> $data['tenant_id'], 
							'type'=> 'renters', 
							'action_id'=> $data['rental_id']
						); 
						$this->add_activity_feed($info);
						
						//Get hash for tenant email viewing
						$this->db->select('hash_mail');
						$qHash = $this->db->get_where('messaging', array('id'=>$msg_id));
						$rHash = $qHash->row();
						
						//Send Email To Tenant About New Message
						$this->db->select('email');
						$q1 = $this->db->get_where('renters', array('id'=>$data['tenant_id']));
						$r = $q1->row(); 
						$subject = 'New Message From Landlord On N4R';
						$message = '<h3>New Message Received</h3><p>You have received a new message from one of your landlords on Network 4 Rentals.</p><p>To view the message click the link below.<br><a href="'.base_url().'renters/view-message-email/'.$rHash->hash_mail.'">View Message</a></p>';
						$alt_message = 'You have received a new message from one of your tenants on Network 4 Rentals';
						$email = $r->email;
						$returnHash = $rHash->hash_mail;
						$this->sendEmail($email, $message, $subject, $alt_message = null);
					}
					if($msg_id>0) {
						return $returnHash;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
			
			//TODO: return landlord email for email
		}
		
		// pulls the messages from the database and returns an array
		function read_message($data, $per_page, $page, $date_to_msg, $date_from_msg)
		{

			$set_viewed = $data['viewed_by'];
			unset($data['viewed_by']);
			$msgs = array();
			
			$query = $this->db->get_where('renter_history', array('id'=>$data['rental_id']));
			if ($query->num_rows() > 0) {
				$row = $query->row(); 
				$groupId = $row->group_id;
				if($row->group_id != 0) {
					$query = $this->db->get_where('admin_groups', array('id'=>$row->group_id));
					$admin_data = $query->row();
					$sub_admins = $admin_data->sub_admins;
					$data['landlord_id'] = $admin_data->main_admin_id;
				}	
			
				if(!empty($date_to_msg)) {
					$date_to_msg = date('Y-m-d', strtotime($date_to_msg));
					$this->db->where('timestamp >=', $date_to_msg);
				}
				if(!empty($date_from_msg)) {
					$date_from_msg = date('Y-m-d', strtotime($date_from_msg));
					$this->db->where('timestamp <=', $date_from_msg);
				}
				
				$this->db->limit($per_page, $page);
				$this->db->order_by('id', 'desc');
				$query = $this->db->get_where('messaging', $data);
				$tenant_viewed = false;
				$landlord_viewed = false;
				foreach ($query->result() as $row) {
				
					if($set_viewed == 'landlord') {
						if($row->landlord_viewed == '0000-00-00 00:00:00') {
							$this->db->where('id', $row->id);
							$now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
							$this->db->update('messaging', array('landlord_viewed'=>$now));
							$row->landlord_viewed = $now;
							$landlord_viewed = true;
						}
					} else {
						if($row->tenant_viewed == '0000-00-00 00:00:00') {
							$this->db->where('id', $row->id);
							$now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
							$this->db->update('messaging', array('tenant_viewed'=>$now));
							$row->tenant_viewed = $now;
							$tenant_viewed = true;
						}
					}
					
					if($row->sent_by == 0) {
						// Check For Group Id and get add sub admin email to the messages
						if($groupId != 0) {
							$q = $this->db->get_where('landlords', array('id'=>$sub_admins));
							if ($q->num_rows() > 0) {
								$r = $q->row();
								$row->email_sent_to_2 = $r->email;
								$subAdminEmail = $r->email;
							}
						}
						// Get Landlord Email
						$this->db->select('email');
						if(empty($groupId)) {
							$q = $this->db->get_where('landlords', array('id'=>$row->landlord_id));
						} else {
							$q = $this->db->get_where('landlords', array('id'=>$sub_admins));
						}
						if ($q->num_rows() > 0) {
							$r = $q->row();
							$landlordEmail = $r->email;
							$row->email_sent_to = $r->email;
						}
						// Get Tenants Name
						$this->db->select('name, email');
						$q = $this->db->get_where('renters', array('id'=>$row->tenant_id));
						if ($q->num_rows() > 0) {
							$r = $q->row();
							$tenantsName = $r->name;
							$tenantsEmail = $r->email;
							$row->tenant_name = $r->name;
						}
						
						
					} else {
						// Get Tenant Email
						$this->db->select('email');
						$q = $this->db->get_where('renters', array('id'=>$row->tenant_id));
						if ($q->num_rows() > 0) {
							$r = $q->row();
							$tenantsEmail = $r->email;
							$row->email_sent_to = $r->email;
						}
						// Get Landlord Name
						$this->db->select('name, email');
						$q = $this->db->get_where('landlords', array('id'=>$row->actual_landlord_sent));
						if ($q->num_rows() > 0) {
							$r = $q->row();
							$landlordsName = $r->name;
							$landlordEmail = $r->email;
							$row->landlord_name = $r->name;
						}
					}
					
					$msgs[] = $row;
				}

				if($landlord_viewed) { 
					$subject = 'Landlord Viewed You Message On N4R';
					$message = '<h3>Message Read</h3><p>Your landlord has viewed the message you sent him through Network 4 Rentals. To view this message, login to you account and view your messages or click the link below if you are already logged in.</p><p><a href="'.base_url().'renters/view-messages/'.$data['rental_id'].'">View Message</a></p>';
					$this->sendEmail($tenantsEmail, $message, $subject, $alt_message = null);
				}
				
				if($tenant_viewed) {
					if(!empty($subAdminEmail)) {
						$landlordEmail = $subAdminEmail;
					}
					$subject = 'Tenant Viewed You Message On N4R';
					$message = '<h3>Message Read</h3><p>One of your tenants has viewed the message you sent him through Network 4 Rentals. To view this message and or reply to it, login to you account and check your activity page.</p>
					<p><a href="'.base_url().'landlords/message-tenant/'.$data['rental_id'].'">View Message</a></p>';
					$alt_message = 'One of your tenants has sent you a message through Network 4 Rentals.';
					$this->sendEmail($landlordEmail, $message, $subject, $alt_message = null);
				}
				return $msgs;
			} else {
				return false;
			}
			
		}
		
		// add activity to activity feed
		function add_activity_feed($data) 
		{	
			//Required: action (what was the action) - user_id (who it belongs to) - type (renters / landlords) - action_id (id to link the activity to the action)
			$data['created'] = date('Y-m-d H:i:s');
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$this->db->insert('activity', $data); 
		}
		
		function sendEmail($email, $message, $subject, $alt_message = null)
		{
			
			$this->load->library('email');
			
			$config['mailtype'] = 'html';
			
			$this->email->initialize($config);
			
			$this->email->from('no-reply@network4rentals.com', 'Network4Rentals');
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
				<table cellpadding="10" bgcolor="#ffffff">
					<tr>
						<td valign="top" align="left">
							'.$message.'<br><br><h3>Unsure Of What To Do Next?</h3><p>If at any point you find yourself lost or unsure of what to do next there are plenty of resources available at your disposal our on <a href="https://network4rentals.com/fas/">faqs</a> page or our <a href="https://network4rentals.com/blog/">blog</a> page.</p>
						</td>
					</tr>
				</table>
			</center>
			</body>
			</html>';
			return $email_body;
		}		

		function get_hashed_message($hash, $tenant=null) 
		{
			$query = $this->db->get_where('messaging', array('hash_mail'=>$hash));
			if ($query->num_rows() > 0) {
				$row = $query->row_array(); 
				
				$this->db->select('name, email');
				if($row['actual_landlord_sent']>0) {
					$q = $this->db->get_where('landlords', array('id'=>$row['actual_landlord_sent']));
				} else {
					$q = $this->db->get_where('landlords', array('id'=>$row['landlord_id']));
				}
				$r = $q->row_array(); 
				$row['landlords_email'] = $r['email'];
				$row['landlords_name'] = $r['name'];
				
				$this->db->select('name, email');
				$q = $this->db->get_where('renters', array('id'=>$row['tenant_id']));
				$r = $q->row_array(); 
				$row['tenants_email'] = $r['email'];
				$row['tenants_name'] = $r['name'];
				
				if($tenant = 'y' && $row['tenant_viewed'] == '0000-00-00 00:00:00') {
					$this->db->limit(1);
					$this->db->where('id', $row['id']);
					$this->db->update('messaging', array('tenant_viewed'=>date('Y-m-d H:i:s')));
					
					//Notify landlord 
					$d = array('action'=>'A Tenant Viewed Your Messages', 'user_id'=>$row['landlord_id'], 'type'=>'landlords', 'action_id'=>$row['rental_id']);
					$this->add_activity_feed($d);
					
					$link = base_url().'landlords/message-tenant/'.$row['rental_id'];
					$subject = 'Tenant View Message';
					$message = 'One of your tenants has view the message you sent them. This action has been added to your activity page and documented in the system. To view more details about this action make sure you are logged in and click the link below. You can also view the details of this message by clicking on the view message button on your activity page.<br><br>'.$link;
					$this->sendEmail($row['landlords_email'], $message, $subject);
				}
				
				return $row;
			} else {
				return false;
			}
		}
	
		function update_timestamp_landlord_viewed($message_id)
		{
			$now = date('Y-m-d H:i:s');
			$this->db->where('id', $message_id);
			$this->db->update('messaging', array('landlord_viewed'=>$now));
		}
		
		function print_messages($rental_id) 
		{
			$temp_id = $this->session->userdata('temp_id');
			if(empty($temp_id)) {
				$ids = $this->session->userdata('user_id');
			
				if($this->session->userdata('side_logged_in') == '898465406540564') {
					$query = $this->db->get_where('renter_history', array('id'=>$rental_id, 'tenant_id'=>$ids));
				} else {
					$query = $this->db->get_where('renter_history', array('id'=>$rental_id, 'link_id'=>$ids));
				}
			} else {
				$ids = $this->session->userdata('temp_id');
				$query = $this->db->get_where('renter_history', array('id'=>$rental_id, 'group_id'=>$ids));
			}
			
			
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$data = array(
					'rental_id' 	=> $rental_id, 
					'tenant_id'		=> $row->tenant_id,
					'landlord_id' 	=> $row->link_id
				);
				
				if($row->group_id != 0) {
					$query = $this->db->get_where('admin_groups', array('id'=>$row->group_id));
					$admin_data = $query->row();
					$sub_admins = $admin_data->sub_admins;
					$data['landlord_id'] = $admin_data->main_admin_id;
				}
							
				$this->db->order_by('id', 'asc');
				$query = $this->db->get_where('messaging', $data);
				foreach ($query->result_array() as $row) {
					$this->db->select('email, name');
					$q = $this->db->get_where('renters', array('id'=>$row['tenant_id']));
					if($q->num_rows()>0) {
						$r = $q->row_array(); 
						$row['tenant_email'] = $r['email'];
						$row['tenant_name'] = $r['name'];
					}
					
					
					$this->db->select('email, bName, name');
					$q = $this->db->get_where('landlords', array('id'=>$row['landlord_id']));
					if($q->num_rows()>0) {
						$r = $q->row_array(); 
						$row['landlord_email'] = $r['email'];
						$row['landlord_name'] = $r['name'];
					}
					if($temp_id>0) {
						$main_landlord_id = $this->get_admin_id($temp_id);
						$this->db->select('email');
						$q = $this->db->get_where('landlords', array('id'=>$main_landlord_id));
						if($q->num_rows()>0) {
							$r = $q->row_array(); 
							$row['landlord_email'] = $row['landlord_email'].'<br>'.$r['email'];
						}
					}
					
					
					
					$msg[] = $row;
				}
				return $msg;
			} else {
				return false;
			}
		}
		
		function user_data($rental_id)
		{
			$query = $this->db->get_where('renter_history', array('id'=>$rental_id));
			if($query->num_rows()>0) {
				$row = $query->row_array();
				$group_id = $row['group_id'];
				$landlord_id = $row['link_id'];
				$tenant_id = $row['tenant_id'];
				
				if($group_id>0) {
					$landlord_id = $this->get_sub_admin_id($group_id);
				}
				
				$query = $this->db->get_where('landlords', array('id'=>$landlord_id));
				if($query->num_rows()>0) {
					$info[] = $query->row_array();
				} else {
					return false;
				}
				
				$query = $this->db->get_where('renters', array('id'=>$tenant_id));
				if($query->num_rows()>0) {
					$info[] = $query->row_array();
				} else {
					return false;
				}
				
				return $info;
			} else {
				return false;
			}
		}
		
		
	}	




