<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Fetch_activity_model extends CI_Model {
		
		public function __construct() {        
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
		
		function get_sub_admin_id($group_id) 
		{	
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				return $row->sub_admins;
			}
		}
		
		public function record_count($user, $to = null, $from = null) {
		
			$group_id = $this->session->userdata('temp_id');
			if(!empty($group_id)) {
				$user = $this->get_admin_id($group_id);
			}
			
			if(!empty($to)) {
				$from = date('Y-m-d', strtotime($from));
				$to = date('Y-m-d', strtotime($to));
				if(!empty($group_id)) {
					$sql = "SELECT COUNT(ID) AS total FROM activity WHERE user_id = ? AND created >= ? AND created <= ? AND type = 'landlords' AND group_id = ?";
					$query = $this->db->query($sql, array($user, $from, $to, $group_id));
				} else {
					$sql = "SELECT COUNT(ID) AS total FROM activity WHERE user_id = ? AND created >= ? AND created <= ? AND type = 'landlords' AND group_id IS NULL";
					$query = $this->db->query($sql, array($user, $from, $to));
				}
				
			} else {
				if(!empty($group_id)) {
					$sql = "SELECT COUNT(ID) AS total FROM activity WHERE user_id = ? AND type = 'landlords' AND group_id = ?";
					$query = $this->db->query($sql, array($user, $group_id));
				} else { 
					$sql = "SELECT COUNT(ID) AS total FROM activity WHERE user_id = ? AND type = 'landlords' AND group_id IS NULL";
					$query = $this->db->query($sql, array($user));
				}
				
			}
			
			$row = $query->row();
			return $row->total;
		}
		
		public function fetch_recent_activity($limit, $start, $user, $to = null, $from = null) {
			$group_id = $this->session->userdata('temp_id');
		
			if(!empty($to)) {
				$from = date('Y-m-d', strtotime($from));
				$to = date('Y-m-d', strtotime($to));
				
				$this->db->where('created >=', $from);
				$this->db->where('created <=', $to);
			}
			
			if(!empty($group_id)) {
				$user = $this->get_sub_admin_id($group_id);
				if($group_id == $this->session->userdata('temp_id')) {
					$this->db->where('group_id', $group_id);
				}
			} else {
				$this->db->where('group_id', NULL);
			}
	
			$this->db->limit($limit, $start);
			$this->db->where('user_id', $user);
			$this->db->where('type', 'landlords');
			$this->db->order_by('id', 'desc');
			$query = $this->db->get('activity');
		
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$data[] = $row;
				}
				return $data;
			}
			return false;
		}
		
		public function landlord_check()
		{
			$sql = "SELECT id FROM renter_history WHERE link_id = ?";
			$query = $this->db->query($sql, array($this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {	
				return true;
			} else {
				return false;
			}
		}
		
		function forwardToActivity($data) 
		{	
			$data['created'] = date('Y-m-d H:i:s');
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$query = $this->db->insert('activity', $data); 
			
			if($data['type'] = 'contractor') {
				$data['action'] = 'Recieved new service request from landlord https://network4rentals.com/network/contractor/view-service-request/'.$data['action_id'];
				$this->notify_by_cell($data['user_id'], $data['action']);
			}
		}
		
		function add_activity_feed($data) 
		{	
			$data['created'] = date('Y-m-d H:i:s');
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$query = $this->db->insert('activity', $data); 
			
			if($data['type'] = 'contractor') {
				$this->notify_by_cell($data['user_id'], $data['action']);
			}
		}
		
		private function notify_by_cell($contractor_id, $action) 
		{
			$this->db->select('cell');
			$q = $this->db->get_where('contractors', array('id'=>$contractor_id));
			if($q->num_rows()>0) {
				$row = $q->row();
				if(!empty($row->cell)) {
					
					$msg = 'New action on Network 4 Rentals: '.$action;
					$this->load->model('landlords/sms_handler');
					$this->sms_handler->send_data_message_by_array(array('cell_phone'=>$row->cell, 'message'=>$msg)); //needs cell phone and message
				}
			}
		}
		
	} //ends class
?>