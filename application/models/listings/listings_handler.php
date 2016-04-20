<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Listings_handler extends CI_Model {
		
		var $mLandlordsIds;
		var $suggestRadius;
		
		public function __construct() {        
			parent::__construct();
		}
				
		function getListingAssociations() 
		{
			if(!empty($this->mLandlordsIds)) {
				$this->db->select('landlord_assoc_members.registered_landlord_id, landlord_assoc_members.assoc_id, landlord_page_settings.image, landlord_page_settings.unique_name');
				foreach($this->mLandlordsIds as $val) {
					$this->db->or_where('registered_landlord_id', $val);
				} 
				$this->db->join('landlord_page_settings', 'landlord_page_settings.landlord_id = landlord_assoc_members.assoc_id AND landlord_page_settings.type = "association"');
				$this->db->group_by('landlord_assoc_members.assoc_id');
				$result = $this->db->get('landlord_assoc_members');
				if($result->num_rows()>0) {
					return $result->result();
				}
			}
		}
		
		
			
		function get_listing($id)
		{
			$results = $this->db->get_where('listings', array('id'=>$id, 'active'=>'y'));
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->mLandlordsIds = array($row->owner);
				
				
				$results = $this->db->get_where('listing_images', array('listingId'=>$row->id));
				if($results->num_rows()>0) {
					$row->images = $results->row();
				}
				
				if(!empty($row->contact_id)) {
					$this->db->select('main_admin_id, sub_admins');
					$r = $this->db->get_where('admin_groups', array('id'=>$row->contact_id));
					$rs = $r->row();
					$row->owner = $rs->sub_admins;
				}
			
				$this->db->select('bName, phone, name');
				$results = $this->db->get_where('landlords', array('id'=>$row->owner));
				if($results->num_rows()>0) { 
					$row->landlord = $results->row();
				}
				
				$this->db->select('id, unique_name, image, admin_redirect');
				$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$row->owner));
				if($results->num_rows()>0) { 
					$page = $results->row();
					
					if($page->admin_redirect === 'y') {
						$this->db->select('unique_name, image');
						$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$rs->main_admin_id));
						if($results->num_rows()>0) {
							$page = $results->row();
						}
					}
					
					$row->page = $page;
				}
			
	
				
				return $row;
			} else {
				return false;
			}
		}
		
		function get_inactive_listing($id) 
		{
			$results = $this->db->get_where('listings', array('id'=>$id, 'owner'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$row = $results->row();
				$results = $this->db->get_where('listing_images', array('listingId'=>$row->id));
				if($results->num_rows()>0) { 
					$row->images = $results->row();
				}
				$this->db->select('bName, phone, name');
				$results = $this->db->get_where('landlords', array('id'=>$row->owner));
				if($results->num_rows()>0) {
					$row->landlord = $results->row();
				}
				$this->db->select('unique_name, image');
				$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$row->owner));
				if($results->num_rows()>0) { 
					$row->page = $results->row();
				}
				
				return $row;
			} else {
				return false;
			}		
		}
		
		function update_zips()
		{
			$this->db->where('latitude IS NULL', null, false);
			$results = $this->db->get('listings');
			if($results->num_rows()>0) {
				foreach ($results->result() as $row) {
					$this->db->select('latitude, longitude');
					$r = $this->db->get_where('zips', array('zipCode'=>$row->zipCode)); 
					$q = $r->row();
					$this->db->where('id', $row->id);
					$this->db->update('listings', array('latitude'=>$q->latitude, 'longitude'=>$q->longitude));
				}
			} else {
				echo 'No Results Found';
			}
		}
		
		// NEW LISTING FUNCTIONS
		public function search_listings() 
		{
			$this->suggestRadius = false;
			$listingOptions = array('laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'shopping', 'single_lvl', 'shed', 'park', 'city', 'outside_city', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool');
			
			foreach($listingOptions as $val) {
				$this->form_validation->set_rules($val, ucwords(str_replace('_', '', $val)), 'xss_clean|maxlength[1]|minlength[1]|alpha');
			}
			$this->form_validation->set_rules('zipcode', 'Zip Code', 'integer|maxlength[5]|minlength[5]|xss_clean|required');
			$this->form_validation->set_rules('distance', 'Distance', 'maxlength[1]|minlength[1]|integer|xss_clean');
			$this->form_validation->set_rules('beds', 'Bedrooms', 'maxlength[1]|minlength[1]|integer|xss_clean');
			$this->form_validation->set_rules('baths', 'Bathrooms', 'maxlength[1]|minlength[1]|integer|xss_clean');

			if ($this->form_validation->run() == FALSE) {
				return array('error'=>validation_errors());
			} else {
				extract($_POST);
				
				$conditions = array(
					'zip' => $zipcode,
				);
				
				//Save user filter settings
				$this->session->set_userdata('zip', $zipcode);
				if($beds>0) {
					$this->session->set_userdata('beds', $beds);
					$conditions['bedrooms'] = $beds;
				} else {
					$this->session->unset_userdata('beds');
				}
				if($baths>0) {
					$this->session->set_userdata('baths', $baths);
					$conditions['bathrooms'] = $baths;
				} else {
					$this->session->unset_userdata('baths');
				}
				if($distance>0) {
					$this->session->set_userdata('distance', $distance);
					$conditions['radius'] = $distance;
				} else {
					$conditions['radius'] = 2;
				}		
				if($distance>50) {
					return array('error'=>'Radius cannot exceed 50 Miles');
					exit;
				}
				
				foreach($listingOptions as $val) {
					if($_POST[$val] == 'y') {
						$this->session->set_userdata($val, 'y');
						$conditions[$val] = 'y';
					} else {
						$this->session->unset_userdata($val);
					}
				}
				
				$data = $this->get_radius($conditions); //assoc array with error or success (success will hold the listings)
				if(empty($data)) {
					if($conditions['radius'] != 50) {
						$this->suggestRadius = true;
						$conditions['radius'] = 50;
						$counter = $this->get_radius($conditions);
						if($counter>0) {
							$data['radius_search'] =$counter;
						}
					}
				}
		
				return $data;
			}
			
		}		
		
		private function get_radius($cond)
		{
			$this->db->limit(1);
			$this->db->select('longitude, latitude');
			$query = $this->db->get_where('zips', array('zipCode'=>$cond['zip']));
			if($query->num_rows()>0) {
				$row = $query->row(); 
				$results = $this->by_radius($row->latitude, $row->longitude, $cond);
				return $results;
			} else {
				return array('error', 'Invalid Zip Code');
			}
		}
		
		private function by_radius($lat, $lon, $cond) 
		{
			$wheres = '';
			foreach($cond as $key => $val) {
				if($key!='radius' && $key != 'zip') {
					if($key=='bathrooms' || $key=='bedrooms') {
						$wheres .= 'AND '.$key.' >= '.$val.' ';
					} else {
						$wheres .= 'AND '.$key.' = "'.$val.'" ';
					}
				}
			}
		
			$radius = $radius ? $radius : 1;
			$sql = 'SELECT listings.id, listings.owner, listings.lastmodified, listings.title, listings.price, listings.city, listings.stateAbv, listings.zipCode, listings.bedrooms, listings.address, listings.bathrooms,  listing_images.image1, listing_images.image2, listing_images.image3, listing_images.image4, listing_images.image5, listing_images.listingId, listing_images.featured_image, listings.latitude, listings.longitude FROM listings JOIN listing_images ON listing_images.listingId = listings.id WHERE (3958*3.1415926*sqrt((Latitude-'.$lat.')*(Latitude-'.$lat.') + cos(Latitude/57.29578)*cos('.$lat.'/57.29578)*(Longitude-'.$lon.')*(Longitude-'.$lon.'))/180) <= '.$cond['radius'].' AND active = "y" '.$wheres.' GROUP BY listings.id ORDER BY lastmodified DESC';
 
			$result = $this->db->query($sql);
			if($result->num_rows()>0) {
				if($this->suggestRadius) {
					return $result->num_rows();
				} else {
					$landlords_ids = array();
					foreach($result->result_array() as $key => $val) {
						$landlords_ids[] = $val['owner'];	
					}
					$this->mLandlordsIds = array_unique($landlords_ids);
					
					return array('success' => $result->result_array());
				}
			}
			
			return array();
		}
		
		
		function update_listing_lats_longs() 
		{
			 
			/*$this->db->select('id, zipCode');
			$results = $this->db->get('listings');
			if($results->num_rows()>1) {
				foreach($results->result() as $row) {
					
					$cords = $this->getCityCords($row->zipCode);
					
					$this->db->where('id', $row->id);
					$this->db->update('listings', array('latitude'=> $cords['lat'], 'longitude'=> $cords['lng']));
					
					echo $row->id.' Has been updated '.$cords['lat'].' / '.$cords['lng'].'<br>';
				}
			}
			*/
		}
		
		function getCityCords($zip)
		{
			/*
			$query = $this->db->get_where('zips', array('zipCode'=>$zip));
			$row = $query->row();
			$data = array('lat'=>$row->latitude, 'lng'=>$row->longitude);
			return $data;
			*/
		}
		
		function getCoords($address)
		{
			$address = str_replace(" ", "+", $address); // replace all the white space with "+" sign to match with google search pattern
 
			$url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=$address";
			 
			$response = file_get_contents($url);
			 
			$json = json_decode($response,TRUE); //generate array object from the response from the web
			$data = array(
				'lat'=>$json['results'][0]['geometry']['location']['lat'],
				'lng'=>$json['results'][0]['geometry']['location']['lng'],
			);
			return $data;
		}
		
	} 

	
?>