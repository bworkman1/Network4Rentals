<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Zips_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        } 

        public function available_zips($zip, $service) {
            $this->db->select('longitude, latitude');
            $results = $this->db->get_where('zips', array('zipCode'=>$zip));
            if ($results->num_rows() > 0) {
                $row = $results->row();
                $radius = '10';
				
                $sql = 'SELECT zipCode, advertiser_price, stateAbv, city FROM zips WHERE (3958*3.1415926*sqrt((Latitude-'.$row->latitude.')*(Latitude-'.$row->latitude.') + cos(Latitude/57.29578)*cos('.$row->latitude.'/57.29578)*(Longitude-'.$row->longitude.')*(Longitude-'.$row->longitude.'))/180) <= '.$radius.' AND zipCode != "" AND stateAbv != "" AND city != ""';
                $result = $this->db->query($sql);
                if ($result->num_rows() > 0) {
					$zips = array();
					foreach ($result->result() as $row) {
						$this->db->where('zip_purchased', $row->zipCode);
						$this->db->where('service_purchased', $service);
						$q = $this->db->count_all_results('advertiser_zips');
						$row->taken = 3-$q;
						$row->service_side = $service;
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


