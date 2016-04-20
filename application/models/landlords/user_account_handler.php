<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class User_account_handler extends CI_Model {
	
		function user_account_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		public function update_password($pwd) 
		{
			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->update('landlords', array('pwd' => $pwd)); 
			if ($this->db->trans_status() === FALSE) {
				return false;
			} else {
				return true;
			}
		}
		
		public function check_unique_user($username) 
		{
			$results = $this->db->get_where('landlords', array('user'=>$username));
			if($results->num_rows()>0) {
				return true; //user found
			} else {
				return false; //user not found
			}
		}
		
		public function check_unique_email($email) 
		{
			$results = $this->db->get_where('landlords', array('email'=>$email));
			if($results->num_rows()>0) {
				return true; //user found
			} else {
				return false; //user not found
			}
		}
		
		public function landlord_info() 
		{
			$this->db->select('id, user, email, name, address, city, state, zip, phone, bName, alt_phone, forwarding_email, cell_phone, sms_msgs, forwarding_sms_msgs, forwarding_cell, default_partial_payments, default_min_payment,	default_auto_pay_discount, online_payment_discount');
			$this->db->where('id', $this->session->userdata('user_id'));
			$query = $this->db->get('landlords');
			if ($query->num_rows() > 0) {
				$row = $query->row_array(); 
				$this->db->select('unique_name');
				$q = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$row['id']));
				if($q->num_rows()>0) {
					$r = $q->row();
					$row['unique_name'] = $r->unique_name;
				}
				return $row;
			} else {
				return false;
			}
		}
		
		public function update_landlord_info($data) 
		{
			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->update('landlords', $data); 
			if ($this->db->trans_status() === FALSE) {
				return false;
			} else {
				return true;
			}
		}
		
		public function edit_forwarding_address($info) 
		{
			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->update('landlords', $info); 
			if ($this->db->trans_status() === FALSE) {
				return false;
			} else {
				return true;
			}
		}
		
		public function find_landlord_info($id) 
		{
			$this->db->select('email, name, address, city, state, zip, phone, bName, alt_phone, forwarding_email');
			$this->db->where('id', $id);
			$query = $this->db->get('landlords');
			if ($query->num_rows() > 0) {
				$row = $query->row_array(); 
				return $row;
			} else {
				return false;
			}
		}
		
		public function remove_forwarding_email()
		{
			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->update('landlords', array('forwarding_email'=>'')); 
			if ($this->db->trans_status() === FALSE) {
				return false;
			} else {
				return true;
			}
		}
		
		public function get_current_landlord_info($id) 
		{
			$query = $this->db->get_where('admin_groups', array('main_admin_id'=>$id, 'sub_admins'=>$this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {
				return $query->row_array(); 
			}
			
		}
		
		public function get_current_tenants($id) {
			$temp_id = $this->session->userdata('temp_id');
			
			$this->db->select('tenant_id, id');
			$this->db->where('current_residence', 'y');
			if(!empty($temp_id)) {
				$this->db->where('group_id', $temp_id);
			} else {
				$this->db->where('link_id', $id);
			}
			
			$query = $this->db->get('renter_history');
			if ($query->num_rows() > 0) {
				return $query->result_array(); 
			}
		}
		
		public function get_tenants_signup_date($date, $type)
		{
			//checks to see if admin is here
			$admins = array('23', '156', '75', '80');
			if(!in_array($this->session->userdata('user_id'), $admins)) {
				return false;
			}
			$this->db->select('id, name, email, phone, browser_info, hear');
			$this->db->like('sign_up', $date);
			$result = $this->db->get($type);
			if ($result->num_rows() > 0) {
				foreach ($result->result() as $row) {
					$data[] = $row;
				}
				return json_encode($data);
			} else {
				return false;
			}
		}
		
		public function get_landlords_signup_date($date, $type)
		{
			//checks to see if admin is here
			$admins = array('23', '73', '156', '80');
			if(!in_array($this->session->userdata('user_id'), $admins)) {
				return false;
			}
			$this->db->select('id, name, email, phone, browser_info, hear');
			$this->db->like('sign_up', $date);
			$result = $this->db->get($type);
			if ($result->num_rows() > 0) {
				foreach ($result->result() as $row) {
					$data[] = $row;
				}
				return json_encode($data);
			} else {
				return false;
			}
		}
		
		function check_public_page()
		{
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=> $this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function get_landlord_email($id)
		{
			$this->db->limit(1);
			$this->db->select('email, name');
			$query = $this->db->get_where('landlords', array('id'=>$id));
			return $query->row();
		}
		
		public function getAdminLandlordId($group_id)
		{
			$this->db->limit(1);
			$this->db->select('sub_admins');
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			return $query->row();
		}
		
		
	}