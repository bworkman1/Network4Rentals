<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Activity_handler extends CI_Model {
		
		public function __construct() {
            parent::__construct();
        }
		
		public function insert_activity($data)
		{
			//Data must have - action - action_id
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$data['created'] = date('Y-m-d H-i-s');
			$data['user_id'] = $this->session->userdata('user_id');
			$data['type'] = 'contractor';
			
			$this->db->insert('activity', $data);
		}
		
		public function record_count($user, $to = null, $from = null) {
			if(!empty($to)) {
				$from = date('Y-m-d', strtotime($from));
				$to = date('Y-m-d', strtotime($to));
				$sql = "SELECT COUNT(ID) AS total FROM activity WHERE user_id = ? AND created >= ? AND created <= ? AND type = 'contractor' AND group_id IS NULL";
				$query = $this->db->query($sql, array($user, $from, $to));				
			} else {
				$sql = "SELECT COUNT(ID) AS total FROM activity WHERE user_id = ? AND type = 'contractor'";
				$query = $this->db->query($sql, array($user));
			}
			$row = $query->row();
			return $row->total;
		}
		
		public function fetch_recent_activity($limit, $start, $user, $to = null, $from = null) {
			if(!empty($to)) {
				$from = date('Y-m-d', strtotime($from));
				$to = date('Y-m-d', strtotime($to));
				
				$this->db->where('created >=', $from);
				$this->db->where('created <=', $to);
			}
	
			$this->db->limit($limit, $start);
			$this->db->where('user_id', $user);
			$this->db->where('type', 'contractor');
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
		
		
	}