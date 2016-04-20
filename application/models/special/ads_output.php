<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
				
	class Ads_output extends CI_Model {
		
		// Call the Model constructor
		function ads_output()
		{		
			parent::__construct();
		}
		
		function get_ads_in_location() 
		{
			// $services_array = array('', 'Landlords', 'Tenants', 'Contractors', 'Listings');
			$zipCode = $this->session->userdata('ad_zipCode');
			if(empty($zipCode)) {
				$this->load->library('ip2location');
				$locations = $this->ip2location->getCity($_SERVER['REMOTE_ADDR']);
				foreach ($locations as $field => $val) {
					if($field=='zipCode') {
						$zipCode = $val;
					}
				}
			}
			
			$section = $this->uri->segment(1);
			switch ($section) {
				case 'landlords':
					$section_num = '1';
					break;
				case 'renters':
					$section_num = '2';
					break;
				case 'contractors':
					$section_num = '3';
					break;
				case 'listings':
					$section_num = '2';
					break;
				default:
					$section_num = null;
					break;
			}
			
			if(!empty($section_num) || !empty($zipCode)) {
				$this->db->select('id, service_purchased, advertiser_id, impressions');
				//$zipCode = '43023';
				//$section_num = '1';
				$this->db->order_by('id', 'random');
				$query = $this->db->get_where('advertiser_zips', array('active'=>'y', 'zip_purchased'=>$zipCode, 'service_purchased'=>$section_num));
				if($query->num_rows()>0) {
					$ad_row = array();
					foreach ($query->result() as $row) {
						$ads = $this->db->get_where('advertiser_ads', array('active'=>'y', 'ref_id'=>$row->id, 'service_type'=>$row->service_purchased));
						$data_row = $ads->row();
						$data_row->advertiser_zips_id = $row->id;
						$data_row->impressions = $row->impressions;
						$advertiser = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$row->advertiser_id, 'type'=>'advertiser'));
						$info = $advertiser->row();
						$data_row->advertiser_id = $row->advertiser_id;
						$data_row->bName = $info->bName;
						$data_row->phone = $info->phone;
						
						$ad_row[] = $data_row;
					}
					foreach($ad_row as $key => $val) {
						$total = $val->impressions+1;
						$this->db->where('id', $val->advertiser_zips_id);
						$this->db->update('advertiser_zips', array('impressions'=>$total));
					}
					return $ad_row;
				}
			}
		}
		
	
	

	
		
		
	}