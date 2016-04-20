<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class member_handler extends CI_Model {
		
		function Member_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function add_member($data) 
		{	
			$id = '';
			if(!empty($data['registered_landlord_id'])) {
				$id = $this->check_for_landlord_email($data['email']);
			}
			
			$data['assoc_id'] = $this->session->userdata('user_id');
			$data['member_type'] = ucwords($data['member_type']);
			if(!empty($id)) {
				$data['registered_landlord_id'] = $id;
			}
			
			$this->db->insert('landlord_assoc_members', $data);
			if($this->db->affected_rows()>0) {
				if(!empty($data['registered_landlord_id'])) {
					//notify landlord
					$this->load->model('landlord-assoc/account_handler');
					$info = $this->account_handler->get_account_details();
					$name_array = explode(' ', $data['name']);
					$this->load->model('special/send_email');
					
					$message = '<h4>Hello '.$name[0].',</h4><p>Your membership with '.$info->title.' needs to be confirmed. Login to your account and in your activity page you will find the link to verify your membership. Click confirm membership and you\'re all set.</p>';
					$message .= '';
					$subject = 'Verify N4R Landlord Association Membership';
					$this->send_email->sendEmail($data['email'], $message, $subject);
					$this->load->model('special/add_activity');
					$action = 'Verify Membership Landlord Association Membership';
					$user_id = $data['registered_landlord_id'];
					$type = 'landlords';
					$action_id = $this->session->userdata('user_id');
					$this->add_activity->add_new_activity($action, $user_id, $type, $action_id);
				}
				return true;
			} else {
				return false;
			}
		}
		
		
		
		public function memberCategories() 
		{
			$this->db->select('member_type');
			$this->db->group_by('member_type');
			$results = $this->db->get_where('landlord_assoc_members', array('assoc_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return $results->result();
			} 
			return false;
		}
		
		function show_member_details($id) 
		{
			$results = $this->db->get_where('landlord_assoc_members', array('id'=>$id));
			if($results->num_rows()>0) {
				$row = $results->row();
				if($row->registered_landlord_id>0 &&  $row->custom_values === 'n') {
					$data = $this->get_registered_member($row->registered_landlord_id);
					$row->name = $data->name;
					$row->email = $data->email;
					$row->address = $data->address;
					$row->city = $data->city;
					$row->zip = $data->zip;
					$row->phone = $data->phone;
					$row->state = $data->state;
				}
				$row->due_date = date('m/d/Y', strtotime($row->due_date));
				
				return $row;
			} else {
				$array = array('error'=>'Error');
				return $array;
			}
		}
		
		
		
		function check_for_landlord_email($email) {
			$this->db->select('id');
			$results = $this->db->get_where('landlords', array('email'=>$email));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row->id;
			} else {
				return 0;
			}
		}
		
		function get_members()
		{
			$this->db->select('name, active, id, position, registered_landlord_id, member_type, accepted');
			$this->db->order_by('stack_number');
			$results = $this->db->get_where('landlord_assoc_members', array('assoc_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				foreach ($results->result() as $row) {
					if($row->registered_landlord_id > 0) {
						$update = $this->get_registered_member($row->registered_landlord_id);
						if($update !== false) {
							$row->name = $update->name;
						}
					}
					$new_data[] = $row;
				}
				return $new_data;
			} else {
				return 0;
			}
		}

		function update_member_details($data) 
		{
			$id = $data['member_id'];
			unset($data['member_id']);
			unset($data['id']);
			$data['member_type'] = ucwords($data['member_type']);
			$this->db->where('id', $id);
			$this->db->where('assoc_id ', $this->session->userdata('user_id'));
			$this->db->update('landlord_assoc_members', $data);
		
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function get_registered_member($id)
		{
			$this->db->select('name, email, phone, address, city, state, zip');
			$result = $this->db->get_where('landlords', array('id'=>$id));
			if($result->num_rows()>0) {
				return $result->row();
			} else {
				return false;
			}
		}
		
		function reorder_members($data)
		{
			$this->db->where('id', $data['id']);
			$this->db->update('landlord_assoc_members', array('stack_number'=>$data['stack_number']));
		}
		
		function delete_member($id) 
		{
			$this->db->select('id');
			$result = $this->db->get_where('landlord_assoc_members', array('id'=>$id, 'assoc_id'=>$this->session->userdata('user_id')));
			if($result->num_rows()>0) {
				$this->db->where('id', $id);
				$this->db->delete('landlord_assoc_members');
				if($this->db->affected_rows()>0) {
					$this->load->model('special/add_activity');
					$action = 'You have been deleted from a landlord association';
					$user_id = $data['registered_landlord_id'];
					$type = 'landlords';
					$action_id = $this->session->userdata('user_id');
					$this->add_activity->add_new_activity($action, $user_id, $type, $action_id);
					
					return true;
				} else {
					return false;
				}
				
			} else {
				return false;
			}
		}
		
	}