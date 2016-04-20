<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Service_request_handler extends CI_Model {
		
		var $table = 'all_service_request';
		
		function Service_request_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function new_service_request($data) 
		{	
			$data['submitted'] = date('Y-m-d H:i:s');
			$data['tenant_id'] = $this->session->userdata('user_id');
			$query = $this->db->get_where('renter_history', array('tenant_id' => $data['tenant_id'], 'current_residence' => 'y'));
			if ($query->num_rows() > 0) {
				$row = $query->row();
				if(!empty($row->group_id)) {
					$data['group_id'] = $row->group_id;
				} else {
					$data['group_id'] = NULL;
				}
				$data['landlord_id'] = $row->link_id;
				$data['listing_id'] = $row->listing_id;
			}
			if(!empty($data['group_id'])) {
				$this->db->select('main_admin_id');
				$query = $this->db->get_where('admin_groups', array('id'=>$data['group_id']));
				if($query->num_rows()>0) {
					$row = $query->row();
					$data['landlord_id'] = $row->main_admin_id;
				}
			}
			$data['email_hash'] = md5(date('Y-m-d H:i:s').$_SERVER['SERVER_ADDR'].rand(1,100000));
			
			$this->db->insert($this->table,$data);
			$last_id = $this->db->insert_id();
			$data = array('last_id'=>$last_id, 'group_id'=>$data['group_id']);
			return $data;
		}
		
		function getRenterNotes($id) 
		{
			$query = $this->db->get_where('service_request_notes', array('ref_id'=>$id, 'visibility'=>3));
			return $query->result();
		}
		
		function get_request_hash($id) {
			$query = $this->db->get_where('all_service_request', array('id'=>$id));
			if ($query->num_rows() > 0) {
				
				$row = $query->row_array();
				if(!empty($row['group_id'])) {
					$row['landlord_id'] = $this->get_sub_admin_id($row['group_id']);
				}
				return $row;
			} else {
				return false;
			}
			
		}
	
		function addNote($note, $id) 
		{
			$request = $this->view_service_request($id);
			if(!empty($request)) {
				$data = array(
					'note' => $note,
					'ref_id' => $id,
					'visibility' => 3,
					'landlord_id' => $request['landlord_id'],
				);
				$this->db->insert('service_request_notes', $data);
				if($this->db->insert_id()>0) {
					
					$this->load->model('special/add_activity');
					$action = 'Add a note to a service request<br><b><small>'.$request['rental_address'].' '.$request['rental_city'].', '.$request['rental_state'].'</small></b>';
					$this->add_activity->add_new_activity($action, $request['landlord_id'], 'landlords', $id);
					
					return true;
				} else {
					return 'Something went wrong adding the service request, try again';
				}
				
			}
			return 'Invalid service request, try again';
		}
		
		
		
		function get_landlord_stats($id, $bool) 
		{	
			if($bool == false) {
				$sql = "SELECT count(id) AS count FROM all_service_request WHERE tenant_id = ? AND landlord_id = ? AND (group_id = 0 OR group_id IS NULL)";
				$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
				$row = $query->row_array();
				$result = $row['count'];
			} else {
				$sql = "SELECT count(id) AS count FROM all_service_request WHERE tenant_id = ? AND group_id = ?";
				$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
				$row = $query->row_array();
				$result = $row['count'];
			}

			return($result);
		}

		function get_landlord_stats_complete($id, $bool) 
		{	
			if($bool == false) {
				$sql = "SELECT count(id) AS count FROM all_service_request WHERE tenant_id = ? AND landlord_id = ? AND complete = 'y' AND (group_id = 0 OR group_id IS NULL)";
				$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
				$row = $query->row_array();
				$result = $row['count'];
			} else {
				$sql = "SELECT count(id) AS count FROM all_service_request WHERE tenant_id = ? AND group_id = ? AND complete = 'y'";
				$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
				$row = $query->row_array();
				$result = $row['count'];
			}			
			return($result);
		}
	
		function get_landlord_stats_incomplete($id, $bool) 
		{
			if($bool == false) {
				$sql = "SELECT count(id) AS count FROM all_service_request WHERE tenant_id = ? AND landlord_id = ? AND complete = 'n' AND (group_id = 0 OR group_id IS NULL)";
				$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
				$row = $query->row_array();
				$result = $row['count'];
			} else {
	
				$sql = "SELECT count(id) AS count FROM all_service_request WHERE tenant_id = ? AND group_id = ? AND complete = 'n'";
				$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
				$row = $query->row_array();
				$result = $row['count'];
			}
			return($result);
		}
		
		function group_service_request_by_landlord()
		{			
			$this->db->select('id, group_id, link_id,  rental_address, rental_city, rental_state, rental_zip');
			$this->db->where('tenant_id', $this->session->userdata('user_id'));
			$this->db->order_by('id', 'desc');
			$query = $this->db->get('renter_history');
			$results = array();
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$r = $this->db->get_where('all_service_request', array('complete'=>'y', 'rental_id'=>$row->id));
					$row->complete = $r->num_rows();
					$r = $this->db->get_where('all_service_request', array('complete'=>'n', 'rental_id'=>$row->id));
					$row->incomplete = $r->num_rows();
					
					$data[] = $row;
				}
				$results = $query->result();
			}
			
			return $results;
		}
		
		function service_request_by_landlord($id) 
		{
			$query = $this->db->get_where('all_service_request', array('rental_id'=>$id, 'tenant_id'=>$this->session->userdata('user_id')));
			$this->db->order_by('id', 'desc');
			return $query->result_array();
		}
		
		function view_service_request($id)
		{
			$query = $this->db->get_where('all_service_request', array('tenant_id'=>$this->session->userdata('user_id'), 'id'=>$id));
			$row = $query->row_array();
			$this->db->select('rental_address, rental_city, rental_state');
			$q = $this->db->get_where('renter_history', array('id'=>$row['rental_id']));
			$r = $q->row_array();
			$row['rental_address'] = $r['rental_address'];
			$row['rental_city'] = $r['rental_city'];
			$row['rental_state'] = $r['rental_state'];
			return $row;
		}
		
		function update_hash_for_email($id, $hash) 
		{	
			$data = array('email_hash' => $hash);
			$this->db->where("id",$id);
			$this->db->where("tenant_id",$this->session->userdata('user_id'));
			$this->db->update($this->table,$data);
		}
		
		function mark_as_complete($id) 
		{
			$data = array('email_hash' => '', 'complete' => 'y', 'completed' => date('Y-m-d H:m:s'));
			$this->db->where("id",$id);
			$this->db->where("tenant_id",$this->session->userdata('user_id'));
			$this->db->update($this->table,$data);
			return true;
		}
		
		function show_service_request_via_email($hash) 
		{
			$query = $this->db->get_where('all_service_request', array('email_hash'=>$hash));
			if ($query->num_rows() > 0) {
				$row = $query->row_array();
				
				if(!empty($row['listing_id'])) {
					$query = $this->db->get_where('home_items', array('listing_id'=>$row['listing_id'], 'service_type'=>$row['service_type']));
					if ($query->num_rows() > 0) {
						foreach ($query->result() as $r) {
						
							$items[] = array(
								'desc' => $r->desc,
								'modal_num' => $r->modal_num,
								'brand' => $r->brand,
								'serial' => $r->serial,
								'service_type' => 	$r->service_type,		
							);
						}
						$row['items'] = $items;
					}
				}
				
				if($row['viewed'] == '0000-00-00 00:00:00') {
					$data = array('viewed' => date('Y-m-d H:i:s'));
					$this->db->where("email_hash", $hash);
					$this->db->update($this->table, $data);		
				}
				$query = $this->db->get_where('renter_history', array('id'=>$row['rental_id']));
				if($query->num_rows() > 0) {
					$r = $query->row_array();
					$row['rental_address'] = $r['rental_address'];
					$row['rental_city'] = $r['rental_city'];
					$row['rental_state'] = $r['rental_state'];
					$row['rental_zip'] = $r['rental_zip'];
					$query = $this->db->get_where('renters', array('id'=>$row['tenant_id']));
					if($query->num_rows() > 0) {
						$r = $query->row_array();
						$row['name'] = $r['name'];
						$row['email'] = $r['email'];
						$row['phone'] = $r['phone'];
						
					}
				}
				
				return $row;
			} else {
				return false;
			}			
		}
		
		function get_sub_admin_id($id)
		{	
			$query = $this->db->get_where('admin_groups', array('id'=>$id));
			if ($query->num_rows() > 0) {
				$row = $query->row(); 
				return $row->sub_admins;
			} else {
				return false;
			}
		}
		
		function get_business_name_of_group($id)
		{	
			$query = $this->db->get_where('admin_groups', array('id'=>$id));
			if ($query->num_rows() > 0) {
				return $query->row_array();
			} else {
				return false;
			}
			
		}
			
		function get_forwarding_email($id)
		{
			$this->db->select('forwarding_email');
			$results = $this->db->get_where('landlords', array('id'=>$id));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row->forwarding_email;
			} else {
				return false;
			}
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
		
	}
	
	