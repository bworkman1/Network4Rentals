<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class User_details extends CI_Model {

        public function __construct() {

            parent::__construct();

        }

		public function get_user_details()
		{
			$results = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}
			
		}
		
		public function get_payment_details()
		{
			$results = $this->db->get_where('payments', array('user_id'=>$this->session->userdata('user_id'), 'type'=>'contractor'));
			if($results->num_rows()>0) {
				return $results->result();
			} else {
				return false;
			}
		}		
		
		public function get_addon_payments()
		{
			$results = $this->db->get_where('contractor_purchases', array('contractor_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return $results->result();
			} else {
				return false;
			}
		}
		
		public function update_password($pwd) 
		{
			$this->db->limit(1);
			$this->db->where('id', $this->session->userdata('user_id'));
			$results = $this->db->update('contractors', array('password'=>md5($pwd)));
			if($this->db->affected_rows()>0) {
				$this->load->model('contractor/activity_handler');
				$this->activity_handler->insert_activity(array('action'=>'Changed password','action_id'=>''));
				return true;
			} else {
				return false;
			}			
		}
		
		public function update_user_details($data)
		{
			$this->db->limit(1);
			$this->db->where('id', $this->session->userdata('user_id'));
			$results = $this->db->update('contractors', $data);
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}			
		}
		
		public function update_personal_info($data)
		{
			$this->db->where('id', $this->session->userdata('user_id'));
			$results = $this->db->update('contractors', $data);
			$updated = $this->db->affected_rows();
			if($updated>0) {
				return true;
			} else {
				return false;
			}
		}
	
	}