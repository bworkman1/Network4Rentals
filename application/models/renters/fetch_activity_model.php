<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Fetch_activity_model extends CI_Model {
		
		public function __construct() {        
			parent::__construct();
		}
		
		public function record_count($user, $to = null, $from = null) {
			$this->db->where('user_id', $user);
			$this->db->where('type', 'renters');
			if(!empty($to)) {
				$this->db->where('created >=', $from);
				$this->db->where('created <=', $to);
			}
			$sorted = $this->session->userdata('sort_activity_by');
			if(!empty($sorted)) {
				$this->db->where('action', $sorted);
			}
			$this->db->from('activity');
			return $this->db->count_all_results();
		}
		
		public function fetch_recent_activity($limit, $start, $user, $to = null, $from = null) {
			
			$this->db->where('user_id', $user);
			$this->db->where('type', 'renters');
			if(!empty($to)) {
				$this->db->where('created >=', $from);
				$this->db->where('created <=', $to);
			}
			$sorted = $this->session->userdata('sort_activity_by');
			if(!empty($sorted)) {
				$this->db->where('action', $sorted);
			}
			$this->db->order_by('id', 'desc');
			$this->db->limit($limit, $start);
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
			$sql = "SELECT id FROM renter_history WHERE tenant_id = ?";
			$query = $this->db->query($sql, array($this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {	
				return true;
			} else {
				return false;
			}
		}
		
		function sort_options()
		{
			$sql = "Select action from `activity` WHERE type = 'renters' AND user_id = '".$this->session->userdata('user_id')."' GROUP BY action";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0) {	
				return $query->result_array();
			} else {
				return false;
			}
		}
		
	} //ends class
?>