<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_settings extends CI_Model {
	
	function payment_settings()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function have_details()
	{
		$this->db->where('id', $this->session->userdata('user_id'));
		$this->db->update('landlords', array('applied_auth'=>'y'));
	}
	
	function check_settings()
	{
		$payment_group = $this->session->userdata('payment_group');
		$this->db->select('net_api, net_hash, net_key, allow_payments, accept_cc, accept_echeck');
		if(!empty($payment_group)) {
			$this->db->where('landlord_id', $this->session->userdata('user_id'));
			$this->db->where('group_id', $this->session->userdata('payment_group'));
		} else {
			$this->db->where('group_id', NULL);
			$this->db->where('landlord_id', $this->session->userdata('user_id'));
		}
		$query = $this->db->get('payment_settings');
		if ($query->num_rows() > 0) {
			$row = $query->row_array(); 
			return $row;
		} else {
			return false;
		}
	}
	
	function update_payment_settings($data)
	{	
		$payment_group = $this->session->userdata('payment_group');
		if(!empty($payment_group)) {
			$data['group_id'] = $this->session->userdata('payment_group');
			$query = $this->db->get_where('payment_settings', array('group_id'=>$this->session->userdata('payment_group'), 'landlord_id'=>$this->session->userdata('user_id')));
		} else {
			$query = $this->db->get_where('payment_settings', array('group_id'=>NULL, 'landlord_id'=>$this->session->userdata('user_id')));
		}
		
		if($query->num_rows()>0) {
			//Update payment row
			$row = $query->row();
			$this->db->where('id', $row->id);
			$this->db->update('payment_settings', $data);
		} else {
			//Insert Payment Row
			$row = $query->row();
			$this->db->insert('payment_settings', $data);
		}
		
		if ($this->db->trans_status() === FALSE) { 
			return false;
		} else {
			return true;
		}
	}
	
	function get_group_admins() 
	{
		$query = $this->db->get_where('admin_groups', array('main_admin_id'=>$this->session->userdata('user_id')));
		if($query->num_rows()>0) {
			return $query->result();
		}
	}
	
	function add_update_payment_details()
	{ 
		//$this->db->select('id');
		//$this->db->get_where
	
			//	net_api	net_hash	net_key	allow_payments	accept_cc	accept_echeck	landlord_id	group_id
	}
	

}
