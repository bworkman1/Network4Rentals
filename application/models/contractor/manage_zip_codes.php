<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Manage_zip_codes extends CI_Model {
				
        public function __construct() {

            parent::__construct();

        }
		
		public function addZip($data) //Takes values from ajax function add
		{
			if($data['service']<15) {
				$this->db->select('zipCode, city, stateAbv');
				$results = $this->db->get_where('zips', array('zipCode'=>$data['zip']));
				if($results->num_rows()>0) {
					$row = $results->row();
					$data['city'] = $row->city;
					$data['state'] = $row->stateAbv;
					
					$this->db->select('sub_id');
					$results = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
					if($results->num_rows()>0) {
						$row = $results->row();
						$sub_id = $row->sub_id;
					} else {
						$feed = array('error'=>'3');
					}
					
					$results = $this->db->get_where('contractor_zip_codes', array('contractor_id'=>$this->session->userdata('user_id'), 'service_type'=>$data['service'], 'zip'=>$data['zip'], 'sub_id'=>$sub_id));
					if($results->num_rows()>0) {
						$feed = array('error'=>'5');
					} else {
						$results = $this->db->insert('contractor_zip_codes', array('zip'=>$data['zip'], 'contractor_id'=>$this->session->userdata('user_id'), 'service_type'=>$data['service'], 'sub_id'=>$sub_id));
						if($this->db->insert_id()>0) {
							$this->load->model('contractor/activity_handler');
							$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14=>'Pest Control / Exterminator');
							$activity = 'Added Zip '.$data['zip'].'/'.$services_array[$data['service']].' to account';
							$this->activity_handler->insert_activity(array('action'=>$activity,'action_id'=>''));

							$feed = array('success'=>$this->db->insert_id()); 
						} else {
							$feed = array('error'=>'7');
						}
					}
					
				} else {
					$feed = array('error'=>'4'); // zip code not found
				}
			} else {
				$feed = array('error'=>'3'); //Service not found
			}
			return $feed;
			
		}
		
		public function get_current_zips()
		{
			$sub_id = $this->get_sub_id();
			$results = $this->db->get_where('contractor_zip_codes', array('contractor_id'=>$this->session->userdata('user_id'), 'sub_id'=>$sub_id));
			if($results->num_rows()>0) {
				foreach($results->result() as $row) {
					$this->db->select('city, stateAbv');
					$zip = $this->db->get_where('zips', array('zipCode'=>$row->zip));
					$zipData = $zip->row();
					$row->city = $zipData->city;
					$row->state = $zipData->stateAbv;
					$data[] = $row;
				}
				return $data;
			} else {
				return array('error'=>'Looks like you have not selected any zip codes.');
			}
		}
		
		private function get_sub_id()
		{
			$this->db->select('sub_id');
			$results = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row->sub_id;
			}
		}
		
		function remove_zip_code($data) 
		{
			$data['sub_id'] = $this->get_sub_id();
			$data['contractor_id'] = $this->session->userdata('user_id');
			$this->db->where('purchased !=', 'y');
			$this->db->limit(1);
			$results = $this->db->delete('contractor_zip_codes', $data);
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
	
		
    }


