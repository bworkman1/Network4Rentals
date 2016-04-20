<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources_handler extends CI_Model {
	
	public function __construct() {        
		parent::__construct();
	}
	
	public function search_radius($zip, $radius, $serviceId)
	{
		$this->db->select('longitude, latitude');
		$query = $this->db->get_where('zips', array('zipCode'=>$zip));
		if($query->num_rows()>0) {
			$row = $query->row();
			$results = $this->zipcodeRadius($row->latitude, $row->longitude, $radius, $serviceId);
			return $results;
		} else {
			return false;
		}
	}
	
	function zipcodeRadius($lat, $lon, $radius, $serviceId) 
	{
		$radius = $radius ? $radius : 1;
		$sql = 'SELECT zips.zipCode, contractor_zip_codes.contractor_id, contractor_zip_codes.zip FROM zips 
				JOIN contractor_zip_codes
				ON contractor_zip_codes.zip=zips.zipCode
				WHERE (3958*3.1415926*sqrt((zips.Latitude-'.$lat.')*(zips.Latitude-'.$lat.') + cos(zips.Latitude/57.29578)*cos('.$lat.'/57.29578)*(zips.Longitude-'.$lon.')*(zips.Longitude-'.$lon.'))/180) <= '.$radius.' AND contractor_zip_codes.service_type = '.$serviceId;
		
		$result = $this->db->query($sql);
		if($result->num_rows()>0) {
			return $result->result();
		} else {
			return false;
		}
	}
	
	public function get_active_contractors_in_zip($data)
	{
		$contractors = array();
		$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
		
		$results = $this->search_radius((int)$data['zip'], (int)$data['radius'], (int)$data['service']);
		$addedContractor = array();
		foreach($results as $key => $val) {
			if(!in_array($val->contractor_id, $addedContractor)) {
				$this->db->select('landlord_id, bName, desc, image, address, city, state, phone, unique_name');
				$q = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$val->contractor_id, 'active'=>'y', 'type'=>'contractor'));
				if($q->num_rows()>0) {
					foreach($q->result() as $r) {
						$contractors[] = $r;
					}
				}
				$addedContractor[] = $val->contractor_id;
			}
			
			
		}

		return $contractors;
	}
	
	public function search_contractors($data)
	{
		$results = $this->get_active_contractors_in_zip($data);
		
		return $results;
	}
	
	private function getZipCords($zip) 
	{
		$this->db->select('longitude, latitude');
		$query = $this->db->get_where('zips', array('zipCode'=>$zip));
		if($query->num_rows()>0) {
			return $query->row();
		} else {
			return false;
		}
	}
	
	public function searchSupplyHouses($data) 
	{
		$cords = $this->getZipCords($data['zip']);
		if(!empty($cords)) {
			return $this->supplyHouseRadius($cords->latitude, $cords->longitude, $data['radius'], $data['service']);
		}
		return false;
	}
	
	
	
	private function supplyHouseRadius($lat, $lon, $radius, $serviceType) 
	{
		if($type>0) {
			$extra = ' AND resource_service_types LIKE "%'.$serviceType.'%"';
		} else {
			$extra = '';
		}
		$radius = $radius ? $radius : 1;
		$sql = 'SELECT * FROM supply_houses WHERE (3958*3.1415926*sqrt((lat-'.$lat.')*(lat-'.$lat.') + cos(lat/57.29578)*cos('.$lat.'/57.29578)*(longitude-'.$lon.')*(longitude-'.$lon.'))/180) <= '.$radius.$extra;
		$result = $this->db->query($sql);
		if($result->num_rows()>0) {
			
			if($serviceType>0) {
				$data = array();
				foreach($result->result() as $row) {
					
					$serviceTypeArray = explode(',', $row->resource_service_types);
		
					if(in_array($serviceType, $serviceTypeArray)) {
						if($row->page_settings_id>0) {
							$publicPageId = $this->getSupplyHousePublicPage($row->page_settings_id);
							$row->unique_name = $publicPageId->unique_name;
						}
						$data[] = $row;
					}
				}
				return $data;
			} else {
				
				foreach($result->result() as $row) {
					$publicPageId = $this->getSupplyHousePublicPage($row->page_settings_id);
					$row->unique_name = $publicPageId->unique_name;
					$data[] = $row;
				}
				return $data;
			}
		} else {
			return false;
		}
	}
	
	private function getSupplyHousePublicPage($publicPageId) 
	{
		$this->db->limit(1);
		$this->db->select('unique_name');
		$query = $this->db->get_where('landlord_page_settings', array('id' => $publicPageId));
		return $query->row();
	}
}