<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Ad_handler extends CI_Model {

        public function __construct() {
            parent::__construct();
        }
		
		public function get_ads()
		{
			$this->db->select('id, zip,service_type,impressions,clicks,deactivation_date');
			$results = $this->db->get_where('contractor_zip_codes', array('contractor_id'=>$this->session->userdata('user_id'), 'active'=>'y', 'purchased'=>'y'));
			if($results->num_rows()>0) {
				$this->load->library('table');
				$data = array();
				$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14=>'Pest Control &#124; Exterminator'); 
				$count = 1;
				foreach ($results->result_array() as $row) {
					$id = $row['id'];
					$row['id'] = $count++;
					$row['deactivation_date'] = date('m-d-Y', strtotime($row['deactivation_date']));
					$row['service_type'] = $services_array[$row['service_type']];
					$row['button'] = '<div class="text-right"><a href="'.base_url().'/network/contractor/edit-ad/'.$id.'/" class="btn btn-success btn-md"><i class="fa fa-gears"></i></a></div>';
					$data[] = $row;
				}
				$this->table->set_heading('#', 'Zip', 'Service Type', 'Impressions', 'Clicks', 'Expires On', '<div class="text-right">Manage</div>');
				
				return $this->formate_data_table($data);
			} else {
				return false;
			}
		}
		
		public function past_ads()
		{
			$this->db->select('id, zip,service_type,impressions,clicks,deactivation_date');
			$results = $this->db->get_where('contractor_zip_codes', array('contractor_id'=>$this->session->userdata('user_id'), 'active'=>'n', 'purchased'=>'y'));
			if($results->num_rows()>0) {
				$this->load->library('table');
				$data = array();
				$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14=>'Pest Control &#124; Exterminator'); 
				$count = 1;
				foreach ($results->result_array() as $row) {
					$id = $row['id'];
					$row['id'] = $count++;
					$row['deactivation_date'] = date('m-d-Y', strtotime($row['deactivation_date']));
					$row['service_type'] = $services_array[$row['service_type']];
					//$row['button'] = '<div class="text-right"><a href="'.base_url().'/network/contractor/edit-ad/'.$id.'/" class="btn btn-success btn-md"><i class="fa fa-gears"></i></a></div>';
					$data[] = $row;
				}
				$this->table->set_heading('#', 'Zip', 'Service Type', 'Impressions', 'Clicks', 'Expired On');
				
				return $this->formate_data_table($data);
			} else {
				return false;
			}
		}
		
		public function available_zips($zip, $serviceType)
		{
			
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
					
						$this->db->select('zip, service_type, contractor_id');
						$this->db->where('zip', $row->zipCode);
						$this->db->where('service_type', $serviceType);
						$this->db->where('active', 'y');
						$this->db->where('purchased', 'y');
						$q = $this->db->get_where('contractor_zip_codes');
						if($q->num_rows()>2) {
						
							$row->taken = true;
							
						} else {
							$r = $q->row();
							$row->taken = false;
							$row->contractor_id = $r->contractor_id;
							if($r->contractor_id == $this->session->userdata('user_id')) {
								$row->dup = true;
							} else {
								$row->dup=false;
							}
						}
						$row->test1 = $zip;
	
						$row->serviceType = $serviceType;
						$zips[] = $row;
					}
					return $zips;
				} else {
					return false;
				}
				
            } else {
				return false;
			}
        }

		public function remove_zip_code_cart($data)
		{
			$index = '';
			$zips = $this->session->userdata('ad_zips');
			$service = $this->session->userdata('ad_service');		

			for($i=0;$i<count($zips);$i++) {
				if($zips[$i] == $data['zip']) {
					if($service[$i]==$data['service']) {
						$index = $i;
					}
				}
			}
	
			unset($zips[$index]);
			$this->session->set_userdata('ad_zips', array_values($zips));
			
			unset($service[$index]);
			$this->session->set_userdata('ad_service', array_values($service));
					
			$city = $this->session->userdata('ad_city');
			unset($city[$index]);
			$this->session->set_userdata('ad_city', array_values($city));
			
			$state = $this->session->userdata('ad_state');
			unset($state[$index]);
			$this->session->set_userdata('ad_state', array_values($state));
				
			return $index;
		}
	
		public function remove_zip_code_cart_create_account($data)
		{
			$index = '';
			$zips = $this->session->userdata('zips');
			$service = $this->session->userdata('service');		

			for($i=0;$i<count($zips);$i++) {
				if($zips[$i] == $data['zip']) {
					if($service[$i]==$data['service']) {
						$index = $i;
					}
				}
			}
	
			unset($zips[$index]);
			$this->session->set_userdata('zips', array_values($zips));
			
			unset($service[$index]);
			$this->session->set_userdata('service', array_values($service));
					
			$city = $this->session->userdata('city');
			unset($city[$index]);
			$this->session->set_userdata('city', array_values($city));
			
			$state = $this->session->userdata('state');
			unset($state[$index]);
			$this->session->set_userdata('state', array_values($state));
				
			return $index;
		}
	
		public function check_zip_create_account($data)
		{	
			if($data['service']<15) {
				$this->db->select('zipCode, city, stateAbv');
				$results = $this->db->get_where('zips', array('zipCode'=>$data['zip']));
				if($results->num_rows()>0) {
					$row = $results->row();
					$data['city'] = $row->city;



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
					
					
					$added = $this->add_zip_to_cart_create_account($data);
					return $added;
									
					
				} else {
					return '4'; // zip code not found
				}
			} else {
				return '3'; //Service not found
			}
		}
	
		public function check_zip($data)
		{	
			if($data['service']<15) {
				$this->db->select('zipCode, city, stateAbv');
				$results = $this->db->get_where('zips', array('zipCode'=>$data['zip']));
				if($results->num_rows()>0) {
					$row = $results->row();
					$data['city'] = $row->city;



					$data['state'] = $row->stateAbv;
					$zips = $this->session->userdata('ad_zips');
					$service = $this->session->userdata('ad_service');
					
					if(!empty($zips)) {
						for($i=0;$i<count($zips);$i++) {
							if($zips[$i]==$data['zip']) {
								if($service[$i] == $data['service']) {
									return '5'; // Zip Already Added
								}
							}
						}
					}
					
					$results = $this->db->get_where('contractor_zip_codes', array('zip'=>$data['zip'], 'service_type'=>$data['service'], 'active'=>'y'));
					if($results->num_rows()>2) {
						return '6'; // Zip Code For This Service Has Already Been Taken
					} else {
						$added = $this->add_zip_to_cart($data);
						return $added;
					}					
					
				} else {
					return '4'; // zip code not found
				}
			} else {
				return '3'; //Service not found
			}
		}
		
		public function pull_ad($id) 
		{
			$this->db->select();
			$results = $this->db->get_where('contractor_zip_codes', array('id'=>$id, 'contractor_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}
		}
		
		public function add_zip_to_cart_create_account($data) 
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
				$this->session->set_userdata('city', array($data['city']));
				$this->session->set_userdata('state', array($data['state']));
			}	
		
			return "43";
		}
		
		public function add_zip_to_cart($data) 
		{		

			$zips = $this->session->userdata('ad_zips');
			if(!empty($zips)) {
			
				$zips[] = $data['zip'];
				foreach($zips as $val) {
					$new_zips[] = $val;
				}
				$this->session->set_userdata('ad_zips', $new_zips);
				
				$service = $this->session->userdata('ad_service');
				$service[] = $data['service'];
				foreach($service as $val) {
					$service_array[] = $val;
				}
				$this->session->set_userdata('ad_service', $service_array);
								
				$city = $this->session->userdata('ad_city');
				$city[] = $data['city'];
				foreach($city as $val) {
					$new_city[] = $val;
				}
				$this->session->set_userdata('ad_city', $new_city);
				
				$state = $this->session->userdata('ad_state');
				$state[] = $data['state'];
				foreach($state as $val) {
					$new_state[] = $val;
				}
				$this->session->set_userdata('ad_state', $new_state);
				
			} else {
				$this->session->set_userdata('ad_zips', array($data['zip']));
				$this->session->set_userdata('ad_service', array($data['service']));
				$this->session->set_userdata('ad_city', array($data['city']));
				$this->session->set_userdata('ad_state', array($data['state']));
			}	
		
			return "43";
		}
		
		public function edit_ad($data)
		{
			if((int)$data['id']>0) {
				$this->db->select('service_type, ad_image');
				$r = $this->db->get_where('contractor_zip_codes', array('id'=>$data['id'], 'contractor_id'=>$this->session->userdata('user_id')));
				if($r->num_rows()>0) {
					$current = $r->row();
				} else {
					return array('error'=>'Error, no ad found. try again');
				}
				$update = true;
				if(!empty($data['file']['name'])) {
					$img = $this->uploadImage($data['file']);
					if(isset($img['error'])) {
						$update = false;
					} else { 
						$data['ad_image'] = $img['success'];
					}
				} else {
					$data['ad_image'] = $current->ad_image;
				}
				
				if($update === true) {
					$applyTo = $data['apply_post'];
					$id = $data['id'];
					unset($data['apply_post']);
					unset($data['file']);
					unset($data['id']);
					$data['phone'] = preg_replace("/[^A-Za-z0-9]/", '', $data['phone']);
					if($applyTo == 1) {
						$this->db->where('id', $id);
					} else if ($applyTo == 2) {
						$this->db->where('service_type', $current->service_type);
					}
					$this->db->where('contractor_id', $this->session->userdata('user_id'));
					$this->db->where('active', 'y');
					$this->db->where('purchased', 'y');
					$this->db->update('contractor_zip_codes', $data);
				
					return true;
					
				} else {
					return $img['error'];
				}
			} else {
				return 'Error updating ad, try again';
			}
		}
		
		
		//PRIVATE FUNCTIONS BELOW
		
		private function uploadImage($image) 
		{
			if(!empty($image['name'])) {
				$config['upload_path'] = './contractor-images/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size'] = '5000KB';
				$this->load->library('upload', $config);
				
				$file = "file";
				
				if($this->upload->do_upload($file)) {
					$upload = $this->upload->data();
					$file = $upload['file_name'];
					$max_height = 400;
					$max_width = 450;
					if ($upload['image_width']>$max_width || $upload['image_height']>$max_height) {
						// Resize The Image
						$config['image_library'] = 'GD2'; 
						$config['source_image']	= FCPATH.'contractor-images/'.$file;
						$config['maintain_ratio'] = TRUE;
						$config['width']	 = 450;
						$config['height']	= 400;

						$this->load->library('image_lib', $config);
						$this->image_lib->resize();
						$this->image_lib->initialize($config);
					}
					
					$feedback = array('success' => $file);
					$_POST['image'] = $file;
				} else {
					$feedback = array('error' => $this->upload->display_errors());
				}
			} else {
				$feedback = array('error'=>'Image not uploaded, invalid file name.');
			}
			return $feedback;
		}
		
		private function formate_data_table($data){
			$tmpl = array (
				'table_open'          => '<div class="table-responsive"><table class="table table-striped table-condensed" border="0" cellpadding="4" cellspacing="0">',

				'heading_row_start'   => '<tr>',
				'heading_row_end'     => '</tr>',
				'heading_cell_start'  => '<th>',
				'heading_cell_end'    => '</th>',

				'row_start'           => '<tr>',
				'row_end'             => '</tr>',
				'cell_start'          => '<td>',
				'cell_end'            => '</td>',

				'row_alt_start'       => '<tr>',
				'row_alt_end'         => '</tr>',
				'cell_alt_start'      => '<td>',
				'cell_alt_end'        => '</td>',

				'table_close'         => '</table></div>'
			);

			$this->table->set_template($tmpl);
			$table = $this->table->generate($data);
			return $table;
		}
		
		
	}
	
	
?>