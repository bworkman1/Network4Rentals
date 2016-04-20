<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Checklist_handler extends CI_Model {

		function Checklist_handler()

		{

			// Call the Model constructor

			parent::__construct();

		}
		
		var $table = 'checklist';
		var $table1 = 'renter_history';
		
		function get_renter_row_data($id) 
		{
			$query = $this->db->get_where($this->table1, array('id' => $id));
			if ($query->num_rows() > 0) {
   				$row = $query->row_array(); 
				return $row;
			} else {
				return false;
			}
		}

		function add_new_checklist($data)
		{
	
			$this->db->insert($this->table,$data);
			$checklist_id = $this->db->insert_id();
			$landlord_id = $data['landlord'];
			$data = array('checklist_id' => $checklist_id);
			$this->db->where("tenant_id",$this->session->userdata('user_id'));
			$this->db->where("link_id", $landlord_id);
       		$this->db->update($this->table1,$data);
			return $checklist_id;
		}

		function check_for_submission($id) 
		{
			$sql = "SELECT current_residence, checklist_id FROM renter_history WHERE tenant_id = ? AND id = ?";
			$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
			if ($query->num_rows() > 0) {
				$row = $query->row_array(); 
				if($row['current_residence'] == 'n') {
					return 1;
				}
				if(!empty($row['checklist_id'])) {
					return 2;
				}
				return 3;
			} else {
				return 0;
			}
		}
		
		function view_checklist($id)
		{
			$sql = "SELECT * FROM checklist WHERE tenant = ? AND id = ?";
			$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
			if ($query->num_rows() > 0) {
				$row = $query->row_array(); 
				return $row;
			} else {
				return false;
			}
		}

	}