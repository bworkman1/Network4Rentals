<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_request_handler extends CI_Model { 
	
	/*
		PASS IN SERVICE REQUEST ID
		
		RESULTS: Associative array with landlord, renter, rental, request keys
	*/
	var $serviceId;
	
	function __construct()
    {
		$this->load->library('session');
        // Call the Model constructor
        parent::__construct();
	}
	
	public function build_service_request($service_request_id, $hash=null) 
	{
		$this->serviceId = $service_request_id;
		if($this->valididateRequest($hash)) {
			$data = array();
			$data['request'] = $this->getSingleServiceRequest($this->serviceId);
		
			if(!empty($data['request']->group_id)) {
				//If group id is not empty get the manager of this service requests account
				$data['request']->landlord_id = $this->getGroupId($data['request']->group_id);
			}
			
			$data['landlord'] = $this->getSingleLandlord($data['request']->landlord_id);
			$data['renter'] = $this->getSingleTenant($data['request']->tenant_id);
			if(!empty($data['request']->listing_id)) {
				$data['rental'] = $this->getSingleProperty($data['request']->listing_id);
			} else {
				$data['rental'] = $this->getRentalProperty($data['request']->rental_id);
			}
			
			$data['items'] = $this->getRentalItems($data['request']->listing_id, $data['request']->service_type);
			
			return $data;
		} else {
			return false;
		}
	}
	
	private function valididateRequest($hash=null) 
	{
		$side = $this->session->userdata('side_logged_in');
		if(!empty($side)) {			
			if($side == '898465406540564') {
				//RENTER 
				$column = 'tenant_id';
			} elseif($side == '8468086465404') {
				//LANDLORD
				$column = 'landlord_id';
			} elseif($side == '203020320389822') {
				//CONTRACTOR
				$column = 'contractor_id';
			} else {
				//INVALID 
				return false;
			}
			$query = $this->db->get_where('all_service_request', array($column=>$this->session->userdata('user_id'), 'id'=>$this->serviceId));
			if($query->num_rows()==0) {
				return false;
			}
		} else {
			if(!empty($hash)) {
				$query = $this->db->get_where('all_service_request', array('email_hash'=>$this->serviceId));
				if($query->num_rows()==0) {
					return false;
				} else {
					$row = $query->row();
					$this->serviceId = $row->id;
				}
			} else {
				return false;
			}
		}
		
		return true;
	}
	
	private function getSingleServiceRequest() 
	{
		$query = $this->db->get_where('all_service_request', array('id'=>$this->serviceId));
		return $query->row();
	}
	
	private function getSingleLandlord($landlord_id)
	{
		$this->db->select('id, name, address, city, state, email, zip, phone, bName, cell_phone, sms_msgs, forwarding_sms_msgs, forwarding_cell');
		$query = $this->db->get_where('landlords', array('id'=>$landlord_id));
		return $query->row();
	}
	
	private function getRentalProperty($property_id) 
	{
		//rental_address	rental_city	rental_state	rental_zip
		$query = $this->db->get_where('renter_history', array('id'=>$property_id));
		return $query->row();
	}
	
	private function getSingleProperty($property_id)
	{
		//rental_address	rental_city	rental_state	rental_zip
		$query = $this->db->get_where('listings', array('id'=>$property_id));
		$row = $query->row();
		$data = new stdClass();
		if(!empty($row)) {
			$data->rental_address = $row->address;
			$data->rental_city = $row->city;
			$data->rental_state = $row->stateAbv;
			$data->rental_zip = $row->zipCode;
		}
		return $data;
	}
	
	private function getSingleTenant($renter_id)
	{
		$this->db->select('id, email, name, phone, alt_phone, cell_phone, sms_msgs');
		$query = $this->db->get_where('renters', array('id'=>$renter_id));
		return $query->row();
	}
	
	private function getGroupId($groupId)
	{
		$this->db->select('sub_admins');
		$query = $this->db->where('admin_groups', array('id'=>$groupId));
		return $query->row();
	}
	
	private function getRentalItems($listing_id, $type)
	{
		$query = $this->db->get_where('home_items', array('listing_id'=>$listing_id, 'service_type'=>$type));
		return $query->result();
	}
	
	private function getRelatedRequests()
	{
		
	}
	
	private function sendTextMsg()
	{
		
	}
	
	private function sendEmail()
	{
		
	}
	
}