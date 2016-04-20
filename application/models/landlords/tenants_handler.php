<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tenants_handler extends CI_Model {
	
	function tenants_handler()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function tenant_count()
	{
		$temp_id = $this->session->userdata('temp_id');
		
		if(empty($temp_id)) {
			$id = $this->session->userdata('user_id');
			$this->db->where('link_id', $id);
			$this->db->where('group_id', '0');
		} else {
			$admin_id = $this->get_admin_id($this->session->userdata('temp_id'));
			$this->db->where('group_id', $this->session->userdata('temp_id'));
			$this->db->where('link_id', $admin_id);
		}	
		
		$this->db->select('id');
		$query = $this->db->get_where('renter_history', array('current_residence'=>$this->session->userdata('current_residence')));
		$total = $query->num_rows();
		return $total;	
	}
	
	function show_tenants($limit, $start) 
	{
		$temp_id = $this->session->userdata('temp_id');
		if(empty($temp_id)) {
			$id = $this->session->userdata('user_id');
		} else {
			$id = $this->session->userdata('temp_id');
		}
		
		
		$this->db->select('renter_history.*');
		$this->db->select('renters.*');
		$this->db->select("renters.*, renters.id AS renters_id, renter_history.id AS rental_id");
		if(!empty($temp_id)) {
			$this->db->where('group_id', $id);
		} else {
			$this->db->where('link_id', $id);
			$this->db->where('group_id', '0');
		}
		
		$this->db->join('renters', 'renter_history.tenant_id = renters.id');
	
		
		
		$this->db->where('current_residence', $this->session->userdata('current_residence'));
		$query = $this->db->get('renter_history', $limit, $start);
		if($query->num_rows() > 0)
		{// Technical Operations officer
			$data = $query->result_array();
			
			return $data; 
		}
		return false;
	}
	
	function show_checklist_details($id)
	{
		$query = $this->db->get_where('checklist', array('id'=>$id));
		if ($query->num_rows() > 0) {
			$row = $query->row_array(); 
			$query = $this->db->get_where('renters', array('id'=>$row['tenant']));
			if ($query->num_rows() > 0) {
				$r = $query->row_array();
				$row['name'] = $r['name'];
				$row['email'] = $r['email'];
				$row['phone'] = $r['phone'];
				$row['alt_phone'] = $r['alt_phone'];
				$query = $this->db->get_where('renter_history', array('checklist_id'=>$id));
				if ($query->num_rows() > 0) {
					$r = $query->row_array();
					$row['rental_address'] = $r['rental_address'];
					$row['rental_city'] = $r['rental_city'];
					$row['rental_state'] = $r['rental_state'];
					$row['rental_zip'] = $r['rental_zip'];
					return $row;
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
	
	public function savePaymentData($data)
	{
		if($this->check_landlord_payment_settings()) {
			if($this->authorizePaymentChanges($data['id'])) {
				$feedback = array('success' => 'Settings saved successfully');
				$this->db->where('id', $data['id']);
				unset($data['id']);
				$query = $this->db->update('renter_history', $data);
				if($this->db->affected_rows()>0) {
					$feedback = array('success' => 'Settings saved successfully');
				} else {
					$feedback = array('error' => 'Settings not saved, maybe you didn\'t change anything?');
				}
			} else {
				$feedback = array('error' => 'You do not have access to setting these values.');
			}
		} else {
			$feedback = array('error' => 'Your payment settings have not been setup yet. Visit your settings page to learn more. <a href="https://network4rentals.com/network/landlords/payment-settings">Here</a>');
		}
		return $feedback;
	}
	
	public function authorizePaymentChanges($id) 
	{
		$query = $this->db->get_where('renter_history', array('id'=>$id, 'link_id'=>$this->session->userdata('user_id')));
		if($query->num_rows()>0) {
			
			return true;
		}
		return false;
	}
	
	function tenants_info($id)
	{
		$temp_id = $this->session->userdata('temp_id');
		if(empty($temp_id)) {
			$ids = $this->session->userdata('user_id');
			$query = $this->db->get_where('renter_history', array('id'=>$id, 'link_id'=>$ids));
		} else {
			$ids = $this->session->userdata('temp_id');
			$query = $this->db->get_where('renter_history', array('id'=>$id, 'group_id'=>$ids));
		}		
		
		if($query->num_rows()>0) {
			$data = $query->row_array();
			$q = $this->db->get_where('renters', array('id'=>$data['tenant_id']));
			if($q->num_rows() > 0) {
				$r = $q->row_array();
				$data['email'] = $r['email'];
				$data['phone'] = $r['phone'];
				$data['alt_phone'] = $r['alt_phone'];
				$data['name'] = $r['name'];
			}
			if(!empty($data['group_id'])) {
				$query = $this->db->get_where('admin_groups', array('id'=>$data['group_id']));
				if($query->num_rows() > 0) {
					$row = $query->row_array();
					$data['bName'] = $row['sub_b_name'];
				}
			} else {
				$query = $this->db->get_where('landlords', array('id'=>$data['link_id']));
				if($query->num_rows() > 0) {
					$row = $query->row_array();
					if(empty($row['bName'])) {
						$data['bName'] = $row['bName'];
					} else {
						$data['bName'] = $row['name'];
					}
				}
			}
			
			if(!empty($temp_id)) {
				$ids = $this->get_admin_id($temp_id);
				$this->db->where('contact_id', $temp_id);
			} else {
				$this->db->where('contact_id', NULL);
			}
			
			$query = $this->db->get_where('listings', array('owner'=>$ids));
			if($query->num_rows() > 0) {
				$properties = array();
				foreach ($query->result_array() as $row) {
					$properties[$row['id']] = $row['address'].' - '.$row['zipCode'];
					if($row['id'] == $data['listing_id']) {
						$data['google_map'] = $row['map_correct'];
					}
				}
				$data['properties'] = $properties;
			}
			
			return $data;
		} else {
			return false;
		}
	} 
	
	function check_landlord_payment_settings()
	{
		$this->db->select('net_api, net_hash, allow_payments');
		$results = $this->db->get_where('payment_settings', array('landlord_id'=> $this->session->userdata('user_id')));
		if($results->num_rows()>0) {
			$row = $results->row();
			if($row->net_api != '' AND $row->net_hash != '' AND $row->allow_payments == 'y') {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function get_admin_id($group_id) 
	{	
		$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
		if($query->num_rows()>0) {
			$row = $query->row();
			return $row->main_admin_id;
		}
	}
	
	function rental_payments($tenant_info, $limit = null)
	{
		if(!empty($limit)) {
			$this->db->limit(4);
		}
		$this->db->order_by('paid_on', 'desc');
		$query = $this->db->get_where('payment_history', array('ref_id'=>$tenant_info['ref_id'], 'landlord_id'=>$tenant_info['landlord_id'], 'tenant_id'=>$tenant_info['tenant_id']));
		return $query->result();
	}
	
	function tenant_settings($data) {
		
		$this->db->select('id');
		$this->db->where('id', $data['rental_id']);
		$this->db->where('link_id', $this->session->userdata('user_id'));
		
		$this->db->or_where('group_id', $this->session->userdata('user_id'));
		$this->db->where('id', $data['rental_id']);
		
		$results = $this->db->get('renter_history');
		
		if($results->num_rows()>0) {
			$this->db->where('id', $data['rental_id']);	
			if(isset($data['payments_allowed'])) {
				$this->db->update('renter_history', array('payments_allowed'=> $data['payments_allowed']));
			} else if(isset($data['partial_payments'])) {
				$this->db->update('renter_history', array('partial_payments'=> $data['partial_payments']));
			} else if(isset($data['min_payment'])) {
				$this->db->update('renter_history', array('min_payment'=> $data['min_payment']));
			} else if(isset($data['auto_pay_discount'])) {
				$this->db->update('renter_history', array('auto_pay_discount'=> $data['auto_pay_discount']));
			} else if(isset($data['discount_payment'])) {
				$this->db->update('renter_history', array('discount_payment'=> $data['discount_payment']));
			} else {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}
	
}