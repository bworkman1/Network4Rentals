<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class User_details extends CI_Model {
		
		var $mUserId;
		var $mUserType;
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function get_user_info($id, $type) 
		{
			$this->mUserId = $id;
			$this->mUserType = $type;
			
			if($type=='renter') {
				$data = $this->grab_all_renter_details();
			} elseif($type=='landlord') {
				$data = $this->grab_all_landlord_details();
			} elseif($type=='contractor') {
			
			} elseif($type=='advertiser') {
				
			} else {
				return false;
			}
			return $data;
		}
		
		private function grab_all_renter_details() 
		{
			$this->load->model('admin/users');$this->load->model('admin/users');
			$data['user_details'] = $this->users->get_user_details('renters', $this->mUserId);
			$data['service_requests'] = $this->users->service_requests($this->mUserId, 'tenant_id');
			$data['transactions'] = $this->users->tenant_transactions($this->mUserId);
			$data['rental_history'] = $this->users->tenant_rental_history($this->mUserId);

			return $data;
		}
		
		private function grab_all_landlord_details()
		{
			$this->load->model('admin/landlord_details');
			$this->load->model('admin/users');
			
			$data['user_details'] = $this->users->get_user_details('landlords', $this->mUserId);
			$data['user_properties'] = $this->landlord_details->get_landlord_properties($this->mUserId);
			$data['tenants'] = $this->landlord_details->get_landlords_tenants($this->mUserId);
			$data['service_requests'] = $this->users->service_requests($this->mUserId, 'landlord_id');
			
			
			return $data;
		}
		
	}
		
		