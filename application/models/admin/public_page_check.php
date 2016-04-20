<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Public_page_check extends CI_Model {
		
		var $all_ids;
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		public function check_details()
		{	
			$this->all_user_ids();
			$data['invalid_page_names'] = $this->invalid_page_names();
			$data['no_landlord_page_setup'] = $this->no_landlord_page_setup();
			return $data;
		}
		
		private function all_user_ids() 
		{
			$this->db->select('id, bName, name');
			$landlords = $this->db->get('landlords');
			foreach($landlords->result() as $row) {
				$all_ids['landlords'][] = array(
					'id'=>$row->id,
					'bName'=>$row->bName,
					'name'=>$row->name,
				);
			}
			
			$this->db->select('id');
			$contractors = $this->db->get('contractors');
			foreach($contractors->result() as $row) {
				$all_ids['contractors'][] = $row->id;
			}
			
			$this->all_ids = $all_ids;
			
		}
		
		private function no_landlord_page_setup()
		{
			$this->db->where('type', 'landlord');
			$this->db->select('landlord_id');
			$result = $this->db->get('landlord_page_settings');
			if($result->num_rows()>0) {
				foreach($result->result() as $row) {
					$landlord_page_id[] = $row->landlord_id;
				}
				
				
				
				foreach($this->all_ids['landlords'] as $key => $val) {
					$allLandlordIds[] = $val['id'];
					if(!empty($val['bName'])) {
						$name = $val['bName'];
					} else {
						$name = $val['name'];
					}
					$newIndexLandlords[$val['id']] = $name;
				}
				
				$not_set = array_diff($allLandlordIds, $landlord_page_id);
				
				
				foreach($not_set as $val) {
					array_search($val, $this->all_ids['landlords']);
					$noPageLandlords[] = array(
						'id' => $val,
						'name' => $newIndexLandlords[$val],
					);
				}

				return $noPageLandlords;
			}
			return false;
		}
				
		private function invalid_page_names() 
		{
			$this->db->select('bName, unique_name');
			$result = $this->db->get_where('landlord_page_settings');
			$invalid = array();
			foreach($result->result() as $row) {
				if(strlen($row->unique_name)==32) {
					$invalid[] = array(
						'bname' => $row->bName,
						'unique_name' => $row->unique_name,
					);
				}
			}
			return $invalid;
		}
		
	}