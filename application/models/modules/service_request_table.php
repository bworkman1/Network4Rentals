<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_request_table extends CI_Model { 
	
	var $mTotalResults;
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function recentServiceRequests($limit, $rental_id = null)
	{
		$this->db->limit($limit);
		$this->db->order_by('id', 'desc');
		if(!empty($rental_id)) {
			$this->db->where('rental_id', $rental_id);
		}
		if($this->session->userdata('user_type')=='renter') {
			$this->db->where('tenant_id', $this->session->userdata('user_id'));
		} else {
			$this->db->where('landlord_id', $this->session->userdata('user_id'));
		}
		$query = $this->db->get('all_service_request');
		return $query->result();
	}
	
	public function allServiceRequests($rental_id=null)
	{
		$userType = $this->session->userdata('user_type');
		if($userType == 'renter') {
			$this->db->where('tenant_id', $this->session->userdata('user_id'));
		} elseif($userType == 'landlord') {
			$this->db->where('landlord_id', $this->session->userdata('user_id'));
		}
		
		if(!empty($rental_id)) {
			$this->db->where('ref_id', $rental_id);
		}
		
		$query = $this->db->get('all_service_request');
		$this->mTotalResults = $query->num_rows();
		
		return $query->result();
	}
	
}