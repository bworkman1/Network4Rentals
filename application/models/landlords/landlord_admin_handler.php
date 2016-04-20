<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class landlord_admin_handler extends CI_Model {
		function Landlord_admin_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function add_admin($data) 
		{
			$return_email = $data['email'];
			$this->db->select('id');
			$this->db->where('email', $data['email']);
			$query = $this->db->get('landlords');
			if($query->num_rows()>0) {
				$row = $query->row(); // returns $row->id of the username when found
				
				$this->db->select('id, sub_admins, main_admin_id, options, sub_b_name');
				$this->db->where('sub_admins', $row->id);
				$this->db->where('main_admin_id', $this->session->userdata('user_id'));
				$this->db->where('sub_b_name', $data['sub_b_name']);
				$query = $this->db->get('admin_groups');
				if($query->num_rows()>0) {
					return 3; // Current User Already Exists Under This Admin
				} else {
					$data = array(
					   'main_admin_id' => $this->session->userdata('user_id'),
					   'sub_admins' => $row->id,
					   'sub_b_name' => $data['sub_b_name']
					);
					$result = $this->db->insert('admin_groups', $data); 		
					if($this->db->affected_rows()>0) {
						return $return_email; // Admin added to account
					}
				}
			} else {
				return 2; // No User Found With That User Name
			}
		}
		
		function get_admin_personal_details() 
		{
		
		}
		
		function show_my_admins() 
		{
			$this->db->select('sub_admins, options, sub_b_name, id');
			$this->db->where('main_admin_id', $this->session->userdata('user_id'));
			$query = $this->db->get('admin_groups');
			$results = array();
			if($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$this->db->select('name, bName');
					$this->db->where('id', $row->sub_admins);
					$querys = $this->db->get('landlords');	
					foreach ($querys->result() as $rows) {
						$results[] = array('sub_b_name' => $row->sub_b_name, 'name' => $rows->name, 'bName' => $rows->bName, 'permissions' => $row->options, 'id'=>$row->id);
					}
				}
				
				return $results;
			} else {
				return false;
			}
		}
		
		function switch_account($id) 
		{
			$this->db->where('id', $id);
			$this->db->where('sub_admins', $this->session->userdata('user_id'));
			$this->db->or_where('main_admin_id', $this->session->userdata('user_id'));
			$query = $this->db->get('admin_groups');
			if ($query->num_rows() > 0) {
				$this->session->set_userdata('temp_id', $id);
				return true;
			} else {
				return false;
			}
		}
		
		function user_admin_accounts()
		{
			$this->db->select('sub_admins, options, sub_b_name, main_admin_id');
			$this->db->where('sub_admins', $this->session->userdata('user_id'));
			$query = $this->db->get('admin_groups');
			$results = array();
			if($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$this->db->select('name, bName, id');
					$this->db->where('id', $row->main_admin_id);
					$querys = $this->db->get('landlords');	
					foreach ($querys->result() as $rows) {
						$results[] = array('id' => $row->main_admin_id, 'sub_b_name' => $row->sub_b_name, 'name' => $rows->name, 'bName' => $rows->bName);
					}
				}
				
				return $results;
			} else {
				return false;
			}
		}
		
		function remove_admin_accounts($sub_b_name, $sub_group_id, $new_email)
		{
			//Verify That This Is A Valid Request From Landlord
			$test = $this->db->get_where('admin_groups', array('main_admin_id'=>$this->session->userdata('user_id'), 'id'=>$sub_group_id));
			if($test->num_rows()==0) {
				$this->session->set_flashdata('error', 'You Are Not The Admin Of This Account, Try Again');
				return null;
			}
			
			if(!empty($new_email)) {
				$results = $this->db->get_where('landlords', array('email'=>$new_email));
				if($results->num_rows()>0) {
					$row = $results->row();
					
					//Update the admin_groups table with the correct id;
					$this->db->where('id', $sub_group_id);
					
					$this->db->update('admin_groups', array('sub_admins'=>$row->id, 'sub_b_name'=>$sub_b_name));
					
					if(empty($row->bName)) {
						$names = $row->name;
					} else {
						$names = $row->bName;
					}
					$this->session->set_flashdata('success', $names.' Has Been Added As The Manager Of This Account');
				} else {
					$this->session->set_flashdata('error', 'Manager Not Found, Check With That User To Make Sure They Have Registered And What Email Address They Used');
				}	
			} else {
				$this->db->where('id', $sub_group_id);
				$this->db->update('admin_groups', array('sub_b_name'=>$sub_b_name));	
				$this->session->set_flashdata('success', 'The Sub Business Name Has Been Changed To '.$sub_b_name);
			}

			/* else {
				$query = $this->db->get_where('admin_groups', array('main_admin_id'=>$this->session->userdata('user_id'), 'id'=>$row_id));
				if($query->num_rows()>0) {
					if($reassign != 0) {
						$query = $this->db->get_where('admin_groups', array('id'=>$reassign, 'main_admin_id'=>$this->session->userdata('user_id')));
						if ($query->num_rows() > 0) {
							$r = $query->row_array(); 
							$new_admin_id = $r['sub_admins'];		

							$this->db->where('id', $row_id);
							$this->db->update('admin_groups', array('sub_admins'=>$new_admin_id));					
						}
					} else {
						$this->db->where('id', $row_id);
						$this->db->update('admin_groups', array('sub_admins'=>$this->session->userdata('user_id')));	
					}
					
					if($this->db->affected_rows()>0) {
						$this->session->set_flashdata('success', 'The Manager You Selected Has Been Removed');
					} else {
						$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
					}
				} else {
					$this->session->set_flashdata('error', 'You are not authorized to make this type of change');
				}
			}
			*/

		}
		
		function assign_admin($data)
		{	
			//$data holds rental_id and admin_id
			//check if landlord and manager/admin is active
			if($data['admin_id']==0) {
				$where = array('current_residence'=>'y', 'id'=>$data['rental_id'], 'link_id'=>$this->session->userdata('user_id'));
				$this->db->where($where);
				$this->db->limit(1);
				$result = $this->db->update('renter_history', array('group_id'=>$data['admin_id']));
				if($this->db->affected_rows()>0) {
					return true;
				} else {
					return false;
				}
			} else {
				$query = $this->db->get_where('admin_groups', array('main_admin_id'=>$this->session->userdata('user_id'), 'id' => $data['admin_id']));
				if($query->num_rows()>0) { // admin is linked to this manager
				
					$where = array('current_residence'=>'y', 'id'=>$data['rental_id'], 'link_id'=>$this->session->userdata('user_id'));
					$this->db->where($where);
					$this->db->limit(1);
					$query = $this->db->update('renter_history', array('group_id'=>$data['admin_id']));
					if($this->db->affected_rows()>0) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
	}