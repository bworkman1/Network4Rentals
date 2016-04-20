<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Shopping_cart extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
		
		public function check_zip($data)
		{	
			if($data['service']<15) {
				$this->db->select('advertiser_price, zipCode, city, stateAbv');
				$results = $this->db->get_where('zips', array('zipCode'=>$data['zip']));
				if($results->num_rows()>0) {
					$row = $results->row();
					$data['city'] = $row->city;
					$cost = explode('.', $row->advertiser_price);
					if($cost[0]!=$data['price']) {
						return '2'; // price doesn't match
					} else {
						$data['price'] = $row->advertiser_price;
						$data['state'] = $row->stateAbv;
						$zips = $this->session->userdata('zips');
						$service = $this->session->userdata('service');
						
						if(!empty($zips)) {
							for($i=0;$i<count($zips);$i++) {
								if($zips[$i]==$data['zip']) {
									if($service[$i] == $data['service']) {
										return '5'; // Zip Already Added
									}
								}
							}
						}
						$results = $this->db->get_where('advertiser_zips', array('zip_purchased'=>$data['zip']));
						if($results->num_rows()>2) {
							return '6'; // Zip Code For This Service Has Already Been Taken
						} else {
							$added = $this->add_zip_to_cart($data);
							return $added;
						}					
					}
				} else {
					return '4'; // zip code not found
				}
			} else {
				return '3'; //Service not found
			}
		}

		public function add_zip_to_cart($data) 
		{		

			$zips = $this->session->userdata('zips');
			if(!empty($zips)) {
			
				$zips[] = $data['zip'];
				foreach($zips as $val) {
					$new_zips[] = $val;
				}
				$this->session->set_userdata('zips', $new_zips);
				
				$service = $this->session->userdata('service');
				$service[] = $data['service'];
				foreach($service as $val) {
					$service_array[] = $val;
				}
				$this->session->set_userdata('service', $service_array);
				
				$price = $this->session->userdata('price');
				$price[] = $data['price'];
				foreach($price as $val) {
					$new_price[] = $val;
				}
				$this->session->set_userdata('price', $new_price);
				
				$city = $this->session->userdata('city');
				$city[] = $data['city'];
				foreach($city as $val) {
					$new_city[] = $val;
				}
				$this->session->set_userdata('city', $new_city);
				
				$state = $this->session->userdata('state');
				$state[] = $data['state'];
				foreach($state as $val) {
					$new_state[] = $val;
				}
				$this->session->set_userdata('state', $new_state);
				
			} else {
				$this->session->set_userdata('zips', array($data['zip']));
				$this->session->set_userdata('service', array($data['service']));
				$this->session->set_userdata('price', array($data['price']));
				$this->session->set_userdata('city', array($data['city']));
				$this->session->set_userdata('state', array($data['state']));
			}	
		
			return "43";
		}
		
		public function remove_zip_code_cart($data)
		{
			$index = '';
			$zips = $this->session->userdata('zips');
			$service = $this->session->userdata('service');		

			for($i=0;$i<count($zips);$i++) {
				if($zips[$i] == $data['zip']) {
					$index = $i;
				}
			}
	
			unset($zips[$index]);
			$this->session->set_userdata('zips', array_values($zips));
			
			unset($service[$index]);
			$this->session->set_userdata('service', array_values($service));
			
			$price = $this->session->userdata('price');
			unset($price[$index]);
			$this->session->set_userdata('price', array_values($price));
			
			$city = $this->session->userdata('city');
			unset($city[$index]);
			$this->session->set_userdata('city', array_values($city));
			
			$state = $this->session->userdata('state');
			unset($state[$index]);
			$this->session->set_userdata('state', array_values($state));
				
			return $index;
		}
		
    }


