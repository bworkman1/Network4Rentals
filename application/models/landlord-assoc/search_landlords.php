<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class search_landlords extends CI_Model {
		
		var $mCurrentMemberIds; //ARRAY OF IDS
		
		function Search_landlords()
		{
			// Call the Model constructor
			parent::__construct();
		}
		

		
		function search_if_user_exists($data)
		{
			$this->check_current_members();
			$this->db->limit(15);
			$this->db->select('landlords.id,  landlords.name,  landlords.city, landlords.state, landlords.zip, landlord_page_settings.image, landlord_page_settings.bName');
			$this->db->like('landlords.'.$data['searchBy'], $data['searchFor']);
			$this->db->join('landlord_page_settings', 'landlords.id = landlord_page_settings.landlord_id');
			$this->db->where('landlords.confirmed', 'y');
			if(!empty($this->mCurrentMemberIds)) {
				foreach($this->mCurrentMemberIds as $val) {
					$this->db->where('landlords.id !=', $val);
				}
			}
			$results = $this->db->get('landlords');
			if($results->num_rows()>0) {
				return $results->result();
			} else {
				$data = array('none'=>'No Results Found');
				return $data;
			}
			
		}
		
		private function check_current_members() {
			$this->db->select('registered_landlord_id');
			$results = $this->db->get_where('landlord_assoc_members', array('assoc_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$memberArray = array();
				foreach($results->result() as $key => $val) {
					if($val>0) {
						$memberArray[] = $val->registered_landlord_id;
					}
				}
				$this->mCurrentMemberIds = $memberArray;
			}
		}
		
	}