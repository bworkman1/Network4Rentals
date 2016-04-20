<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Suggested_contractors extends CI_Model {
		
		private $contractorSameZipPts = 10; //If contractor is located in the same zip code ADDITIONAL 10 pts 
		private $contractorEverWorkedZipPts = 20; //Ever had a job in that area 20 pts
		private $contractorNotesPts = 10; //If a note has been left in the system in past 30 days ADDITIONAL 10 pts (possibly by notes total)
		private $landlordUsedContractorPts = 20; //Landlord used them in the last 90 days ADDITIONAL 20 pts (every time they used them)
		private $contractorJobsAtSitePts = 5; //Receive 5pts for every job they have done in the job site zip code (every time it hits)
		private $landlordEverUsedConPts = 50; //Has the landlord ever used this contractor 50 pts (EVER USED)
		private $totalPoints = 0;
		
        public function __construct() {
            parent::__construct();
        }
		
		function pull_suggestions($id)
		{
			$serviceRequestDetails = $this->service_request_details($id); //Holds all service request details **// NARROW DOWN CALL SELECTION

			if($serviceRequestDetails === false) {
				return false; //NO SERVICE REQUEST FOUND
				exit;
			}
	
			$elibleContractors = $this->get_eligible_contractors($serviceRequestDetails['service_type'], $serviceRequestDetails['rental_zip']);
			if($elibleContractors===false) {
				return false; //NO CONTRACTORS FOUND
				exit;
			}
			
			$scoreArray = array();
			
			foreach($elibleContractors as $key => $val) {
				$contractor_details = $this->get_contractor_details($val);
				if(!empty($contractor_details) && $contractor_details['id'] != 53) {
					$score = $this->contractorSameZipAsSite($contractor_details['zip'], $serviceRequestDetails['rental_zip']); // +10
					
					$score = $this->everHadAJobFromLandlord($serviceRequestDetails['link_id'], $val, $score); 
					$score = $this->everHadAJobInTheArea($val, $serviceRequestDetails['rental_zip'], $score) ;
					$score = $this->jobs_performed_in_zip($val, $serviceRequestDetails['rental_zip'], $score);
					
					$scoreArray[$key] = $score;
					
					$contractor_details['score'] = $score;
					$data[] = $contractor_details;
				}
			}
			
			usort($data, function ($item1, $item2) {
				if ($item1['score'] == $item2['score']) return 0;
				return $item1['score'] > $item2['score'] ? -1 : 1;
			});
			
			
			
			return $data;			
		}
		

		
		private function service_request_details($id)
		{
			$this->db->join('renter_history', 'renter_history.id = all_service_request.rental_id');
			$this->db->where('all_service_request.id', $id);
			$results = $this->db->get('all_service_request');
			if($results->num_rows()>0) {
				return $results->row_array();
			} else {
				$this->db->join('listings', 'listings.id = all_service_request.listing_id');
				$this->db->where('all_service_request.id', $id);
				$results = $this->db->get('all_service_request');
				if($results->num_rows()>0) {
					$data = $results->row_array();
					$data['rental_zip'] = $data['zipCode'];
					return $data;
				} else {
					return false;
				}
			}
		}
		
		private function used_contractor($contractor_id) 
		{
			//CHECKS IF THE LANDLORD HAS EVER USED THE CONTRACTOR BEFORE AND RETURNS A BOOLEN VALUE
			$landlord_id = $this->session->userdata('user_id');
			$results = $this->db->get_where('all_service_request', array('contractor_id'=>$contractor_id, 'landlord_id'=>$landlord_id));
			if($results->num_rows()>1) {
				return true;
			} else {
				return false;
			}
			
		}
		
		private function get_eligible_contractors($service_type, $zip)
		{
			$this->db->select('contractor_id');
			$this->db->group_by('contractor_id');
			$results= $this->db->get_where('contractor_zip_codes', array('service_type'=>$service_type, 'zip'=>$zip));
			if($results->num_rows()>0) {
				$contractors = array();
				foreach($results->result_array() as $val) {
					$contractors[] = $val['contractor_id'];
				}
				return $contractors;
			} else {
				return false;
			}
		}
		
		private function everHadAJobInTheArea($contractor_id, $site_zip, $points) 
		{
			$this->db->select('renter_history.id');
			$this->db->join('renter_history', 'renter_history.id = all_service_request.rental_id');
			$this->db->where('all_service_request.contractor_id', $contractor_id);
			$this->db->where('renter_history.rental_zip', $site_zip);
			$results = $this->db->get('all_service_request');
			
			if($results->num_rows()>1) {
				$count = $results->num_rows()-1;
				$bonusPoints = ($this->contractorEverWorkedZipPts*$count)+$points;
				return $bonusPoints;
			} else {
				return $points;
			}			
			
		}
		
		private function get_contractor_details($id) 
		{
			$this->db->select('contractors.id, contractors.zip, contractors.address, contractors.city, contractors.state, contractors.last_login, contractors.bName, contractors.email, landlord_page_settings.unique_name');
			$this->db->join('landlord_page_settings', 'landlord_page_settings.landlord_id = contractors.id');
			$results = $this->db->get_where('contractors', array('contractors.id'=>$id, 'contractors.active'=>'y', 'landlord_page_settings.type'=>'contractor'));
			if($results->num_rows()>0) {
				return $results->row_array();
			} else {
				return false;
			}
		}

		private function everHadAJobFromLandlord($landlord_id, $contractor_id, $points) 
		{	
			$this->db->select('submitted');
			$this->db->where('contractor_id', $contractor_id);
			$this->db->where('landlord_id', $landlord_id);
			$this->db->from('all_service_request');
			$results = $this->db->get();
			if($results->num_rows()>1) {
				$count = 0;
				foreach($results->result() as $val) {
					if(strtotime($val->submitted)<strtotime('-90 days')){
						
					} else {
						$count++;
					}
				}
				if($count>0) {
					$count = $count-1;
					$bonusPoints = $this->landlordUsedContractorPts*$count;
				} else {
					$bonusPoints = 0;
				}
				$points = ($this->landlordEverUsedConPts+$points)+$bonusPoints;
		
			}
			return $points;
		}
		
		private function jobs_performed_in_zip($id, $site_zip, $score)
		{
			$this->db->select('all_service_request.id');
			$this->db->from('all_service_request');
			$this->db->join('renter_history', 'renter_history.id = all_service_request.rental_id');
			$this->db->where('all_service_request.contractor_id', $id);
			$this->db->where('renter_history.rental_zip', $site_zip);
			$results = $this->db->get();
			$counter = $results->num_rows();
			if($counter>1) {
				$counter=$counter-1;
				$subTotal = $this->contractorJobsAtSitePts*$counter;
				$total = $subTotal+$score;
				return $total;
			} else {
				return $score;
			}
		}
		
		private function contractorSameZipAsSite($contractor_zip, $site_zip)
		{
			if($contractor_zip == $site_zip) {
				return $this->contractorSameZipPts;
			} else {
				return 0;
			}
		}
		
	}
	
	
	/*
		LOCATION: If contractor is located in the same zip code ADDITIONAL 10 pts  									X
		LOCATION: Ever had a job in that area 20 pts 																X
		
		NOTES: If a note has been left in the system in past 30 days ADDITIONAL 10 pts (possibly by notes total)    
		REQUESTS: Landlord used them in the last 90 days ADDITIONAL 20 pts (every time they used them)              X
		JOBS: Receive 5pts for every job they have done in the job site zip code (every time it hits)                      X
		
		LANDLORD: Has the landlord ever used this contractor 50 pts (EVER USED)  									X
	*/