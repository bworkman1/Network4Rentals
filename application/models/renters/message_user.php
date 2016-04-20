<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Message_user extends CI_Model {
	
		var $table = "messaging";
		
		function Message_user()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function add_message($data)
		{	
			$query = $this->db->get_where('renter_history', array('id'=>$data['rental_id'], 'tenant_id'=>$this->session->userdata('user_id')));
			$row = $query->row_array(); 
			$landlord_id = $this->get_rental_landlord_id($data['rental_id']);
			$data['landlord_id'] = $landlord_id;
	
			$this->db->insert($this->table,$data);
			$last_id = $this->db->insert_id();
			return $last_id;
		}
		
		function show_message_from_email($hash) // Allows users to see the message without logging in once before logging in
		{
			$sql = "SELECT * FROM messaging WHERE hash_mail = ? LIMIT 1";
			$query = $this->db->query($sql, array($hash));
			if($query->num_rows()>0) {
				$row = $query->row();
				$sql = "UPDATE messaging SET hash_mail = '' WHERE hash_mail = ? LIMIT 1";
				$query = $this->db-query($sql, array($hash));
				
				return $row; // Returns object
			} else {
				return false;
			}
		}
		
		function get_landlords_info($id) 
		{
			$sql = "SELECT id, email, bName, name, address, city, state, zip, phone, sign_up, forwarding_email, alt_phone FROM landlords WHERE id = ? LIMIT 1";
			$result = $this->db->query($sql, array($id));
			if ($result->num_rows() > 0) 
			{
				$row = $result->row_array(); 
				return $row;
			}
			else 
			{
				return false;
			}
			
		}
		
		function count_chat_messages($landlord_id) 
		{
			$sql = "SELECT count(id) FROM messaging WHERE tenant_id = ? AND rental_id = ?";
			$query = $this->db->query($sql, array($this->session->userdata('user_id'), $landlord_id));
			
			return $query->row_array();
		}
		
		function get_new_message_count($id) 
		{
			$sql = "SELECT count(id) FROM messaging WHERE tenant_id = ? AND rental_id = ? AND (parent_id = ? AND tenant_viewed = '0000-00-00 00:00:00') OR (id = ? AND tenant_viewed = '0000-00-00 00:00:00')";
			$query = $this->db->query($sql, array($this->session->userdata('user_id'), $this->session->userdata('rental_id'), $id, $id));
			$count = $query->row_array();
			return $count;
		}
		
		function update_viewied_time($id) // Called from ajax request via button click
		{
			$this->db->get_where('messaging', array('id'=>$id));
			if ($query->num_rows() > 0)	{
				$row = $query->row();
				if($row['sent_by'] == 0 AND $row['tenant_viewed'] == '0000-00-00 00:00:00') {
					$sql = "UPDATE messaging SET tenant_viewed = NOW() WHERE tenant_id = ? AND rental_id = ? AND (id = ? OR parent_id = ?) AND tenant_viewed = '0000-00-00 00:00:00'";
					$this->db->query($sql, array($this->session->userdata('user_id'), $this->session->userdata('rental_id'), $id, $id));
					
					$query = $this->db->get_where('messaging', array('id'=>$id));
					$row = $query->row_array(); 
					
					$this->load->model('renters/user_model');
					$data = array('action'=>'A Tenant Viewed Your Messages', 'user_id'=>$row['landlord_id'], 'type'=>'landlords', 'action_id'=>$row['id']);
					$this->user_model->add_activity($data);
				}
			}
		}
		
		function message_reply($data)
		{
			$query = $this->db->get_where('renter_history', array('id'=>$data['rental_id'], 'tenant_id'=>$this->session->userdata('user_id')));
			$row = $query->row_array(); 
			$landlord_id = $this->get_rental_landlord_id($data['rental_id']);
			$data['landlord_id'] = $landlord_id;
			$this->db->insert($this->table,$data);
			$last_id = $this->db->insert_id();
			return $last_id;
		}
		
		function print_messages($id) {
			$sql = "SELECT * FROM messaging WHERE tenant_id = ? AND rental_id = ? AND (id = ? or parent_id = ?) ORDER BY parent_id DESC, timestamp DESC";			
			$query = $this->db->query($sql, array($this->session->userdata('user_id'), $this->session->userdata('rental_id'), $id, $id));
			$data = $query->result_array();
			$count=0;
			foreach ($data as $row) {
				if($row['sent_by'] == 0) {
					$info = $this->get_landlords_info($row['landlord_id']);
					$data[$count]['name'] = $info['name'];
					$data[$count]['email'] = $info['email'];
				}
				$count++;
			}
			return $data;
		}
		
		function show_landlords() {
			$sql = "SELECT id, link_id, group_id FROM renter_history WHERE tenant_id = ? ORDER BY current_residence DESC";
			$results = $this->db->query($sql, array($this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$count = 0;
				foreach ($results->result() as $row) {
					if(!empty($row->group_id)) {
						$q = $this->db->get_where('admin_groups', array('id'=>$row->group_id));
						if ($q->num_rows() > 0) {
							$rs = $q->row_array();
							$admin_id = $rs['sub_admins'];
							$bName = $rs['sub_b_name'];
							
							$this->db->select('id, bName, name');
							$r = $this->db->get_where('landlords', array('id'=>$admin_id));
							
							$data[] = $r->row_array();
							$data[$count]['bName'] = $bName;
						}
					} else {
						$this->db->select('id, bName, name');
						$r = $this->db->get_where('landlords', array('id'=>$row->link_id));
						$data[] = $r->row_array();
						
					}
					$data[$count]['message_id'] = $row->id;
					$data[$count]['rental_id'] = $row->id;
					
					$results = $this->db->get_where('messaging', array('tenant_viewed'=>'0000-00-00 00:00:00', 'tenant_id'=> $this->session->userdata
					('user_id'), 'rental_id'=>$row->id ));
					
					$data[$count]['new_messages'] = $results->num_rows();
					$count++;
					
					
				}
		
				return $data;

			} else {
				return false;
			}
		}
		
		function get_rental_landlord_id($id)
		{
			$query = $this->db->get_where('renter_history', array('tenant_id'=>$this->session->userdata('user_id'),'id'=>$id));
			if ($query->num_rows() > 0) {
				$results= $query->row_array();
				$groupId = (int)$results['group_id'];
				if($groupId>0) {
					$q = $this->db->get_where('admin_groups', array('id'=>$results['group_id']));
					if ($q->num_rows() > 0) {
						$r= $q->row_array();
						return $r['sub_admins'];
					}
				} else {
					return $results['link_id'];				
				}
			} else {
				return false;
			}			
		}
		
		
		//THIS FUNCTION IS SUBJECT TO REMOVAL RETURNS TWO DIFFERENT TYPES OF DATA
		function get_rental_info($id)
		{
			$query = $this->db->get_where('renter_history', array('tenant_id'=>$this->session->userdata('user_id'),'id'=>$id));
			if ($query->num_rows() > 0) {
				$results= $query->row_array();
				$groupId = (int)$results['group_id'];
				if($groupId>0) {
					$q = $this->db->get_where('admin_groups', array('id'=>$results['group_id']));
					if ($q->num_rows() > 0) {
						$r= $q->row_array();
						$row = $this->get_landlords_info($r['sub_admins']);
						$row['bName'] = $r['sub_b_name'];
						$row['actual_id'] = $r['main_admin_id'];
						return $row;
					}
				} else {
					return $results['link_id'];				
				}
				
			} else {
				return false;
			}
		}
		
		
		
		function get_landlord_email($message_id) {
			$query = $this->db->get_where('messaging', array('id'=>$message_id));
			if ($query->num_rows() > 0) {
				$row = $query->row_array(); 
				$query = $this->db->get_where('renter_history', array('id'=>$row['rental_id']));
				if ($query->num_rows() > 0) {
					$row = $query->row_array(); 
					if($row['group_id']>0) {
						//Return Admin Info
						$query = $this->db->get_where('admin_groups', array('id'=>$row['group_id']));
						if ($query->num_rows() > 0) {
							$row = $query->row_array(); 
							return $this->get_landlords_info($row['sub_admins']);
						} else {
							echo '1';//return false;
							exit;
						}
					} else {
						return $this->get_landlords_info($row['link_id']);
					}
				} else {
					//No Rental Id Found
					echo '2';//return false;
					exit;
				}
			} else {
				//Something went wrong with the message insert
				echo '3';//return false;
				exit;
			}
		}
		
		function get_hashed_message($hash) 
		{
			$query = $this->db->get_where('messaging', array('hash_mail'=>$hash));
			if ($query->num_rows() > 0) {
				$row = $query->row_array(); 
				
				$this->db->select('name, email');
				$this->db->get_where('renters', array('id', $row['tenant_id']));
				
				$this->db->select('name, email');
				$this->db->get_where('renters', array('id', $row['tenant_id']));
				
				
				return $row;
			} else {
				return false;
			}
		}
		
		function get_tenant_information($id) 
		{
			$query = $this->db->get_where('renter_history', array('id'=>$id));
			if ($query->num_rows() > 0) {
				$row = $query->row_array(); 
				return $row;
			} else {
				return false;
			}
		}
		
		function get_users_details($id) 
		{
			$this->db->select('name, email, phone');
			$query = $this->db->get_where('renters', array('id' => $id));
			foreach ($query->result_array() as $row) {
				return $row;
			}
		}		
	}