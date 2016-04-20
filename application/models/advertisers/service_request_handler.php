<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_request_handler extends CI_Model {
	
	function service_request_handler()
	{
		// Call the Model constructor
		parent::__construct();
	}
	 
	function get_active_service_requests()
	{
		$complete = $this->session->userdata('ser_comp');
		if(empty($complete)) {
			$complete = 'n';
		}
		
		$results = $this->db->get_where('all_service_request', array('contractor_id'=>$this->session->userdata('user_id'), 'complete'=>$complete));
		if($results->num_rows()>0) {
			foreach($results->result() as $row) {
				if(empty($row->group_id)) {
					$this->db->select('name');
					$q = $this->db->get_where('landlords', array('id'=>$row->landlord_id));
					$r = $q->row();
					$row->landlord_name = $r->name;
				} else {
					$this->db->select('sub_admins');
					$q = $this->db->get_where('admin_groups', array('id'=>$row->group_id));
					$r = $q->row();
					
					$this->db->select('name');
					$q = $this->db->get_where('landlords', array('id'=>$r->sub_admins));
					$r = $q->row();
					$row->landlord_name = $r->name;
				}
			}
			
			return $results->result();
		} else {
			return false;
		}
	}
	
	function view_service_requests($request_id)
	{
		
		$id = $this->session->userdata('user_id');
		
		$query = $this->db->get_where('all_service_request', array('contractor_id'=>$id, 'id'=>$request_id));
		if ($query->num_rows() > 0) {
			$service_requests = $query->row_array();
			
			$landlord_id = $service_requests['landlord_id'];
			
			$service_listing_id = $service_requests['rental_id'];
			$service_requests['request_id'] = $request_id;
			//Grab Rental Home Info
			if(!empty($service_listing_id)) {
				$query = $this->db->get_where('renter_history', array('id' => $service_requests['rental_id']));
				$row = $query->row_array(); 
				
				$service_requests['address'] = $row['rental_address'];
				$service_requests['city'] = $row['rental_city'];
				$service_requests['state'] = $row['rental_state'];
				$service_requests['zip'] = $row['rental_zip'];
				$service_requests['checklist_id'] = $row['checklist_id'];
				$service_requests['listing_id'] = $row['listing_id'];
				$group_id = $row['group_id'];
			}
	
			//Grab Tenant Info
			if(!empty($service_requests['tenant_id'])) {
				$query = $this->db->get_where('renters', array('id' => $service_requests['tenant_id']));
				$row = $query->row_array(); 
				$service_requests['tenant_name'] = $row['name'];
				$service_requests['tenant_phone'] = $row['phone'];
				$service_requests['tenant_email'] = $row['email'];
				$service_requests['who'] = '1';
			} else {
				$query = $this->db->get_where('listings', array('id' => $service_requests['listing_id']));
				$row = $query->row_array(); 

				$service_requests['address'] = $row['address'];
				$service_requests['city'] = $row['city'];
				$service_requests['state'] = $row['stateAbv'];
				$service_requests['zip'] = $row['zipCode'];
				$service_requests['who'] = '0';
				
			}

			//Grab Landlord Info
			if($group_id>0) {
				$query = $this->db->get_where('admin_groups', array('id' => $group_id));
				$row = $query->row_array(); 
				$bName = $row['sub_b_name'];
				
				$query = $this->db->get_where('landlords', array('id' => $row['sub_admins']));
				$row = $query->row_array();
				$service_requests['landlord_email'] = $row['email'];			
				$service_requests['landlord_name'] = $row['name'];			
				$service_requests['landlord_phone'] = $row['phone'];					
				$service_requests['landlord_alt_phone'] = $row['alt_phone'];	
				$service_requests['landlord_city'] = $row['city'];	
				$service_requests['landlord_state'] = $row['state'];	
				$service_requests['landlord_zip'] = $row['zip'];	
			} else {
				$query = $this->db->get_where('landlords', array('id' => $landlord_id));
				$row = $query->row_array();
				$service_requests['landlord_email'] = $row['email'];			
				$service_requests['landlord_name'] = $row['name'];			
				$service_requests['landlord_phone'] = $row['phone'];			
				$bname = $row['bName'];			
				$service_requests['landlord_alt_phone'] = $row['alt_phone'];	
				$service_requests['landlord_city'] = $row['city'];	
				$service_requests['landlord_state'] = $row['state'];	
				$service_requests['landlord_zip'] = $row['zip'];	
			}
			if($group_id>0) {
				$service_requests['bName'] = $bName;	
			} else {
				$service_requests['bName'] = $bname;
			}
			
			$this->db->order_by('s_timestamp', 'desc');
			$query = $this->db->get_where('service_request_notes', array('ref_id' => $request_id));
			$notes = array();
			foreach ($query->result() as $row) {
				$add = true;
				if($row->visibility == 0) {
					if($row->landlord_id != $this->session->userdata('user_id')) {
						$add = false;
					}
				}
				if($add) {
					$notes[] = array(
						'note' => $row->note,
						'visibility' => $row->visibility,
						's_timestamp' => $row->s_timestamp
					);
				}
			}

			$service_requests['notes'] = $notes;
			if(!empty($service_requests['listing_id'])) {
				$query = $this->db->get_where('home_items', array('listing_id'=>$service_requests['listing_id'], 'service_type'=>$service_requests['service_type']));
				if ($query->num_rows() > 0) {
					foreach ($query->result() as $row) {
					
						$items[] = array(
							'desc' => $row->desc,
							'modal_num' => $row->modal_num,
							'brand' => $row->brand,
							'serial' => $row->serial,
							'service_type' => 	$row->service_type,		
						);
					}
					$service_requests['items'] = $items;
				}
				
				//Incomplete Service Requests
				$query = $this->db->get_where('all_service_request', array('rental_id'=>$service_requests['rental_id'], 'complete'=>'n', 'id !='=>$request_id));
				if ($query->num_rows() > 0) {
					foreach ($query->result_array() as $row) {
						$service_requests['incomplete_requests'][] = $row;
					}
				} else {
					$service_requests['incomplete_requests'] = '';
				}
				
			}
			return $service_requests;
		} else {
			return false;
		}
	}
	
	function get_service_request_ads($data)
	{
		$ad = array();
		$temp_array = array();
		$ids = array();
		$this->db->limit(3);
		$results = $this->db->get_where('contractor_zips', array('zip_purchased'=>$data['zip'], 'service_purchased'=>$data['service'], 'active'=>'y'));
		foreach ($results->result() as $row) {
			$query = $this->db->get_where('contractor_ads', array('ref_id'=>$row->id, 'active'=>'y'));
			$r = $query->row();
			$temp_array['id'] = $row->id;
			$temp_array['title'] = $r->title;
			$temp_array['desc'] = $r->desc;
			
			$this->db->select('unique_name, image, bName, name, phone, email');
			$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$row->contractor_id, 'type'=>'contractor'));
			$r = $query->row();
			$temp_array['logo'] = $r->image;
			$temp_array['url'] = $r->unique_name;
			$temp_array['b_name'] = $r->bName;
			$temp_array['name'] = $r->name;
			$temp_array['email'] = $r->email;
			$temp_array['phone'] = $r->phone;
			
			if(!empty($temp_array['url'])) {
				$ids[] = $row->id;
				$ad[] = $temp_array;
			}
		}
		if(!empty($ids)) {
			foreach($ids as $val) {
				$this->db->select('impressions');
				$query = $this->db->get_where('contractor_zips', array('id'=>$val, 'active'=>'y'));
				if($query->num_rows()>0) {
					$row = $query->row();
					if(!empty($row->impressions)) {
						$impressions = $row->impressions+1;
					} else {
						$impressions = 1;
					}
					$this->db->where('id', $val);
					$this->db->update('contractor_zips', array('impressions'=>$impressions));
				}
			}
		}
		return $ad;
	}
}

