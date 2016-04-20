<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/*
		Send messages back and forth between users
		Create a time stamp
		Upload a file
		Email user about new message
		Email user when the other reads it
	*/
	
	class Messaging_modal extends CI_Model {
		
		var $mUserType;
		var $mTimeStamp;
		var $mLandlordId;
		var $mTenantId;
		var $mMessagesTo;
		var $mMessagesFrom;
		var $mRentalId;
		var $mMgrId;
		var $mLandlordName;
		var $mTenantName;
		var $mOffset;
		var $mTenantEmail;
		var $mLandlordEmail;
		var $mLandlordCell;
		var $mRenterCell;
		var	$mRenterAcceptSMS;
		var $mLandlordAcceptSMS;
		var $mFileName;
		var $mEmailHash;
		
		// Call the Model constructor
		public function messaging_handler()
		{		
			parent::__construct();
		}
		
		public function build_message($args)
		{		
			$this->mRentalId = $args['rental_id'];
			$this->mUserType = $args['type'];
			$this->mTimeStamp = date('Y-m-d H:i:s');

			
			if($this->mUserType == 'renter') { 
				$this->mTenantId = $this->session->userdata('user_id');
			} else {
				$this->mLandlordId = $this->session->userdata('user_id');
			}
			
			if($this->get_rental_details()) {
				if(!empty($args['file'])) {
					$uploaded = $this->uploadFile($args['file']);
					if(isset($uploaded['error'])) {
						$this->session->set_flashdata('error', $uploaded['error']);
						$this->session->set_flashdata('msg', $args['message']);
						return false;
						exit;
					} else {
						$this->mFileName = $uploaded['success'];
					}
				}
			
				if($this->add_new_message($args['msg'])) {
					$this->session->set_flashdata('success', 'Message sent successfully, you will receive a message once they read it');
				} else {
					$this->session->set_flashdata('error', 'Something went wrong, try again');
				}
				
			} else {
				return false;
			}
		}
		
		public function add_new_message($msg)
		{
			$this->mEmailHash = md5($this->session->userdata('user_id').date('Y-m-d H:i:m'));
			
			$this->set_landlord_name();
			$this->set_tenant_name();
			
			if(empty($this->mFileName)) {
				$this->mFileName = ' ';
			}
			
			$data = array(
				'message' => $msg,
				'tenant_id' => $this->mTenantId,
				'rental_id' => $this->mRentalId,
				'timestamp' => $this->mTimeStamp,
				'hash_mail' => $this->mEmailHash,
				'attachment' => $this->mFileName,
				'landlord_id' => $this->mLandlordId,
				'group_id' => $this->mMgrId,
			);
			
			if($this->mUserType == 'renter') {
				$data['sent_by'] = '0';
				$data['tenant_viewed'] = $this->mTimeStamp;
				$data['landlord_viewed'] = '0000-00-00 00:00:00';
			} else {
				$data['sent_by'] = '1';
				$data['actual_landlord_sent'] = $this->session->userdata('user_id');
				$data['landlord_viewed'] = $this->mTimeStamp;
				$data['tenant_viewed'] = '0000-00-00 00:00:00';
			}
		
			$this->db->insert('messaging', $data);
			if($this->db->insert_id()>0) {
				$this->send_new_msg_notifications();
				$this->load->model('special/add_activity');
				
				if($this->mUserType == 'renter') {
					$action = 'Sent Landlord A New Message<br><b><small>'.$this->mLandlordName.'</small></b>';
					$this->add_activity->add_new_activity($action, $this->mTenantId, 'renters', $this->mRentalId, $this->mMgrId);
				
					$action = 'Message Received From Tenant<br><b><small>'.$this->mTenantName.'</small></b>';
					$this->add_activity->add_new_activity($action, $this->mLandlordId, 'landlords', $this->mRentalId, $this->mMgrId);
				} else {
				
					$action = 'New Message From Landlord<br><b><small>'.$this->mLandlordName.'</small></b>';
					$this->add_activity->add_new_activity($action, $this->mTenantId, 'renters', $this->mRentalId, $this->mMgrId);
				
					$action = 'Message Sent To Tenant<br><b><small>'.$this->mTenantName.'</small></b>';
					$this->add_activity->add_new_activity($action, $this->mLandlordId, 'landlords', $this->mRentalId, $this->mMgrId);
				}
			
				return true;
			} else {
				return false;
			}
			
		}
		
		public function show_messages($data)
		{
			$this->mTimeStamp = date('Y-m-d H:i:s');
			$this->mUserType = $data['type'];
			$this->mRentalId = $data['rental_id'];
			$this->mMessagesTo = $this->session->userdata('date_to_msg');
			$this->mMessagesFrom = $this->session->userdata('date_from_msg');
			$this->mOffset = $data['offset'];
			
			if($this->mUserType == 'landlord') {
				$this->mLandlordId = $this->session->userdata('user_id');
			} elseif($this->mUserType == 'renter') {
				$this->mTenantId = $this->session->userdata('user_id');
			} else {
				$this->session->set_flashdata('error', 'Invalid selection');
				return false;
			}
			
			if($this->get_other_users_id()) { //this sets mMgrId, mLandlordId, mTenantId
				
				$data['results'] = $this->retrieve_messages();
				$data['links'] = $this->message_pagination();
				if($this->mUserType=='renter') {
					$data['message_to'] = array(
						'name' => $this->mLandlordName,
						'email'=> $this->mLandlordEmail,
					);
				} else {
					$data['message_to'] = array(
						'name' => $this->mTenantName,
						'email'=> $this->mTenantEmail,
					);
				}
				
				return $data;
			} else {
				$this->session->set_flashdata('error', 'Invalid selection');
				return false; 
			}
		}
		
		/* ADDING MESSAGE FUNCTIONS */
		
		private function get_rental_details()
		{
			$this->db->select('link_id, group_id, tenant_id');
			if($this->mUserType == 'renter') {
				$results = $this->db->get_where('renter_history', array('id'=>$this->mRentalId, 'tenant_id'=>$this->mTenantId));
			} else {
				$results = $this->db->get_where('renter_history', array('id'=>$this->mRentalId, 'link_id'=>$this->mLandlordId));
			}
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->mLandlordId = $row->link_id;
				$this->mTenantId = $row->tenant_id;
				if(empty($row->group_id)) {
					$this->mMgrId = NULL;
				} else {
					$this->mMgrId = $row->group_id;
				}
				
				return true;
			}
			return false;
		}
		
		private function uploadFile($file)
		{
			if(!empty($file['name'])) {
				$config['upload_path'] = './message-uploads/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|PNG|JPG|JPEG|docx|doc';
				$config['max_size'] = '5000KB';
				$this->load->library('upload', $config);
				
				$file = "file";
				
				if($this->upload->do_upload($file)) {
					$upload = $this->upload->data();
					return array('success' => $upload['file_name']);
				} else {
					return array('error' => $this->upload->display_errors());
				}
			}
		}
		
		private function send_new_msg_notifications() 
		{
			$this->load->model('special/send_email');
			
			if($this->mUserType == 'renter') {
				$email = $this->mLandlordEmail;
				$subject = 'New Message From Tenant On N4R';
				
				$message = '<h3>New Message Received</h3><p>You have received a new message from one of your tenants on Network 4 Rentals.</p><p>To view the message click the link below.<br><a href="'.base_url().'renters/view-message-landlord/'.$this->mEmailHash.'">View Message</a></p>';
				$alt_message = 'You have received a new message from one of your tenants on Network 4 Rentals';	
				
				$text_msg = 'You Have Received A New Message From One Of Your Tenants. '.base_url().'renters/view-message-landlord/'.$this->mEmailHash;
			} else {
				$email = $this->mTenantEmail;
				$subject = 'New Message From Your Landlord On N4R';
				$message = '<h3>New Message Received</h3><p>You have received a new message from one of your landlords on Network 4 Rentals.</p><p>To view the message click the link below.<br><a href="'.base_url().'renters/view-message-email/'.$this->mEmailHash.'">View Message</a></p>';	
				
				$alt_message = 'You have received a new message from one of your landlords on Network 4 Rentals';
				
				$text_msg = 'You have received a new message from one of your landlords on Network 4 Rentals, '.base_url().'renters/view-message-email/'.$this->mEmailHash;
			}
			
			$this->send_email->sendEmail($email, $message, $subject, $alt_message);
			
			$this->notifiy_user_sms($text_msg);
			
			
			
		}
		
		/* RETRIEVING MESSAGES FUNCTIONS */
		
		private function retrieve_messages() 
		{
			$this->mark_messages_viewed();
			$this->db->order_by('id', 'desc');
			if(empty($this->mOffset)) {
				$this->mOffset = 0;
			} 
			
			if(!empty($this->mMessagesTo) && !empty($this->mMessagesFrom)) {
				$dates = array($this->mMessagesFrom, $this->mMessagesTo);
				if($this->validateDate($dates)) {
					$this->db->where('timestamp >', date('Y-m-d', strtotime($this->mMessagesTo)));
					$this->db->where('timestamp <', date('Y-m-d', strtotime($this->mMessagesFrom)));
				}
			}
			
			$this->db->limit(20, $this->mOffset);
			$results = $this->db->get_where('messaging', array('tenant_id'=>$this->mTenantId, 'rental_id'=>$this->mRentalId, 'landlord_id'=>$this->mLandlordId)); 
			if($results->num_rows()>0) {
				$data = array(); 
				foreach($results->result() as $key => $val) {
					$val->landlord_name = $this->mLandlordName;
					$val->tenant_name = $this->mTenantName;
					if($val->sent_by == 1) { 
						$val->email_sent_to = $this->mTenantEmail;
					} else {
						$val->email_sent_to = $this->mLandlordEmail; 
					}
					$data[] = $val; 
				}
				return $data;
			} else { 
				return false;
			}
		}
		
		private function mark_messages_viewed()
		{
			$this->db->where('tenant_id', $this->mTenantId);
			$this->db->where('landlord_id', $this->mLandlordId);
			$this->db->where('rental_id', $this->mRentalId);
			
			if($this->mUserType == 'renter') {
				$this->db->where('tenant_viewed', '0000-00-00 00:00:00');
				$this->db->update('messaging', array('tenant_viewed'=>$this->mTimeStamp));
				if($this->db->affected_rows()>0) {
					$this->send_notifications();
				}
			} else {
				$this->db->where('landlord_viewed', '0000-00-00 00:00:00');
				$this->db->update('messaging', array('landlord_viewed'=>$this->mTimeStamp));
				if($this->db->affected_rows()>0) {
					$this->send_notifications();
				}
			}
			
		}
		
		private function send_notifications() 
		{
			$this->load->model('special/send_email');
			
			if($this->mUserType == 'renter') {
			
				$email = $this->mLandlordEmail;
				$subject = 'Tenant Viewed You Message On N4R';
				$message = '<h3>Message Read</h3><p>One of your tenants has viewed the message you sent him through Network 4 Rentals. To view this message and or reply to it, login to you account and check your activity page.</p>
				<p><a href="'.base_url().'landlords/message-tenant/'.$this->mRentalId.'">View Message</a></p>';
				$alt_message = 'One of your tenants has sent you a message through Network 4 Rentals.';	
				$text_msg = 'One of your tenants has viewed the message you sent them through Network 4 Rentals. To view this message, login to your account.';
			} else {
			
				$email = $this->mTenantEmail;
				$subject = 'Landlord Viewed You Message On N4R';
				$message = '<h3>Message Read</h3><p>Your landlord has viewed the message you sent him through Network 4 Rentals. To view this message, login to you account and view your messages or click the link below if you are already logged in.</p><p><a href="'.base_url().'renters/view-messages/'.$this->mRentalId.'">View Message</a></p>';	
				$alt_message = 'Your landlord has sent you a message through Network 4 Rentals.';	
				$text_msg = 'Your landlord has viewed the message you sent them through Network 4 Rentals. To view this message, login to your account.';
			}
			$this->send_email->sendEmail($email, $message, $subject, $alt_message);
			
			$this->load->model('special/add_activity');
			
			if($this->mUserType == 'renter') {
				$action = 'A Tenant Viewed Your Messages<br><b><small>'.$this->mTenantName.'</small></b>';
				$this->add_activity->add_new_activity($action, $this->mLandlordId, 'landlords', $this->mRentalId, $this->mMgrId);
			} else {
				$action = 'Landlord Viewed Your Message<br><b><small>'.$this->mLandlordName.'</small></b>';
				$this->add_activity->add_new_activity($action, $this->mTenantId, 'renters', $this->mRentalId, $this->mMgrId);
			}
			
			$this->notifiy_user_sms($text_msg);
			
		}
		
		private function notifiy_user_sms($msg)
		{
			if($this->mUserType == 'renter') {		
				if(!empty($this->mLandlordCell) && $this->mLandlordAcceptSMS == 'y') {
					$this->send_data_message($this->mLandlordCell, $msg);
				}
			} else {
				if(!empty($this->mRenterCell) && $this->mRenterAcceptSMS == 'y') {
					$this->send_data_message($this->mRenterCell, $msg);
				}
			}
		}
		
		private function message_pagination() 
		{
			$this->load->library('pagination');
			if($this->mUserType == 'renter') {
				$config['base_url'] = base_url().'renters/view-messages/'.$this->mRentalId;
			} else {
				$config['base_url'] = base_url().'landlords/message-tenant/'.$this->mRentalId;
			}
			$config['uri_segment'] = 4;
			$config['total_rows'] = $this->count_message_rows();
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
			
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;	
	
			return $this->pagination->create_links();
		}
		
		private function count_message_rows()
		{
			if(!empty($this->mMessagesTo) && !empty($this->mMessagesFrom)) {
				$dates = array($this->mMessagesFrom, $this->mMessagesTo);
				if($this->validateDate($dates)) {
					$this->db->where('timestamp >', date('Y-m-d', strtotime($this->mMessagesTo)));
					$this->db->where('timestamp <', date('Y-m-d', strtotime($this->mMessagesFrom)));
				}
			}
			
			$results = $this->db->get_where('messaging', array('tenant_id'=> $this->mTenantId, 'landlord_id'=>$this->mLandlordId, 'rental_id'=>$this->mRentalId));
		
			return $results->num_rows();
		}
			
		private function set_tenant_name() 
		{
			$this->db->select('name, email, cell_phone, sms_msgs');
			$results = $this->db->get_where('renters', array('id' => $this->mTenantId));
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->mTenantEmail = $row->email;
				$this->mTenantName = $row->name;
				$this->mRenterAcceptSMS = $row->sms_msgs;
				$this->mRenterCell = $row->cell_phone;
			}
		}
		
		private function set_landlord_name()  
		{
			$this->db->select('bName, name, email, cell_phone, sms_msgs');
			$results = $this->db->get_where('landlords', array('id' => $this->mLandlordId));
			if($results->num_rows()>0) {
				$row = $results->row();
				
				$this->mLandlordEmail = $row->email;
				$this->mLandlordAcceptSMS = $row->sms_msgs;
				$this->mLandlordCell = $row->cell_phone;
				
				if(!empty($row->bName)) {
					$this->mLandlordName = $row->bName;
				} else {
					$this->mLandlordName = $row->name;
				}
			}
		}
		
		private function get_other_users_id() 
		{
			$this->db->select('tenant_id, link_id, group_id');
			
			if($this->mUserType == 'landlord') {
				$this->db->where('link_id', $this->mLandlordId);
			} elseif($this->mUserType == 'renter') {
				$this->db->where('tenant_id', $this->mTenantId);
			} else {
				return false;
			}
			
			$results = $this->db->get_where('renter_history', array('id' => $this->mRentalId));
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->mTenantId = $row->tenant_id;
				$this->mLandlordId = $row->link_id;
				
				$this->set_tenant_name();
				$this->set_landlord_name();
				
				if(!empty($row->group_id)) {
					$this->mMgrId = $this->fetch_group_admin_id($row->group_id);
				}
				
				return true;
			} else {
				return false;
			}
		}
		
		private function validateDate($dates_array) 
		{
			$isValid = true;
			foreach($dates_array as $val) {
				$date_arr  = explode('/', $val);
				if (count($date_arr) == 3) {
					if (checkdate($date_arr[0], $date_arr[1], $date_arr[2])) {
						
					} else {
						$isValid = false;
					}
				} else {
					$isValid = false;
				}
			}
			return $isValid;
		}
		
		private function fetch_group_admin_id($group_id)
		{
			$this->db->select('main_admin_id, sub_admins, sub_b_name');
			$results = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->mLandlordName = $row->sub_b_name;
				return $row->sub_admins;
			} else {
				return 0;
			}
		}
		
		private function send_data_message($num, $msg)
		{			
			$this->load->library('plivo');
			$sms_data = array(
				'src' => '13525593099',
				'dst' => '1'.$num,
				'text' => $msg,
				'type' => 'sms', 
				'url' => '',
				'method' => 'POST',
			);

			$response_array = $this->plivo->send_sms($sms_data);
			if ($response_array[0] == '200' || $response_array[0] == '202') {
				return true;
			} else {
				return false;
			}
		}
		
		
	}