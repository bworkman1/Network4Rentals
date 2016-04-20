<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Employees extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
		
		public function add_employee($data) 
		{
			$data['contractor_id'] = $this->session->userdata('user_id');
			$this->db->insert('contractor_employees', $data);
			if($this->db->insert_id()>0) {
				return true;
			} 
			return false;
		}
		
		public function edit_employee($data, $id)
		{
			$this->db->where('id', $id);
			$this->db->where('contractor_id', $this->session->userdata('user_id'));
			$this->db->update('contractor_employees', $data);
			if($this->db->affected_rows()>0) {
				return true;
			} 
			return false;
		}
		
		public function get_employees()
		{ 
			$query = $this->db->get_where('contractor_employees', array('contractor_id'=>$this->session->userdata('user_id')));
			return $query->result();
		}
		
		public function delete_employee($id) 
		{
			$query = $this->db->delete('contractor_employees', array('id'=>$id,'contractor_id'=>$this->session->userdata('user_id')));
			if($this->db->affected_rows()>0) {
				return true;
			} 
			return false;
		}
		
		public function get_single_employee($id) 
		{
			$query = $this->db->get_where('contractor_employees', array('id'=>$id, 'contractor_id'=>$this->session->userdata('user_id')));
			return $query->row();
		}
		
	}