<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_switch_handler extends CI_Model {

	function admin_switch_handler()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function my_groups()
	{
		$this->db->where('sub_admins', $this->session->userdata('user_id'));
		$this->db->or_where('main_admin_id', $this->session->userdata('user_id'));
		$query = $this->db->get('admin_groups');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	function show_properties()
	{
		$temp_id = $this->session->userdata('temp_id');
		if(empty($temp_id)) {
			$id = $this->session->userdata('user_id');
			$query = $this->db->get_where('listings', array('owner'=>$id));
		} else {
			$id = $this->session->userdata('temp_id');
			$query = $this->db->get_where('listings', array('contact_id'=>$this->session->userdata('temp_id')));
		}	
		
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	function property_details_ajax($property) 
	{
		$temp_id = $this->session->userdata('temp_id');
		if(empty($temp_id)) {
			$id = $this->session->userdata('user_id');
		} else {
			$id = $this->session->userdata('temp_id');
		}	
		$query = $this->db->get_where('listings', array('id'=> $property,'owner'=>$id));
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			//$query = $this->db->get_where('renter_history', array('listing_id'=>$row['id'],'current_residence'=>'y');
			//if ($query->num_rows() > 0) {
			//	$r = $query->row_array();
			//}
			return json_encode($row);
			
		} else {
			return false;
		}	
	}
	
}