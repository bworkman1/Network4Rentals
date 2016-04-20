<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Service_request_handler extends CI_Model {
		
		function service_request_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		 
		function get_admin_id($group_id) 
		{	
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				return $row->main_admin_id;
			}
		}
		
		public function addContractorDate($id)
		{
			$this->db->where('id', $id);
			$this->db->update('all_service_request', array('contractor_received'=>date('Y-m-d H:i:s')));
		}
		
		public function markRequestIncomplete($id) 
		{
			$this->db->where('id', $id);
			$this->db->limit(1);
			$this->db->where('landlord_id', $this->session->userdata('user_id'));
			$this->db->update('all_service_request', array('complete' => 'n'));
			if($this->db->affected_rows()>0) {
				return true;
			}
			return false;
		}
		
		function search($data)
		{
			foreach($data as $key => $val) {
				if(!empty($val)) {
					switch($key) { 
						case "start_date":
							$val = date('Y-m-d', strtotime($val));
							$this->db->where('submitted >', $val);
							break;
						case "end_date":
							$val = date('Y-m-d', strtotime($val));
							$this->db->where('submitted <', $val);
							break;
						case "address":
							$this->db->where('listing_id', $val);
							break;						
						case "service_type":
							$this->db->where('service_type', $val);
							break;
					}
				}
			} 
			
		
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->session->userdata('temp_id');
				$this->db->where('group_id', $id);
			} else {
				$id = $this->session->userdata('user_id');
				$this->db->where('landlord_id', $id);
			}
			
			
			$this->db->order_by('id','desc');
			$query = $this->db->get_where('all_service_request', array('reoccurring'=>'n'));
			foreach ($query->result_array() as $row) {
				if(!empty($row['rental_id'])) {
					$q = $this->db->get_where('renter_history', array('id'=>$row['rental_id']));
					$r = $q->row_array();
					$row['address'] = $r['rental_address'].' '.$r['rental_city'].' '.$r['rental_state'];
					$results[] = $row;
				} else {
					$q = $this->db->get_where('listings', array('id'=>$row['listing_id']));
					$r = $q->row_array();
					$row['address'] = $r['address'].' '.$r['city'].' '.$r['stateAbv'];
					$results[] = $row;
				}
			}
			
			return $results;
		}
		
		function my_group_ids() // Get all the ids of the admin_groups with current logged in id
		{
			$temp_id = $this->session->userdata('temp_id');
			if(empty($temp_id)) {
				$id = $this->session->userdata('user_id');
			} else {
				$id = $this->session->userdata('temp_id');
			}	
			$this->db->select('id');
			$query = $this->db->get_where('admin_groups', array('sub_admins' => $id));
			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$ids[] = $row['id'];
				}
				return $ids;
			}
		}
		
		function service_request_counter()
		{
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
				$this->db->where('group_id !=', '');
			} else {
				$id = $this->session->userdata('user_id');
				$this->db->where('group_id', NULL);
			}
			$this->db->select('id');
			$this->db->where('landlord_id', $id);
			$this->db->where('reoccurring', 'n');
			$this->db->from('all_service_request');
		
			$counter = $this->db->count_all_results();
			return $counter;
		}

		function complete_service_request_counter()
		{
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
				$this->db->where('group_id !=', '');
			} else {
				$id = $this->session->userdata('user_id');
				$this->db->where('group_id', NULL);
			}
			$this->db->select('id');
			$this->db->where('landlord_id', $id);
			$this->db->where('complete', 'y');
			$this->db->where('reoccurring', 'n');
			$this->db->from('all_service_request');
		
			$counter = $this->db->count_all_results();
			return $counter;
		}		
		
		function show_all_service_request($per, $page, $status=null)
		{
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
				$group = true;
			} else {
				$id = $this->session->userdata('user_id');
				$group = false;
			}
			$this->db->select('submitted, tenant_id, rental_id, service_type, id, landlord_id, complete, cost, listing_id');
			if($group) {
				$this->db->where('group_id', $this->session->userdata('temp_id'));
			} else {
				$this->db->where('landlord_id', $id);
				$this->db->where('group_id', NULL);
			}
			
			if(!empty($status)) {
				$this->db->where('complete', $status);
			}
			$this->db->where('reoccurring', 'n');
			$this->db->order_by('id', 'desc');
			$this->db->limit($per, $page);
			$query = $this->db->get('all_service_request');
			
			if ($query->num_rows() > 0) {
				$service_requests = $query->result_array();
				
				for($i=0;$i<count($service_requests);$i++) {
					if(!empty($service_requests[$i]['listing_id'])) {
						$query = $this->db->get_where('listings', array('id'=>$service_requests[$i]['listing_id']));
						if ($query->num_rows() > 0) {
							$r = $query->row_array();
							$service_requests[$i]['address'] = $r['address'].' '.$r['city'];
						}
					} else {
						$q = $this->db->get_where('renter_history', array('id' => $service_requests[$i]['rental_id']));
						$row = $q->row_array(); 
						$service_requests[$i]['address'] = $row['rental_address'].' '.$row['rental_city'];
					}
				}
				return $service_requests;
			} else {
				return false;
			}
		}	
		
		function unread_service_request() 
		{
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->session->userdata('temp_id');
				$this->db->where('group_id', $id);
			} else {
				$id = $this->session->userdata('user_id');
				$this->db->where('landlord_id', $id);
			}
			$this->db->from('all_service_request');
			$this->db->where('reoccurring', 'n');
			$this->db->where('viewed', '0000-00-00 00:00:00');
			$results = $this->db->count_all_results();
			return $results;
		}
		
		function new_service_request_counter()
		{
			$my_groups = $this->my_group_ids(); // Group id array
			
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
			} else {
				$id = $this->session->userdata('user_id');
			}	
			
			$this->db->select('id');
			$this->db->where('landlord_id', $id);
			$this->db->where('reoccurring', 'n');
			$this->db->where('viewed', '0000-00-00 00:00:00');
			if(!empty($my_groups)) {
				foreach($my_groups as $key => $val) {
					$this->db->or_where('group_id', $val);
				}
			}
			$this->db->from('all_service_request');
		
			$counter = $this->db->count_all_results();
			return $counter;
		}

		function show_all_new_service_request($per, $page)
		{				
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
			} else {
				$id = $this->session->userdata('user_id');
			}
			$this->db->select('submitted, tenant_id, rental_id, listing_id, service_type, id, landlord_id');
			$this->db->where('landlord_id', $id);
			$this->db->where('viewed', '0000-00-00 00:00:00');
			$this->db->where('reoccurring', 'n');
			$this->db->order_by('submitted', 'DESC');
			$this->db->limit($per, $page);
			$query = $this->db->get('all_service_request');
			
			if ($query->num_rows() > 0) {
				$service_requests = $query->result_array();
				for($i=0;$i<count($service_requests);$i++) {
					if(!empty($service_requests[$i]['listing_id'])) {
						$query = $this->db->get_where('listings', array('id'=>$service_requests[$i]['listing_id']));
						if ($query->num_rows() > 0) {
							$r = $query->row_array();
							$service_requests[$i]['address'] = $r['address'].' '.$r['city'];
						}
					} else {
						$q = $this->db->get_where('renter_history', array('id' => $service_requests[$i]['rental_id']));
						$row = $q->row_array(); 
						$service_requests[$i]['address'] = $row['rental_address'].' '.$row['rental_city'];
					}
				}
				return $service_requests;
			} else {
				return false;
			}
			
		}	
		
		function new_service_requests()
		{
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
			} else {
				$id = $this->session->userdata('user_id');
			}	
			$this->db->where('reoccurring', 'n');
			$this->db->from('all_service_request');
			$this->db->where('landlord_id', $id);
			$this->db->where('viewed', '0000-00-00 00:00:00');
			$results = $this->db->count_all_results();
			return $results;
		}
		
		function view_service_requests($request_id)
		{
			$group_id = '';
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
			} else {
				$id = $this->session->userdata('user_id');
			}
			$query = $this->db->get_where('all_service_request', array('landlord_id'=>$id, 'id'=>$request_id, 'reoccurring'=>'n'));
			if ($query->num_rows() > 0) {
				$service_requests = $query->row_array();
				
				if($service_requests['viewed'] == '0000-00-00 00:00:00') {
					$this->db->where('id', $request_id);
					$this->db->update('all_service_request', array('viewed' => date('Y-m-d H:i:s'))); 
					$service_requests['sendMail'] = true;
				} else {
					$service_requests['sendMail'] = false;
				}
		
				$service_listing_id = $service_requests['rental_id'];
				$service_requests['request_id'] = $request_id;
				//Grab Rental Home Info
				if(!empty($service_listing_id)) {
					$query = $this->db->get_where('renter_history', array('id' => $service_requests['rental_id']));
					$row = $query->row_array(); 
					
					$service_requests['address'] = $row['rental_address'];
					$service_requests['city'] = $row['rental_city'];
					$service_requests['state'] = $row['rental_state'];
					$service_requests['zip'] = $row['rental_zip'];
					$service_requests['checklist_id'] = $row['checklist_id'];
					$service_requests['listing_id'] = $row['listing_id'];
					$group_id = $row['group_id'];
				}
		
				//Grab Tenant Info
				if(!empty($service_requests['tenant_id'])) {
					$query = $this->db->get_where('renters', array('id' => $service_requests['tenant_id']));
					$row = $query->row_array(); 
					$service_requests['tenant_name'] = $row['name'];
					$service_requests['tenant_phone'] = $row['phone'];
					$service_requests['tenant_email'] = $row['email'];
					$service_requests['who'] = '1';
				} else {
					$query = $this->db->get_where('listings', array('id' => $service_requests['listing_id']));
					$row = $query->row_array(); 

					$service_requests['address'] = $row['address'];
					$service_requests['city'] = $row['city'];
					$service_requests['state'] = $row['stateAbv'];
					$service_requests['zip'] = $row['zipCode'];
					$service_requests['who'] = '0';
					
				}
				
				//Grab Landlord Info
				if($group_id>0) {
					$query = $this->db->get_where('admin_groups', array('id' => $group_id));
					$row = $query->row_array(); 
					$bName = $row['sub_b_name'];
					
					$query = $this->db->get_where('landlords', array('id' => $row['sub_admins']));
					$row = $query->row_array();
					$service_requests['landlord_email'] = $row['email'];			
					$service_requests['landlord_name'] = $row['name'];			
					$service_requests['landlord_phone'] = $row['phone'];					
					$service_requests['landlord_address'] = $row['address'];	
					$service_requests['landlord_alt_phone'] = $row['alt_phone'];	
					$service_requests['landlord_city'] = $row['city'];	
					$service_requests['landlord_state'] = $row['state'];	
					$service_requests['landlord_zip'] = $row['zip'];	
				} else {
					$query = $this->db->get_where('landlords', array('id' => $id));
					$row = $query->row_array();
					$service_requests['landlord_email'] = $row['email'];			
					$service_requests['landlord_name'] = $row['name'];			
					$service_requests['landlord_phone'] = $row['phone'];	
					$service_requests['landlord_address'] = $row['address'];					
					$bname = $row['bName'];			
					$service_requests['landlord_alt_phone'] = $row['alt_phone'];	
					$service_requests['landlord_city'] = $row['city'];	
					$service_requests['landlord_state'] = $row['state'];	
					$service_requests['landlord_zip'] = $row['zip'];	
				}
				if($group_id>0) {
					$service_requests['bName'] = $bName;	
				} else {
					$service_requests['bName'] = $bname;
				}
				
				
				
				$this->db->order_by('s_timestamp', 'desc');
				$query = $this->db->get_where('service_request_notes', array('ref_id' => $request_id));
				$notes = array();
				foreach ($query->result() as $row) {
					if($row->contractor_id>0) {
						$this->db->select('bName');
						$r = $this->db->get_where('contractors', array('id'=>$row->contractor_id));
						$c = $r->row();
						$contractor_name = $c->bName;
					} else {
						$contractor_name = '';
					}
					
					$add = true;
					if($row->visibility == 0) {
						if($row->landlord_id != $this->session->userdata('user_id')) {
							$add = false;
						}
					}
					if($add) {
						$notes[] = array(
							'note' => $row->note,
							'visibility' => $row->visibility,
							's_timestamp' => $row->s_timestamp,
							'contractor_image' => $row->contractor_image,
							'contractor_id' => $row->contractor_id,
							'landlord_id' => $row->landlord_id,
							'contractor_name' => $contractor_name
							
						);
					}
				}

				$service_requests['notes'] = $notes;
				if(!empty($service_requests['listing_id'])) {
					$query = $this->db->get_where('home_items', array('listing_id'=>$service_requests['listing_id'], 'service_type'=>$service_requests['service_type']));
					if ($query->num_rows() > 0) {
						foreach ($query->result() as $row) {
						
							$items[] = array(
								'desc' => $row->desc,
								'modal_num' => $row->modal_num,
								'brand' => $row->brand,
								'serial' => $row->serial,
								'service_type' => 	$row->service_type,	
								'image' => $row->image
							);
						}
						$service_requests['items'] = $items;
					}
					
					//Incomplete Service Requests
					//if(!empty($service_requests['rental_id'])) {
					//	$query = $this->db->get_where('all_service_request', array('rental_id'=>$service_requests['rental_id'], 'complete'=>'n', 'id !='=>$request_id));
					//} else {
						$query = $this->db->get_where('all_service_request', array('listing_id'=>$service_requests['listing_id'], 'complete'=>'n', 'id !='=>$request_id));
					//}
					
					if ($query->num_rows() > 0) {
						foreach ($query->result_array() as $row) {
							$service_requests['incomplete_requests'][] = $row;
						}
					} else {
						$service_requests['incomplete_requests'] = '';
					}
					
				}
				
				if($service_requests['sendMail']) {
					$this->load->model('special/send_email');
					$subject = 'Your landlord has viewed your service request';
					$message = '<h2>Service Request Viewed</h2>';
					$message .= '<p>We wanted to let you know, your service request has been viewed by your landlord.</p><table border="0" cellpadding="0" cellspacing="0" style="background-color:#4CC1EE; border:1px solid #353535; border-radius:5px;">
						<tr>
							<td align="center" valign="middle" style="color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; letter-spacing:-.5px; line-height:150%; padding-top:15px; padding-right:30px; padding-bottom:15px; padding-left:30px;">
								<a href="https://network4rentals.com/network/renters/view-request/'.$service_requests['request_id'].'" target="_blank" style="color:#FFFFFF; text-decoration:none;">View Service Request</a>
							</td>
						</tr>
					</table>';
					$this->send_email->sendEmail($service_requests['tenant_email'], $message, $subject);
					
					$this->load->model('special/add_activity');
					$action = 'Landlord Viewed Your Service Request';
					$this->add_activity->add_new_activity($action, $service_requests['tenant_id'], 'renters', $service_requests['request_id']);
				}
				
				return $service_requests;
			} else {
				return false;
			}
		}
		
		function mark_as_complete($data) 
		{
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
			} else {
				$id = $this->session->userdata('user_id');
			}
			$this->db->where("id", $data['id']);
			$this->db->where("landlord_id", $id);
			unset($data['id']);

			$this->db->update('all_service_request', $data);
			return true;
		}
			
		function add_note($data) 
		{
			$query = $this->db->insert('service_request_notes', $data); 
			if($query) {
				return true;
			} else {
				return false;
			}
		}
		
		function property_report($rental_id) 
		{
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
			} else {
				$id = $this->session->userdata('user_id');
			}
			
			$this->db->select('listing_id, rental_address, rental_city, rental_state, rental_zip');
			$query = $this->db->get_where('renter_history', array('link_id'=>$id, 'id'=>$rental_id, 'reoccurring'=>'n'));
			if ($query->num_rows() > 0) {
				$results = $query->row_array(); //This Gives Us The Address 
				$listing_id = $results['listing_id'];
				
				// Get All Tenants That Have Lived At This Property
				$this->db->select('id, tenant_id');
				$query = $this->db->get_where('renter_history', array('listing_id'=>$listing_id));
				if ($query->num_rows() > 0) {
					//Loop Through All Rental History For This Property And Grab The Tenant Ids
					$count = 0;
					foreach($query->result_array() as $row) {
						$this->db->select('id, name, email, phone');
						$q = $this->db->get_where('renters', array('id'=>$row['tenant_id']));
						if ($q->num_rows() > 0) {
							$tenant_info[] = $q->row_array(); // Tenant Info
						}	
						$tenant_info[$count]['history_id'] = $row['id'];		
						$count++;
					}
				}
				
				$query = $this->db->get_where('all_service_request', array('rental_id'=>$results['listing_id']));
				if ($query->num_rows() > 0) {
					$service_cost = 0;
					foreach($query->result_array() as $row) {
					
					
						if($row['completed'] != '0000-00-00 00:00:00') {
							$service_cost = $service_cost+(int)$row['cost']; //Total the cost of all the service request
						}
						$service_requests[] = $row;
					}
				}					
				
				
				//return $results;
			} else {
				$this->session->set_flashdata('error', 'Property Not Found, Try Again');
				redirect('landlords/view-service-request/'.$id);
				exit;
			}
		}
		
		function set_email_hash($data, $id) 
		{
			$this->db->limit(1);
			$this->db->where('id', $data['id']);
			$this->db->update('all_service_request', array('email_hash'=>$emailHash)); 
		}
		
		function view_service_requests_email($email_hash, $ids)
		{
			$query = $this->db->get_where('all_service_request', array('email_hash'=>$email_hash, 'landlord_id'=>$ids));
			if ($query->num_rows() > 0) {
				$service_requests = $query->row_array();
				
				//Grab Rental Home Info
				$query = $this->db->get_where('renter_history', array('id' => $service_requests['rental_id']));
				$row = $query->row_array(); 
				
				$service_requests['request_id'] = $service_requests['id'];
				$service_requests['address'] = $row['rental_address'];
				$service_requests['city'] = $row['rental_city'];
				$service_requests['state'] = $row['rental_state'];
				$service_requests['zip'] = $row['rental_zip'];
				$service_requests['checklist_id'] = $row['checklist_id'];
				$service_requests['listing_id'] = $row['listing_id'];
				$group_id = $row['group_id'];
				
				//Grab Tenant Info
				$query = $this->db->get_where('renters', array('id' => $row['tenant_id']));
				$row = $query->row_array(); 
				$service_requests['tenant_name'] = $row['name'];
				$service_requests['tenant_phone'] = $row['phone'];
				$service_requests['tenant_email'] = $row['email'];
				
				//Grab Landlord Info
				if($group_id>0) {
					$query = $this->db->get_where('admin_groups', array('id' => $group_id));
					$row = $query->row_array(); 
					$bName = $row['sub_b_name'];
					
					$query = $this->db->get_where('landlords', array('id' => $row['sub_admins']));
					$row = $query->row_array();
					$service_requests['landlord_email'] = $row['email'];			
					$service_requests['landlord_name'] = $row['name'];			
					$service_requests['landlord_phone'] = $row['phone'];					
					$service_requests['landlord_alt_phone'] = $row['alt_phone'];	
					$service_requests['landlord_city'] = $row['city'];	
					$service_requests['landlord_state'] = $row['state'];	
					$service_requests['landlord_zip'] = $row['zip'];	
				} else {
					$query = $this->db->get_where('landlords', array('id' => $id));
					$row = $query->row_array();
					$service_requests['landlord_email'] = $row['email'];			
					$service_requests['landlord_name'] = $row['name'];			
					$service_requests['landlord_phone'] = $row['phone'];			
					$bname = $row['bName'];			
					$service_requests['landlord_alt_phone'] = $row['alt_phone'];	
					$service_requests['landlord_city'] = $row['city'];	
					$service_requests['landlord_state'] = $row['state'];	
					$service_requests['landlord_zip'] = $row['zip'];	
				}
				if($group_id>0) {
					$service_requests['bName'] = $bName;	
				} else {
					$service_requests['bName'] = $bname;
				}
				
				$this->db->order_by('s_timestamp', 'desc');
				$query = $this->db->get_where('service_request_notes', array('ref_id' => $service_requests['request_id'], 'visibility'=>'1'));
				$notes = array();
				foreach ($query->result() as $row) {
					$notes[] = array(
						'note' => $row->note,
						'visibility' => $row->visibility,
						's_timestamp' => $row->s_timestamp
					);
				}
				$service_requests['notes'] = $notes;

				if(!empty($service_requests['listing_id'])) {
					$query = $this->db->get_where('home_items', array('listing_id'=>$service_requests['listing_id'], 'service_type'=>$service_requests['service_type']));
					if ($query->num_rows() > 0) {
						foreach ($query->result() as $row) {
							$items[] = array(
								'desc' => $row->desc,
								'modal_num' => $row->modal_num,
								'brand' => $row->brand,
								'serial' => $row->serial,
								'service_type' => 	$row->service_type,		
							);
						}
						$service_requests['items'] = $items;
					}
				
				}
				return $service_requests;
			} else {
				return false;
			}
		}	
		
		function add_service_request($data) 
		{	
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
			} else {
				$id = $this->session->userdata('user_id');
			}
			$data['landlord_id'] = $id;
			
			if(!empty($data['group_id'])) {
				$query = $this->db->get_where('admin_groups', array('main_admin_id'=>$data['group_id'],'sub_admins'=>$this->session->userdata('user_id')));
				if ($query->num_rows() > 0) {
					$row = $query->row_array();
					$data['group_id'] = $row['id'];
				}
			}
			$query = $this->db->insert('all_service_request', $data);
			if ($this->db->trans_status() === FALSE) {
				return false;
			} else {
				return $this->db->insert_id();
			}
		}
		
		function get_user_properties()
		{
			// GET ALL USERS PROPERTIES HERE
			$this->db->select('id, address, zipCode');
			$query = $this->db->get_where('listings', array('owner'=>$this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {
				return $query->result();
			} else {
				return false;
			}
		}
		
		public function getPropertyById($id, $table) 
		{
			$query = $this->db->get_where($table, array('id'=>$id));
			return $query->row();
		}
		
		function get_service_request_ads($data)
		{
			if(empty($data['current_ads'])) {
				//Pull new ads and add them to the service request
				$this->db->limit(3);
				$this->db->select('*');
				$results = $this->db->get_where('contractor_zip_codes', array('service_type'=>$data['service'], 'zip'=>$data['zip'], 'active'=>'y', 'purchased'=>'y'));
				if($results->num_rows()>0) {
					//Ads found
					$ad_ids = '';
					$ad_data = array();
					foreach($results->result() as $val) {
						$ad_ids .= $val->id.'|'; //Assign the ads to the request
						$ad_data[] = $val; //The actual add details stored in array
					}
					$ad_ids = rtrim($ad_ids, '|'); 	//Trim the / off the end of the string
					$this->db->where('id', $data['request_id']);
					$this->db->update('all_service_request', array('ad_ids'=>$ad_ids));
				} else {
					//No Ads Found
					$ad_data = array();
				}
			} else {
				$current_ad_ids = explode('|', $data['current_ads']);
				if(count($current_ad_ids)==3) {
					//there are already max number of ads
					$ad_data = array();
					foreach($current_ad_ids as $val) {
						$query = $this->db->get_where('contractor_zip_codes', array('id'=>$val)); // Pull the data for each ad
						if($query->num_rows()>0) {
							$ad_data[] = $query->row();
						}
					}
				} else {
					//check if any new ads can be placed
					$this->db->where('active', 'y');
					$this->db->where('purchased', 'y');
					
					$results = $this->db->get_where('contractor_zip_codes', array('service_type'=>$data['service'], 'zip'=>$data['zip']));
					if($results->num_rows()>0) {
						$ad_data = array();
						foreach($results->result() as $val) {
							$ad_ids .= $val->id.'|'; //Assign the ads to the request
							$ad_data[] = $val; //The actual add details stored in array
						}
						$ad_ids = rtrim($ad_ids, '|'); 	//Trim the / off the end of the string
						$this->db->where('id', $date['request_id']);
						$this->db->update('all_service_request', array('ad_ids'=>$ad_ids));
					} else {
						//No Ads Found
						$ad_data = array();
					}
				}
			}
	
			$count = 0;
			foreach($ad_data as $val) {	
				$impressions = $val->impressions+1;
				$this->db->where('id', $val->id);
				$this->db->update('contractor_zip_codes', array('impressions'=>$impressions));
				$this->db->select('unique_name, email');
				$results = $this->db->get_where('landlord_page_settings', array('type'=>'contractor', 'landlord_id'=>$val->contractor_id));
				if($results->num_rows()>0) {
					$r = $results->row();
					$ad_data[$count]->url = $r->unique_name;
					$ad_data[$count]->email = $r->email;
				}
				$count++;
			}

			return $ad_data;
			
			
		}
		
		function check_for_contractor($email, $service_id, $note)
		{
			$this->db->select('id');
			$query = $this->db->get_where('contractors', array('email'=>$email));
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$this->db->where('id', $service_id);
				$this->db->update('all_service_request', array('contractor_id'=>$row->id, 'contractor_note'=>$note));
				return $row->id;
			} else {
				if(!empty($note)) {
					$this->db->update('all_service_request', array('contractor_note'=>$note));
				}
			}
			return false;
		}
		
		function search_contractor($search)
		{
			$this->db->select('bName, email, image, city, state');
			$this->db->like('bName', $search);
			$this->db->where('type', 'contractor');
			$this->db->where('active', 'y');
			$query = $this->db->get('landlord_page_settings');
			if ($query->num_rows() > 0) {
				$result = $query->result();
				return $result;
			} else {
				return false;
			}
		}
		
		
		//PMS 

		public function reoccuring_pms($year, $month)
		{
			$allPM =$this->getAllPM($year, $month);
			$date = $month.'/01/'.$year;
			$daysInMonth = date('t', strtotime($date));

			$events = array();
			
			for($i=1;$i<=$daysInMonth;$i++) {
				$checkDate = $month.'/'.sprintf("%02d", $i).'/'.$year;
				foreach($allPM as $key => $val) {
					$startDate = date('m/d/Y', strtotime($val->reoccurring_date));
					$result = $this->checkReoccurance($startDate, $val->interval, $checkDate);
					if($result==='y') {						
						$events[(int)date('d', strtotime($val->reoccurring_date))][$val->id] = '<a href="'.base_url('landlords/view-preventive-maintenance/'.$val->id).'">'.$val->address.', '.$val->city.'</a>';
					} elseif(is_integer($result)) {
						$events[(int)date('d', strtotime('-'.($result).' day', strtotime($val->reoccurring_date)))][$val->id] = '<a href="'.base_url('landlords/view-preventive-maintenance/'.$val->id).'">'.$val->address.', '.$val->city.'</a>';
					} 
					
				} 
			}

			$this->buildCalendar();
			
			return $events;
			
		}
		
		public function delete_pmr($id) 
		{
			if($id>0) {
				$this->db->limit(1);
				$this->db->delete('all_service_request', array('landlord_id'=>$this->session->userdata('user_id'), 'id'=>$id));
				if($this->db->affected_rows()>0) {
					$this->session->set_flashdata('success', 'The Scheduled Preventive Maintenance you selected has been deleted');
					return true;
				}
				return false;
				$this->session->set_flashdata('error', 'Invalid selection try again');
			} else {
				$this->session->set_flashdata('error', 'Invalid selection try again');
				return false;
			}
		}
		
		private function getAllPM($year, $month, $limit=null)
		{
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
				$group = true;
			} else {
				$id = $this->session->userdata('user_id');
				$group = false;
			}
			$this->db->select('all_service_request.id, all_service_request.interval, all_service_request.reoccurring, all_service_request.reoccurring_date, all_service_request.service_type, all_service_request.listing_id, listings.address, listings.city');
			if($group) {
				$this->db->where('group_id', $this->session->userdata('temp_id'));
			} else {
				$this->db->where('landlord_id', $id);
				$this->db->where('group_id', NULL);
			}
			$lastDay = date('t', strtotime($month.'/01/'.$year));
	
			$this->db->where("DATE_FORMAT(reoccurring_date,'%Y-%m-%d') <= '".$year."-".$month."-".$lastDay."'",NULL,FALSE);
			$this->db->join('listings', 'listings.id = all_service_request.listing_id');
			$this->db->where('reoccurring', 'y');
			$this->db->order_by('id', 'desc');
			
			$query = $this->db->get('all_service_request');
			
			return $query->result();
		}
		
		private function buildCalendar()
		{
			$prefs['template'] = '
				{table_open}<div id="pms"><table class="calendar table table-stripes">{/table_open}
				{week_day_cell}<th class="day_header">{week_day}</th>{/week_day_cell}
				{heading_previous_cell}<th><a class="btn btn-primary pull-left" href="{previous_url}"><<</a></th>{/heading_previous_cell}
				{heading_next_cell}<th><a class="btn btn-primary pull-right" href="{next_url}">>></a></th>{/heading_next_cell}
				{cal_cell_content}<span class="day_listing">{day}</span><ul class="cal-event-list">{content}</ul>{/cal_cell_content}
				
				{cal_cell_content_today}<div class="today"><span class="day_listing">{day}</span><ul class="cal-event-list">{content}</ul></div>{/cal_cell_content_today}
				{cal_cell_no_content}<span class="day_listing">{day}</span>&nbsp;{/cal_cell_no_content}
				{cal_cell_no_content_today}<div class="today"><span class="day_listing">{day}</span></div>{/cal_cell_no_content_today}
				{table_close}</table></div>{/table_close}
			'; 
		
			$prefs['start_day'] = 'sunday';
			$prefs['month_type'] = 'long';
			$prefs['day_type'] = 'short';
			$prefs['day_type'] = 'short';
			$prefs['show_next_prev'] = TRUE;
			$prefs['next_prev_url'] = base_url().'landlords/reoccurring-preventive-maintenance/'; 

			$this->load->library('calendar', $prefs);
		}
		
		function checkReoccurance($startDate, $interval, $today)
		{			
			if($interval==1) { //Every Month
				$result = $this->checkDayMatch($startDate, $today);
				if($result == 'y') {
					return 'y';
				} elseif(is_integer($result)) {
					return $result;
				} else {
					return 'n';
				}
			} else {
				
				$startMonth = date('n', strtotime($startDate));
				$todayMonth = date('n', strtotime($today));				
				$monthRanges = array();
				
				$checkDates = true;
				
				
				if($interval == 3) {
					$interator = 4;
				} elseif($interval == 6) {
					$interator = 2;
				} else {
					$checkDates = false;
				}
				
				$monthCounter = $interval;
				if($checkDates) {
					for($i=0;$i<$interator;$i++) {
						
						if($interval == 3) {
							$monthRanges[] = date('m', strtotime('+'.($monthCounter).' month', strtotime($startDate)));
						} elseif($interval == 6) {
							$monthRanges[] = date('m', strtotime('+'.($monthCounter).' month', strtotime($startDate)));
						}
						$monthCounter = $monthCounter+$interval;
					}					
				
					if(in_array($todayMonth, $monthRanges)) {
						$result = $this->checkDayMatch($startDate, $today);
						if($result == 'y') {
							return 'y';
						} elseif(is_integer($result)) {
							return $result;
						} else {
							return 'n';
						}
					} else {
						return 'n';
					}
				} else {
					$startMonth = date('m-d', strtotime($startDate));
					$todayMonth = date('m-d', strtotime($today));
					if($startMonth==$todayMonth) {
						return 'y';
					}
					return 'n';
				}
		
				
			}
			
		}
	
		function checkDayMatch($startDate, $today)
		{
			$checkDay = date('d', strtotime($startDate));
		
			if($checkDay <= date('t', strtotime($today))) { //checks to make sure date does not exceed any months date
				if($checkDay==date('d', strtotime($today))) {
					return 'y';
				} else {
					return 'n';
				}
			} else {
				$dateDiff = $checkDay-date('t', strtotime($today));
				$other = date('d', strtotime('-'.$dateDiff.' day', strtotime($startDate)));
				//echo $other.' = '.date('d', strtotime($today)).'<br>';
				if($other == date('d', strtotime($today))) {
					return (int)$dateDiff;
				} else {
					return 'n';
				}
			}
		}		
		
		function view_maintenance_requests($request_id, $reoccurring = null)
		{
			$group_id = '';
			$temp_id = $this->session->userdata('temp_id');
			if(!empty($temp_id)) {
				$id = $this->get_admin_id($this->session->userdata('temp_id'));
			} else {
				$id = $this->session->userdata('user_id');
			}
			if($reoccurring == 'y') {
				$query = $this->db->get_where('all_service_request', array('landlord_id'=>$id, 'id'=>$request_id, 'reoccurring'=>'y'));
			} else {
				$query = $this->db->get_where('all_service_request', array('landlord_id'=>$id, 'id'=>$request_id));
			}
	
			if ($query->num_rows() > 0) {
				$service_requests = $query->row_array();
				
				if($service_requests['viewed'] == '0000-00-00 00:00:00') {
					$this->db->where('id', $request_id);
					$this->db->update('all_service_request', array('viewed' => date('Y-m-d H:i:s'))); 
					$service_requests['sendMail'] = true;
				} else {
					$service_requests['sendMail'] = false;
				}
		
				$service_listing_id = $service_requests['rental_id'];
				$service_requests['request_id'] = $request_id;
				//Grab Rental Home Info
				if(!empty($service_listing_id)) {
					$query = $this->db->get_where('renter_history', array('id' => $service_requests['rental_id']));
					$row = $query->row_array(); 
					
					$service_requests['address'] = $row['rental_address'];
					$service_requests['city'] = $row['rental_city'];
					$service_requests['state'] = $row['rental_state'];
					$service_requests['zip'] = $row['rental_zip'];
					$service_requests['checklist_id'] = $row['checklist_id'];
					$service_requests['listing_id'] = $row['listing_id'];
					$group_id = $row['group_id'];
				}
		
				//Grab Tenant Info
				if(!empty($service_requests['tenant_id'])) {
					$query = $this->db->get_where('renters', array('id' => $service_requests['tenant_id']));
					$row = $query->row_array(); 
					$service_requests['tenant_name'] = $row['name'];
					$service_requests['tenant_phone'] = $row['phone'];
					$service_requests['tenant_email'] = $row['email'];
					$service_requests['who'] = '1';
				} else {
					$query = $this->db->get_where('listings', array('id' => $service_requests['listing_id']));
					$row = $query->row_array(); 

					$service_requests['address'] = $row['address'];
					$service_requests['city'] = $row['city'];
					$service_requests['state'] = $row['stateAbv'];
					$service_requests['zip'] = $row['zipCode'];
					$service_requests['who'] = '0';
					
				}
				
				//Grab Landlord Info
				if($group_id>0) {
					$query = $this->db->get_where('admin_groups', array('id' => $group_id));
					$row = $query->row_array(); 
					$bName = $row['sub_b_name'];
					
					$query = $this->db->get_where('landlords', array('id' => $row['sub_admins']));
					$row = $query->row_array();
					$service_requests['landlord_email'] = $row['email'];			
					$service_requests['landlord_name'] = $row['name'];			
					$service_requests['landlord_phone'] = $row['phone'];					
					$service_requests['landlord_alt_phone'] = $row['alt_phone'];	
					$service_requests['landlord_city'] = $row['city'];	
					$service_requests['landlord_state'] = $row['state'];	
					$service_requests['landlord_zip'] = $row['zip'];	
				} else {
					$query = $this->db->get_where('landlords', array('id' => $id));
					$row = $query->row_array();
					$service_requests['landlord_email'] = $row['email'];			
					$service_requests['landlord_name'] = $row['name'];			
					$service_requests['landlord_phone'] = $row['phone'];			
					$bname = $row['bName'];			
					$service_requests['landlord_alt_phone'] = $row['alt_phone'];	
					$service_requests['landlord_city'] = $row['city'];	
					$service_requests['landlord_state'] = $row['state'];	
					$service_requests['landlord_zip'] = $row['zip'];	
				}
				if($group_id>0) {
					$service_requests['bName'] = $bName;	
				} else {
					$service_requests['bName'] = $bname;
				}
				
				
				
				$this->db->order_by('s_timestamp', 'desc');
				$query = $this->db->get_where('service_request_notes', array('ref_id' => $request_id));
				$notes = array();
				foreach ($query->result() as $row) {
					if($row->contractor_id>0) {
						$this->db->select('bName');
						$r = $this->db->get_where('contractors', array('id'=>$row->contractor_id));
						$c = $r->row();
						$contractor_name = $c->bName;
					} else {
						$contractor_name = '';
					}
					
					$add = true;
					if($row->visibility == 0) {
						if($row->landlord_id != $this->session->userdata('user_id')) {
							$add = false;
						}
					}
					if($add) {
						$notes[] = array(
							'note' => $row->note,
							'visibility' => $row->visibility,
							's_timestamp' => $row->s_timestamp,
							'contractor_image' => $row->contractor_image,
							'contractor_id' => $row->contractor_id,
							'landlord_id' => $row->landlord_id,
							'contractor_name' => $contractor_name
							
						);
					}
				}

				$service_requests['notes'] = $notes;
				if(!empty($service_requests['listing_id'])) {
					$query = $this->db->get_where('home_items', array('listing_id'=>$service_requests['listing_id'], 'service_type'=>$service_requests['service_type']));
					if ($query->num_rows() > 0) {
						foreach ($query->result() as $row) {
						
							$items[] = array(
								'desc' => $row->desc,
								'modal_num' => $row->modal_num,
								'brand' => $row->brand,
								'serial' => $row->serial,
								'service_type' => 	$row->service_type,		
							);
						}
						$service_requests['items'] = $items;
					}
					
					//Incomplete Service Requests
					//if(!empty($service_requests['rental_id'])) {
					//	$query = $this->db->get_where('all_service_request', array('rental_id'=>$service_requests['rental_id'], 'complete'=>'n', 'id !='=>$request_id));
					//} else {
						$query = $this->db->get_where('all_service_request', array('listing_id'=>$service_requests['listing_id'], 'complete'=>'n', 'id !='=>$request_id));
					//}
					
					if ($query->num_rows() > 0) {
						foreach ($query->result_array() as $row) {
							$service_requests['incomplete_requests'][] = $row;
						}
					} else {
						$service_requests['incomplete_requests'] = '';
					}
					
				}
			
				return $service_requests;
			} else {
				return false;
			}
		}
		
		private function grab_all_pms($year, $month, $limit) //NO LANDLORDS ATTACHED RAN FROM CRON
		{
			$this->db->select('all_service_request.id, all_service_request.interval, all_service_request.landlord_id, all_service_request.group_id, all_service_request.reoccurring, all_service_request.reoccurring_date, all_service_request.service_type, all_service_request.listing_id, listings.address, listings.city');
			
			$lastDay = date('t', strtotime($month.'/01/'.$year));
			$this->db->limit($limit);
			$this->db->where("DATE_FORMAT(reoccurring_date,'%Y-%m-%d') <= '".$year."-".$month."-".$lastDay."'",NULL,FALSE);
			$this->db->join('listings', 'listings.id = all_service_request.listing_id');
			$this->db->where('reoccurring', 'y');
			$this->db->order_by('id', 'desc');
			
			$query = $this->db->get('all_service_request');
			
			return $query->result();
		}
		
		public function updatePM($id)
		{	
			if($id>0) {
				$this->form_validation->set_rules('interval', 'How Often', 'required|trim|min_length[1]|max_length[2]|xss_clean|numeric');
				$this->form_validation->set_rules('reoccurring_date', 'Reoccurring Date', 'required|trim|min_length[10]|max_length[10]|xss_clean');
				$this->form_validation->set_rules('description', 'Description', 'required|trim|min_length[2]|max_length[500]|xss_clean');
				$this->form_validation->set_rules('service_type', 'Service Type', 'required|trim|min_length[1]|max_length[2]|xss_clean|numeric');
				$this->form_validation->set_rules('admin', 'Assign to Manager', 'trim|min_length[1]|max_length[12]|xss_clean|numeric');
				if($this->form_validation->run() == true) {
					extract($_POST);
					$reoccurring_date = date('Y-m-d', strtotime($reoccurring_date));
					
					if($admin == 0) {
						$admin = null;
					}
					
				
					$this->db->where('landlord_id', $this->session->userdata('user_id'));
					$this->db->where('id', $id);
					$this->db->update('all_service_request', array('interval'=>$interval, 'reoccurring_date'=>$reoccurring_date, 'description'=>$description, 'service_type'=>$service_type, 'group_id'=>$admin, 'reminder_sent' => 'y'));
					if($this->db->affected_rows()>0) {
						$this->session->set_flashdata('success', 'The PM has been updated');
					} else {
						$this->session->set_flashdata('error', 'No changes where made');
					}
				} else {
					$this->session->set_flashdata('error', validation_error());
				}
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, try again');
			}
		}
		
		public function send_pm_reminders() 
		{
			$this->load->model('special/send_email');
			$this->load->model('special/add_activity');
			$today = date('m/d/Y');
			//$today = '08/31/2015';
			$dates = $this->get_intervals($today); //TODAY VAR SKIPS DAY AND RETURNS MONTHS
			$count = 0;

			foreach($dates as $key => $val) {
			
				if($count==0) {
					$sql = "SELECT id, reminder_sent, service_type, landlord_id, group_id FROM `all_service_request` WHERE `reoccurring` = 'y' and (   DATE_FORMAT(reoccurring_date,'%m-%d') = '".date('m')."-".date('d')."' AND `interval` = 1 AND reminder_sent = 'n')";
				}
				for($i=0;$i<count($val);$i++) {
					$sql .= " OR (`reoccurring` = 'y' and ( DATE_FORMAT(reoccurring_date,'%m-%d') = '".$val[$i]."-".date('d')."' AND `interval` = ".$key." AND reminder_sent = 'n'))";
				}
				$count++;
			}
			     
			$sql .= " LIMIT 20";			
			$result = $this->db->query($sql);
			$data = $result->result();

			$user_array = array();
			foreach($data as $key => $val) {
				
				$id = $val->landlord_id;
				if(!empty($val->group_id)) {
					$id = $this->get_sub_admin_id($val->group_id);
				}
				
				$this->db->select('name, email, forwarding_email, cell_phone, sms_msgs');
				$results = $this->db->get_where('landlords', array('id'=>$id));
				if($results->num_rows()>0) {
					$row = $results->row();
					if(!in_array($row->email, $user_array)) {
						$info = array(
							'forwarding_email' => $row->forwarding_email,
							'cell_phone' 	   => $row->cell_phone,
							'sms_msgs'         => $row->sms_msgs,
							'service_type'	   => $val->service_type,
							'service_id'	   => $val->id,
							'name'             => $row->name,
						);
						$user_array[$row->email][] = $info;
					} else {
						$info = array(
							'forwarding_email' => $row->forwarding_email,
							'cell_phone' 	   => $row->cell_phone,
							'sms_msgs'         => $row->sms_msgs,
							'service_type'	   => $val->service_type,
							'service_id'	   => $val->id,
							'name'             => $row->name,
						);
						$user_array[$row->email][] = $info;
					}
				}
			}
			
			if(!empty($user_array)) {
				$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other'); 
				
				$sent_id_arrays = array();
				foreach($user_array as $key => $val) {
					$msg_data = '';
					$total = count($val);
					$name_array = explode(' ', $val[0]['name']);
					
					foreach($val as $k => $v) {
						$msg_data .= '<p><b>'.$services_array[$v['service_type']].'</b> - <a href="'.base_url('landlords/view-preventive-maintenance/'.$v['service_id']).'">View PM</a></p>';
						$sent_id_arrays[] = $v['service_id'];
					}
					
					$email = $key;
					$subject = 'You have '.$total.' PM Reminder for Today';
					$message = '<h4>Hello '.$name_array[0].',</h4>';
					$message .= '<p>This is a friendly reminder that you have '.$total.' preventive maintenance scheduled for today.</p>'.$msg_data;
					$message .= '<p>Visit your calendar to see the full details of these reminders.</p>';
					$message .= '<p><a href="'.base_url('landlords/reoccurring-preventive-maintenance').'">View My Calendar</a></p>';
					
					$emailArray = '';
					if(!empty($val[0]['forwarding_email'])) {
						$email = '';
						$emailArray = array($key, $val[0]['forwarding_email']);
					}
					$this->send_email->sendEmail($email, $message, $subject, $alt_message = null, $emailArray);
					if(!empty($val[0]['cell_phone']) && $val[0]['sms_msgs'] == 'y') {
						$this->send_email->send_data_message($val[0]['cell_phone'], 'This is a reminder that you have '.$total.' Preventive Maintenance today. '.base_url('landlords/reoccurring-preventive-maintenance'));
					}	
					
					$this->add_activity->add_new_activity('Preventive Maintenance Reminder', $id, 'landlord', $v['service_id']);
							
				}
				
				for($i=0;$i<count($sent_id_arrays);$i++) {
					if($i==0) {
						$this->db->where('id', $sent_id_arrays[$i]);
					} else {
						$this->db->or_where('id', $sent_id_arrays[$i]); 
					}
				}
				$this->db->update('all_service_request', array('reminder_sent'=>'y'));
			}
		}
		
		private function get_sub_admin_id($group_id)
		{
			$result = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($result->num_rows()>0) {
				$row = $result->row();
				return $row->sub_admins;
			}
			return false;
			
		}
		
		private function get_intervals($today)
		{			
			$months = array();
			$check_array = array(3, 6, 12);
			$m = date('m', strtotime($today));
			$y = date('Y', strtotime($today));
			$today = $m.'/01/'.$y;
			foreach($check_array as $key => $val) {
				
				if($val==3) {
					$interator = 4;
				} elseif($val==6) {
					$interator = 2;
				} elseif($val==12) {
					$interator = 1;
				}
				$count = $val;
				for($i=0;$i<$interator;$i++) {
					$months[$val][] = date('m', strtotime('+'.($count).' month', strtotime($today)));
					
					$count = $count+$val;
				}
			}
			return $months;
		}
		
		public function reset_pm_reminders()
		{
			$this->db->update('all_service_request', array('reminder_sent'=>'n'));
		}
		
		public function getSupplyHouses($zip, $serviceType) //Service type needs to be an int 
		{
			$this->db->select('business, logo, city, state, address, ad_areas, ad_service_types, url, phone');
			$this->db->order_by('id', 'RANDOM');
			$this->db->like('ad_areas', $zip);
			$this->db->like('ad_service_types', $serviceType);
			$query = $this->db->get('supply_houses');
			
			$count = 0;
			$data = array();
			foreach($query->result() as $row) {
				$adAreasArray = explode('|', $row->ad_areas);
				$adServicesArray = explode(',', $row->ad_service_types);
				
				if( in_array($zip, $adAreasArray) && in_array($serviceType, $adServicesArray) && $count<2) {
					$data[] = $row;
					$count++;
				}
			}
			
			return $data;
			
		}
		
		
	}
	 