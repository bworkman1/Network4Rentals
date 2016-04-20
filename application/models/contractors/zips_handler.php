<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Zips_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        } 

        public function available_zips($zip, $serviceType) {
            $this->db->select('longitude, latitude');
            $results = $this->db->get_where('zips', array('zipCode'=>$zip));
            if ($results->num_rows() > 0) {
                $row = $results->row();
                $radius = '10';
				$serviceType = strtolower($serviceType);
                $sql = 'SELECT zipCode, contractor_price, stateAbv, contractor_price, city FROM zips WHERE (3958*3.1415926*sqrt((Latitude-'.$row->latitude.')*(Latitude-'.$row->latitude.') + cos(Latitude/57.29578)*cos('.$row->latitude.'/57.29578)*(Longitude-'.$row->longitude.')*(Longitude-'.$row->longitude.'))/180) <= '.$radius.' AND zipCode != "" AND stateAbv != "" AND city != ""';
                $result = $this->db->query($sql);
                if ($result->num_rows() > 0) {
					$zips = array();
					foreach ($result->result() as $row) {
						$this->db->where('zip_purchased', $row->zipCode);
						$this->db->where('service_purchased', $serviceType);
						$q = $this->db->count_all_results('contractor_zips');
						$row->taken = 3-$q;
						$row->serviceType = ucwords($serviceType);
						$zips[] = $row;
					}
					return json_encode($zips);
				} else {
					return false;
				}
				
            } else {
				return false;
			}
        }
		
		//New Function for new build, remove above function when new one goes live
		public function show_zips($zip, $serviceType) {
            $this->db->select('longitude, latitude');
            $results = $this->db->get_where('zips', array('zipCode'=>$zip));
            if ($results->num_rows() > 0) {
                $row = $results->row();
                $radius = '10';
                $sql = 'SELECT zipCode, contractor_price, stateAbv, contractor_price, city FROM zips WHERE (3958*3.1415926*sqrt((Latitude-'.$row->latitude.')*(Latitude-'.$row->latitude.') + cos(Latitude/57.29578)*cos('.$row->latitude.'/57.29578)*(Longitude-'.$row->longitude.')*(Longitude-'.$row->longitude.'))/180) <= '.$radius.' AND zipCode != "" AND stateAbv != "" AND city != ""';
                $result = $this->db->query($sql);
                if ($result->num_rows() > 0) {
					$zips = array();
					foreach ($result->result() as $row) {
						$this->db->where('zip_purchased', $row->zipCode);
						$this->db->where('service_purchased', $serviceType);
						$q = $this->db->count_all_results('contractor_zips');
						$row->serviceType = ucwords($serviceType);
						$zips[] = $row;
					}
					return json_encode($zips);
				} else {
					return false;
				}
				
            } else {
				return false;
			}
        }
		

    }


