<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Iso extends CI_Model {
		function iso()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function add_new($data) 
		{
			$this->db->insert('iso', $data);
			if($this->db->insert_id()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function get_isos()
		{
			$this->db->limit(1);
			$results = $this->db->get_where('iso', array('tenant_id'=>$this->session->userdata('user_id')));
			
			if($results->num_rows()>0) {
				$data = $results->result();
			} else {
				$data[0] = array(
					'tenant_id' => '',
					'bedrooms' => '',
					'bathrooms' => '',
					'active' => '',
					'rentFrom' => '',
					'rentTo' => '',
					'zip' => '',
					'central_air' => '',
					'laundry_hook_ups' => '',
					'off_site_laundry' => '',	
					'on_site_laundry' => '',
					'basement' => '',
					'single_lvl' => '',
					'shed' => '',
					'park' => '',
					'inside_city' => '',
					'outside_city' => '',
					'deck_porch' => '',
					'large_yard' => '',
					'fenced_yard' => '',			
					'partial_utilites' => '',
					'all_utilities' => '',
					'appliances' => '',
					'furnished' => '',
					'pool' => '',
					'shopping' => '',
					'garage' => '',
					'parking' => '',
					'pets' => '',
				); 
			
			}

			return array('table'=>$this->table_data($data), 'data'=>$data);
		}
		
		private function table_data($data)
		{			
			$this->load->library('table');
			$tmpl = array (
				'table_open'          => '<div class="table-responsive isoSelections"><table class="table table-striped" border="0" cellpadding="4" cellspacing="0">',

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
			$this->table->set_heading(array('Amenity', 'Would Like', 'Must Have'));
			
			//Unwanted keys in the data, for some reason when unset was used it would erase them from the array in another function
			$unwanted = array('id', 'tenant_id', 'ts', 'bedrooms', 'bathrooms', 'radius', 'active', 'rentFrom', 'rentTo', 'zip', 'sent_email');
			
			//LABELS FOR THE CHECKBOXS THAT COME FROM THE DATABASE
			$labels = array('Central Air', 'Clothes Washer / Dryer Hook-Ups', 'Offsite Laundry', 'Onsite Laundry', 'Basement', 'Single Level Floor Plan', 'Storage Shed', 'Near a Park', 'Within City Limits', 'Outside City Limits', 'Deck / Porch', 'Large Yard', 'Fenced Yard', 'Some Utilities Included', 'Utilities Included', 'Appliances Included', 'Fully Furnished', 'Pool', 'Shopping / Entertainment', 'Garage', 'Off Street Parking', 'Pets Allowed (Some Restrictions May Apply)');
			
			foreach($data as $key=>$val) {
				$c = 0;
				foreach($val as $k => $v) {
					if(!in_array($k, $unwanted)) {
						$check = '';
						$check2 = '';
						if($v==1) {
							$check = 'checked';
						} elseif($v==2) {
							$check2 = 'checked';
						}
						
						$this->table->add_row($labels[$c], '<input type="checkbox" class="'.$k.'" name="'.$k.'" value="1" '.$check.'/>', '<input type="checkbox" class="'.$k.'" name="'.$k.'" value="2" '.$check2.'/>');
						$c++;
					}	
				}	
			}              
			return $this->table->generate();	
		}
		
		function validate_iso_data()
		{
			$this->form_validation->set_rules('bedrooms', 'Bedrooms', 'trim|max_length[1]|xss_clean|required|integer');
			$this->form_validation->set_rules('bathrooms', 'Bathrooms', 'trim|max_length[1]|xss_clean|required|integer');
			$this->form_validation->set_rules('zip', 'Zip Code', 'trim|min_length[5]|max_length[500]|xss_clean|required');
			$this->form_validation->set_rules('rentTo', 'Rent From', 'trim|min_length[3]|max_length[5]|xss_clean|numeric|required');
			$this->form_validation->set_rules('rentFrom', 'Rent To', 'trim|min_length[3]|max_length[5]|xss_clean|numeric|required');
			
			$this->form_validation->set_rules('garage', 'Garage', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('pets', 'Pets', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('central_air', 'Central Air', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('laundry_hook_ups', 'Laundry Hook Ups', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('off_site_laundry', 'Off Site Laundry', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('on_site_laundry', 'On Site Laundry', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('parking', 'Off  Street Parking', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('basement', 'Basement', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('single_lvl', 'Single Level Floor Plan', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('shed', 'Shed', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('park', 'Near a Park', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('inside_city', 'Within City Limits', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('outside_city', 'Outside City Limits', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('deck_porch', 'Deck / Porch', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('large_yard', 'Large Yard', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('fenced_yard', 'Fenced Yard', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('partial_utilites', 'Some Utilities Included', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('all_utilities', 'Utilities Included', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('appliances', 'Appliances Included', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('furnished', 'Fully Furnished', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('pool', 'Pool', 'trim|max_length[1]|xss_clean|numeric');
			$this->form_validation->set_rules('shopping', 'Shopping / Entertainment', 'trim|max_length[1]|xss_clean|numeric');

			if($this->form_validation->run() == TRUE) {
				extract($_POST);
				if($this->validate_zip_codes($zip)) {
					$data = array(
						'rentTo' => $rentTo,
						'zip' => $zip,
						'bathrooms' => $bathrooms,
						'bedrooms' => $bedrooms,
						'rentFrom' => $rentFrom,
						'garage' => $garage,
						'pets' => $pets,
						'central_air' => $central_air,
						'laundry_hook_ups' => $laundry_hook_ups,
						'off_site_laundry' => $off_site_laundry,
						'on_site_laundry' => $on_site_laundry,
						'parking' => $parking,
						'basement' => $basement,
						'single_lvl' => $single_lvl,
						'shed' => $shed,
						'park' => $park,
						'inside_city' => $inside_city,
						'outside_city' => $outside_city,
						'deck_porch' => $deck_porch,
						'large_yard' => $large_yard,
						'fenced_yard' => $fenced_yard,
						'partial_utilites' => $partial_utilites,
						'all_utilities' => $all_utilities,
						'appliances' => $appliances,
						'furnished' => $furnished,
						'pool' => $pool,
						'shopping' => $shopping,
						'tenant_id' => $this->session->userdata('user_id')
					);
					$results = $this->db->get_where('iso', array('tenant_id'=>$this->session->userdata('user_id')));
					if($results->num_rows()>0) {
						//UPDATE ROW
						$this->db->limit(1);
						$this->db->where('tenant_id', $this->session->userdata('user_id'));
						$this->db->update('iso', $data);
						$results = $this->send_user_iso_matches();
						$this->load->model('special/add_activity');
						$this->add_activity->add_new_activity('Made Changes ISO', $this->session->userdata('user_id'), 'renters');
						if($results>0) {
							return array('success'=>'Your ISO was entered successfully. We found '.$results.' matching your search. These properties were sent to your email');
						} else {
							return array('success'=>'Your ISO was entered successfully. Any matching properties will be sent directly to your email');
						}
					} else {
						//INSERT ROW
						$this->db->limit(1);
						$this->db->where('tenant_id', $this->session->userdata('user_id'));
						$this->db->insert('iso', $data);
						$results = $this->send_user_iso_matches();
						
						$this->load->model('special/add_activity');
						$this->add_activity->add_new_activity('Added new ISO', $this->session->userdata('user_id'), 'renters');
						
						if($results>0) {
							return array('success'=>'Your ISO was entered successfully. We found '.$results.' matching your search. These properties were sent to your email');
						} else {
							return array('success'=>'Your ISO was entered successfully. Any matching properties will be sent directly to your email');
						}
					}
				} else {
					return array('error'=>'There was an invalid zip code in your selection, zip codes can only be 5 digit numbers and separated with a green box');
				}
				
			} else {
				return array('error'=>validation_errors());
			}
		}
		
		function delete_iso() 
		{
			$this->db->limit(1);
			$this->db->where('tenant_id', $this->session->userdata('user_id'));
			$this->db->delete('iso');
			if($this->db->affected_rows()>0) {
				$this->load->model('special/add_activity');
				$this->add_activity->add_new_activity('Deleted ISO', $this->session->userdata('user_id'), 'renters');
				return true;
			} else {
				return false;
			}
			
		}
				
		private function validate_zip_codes($zips)
		{
			$zips_array = explode('|', $zips);
			foreach($zips_array as $val) {
				if(!preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/',$val)) {
					return false;
				}
			}
			return true;
		}
		
		function check_matches() // Ran from cron job to check for iso matches
		{
			$today = date('Y-m-d');
			$yesterday = date('Y-m-d',strtotime($today . "-1 days")); // for sql statement to only show the ones that are new
			$this->db->limit(30);
			$this->db->select('iso.id, renters.email, renters.name, iso.*');
			$this->db->join('renters', 'iso.tenant_id = renters.id');
			$query = $this->db->get_where('iso', array('active'=>'y', 'sent_email'=>'n'));
			foreach($query->result() as $key => $val) {
				//SORT OUT THE ZIP CODES
				$zips_array = explode( '|', $val->zip);
				if(count($zips_array)>1) {
					$zipClause = "(";
					foreach($zips_array as $v) {
						if(!empty($v)) {
							$zipClause .= 'zipCode = "'.$v.'" || ';
						}
					}
					$zipClause = rtrim($zipClause, ' || ').')';
				} else {
					$zipClause = 'zipCode = '.$zips_array[0];
				}
				
				$options_array = array('central_air', 'laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'single_lvl', 'shed', 'park', 'inside_city', 'outside_city', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool', 'shopping', 'garage', 'parking', 'pets');
				
				$needs = array();
				$wants = array();
				$wants_sql_select = '';
				
				foreach($options_array as $option) {
					$wants_sql_select .= $option.', ';
					if(!empty($val->$option)) {
						if($val->$option==1) {
							$wants[] = $option;
						}
						
						if($val->$option==2) {
							$needs[] = $option;
						}
					}
				}
			
				$wants_sql_select = rtrim($wants_sql_select, ', ');

				$wants_lvl = 1;
				$needs_lvl = 2;
				
				if(!empty($needs)) {
					$needsClause = 'AND ';
					foreach($needs as $k => $v ) {
						$needsClause .= $v.' = "y" AND ';
					}
					$needsClause = rtrim($needsClause, ' AND ');
				}
				
				if(!empty($wants)) {
					$wantsClause = 'AND ';
					foreach($wants as $k => $v ) {
						$wantsClause .= $v.' = y AND ';
					}
					$wantsClause = rtrim($wantsClause, ' AND ');
				}
				
				$bed_mod = $val->bedrooms -1;
				$bath_mod = $val->bathrooms -1;
				
				$duplicate_id = array(); // HOLDS ALL LISTING IDS AND MAKE SURE THERE ARE NO DUPLICATES
				
				/* NEEDS HANDLER (******WHEN READY ADD ACTIVE = 'Y' AT THE END OF THE QUERY*******) */
				$needs_list = array();
				
				$sql_needs = 'SELECT id, title, bedrooms, bathrooms FROM listings WHERE bedrooms > '.$bed_mod.' AND bathrooms > '.$bath_mod.' AND price <= '.$val->rentTo.' AND price >= '.$val->rentFrom.' AND '.$zipClause. ' '.$needsClause.' AND active = "y" AND lastmodified >= "'.$yesterday.'"';
				
				$q = $this->db->query($sql_needs);
				if($q->num_rows()>0) {
					foreach ($q->result() as $row) {
						$duplicate_id[] = $row->id;
						$needs_list[] = array(
							'listing_id'=>$row->id,
							'title'=>$row->title,
							'bedrooms'=>$row->bedrooms,
							'bathrooms'=>$row->bathrooms,
						);
					}
				}
				
				/* ENDS NEEDS HANDLER */			
				/* WANTS HANDLER (******WHEN READY ADD ACTIVE = 'Y' AT THE END OF THE QUERY*******)*/
				
				$wants_list = array();
				if(!empty($wants)) {
					$sql_wants = 'SELECT id, title, bedrooms, bathrooms, '.$wants_sql_select.' FROM listings WHERE bedrooms > '.$bed_mod.' AND bathrooms > '.$bath_mod.' AND price <= '.$val->rentTo.' AND price >= '.$val->rentFrom.' AND '.$zipClause.' AND active = "y" AND lastmodified >= "'.$yesterday.'"';
					$q = $this->db->query($sql_wants);
				
					if($q->num_rows()>0) {
						foreach ($q->result() as $row) {
					
							$points = 0;
							foreach($options_array as $v) {
								//echo 'Does '.$row->id.' this = y? '.$row->$v.'<br>';
								if($row->$v == 'y') {
									if(in_array($v, $wants)) {
										$points++;
									}
								}
							}
						
							$total_wants = count($wants); //TOTAL AMOUT OF AMINITES THE USER WANTED
							$percentage = round($points / ($total_wants / 100),2); //PERCENTAGE OF WANTS MET
							
							echo 'Wants: '.$total_wants. ' Points: '.$points.' Percentage: '.$percentage.'<br>';
							
							if($percentage>70) {
								if(!in_array($row->id, $duplicate_id)) {
									$wants_list[] = array(
										'listing_id'=>$row->id,
										'match_percent'=>$percentage,
										'title'=>$row->title,
										'bedrooms'=>$row->bedrooms,
										'bathrooms'=>$row->bathrooms,
										'wants'=>$total_wants,
										'matches'=>$points
									);
								}
							}
						}
					}
				}
				
				/* ENDS WANTS HANDLER */
		
				
				if(!empty($wants_list) || !empty($needs_list)) {
					$this->send_available_listings($wants_list, $needs_list, $val->email, $val->name);
					
					$this->db->where('id', $val->id);
					$this->db->update('iso', array('sent_email'=>'y'));
				}
				
				
				
				
				/*
					$wants_list  HOLDS THE VALUES OF THE LISTINGS THAT ARE WHAT THEY WANT
					$needs_list HOLDS THE VALUES OF THE LISTINGS THAT ARE WHAT THEY NEED
					
					NEXT STEP IS TO CREATE THE EMAIL AND LOOP THROUGH THOSE SENDING AN EMAIL TO THE PEOPLE IN THAT LIST
				*/
				
			} //END OF FOREACH USER ISO
				
		}
		
		function reset_iso() 
		{
			$this->db->update('iso', array('sent_email'=>'n'));
		}
		
		private function send_user_iso_matches()
		{
			$today = date('Y-m-d');
			$yesterday = date('Y-m-d',strtotime($today . "-1 days")); // for sql statement to only show the ones that are new
			$this->db->limit(1);
			$this->db->select('iso.id, renters.email, renters.name, iso.*');
			$this->db->join('renters', 'iso.tenant_id = renters.id');
			$query = $this->db->get_where('iso', array('active'=>'y', 'tenant_id'=>$this->session->userdata('user_id')));
			$val = $query->row();

			//SORT OUT THE ZIP CODES
			$zips_array = explode( '|', $val->zip);
			if(count($zips_array)>1) {
				$zipClause = "(";
				foreach($zips_array as $v) {
					if(!empty($v)) {
						$zipClause .= 'zipCode = "'.$v.'" || ';
					}
				}
				$zipClause = rtrim($zipClause, ' || ').')';
			} else {
				$zipClause = 'zipCode = '.$zips_array[0];
			}
			
			$options_array = array('central_air', 'laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'single_lvl', 'shed', 'park', 'inside_city', 'outside_city', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool', 'shopping', 'garage', 'parking', 'pets');
				
			$needs = array();
			$wants = array();
			$wants_sql_select = '';
				
			foreach($options_array as $option) {
				$wants_sql_select .= $option.', ';
				if(!empty($val->$option)) {
					if($val->$option==1) {
						$wants[] = $option;
					}
					
					if($val->$option==2) {
						$needs[] = $option;
					}
				}
			}

			$wants_sql_select = rtrim($wants_sql_select, ', ');

			$wants_lvl = 1;
			$needs_lvl = 2;
			
			if(!empty($needs)) {
				$needsClause = 'AND ';
				foreach($needs as $k => $v ) {
					$needsClause .= $v.' = "y" AND ';
				}
				$needsClause = rtrim($needsClause, ' AND ');
			}
				
			if(!empty($wants)) {
				$wantsClause = 'AND ';
				foreach($wants as $k => $v ) {
					$wantsClause .= $v.' = "y" AND ';
				}
				$wantsClause = rtrim($wantsClause, ' AND ');
			}
				
			$bed_mod = $val->bedrooms;
			$bath_mod = $val->bathrooms;
				
			$duplicate_id = array(); // HOLDS ALL LISTING IDS AND MAKE SURE THERE ARE NO DUPLICATES
				
			/* DREAMING HANDLER (******WHEN READY ADD ACTIVE = 'Y' AT THE END OF THE QUERY*******) */
			$dream_list = array();
			$sql_dream = 'SELECT id, title, bedrooms, bathrooms FROM listings WHERE bedrooms >= '.$bed_mod.' AND bathrooms >= '.$bath_mod.' AND price <= '.$val->rentTo.' AND price >= '.$val->rentFrom.' AND '.$zipClause. ' '.$needsClause.' '.$wantsClause.' AND active = "y"';
					
			$q = $this->db->query($sql_dream);
			if($q->num_rows()>0) {
				foreach ($q->result() as $row) {
					$duplicate_id[] = $row->id;
					$dream_list[] = array(
						'listing_id'=>$row->id,
						'title'=>$row->title,
						'bedrooms'=>$row->bedrooms,
						'bathrooms'=>$row->bathrooms,
					);
				}
			}				
			
			/* NEEDS HANDLER (MAY FIT YOUR NEEDS 100% MUST HAVE 50% WANTS) */
			$needs_list = array();
			$sql_needs = 'SELECT * FROM listings WHERE bedrooms >= '.$bed_mod.' AND bathrooms >= '.$bath_mod.' AND price <= '.$val->rentTo.' AND price >= '.$val->rentFrom.' AND '.$zipClause. ' '.$needsClause.' AND active = "y"';
					
			$q = $this->db->query($sql_needs);
			if($q->num_rows()>0) {
				foreach ($q->result() as $row) {
					foreach($options_array as $v) {
						if($row->$v == 'y') {
							if(in_array($v, $wants)) {
								$points++;
							}
						}
					}
					
					$total_wants = count($wants); //TOTAL AMOUT OF AMINITES THE USER WANTED
					$percentage = round($points / ($total_wants / 100),2); //PERCENTAGE OF WANTS MET
					
						
					if($percentage>50) {
						if(!in_array($row->id, $duplicate_id)) {
							$duplicate_id[] = $row->id;
							$needs_list[] = array(
								'listing_id'=>$row->id,
								'title'=>$row->title,
								'bedrooms'=>$row->bedrooms,
								'bathrooms'=>$row->bathrooms,
							);
						}
					}
				}
			}

			
			/* ENDS WANTS HANDLER */				
			if(!empty($dream_list) || !empty($needs_list)) {
				
				$this->send_available_listings($dream_list, $needs_list, $val->email, $val->name);
				$this->db->where('id', $val->id);
				$this->db->update('iso', array('sent_email'=>'y'));
				return count($dream_list)+count($needs_list);
			}
			return 0;
		}
		
		private function send_available_listings($dream_list, $needs_list, $email, $name) 
		{	

			$name_array = explode(' ', $name);
			$msg = '<h2>Dear '.$name_array[0].',</h2>';
			$msg .= '<p>We have located one or more available properties in our system that may match what you are looking for. Please click on the link below to view suggested properties and landlord contact info.</p>';
			
			if(!empty($dream_list)) {
				$msg .= '<h3>Possible Dream Home</h3>';
				$msg .= '<table width="100%">';
					$msg .= '<tr>';
						$msg .= '<th align="left">Title</th>';
						$msg .= '<th>Bedrooms</th>';
						$msg .= '<th>Bathrooms</th>';
						$msg .= '<th align="right">Link</th>';
					$msg .= '</tr>';
					for($i=0;$i<count($dream_list);$i++) {
						$msg .= '<tr>';
							$msg .= '<td align="left">'.htmlspecialchars($dream_list[$i]['title']).'</td>';
							$msg .= '<td align="center">'.htmlspecialchars($dream_list[$i]['bedrooms']).'</td>';
							$msg .= '<td align="center">'.htmlspecialchars($dream_list[$i]['bathrooms']).'</td>';
							$msg .= '<td align="right"><a href="https://network4rentals.com/network/listings/view-listing/'.htmlspecialchars($dream_list[$i]['listing_id']).'">View Listing</a></td>';
						$msg .= '</tr>';
					}
				$msg .= '</table>';
			}
			
			if(!empty($needs_list)) {
				$msg .= '<h3>May Fit Your Needs</h3>';
				$msg .= '<table width="100%">';
					$msg .= '<tr>';
						$msg .= '<th align="left">Title</th>';
						$msg .= '<th>Bedrooms</th>';
						$msg .= '<th>Bathrooms</th>';
						$msg .= '<th align="right">Link</th>';
					$msg .= '</tr>';
					for($i=0;$i<count($needs_list);$i++) {
						$msg .= '<tr>';
							$msg .= '<td>'.htmlspecialchars($needs_list[$i]['title']).'</td>';
							$msg .= '<td align="center">'.htmlspecialchars($needs_list[$i]['bedrooms']).'</td>';
							$msg .= '<td align="center">'.htmlspecialchars($needs_list[$i]['bathrooms']).'</td>';
							$msg .= '<td align="right"><a href="https://network4rentals.com/network/listings/view-listing/'.htmlspecialchars($needs_list[$i]['listing_id']).'">View Listing</a></td>';
						$msg .= '</tr>';
					}
				$msg .= '</table>';
				$msg .= '<hr>';
			}
			$subject = 'We have rental homes that match your needs';
			$this->load->model('special/send_email');
			$this->send_email->sendEmail($email, $msg, $subject);
			
		}
		
		
		
	}