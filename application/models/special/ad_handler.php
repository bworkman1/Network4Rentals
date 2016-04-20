<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad_handler extends CI_Model { 

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
	}
	
	public function getSupplyHouses($zip, $serviceType) //Service type needs to be an number not the text 
	{
		$this->db->select('business, logo, city, state, address, ad_areas, ad_service_types, url, phone');
		$this->db->order_by('id', 'RANDOM');
		$this->db->like('ad_areas', $zip);
		$this->db->like('ad_service_types', $serviceType);
		$query = $this->db->get('supply_houses');
		
		$count = 0;
		$data = array();
		foreach($query->result() as $row) {
			$adAreasArray = explode('|', $row->ad_areas);
			$adServicesArray = explode(',', $row->ad_service_types);
			
			if( in_array($zip, $adAreasArray) && in_array($serviceType, $adServicesArray) && $count<2) {
				$data[] = $row;
				$count++;
			}
		}
		
		return $data;
		
	}

		
	/* DATA should contain array('current_ads' => array, 'service'=>'NUMBER', 'zip' => 'zip', 'request_id'=>'id'); */
	function get_service_request_ads($data)
	{
		if(empty($data['current_ads'])) {
			//Pull new ads and add them to the service request
			$this->db->limit(3);
			$this->db->select('*');
			$results = $this->db->get_where('contractor_zip_codes', array('service_type'=>$data['service'], 'zip'=>$data['zip'], 'active'=>'y', 'purchased'=>'y'));
			if($results->num_rows()>0) {
				//Ads found
				$ad_ids = '';
				$ad_data = array();
				foreach($results->result() as $val) {
					$ad_ids .= $val->id.'|'; //Assign the ads to the request
					$ad_data[] = $val; //The actual add details stored in array
				}
				$ad_ids = rtrim($ad_ids, '|'); 	//Trim the / off the end of the string
				$this->db->where('id', $data['request_id']);
				$this->db->update('all_service_request', array('ad_ids'=>$ad_ids));
			} else {
				//No Ads Found
				$ad_data = array();
			}
		} else {
			$current_ad_ids = explode('|', $data['current_ads']);
			if(count($current_ad_ids)==3) {
				//there are already max number of ads
				$ad_data = array();
				foreach($current_ad_ids as $val) {
					$query = $this->db->get_where('contractor_zip_codes', array('id'=>$val)); // Pull the data for each ad
					if($query->num_rows()>0) {
						$ad_data[] = $query->row();
					}
				}
			} else {
				//check if any new ads can be placed
				$this->db->where('active', 'y');
				$this->db->where('purchased', 'y');
				
				$results = $this->db->get_where('contractor_zip_codes', array('service_type'=>$data['service'], 'zip'=>$data['zip']));
				if($results->num_rows()>0) {
					$ad_data = array();
					foreach($results->result() as $val) {
						$ad_ids .= $val->id.'|'; //Assign the ads to the request
						$ad_data[] = $val; //The actual add details stored in array
					}
					$ad_ids = rtrim($ad_ids, '|'); 	//Trim the / off the end of the string
					$this->db->where('id', $date['request_id']);
					$this->db->update('all_service_request', array('ad_ids'=>$ad_ids));
				} else {
					//No Ads Found
					$ad_data = array();
				}
			}
		}

		$count = 0;
		foreach($ad_data as $val) {	
			$impressions = $val->impressions+1;
			$this->db->where('id', $val->id);
			$this->db->update('contractor_zip_codes', array('impressions'=>$impressions));
			$this->db->select('unique_name, email');
			$results = $this->db->get_where('landlord_page_settings', array('type'=>'contractor', 'landlord_id'=>$val->contractor_id));
			if($results->num_rows()>0) {
				$r = $results->row();
				$ad_data[$count]->url = $r->unique_name;
				$ad_data[$count]->email = $r->email;
			}
			$count++;
		}

		return $ad_data;
	}
	
	
}