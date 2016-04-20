<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class user_Invite extends CI_Model {
		
		function __construct()
		{
			parent::__construct();
		}
		
		function invite_tenant($data) 
		{

			if($data['sendBy'] == 'text') {
				return $this->send_tenant_text($data['cell'], $data['behalf']);	
			} else {
				return $this->send_tenant_email($data['email'], $data['behalf']);
			}
			
		}
		
		private function send_tenant_text($cell, $group_id)
		{
			$data = $this->check_if_phone_is_cell($cell);
			if($data->Response->carrier_type== "mobile") {
				if(!empty($group_id)) { // NEEDS WORK HERE
					
					$data = $this->get_sub_admin_bName($group_id);
					$name = $data->bName;
					$uniqueLink = 'https://network4rentals.com/network/renters/link-landlord-rentals/'.$data->sub_b_name;
				} else {
					$data = $this->landlord_info($this->session->userdata('user_id'));
			
					$uniqueLink = $this->get_unique_link($this->session->userdata('user_id'));
					if(empty($uniqueLink)) {
						return array('error' => 'You must set your public page so they can connect to  your N4R Profile.<br><a href="'.base_url('landlords/public-page-settings').'">Setup Public Page</a>');
						exit;
					}
					if(empty($data->bName)) {
						$name = $data->bName;
					} else {
						$name = $data->name;
					}
				}
				if(empty($data)) {
					return $data = array('error', 'Landlord details could not be found');
					exit;
				}
				$uniqueLink = 'https://network4rentals.com/network/renters/link-landlord-rentals/'.$uniqueLink;
				$msg = $name.' has invited you to join Network4Rentals. '.$uniqueLink;
				$data = array('cell'=>$cell, 'msg'=>$msg);
				
				if($this->send_sms($data)) {
					$this->addActivity($cell);
					return array('success'=>'Invite sent to '.$cell);
				} else {
					return array('error'=>'Something went wrong, text message not sent.');
				}
				
			} else {
				return array('error'=>'Invalid cell phone number');
			}
		}
		
		private function send_sms($data)
		{
			$this->load->library('plivo');
			$sms_data = array(
				'src' => '13525593099',
				'dst' => '1'.$data['cell'],
				'text' => $data['msg'],
				'type' => 'sms', 
				'url' => 'https://network4rentals.com/networklandlords/none/',
				'method' => 'POST',
			);

			$response_array = $this->plivo->send_sms($sms_data);
			if ($response_array[0] == '200' || $response_array[0] == '202') {
				$data["response"] = json_decode($response_array[1], TRUE);
				return true;
			} else {
				return false;
			}
		}

		private function check_if_phone_is_cell($cell)
		{
				$phone = preg_replace("/[^0-9,.]/", "", $cell);
				$link = 'http://www.carrierlookup.com/index.php/api/lookup?key=66ed44b57ad050d6b2d5eb14c366871dd0afb5d6&number='.$phone;
				$return =  file_get_contents($link);
				$object = json_decode($return);
				return $object;
		
		}
		
		private function send_tenant_email($email, $group_id)
		{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$data = array('error'=>'Invalid email'); 
				return $data;
			} else {
				$info = $this->find_sender_info($group_id);
				if(!isset($info->error)) {
					$name = $info->name;
					if(!empty($info->bName)) {
						$name = $info->bName;
					}
			
					if($group_id>0) {
						$subInfo = $this->get_sub_admin_bName($group_id);
						$name = $subInfo->sub_b_name;
						$id = $this->get_sub_admin_id($group_id);
					} else {
						$id = $this->session->userdata('user_id');
					}
					$uniqueLink = $this->get_unique_link($id);
					if(!empty($uniqueLink)) {
						$subject = $name.' has invited you to join Network 4 Rentals';
						$msg = '<h3>'.$name.' Has Sent You An Invite</h3>
							<p>We have signed up with Network 4 Rentals to help us become more effective while communicating with our tenants. Using Network 4 Rentals allows you to communicate with us day or night, at your convenience. We ask that you create a tenant account at <a href="https://www.network4rentals.com/">N4R</a> to allow us to communicate online. Once you create an account go to "My Rental History" and click on "Add Landlord". Once there you will see a box that says "Search For Your Landlord". In that box if you search for <b>"'.$name.'"</b> you will see our name pop up. Click our name and fill out the rest of the form to connect with us.</p>
							<p>If you have any questions contact us via the message system inside your account.</p>
							<br><br>
							<a href="https://network4rentals.com/network/renters/link-landlord-rentals/'.$uniqueLink.'">Create Account</a>
						';
						$this->email_format($msg);
						if($this->sendEmail($email, $msg, $subject)) {
							$this->addActivity($email);
							return array('success'=>'Email sent successfully to '.$email);
						} else {
							return array('error'=>'Internal error, email not sent. Try again.');
						}
					} else {
						return array('error'=>'You must set-up your public page so they can connect to  your N4R Profile.<br><a href="'.base_url('landlords/public-page-settings').'">Setup Public Page</a>');
					}
				} else { //ERROR IS SET
					return $info;
				}
			}
		}
		
		private function email_format($message)
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
		
		private function sendEmail($email, $message, $subject)
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
		
		private function addActivity($info)
		{
			$this->load->model('landlords/fetch_activity_model');
			$action = 'Invited Tenant To Join - '.$info;
			$data = array('action' =>$action,'user_id' =>$this->session->userdata('user_id'),'type'=>'landlords','action_id' =>'');
			$this->fetch_activity_model->add_activity_feed($data);
		}
		
		private function get_sub_admin_bName($group_id) 
		{
			$results = $this->db->get_where('admin_groups', array('id'=>$group_id, 'main_admin_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}
		}
		
		private function find_sender_info($group_id)
		{
			if($group_id>0) {
				$this->db->limit(1);
				$results = $this->db->get_where('admin_groups', array('id'=>$group_id, 'main_admin_id'=>$this->session->userdata('user_id')));
				if($results->num_rows()>0) {
					$row = $results->row();
					$id = $row->sub_admins;
				} else {
					return array('error'=>'Invalid user data');
				}
			} else {
				$id = $this->session->userdata('user_id');
			}
			
			$results = $this->landlord_info($id);
			if($results !== false) {
				return $results;
			} else {
				return array('error'=>'Invalid user data');
			}
		}
		
		private function landlord_info($id)
		{
			$this->db->limit(1);
			$this->db->select('id, name, bName');
			$results = $this->db->get_where('landlords', array('id'=>$id));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}
		}
		
		private function get_sub_admin_id($group_id)
		{
			$results = $this->db->get_where('admin_groups', array('id'=>$group_id, 'main_admin_id'=>$this->session->userdata('user_id')));
				if($results->num_rows()>0) {
					$row = $results->row();
					$id = $row->sub_admins;
					return $id;
				} else {
					return false;
				}
		}
		
		private function get_unique_link($id) 
		{
			$this->db->limit(1);
			$this->db->select('unique_name');
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$id, 'type'=>'landlord'));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row->unique_name;
			} else {
				return false;
			}
		}
		
	}