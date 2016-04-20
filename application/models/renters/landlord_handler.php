<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Landlord_handler extends CI_Model {
		function Landlord_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		function search_landlords($searched) {		 // Not sure if we need this	
			$this->db->select('id, name, bName');
			$this->db->from('landlords');
			$this->db->like('bName', $searched, 'both');
			$this->db->or_like('name', $searched, 'both'); 
			$this->db->limit(10);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				$row = $query->result_array(); 
				return json_encode($row);
			}
		}
		
		function request_online_payments()
		{
			$this->db->limit(1);
			$results = $this->db->get_where('renter_history', array('tenant_id'=>$this->session->userdata('user_id'), 'current_residence'=>'y', 'request_online_payments'=>'n'));
			if($results->num_rows()>0) {
				$row = $results->row();
				if($row->link_id>0) {
					$this->db->select('name, email');
					$r = $this->db->get_where('landlords', array('id'=>$row->link_id));
					$landlord_info = $r->row();
					
					$name_array = explode(' ', $landlord_info->name);
					
					if(!empty($landlord_info->email)) {
						$message = '<h2>'.$name_array[0].'</h2><p>One of your tenants has indecatited that they would like to have the option to submit online payments. To enable this feature or learn more click the link below.</p><p><a href="https://network4rentals.com/network/landlords/payment-settings">https://network4rentals.com/network/landlords/payment-settings</a>';
						
						$subject = 'Tenant Requested Online Rental Payments';
						$this->load->model('special/send_email');
						if($this->send_email->sendEmail($landlord_info->email, $message, $subject)) {
							$this->db->limit(1);
							$this->db->where('tenant_id', $this->session->userdata('user_id'));
							$this->db->where('current_residence', 'y');
							$this->db->update('renter_history', array('request_online_payments'=>'y'));
							return array('success'=>'Your landlord has been notified that you would like to submit payments online');
						} else {
							return array('error'=>'Email failed to sent, no email address found for the landlord.');
						}
					} else {
						return array('error'=>'No email address found for the landlord.');
					}
				} else {
					return array('error'=>'Landlord not found.');
				}
			} else {
				return array('error'=>'Request has already been sent.');
			}
		}
		
		public function searchForLandlord($searched)
		{
			$searched = str_replace("_20"," ",$searched);
			
			$found_landlords = array();
			
			$this->db->limit(8);
			$this->db->select('id, name, bName, city, zip');
			$this->db->like('name', $searched, 'both');
			$this->db->where('pwd !=', '');
			$this->db->from('landlords');
			$query = $this->db->get();
			foreach($query->result() as $row) {
				
				$found_landlords[] = $row;
				$this->db->limit(8);
				$this->db->select('sub_admins, sub_b_name');
				$q = $this->db->get_where('admin_groups', array('main_admin_id' => $row->id));
				foreach($q->result() as $r) {
					$this->db->limit(8);
					$this->db->select('id, name, bName, city, zip');
					$subAdmins = $this->db->get_where('landlords', array('id' => $r->sub_admins));
					foreach($subAdmins->result() as $v) {
						$v->bName = $r->sub_b_name;
						$found_landlords[] = $v;
					}
					
				}
			}
			
			$this->db->limit(8);
			$this->db->select('id, name, bName, city, zip');
			$this->db->like('bName', $searched, 'both');
			$this->db->where('pwd !=', '');
			$this->db->from('landlords');
			$query = $this->db->get();
			foreach($query->result() as $row) {	
				$found_landlords[] = $row;
			}
			
			return $found_landlords;
			
		}
		
		function searchSubs($searched) {
			$found_landlords = array();
			$searched = str_replace("_20"," ",$searched);
			// Search Sub Admins For Name And Info
			$this->db->select('landlords.id, admin_groups.sub_admins, admin_groups.main_admin_id, admin_groups.sub_b_name, landlords.name, landlords.bName, admin_groups.id, landlords.city, landlords.zip');
			$this->db->from('landlords');
			$this->db->join('admin_groups', 'landlords.id = admin_groups.sub_admins');
			$this->db->like('admin_groups.sub_b_name', $searched, 'both');
			$this->db->limit(15);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$found_landlords = $query->result();
			}
			
			
			
			// Search The Landlord Database Directly For Business Name
			$this->db->select('id, name, bName, city, zip, state');
			$this->db->from('landlords');
			$this->db->like('bName', $searched, 'both');
			$this->db->or_like('name', $searched, 'both'); 
			$this->db->limit(15);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				$search_results = array_merge($query->result(), $found_landlords); 
			} 
			
			if(empty($search_results)) {
				return $found_landlords;
			} else {
				return $search_results;
			}
			
		}
		
		function group_search_data($search) {
			$search = str_replace('_20', ' ', $search);
			$data1 = $this->search_landlords_both($search);
			$data2 = $this->search_group_names($search);
			
			
			
			if(is_array($data1)) {
				$data3 = $this->search_for_sub_accounts($data1);
			}
			
			if(is_array($data1) && is_array($data2)) {
				$data = array_merge($data1, $data2);
			} else {
				if(!empty($data1)) {
					$data = $data1;
				} elseif(!empty($data2)) {
					$data = $data2;
				}
			} 
			
			if(is_array($data3)) {
				$data = array_merge($data, $data3);
			}			
			
			
			for($i=0;$i<count($data);$i++) {
				if(!empty($data[$i]['group_id'])) {
					$dup = null;
					for($ii=$i+1;$ii<count($data);$ii++) {
						if($data[$i]['group_id'] == $data[$ii]['group_id']) {
							$dup = $ii;
						}
						if(!is_null($dup)) {
							array_splice($data, $dup,1);
						}
					}
				}
			} 
			
			$count = 0;
			foreach($data as $key => $val) {
				if(!empty($val['sub_id'])) {
					$this->db->select('id, email, name, phone, state, zip, city, name, bName');
					$query = $this->db->get_where('landlords', array('id'=>$val['sub_id']));
					$row = $query->row();
					$data[$count]['email'] = $row->email;
					$data[$count]['name'] = $row->name;
					$data[$count]['phone'] = $row->phone;
					$data[$count]['state'] = $row->state;
					$data[$count]['zip'] = $row->zip;
					$data[$count]['city'] = $row->city;
				}				
				$count++;
			}
			return json_encode($data);
		}	
		
		function search_for_sub_accounts($data)
		{
			
			foreach($data as $key => $val) {
				if(!empty($val['link_id'])) {
					$this->db->select();
					$query = $this->db->get_where('admin_groups', array('sub_admins'=>$val['link_id']));
					if($query->num_rows()>0) {	
						$datas = array();
						foreach($query->result() as $row  => $val) {
							$info = array(
								'display_name' => $val->sub_b_name,
								'link_id' => $val->main_admin_id,
								'group_id' => $val->id,
								'sub_id' => $val->sub_admins,
								'email' => '',
								'name' => '',
								'phone' => '',
								'state' => '',
								'zip' => '',
								'city' => ''
							);
							$datas[] = $info;
						}
						return $datas;
					}
				}
			}
		}
		
		function search_landlords_both($searched) {
			$this->db->select('id, email, name, phone, state, zip, city, name, bName');
			$this->db->or_like('name', $searched);
			$this->db->or_like('bName', $searched);
			$this->db->limit(15);
			$results = $this->db->get('landlords');
			if($results->num_rows()>0) {
				$data = array();
				foreach($results->result() as $row  => $val) {
					$info = array(
						'link_id' => $val->id,
						'group_id' => NULL,
						'sub_id' => NULL,
						'email' => $val->email,
						'name' => $val->name,
						'phone' => $val->phone,
						'state' => $val->state,
						'zip' => $val->zip,
						'city' => $val->city
					);
					if(empty($val->bName)) {
						$info['display_name'] = '';
					} else {
						$info['display_name'] = $val->bName;
					}
					$data[] = $info;
				}
				return $data;
			}			
		}
		
		function search_group_names($searched) {
			$this->db->like('sub_b_name', $searched);
			$this->db->limit(10);
			$this->db->select('sub_b_name, id, sub_admins, main_admin_id');
			$results = $this->db->get('admin_groups');
			if($results->num_rows()>0) {
				$data = array();
				foreach($results->result() as $row  => $val) {
					$info = array(
						'display_name' => $val->sub_b_name,
						'link_id' => $val->main_admin_id,
						'group_id' => $val->id,
						'sub_id' => $val->sub_admins,
						'email' => '',
						'name' => '',
						'phone' => '',
						'state' => '',
						'zip' => '',
						'city' => ''
					);
					$data[] = $info;
				}
				return $data;
			}
		}

		function search_by_email($email) 
		{
			$email = str_replace("%7c","@",$email);
			$this->db->select('id, name, email, phone');
			$query = $this->db->get_where('landlords', array('email'=>$email));
			if ($query->num_rows() > 0) {
				$row = $query->row(); 
				return json_encode($row);
			}
		}
		
		function get_landlord_by_id($id) 
		{
			$query = $this->db->get_where('landlords', array('id'=>$id));
			if ($query->num_rows() > 0) {
				$row = $query->row();
				return json_encode($row);
			} else {
				return false;
			}
		}
		
		function get_landlord_by_phone($phone)
		{
			$query = $this->db->get_where('landlords', array('phone'=>$phone));
			if ($query->num_rows() > 0) {
				$row = $query->row();
				return json_encode($row);
			} else {
				return false;
			}
		}
		
		function check_for_current_residences()
		{
			$query = $this->db->get_where('renter_history', array('move_out'=>'0000-00-00', 'tenant_id'=>$this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {
				$val = 'y';
				return json_encode($val);
			} else {
				$val = 'n';
				return json_encode($val);
			}			
		}
		
		function check_link_name($unique_name) //checks to see if the link the user clicked from n4r.rentals is a valid unique name and return the landlords info
		{
			$this->db->select('landlord_id');
			$query = $this->db->get_where('landlord_page_settings', array('unique_name'=>$unique_name));
			if($query->num_rows()>0) {
				$row = $query->row();
				if($row->landlord_id>0) {
					$query = $this->db->get_where('landlords', array('id'=>$row->landlord_id));
					if($query->num_rows()>0) {
						return $query->row();
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		function get_landlord_properties($landlord_id)
		{
			$this->db->select('id, address, city, stateAbv');
			$query = $this->db->get_where('listings', array('owner'=>$landlord_id));
			if($query->num_rows()>0) {
				return $query->result();
			} else {
				return false;
			}
		}
		
		function get_property_details($id) 
		{
			$this->db->select('id, address, city, stateAbv, deposit, price, zipCode');
			$query = $this->db->get_where('listings', array('id'=>$id));
			if($query->num_rows()>0) {
				return $query->row();
			} else {
				return false;
			}
		}
		
			
			
		
		
	}
	

	
