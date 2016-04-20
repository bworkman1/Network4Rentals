<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Stats_handler extends CI_Model {	
			
        public function __construct()
		{
            parent::__construct();
        }
		
		function stats_data()
		{
			$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
			$data = array();
			$this->db->select('service_type, contractor_id, impressions, clicks, zip');
			$results = $this->db->get_where('contractor_zip_codes', array('contractor_id'=>$this->session->userdata('user_id'), 'active'=>'y', 'purchased'=>'y'));
			if($results->num_rows()>0) {	
				foreach($results->result() as $row) {
					$row->service_type = $services_array[$row->service_type];
					$row->label = $row->service_type.' - '.$row->zip;
					$data[] = $row;
				}
				return $data;
			} else {
				return false;
			}
		}
		
		function other_stats()
		{
			$data = array();
			$this->db->select('zip, service_type');
			$results = $this->db->get_where('contractor_zip_codes', array('contractor_id'=>$this->session->userdata('user_id'), 'active'=>'y'));
			foreach($results->result() as $val) {
				// $val->zip - $val->service_type;
				$this->db->select('renter_history.id, all_service_request.contractor_id');
				$this->db->from('all_service_request');
				$this->db->where('renter_history.rental_zip', $val->zip);
				$this->db->where('all_service_request.service_type', $val->service_type);
				$this->db->join('renter_history', 'all_service_request.rental_id = renter_history.id');
				$query = $this->db->get();
				
				$count = $query->num_rows();
				$myRequests = 0;
				foreach($query->result() as $v) {
					if($v->contractor_id == $this->session->userdata('user_id')) {
						$myRequests++;
					}
				}
				
				$data[] = array('zip'=>$val->zip, 'service_type'=>$val->service_type, 'total'=>$count, 'my_requests'=>$myRequests);
			}
			return $data;
		}
		
	}