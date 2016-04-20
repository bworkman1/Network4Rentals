<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Search_users extends CI_Model {
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		public function search_for_users()
		{
			$this->form_validation->set_rules('searchFor', 'Search For', 'xss_clean|min_length[3]|max_length[30]');
			$this->form_validation->set_rules('searchBy', 'Search By', 'xss_clean|min_length[4]|max_length[30]|alpha');
			$this->form_validation->set_rules('source', 'Source/Referral', 'xss_clean|min_length[4]|max_length[40]');	
			$this->form_validation->set_rules('user_type', 'User Type', 'required|xss_clean|min_length[1]|max_length[20]|alpha');

			if ($this->form_validation->run() == FALSE) {
				return array('error' => validation_errors());
			} else {
				extract($_POST);
				$allowed_types = array('renters', 'landlords', 'advertisers', 'renters');
				if(in_array(strtolower($user_type), $allowed_types)) {
					$validSearch = true;
					if(!empty($searchBy)) {
						$allowed_types = array('user', 'name', 'email', 'phone', 'city', 'state', 'zip');
						if(in_array($searchBy, $allowed_types)) {
							$validSearch = false;
						}
					}
					
					if($validSearch) {
						$data = array(
							'searchFor' => $searchFor,
							'source' => $source,
							'searchBy' => $searchBy
						);
					
						if($user_type=='Renters') {
							$result = $this->search_for_renter($data);
						}
						if($user_type=='landlords') {
							$result = $this->search_for_landlords($data);
						}
						
						
						return $result;
					} else {
						return array('error'=>'Invalid search by selection');
					}
				} else {
					return array('error' => 'Invalid user type selection');
				}
			}		
		}
		
		private function search_for_renter($data)
		{
			if($data['searchBy'] == 'username' || $data['searchBy'] == 'Name' || $data['searchBy'] == 'Email' || $data['searchBy'] == 'Phone') {
				
				if($data['searchFor'] != '' || $data['searchBy'] != '') {
					$this->db->like($data['searchBy'], $data['searchFor']);
				} elseif(!empty($data['source'])) {
					$this->db->where('source', $data['source']);
				} else {
					return false;
				}
				
				$results = $this->db->get('renters');
				print_r($results->result());
				
			} elseif($data['searchBy'] == 'City' || $data['searchBy'] == 'State' || $data['searchBy'] == 'Zip') {
				
			} else {
				$results = $this->db->get_where('renters', array('hear'=>$data['source']));
				if($results->num_rows()>0) {
					return $results->result();
				}
				return false;
			}
			
			
		}
		
	}