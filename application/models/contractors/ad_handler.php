<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Ad_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
	
		function get_my_zips()
		{
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'contractor'));
			if($results->num_rows()>0) {
				$row = $results->row();
				$visits = $row->visits;
			} else {
				$visits = 0;
			}
				
			$results = $this->db->get_where('contractor_zips', array('contractor_id'=>$this->session->userdata('user_id'), 'active'=>'y'));
			if($results->num_rows()>0) {
				$results = $results->result();
				for($i=0;$i<count($results);$i++) {
					$query = $this->db->get_where('zips', array('zipCode'=>$results[$i]->zip_purchased));
					$r = $query->row();
					$results[$i]->city = $r->city;
					$results[$i]->state = $r->stateAbv;
					$results[$i]->price = $r->contractor_price;
					$results[$i]->visits = $visits;
					$this->db->select('id');
					$r = $this->db->get_where('contractor_ads', array('ref_id'=>$results[$i]->id));
					if($r->num_rows()>0) {
						$results[$i]->created = 'y'; 
					} else {
						$results[$i]->created = 'n';
					}
				}
				return $results;
			} else {
				return false;
			}
			
		}
		
		public function check_public_page()
		{
			$this->db->select('id');
			$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'contractor'));
			if($query->num_rows()>0) {
				return '1';
			} else {
				return '2';
			}
		}
		
		public function get_user_billing_details()
		{
			$this->db->select();
			$results = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
			return $results->row();
		}

    }


