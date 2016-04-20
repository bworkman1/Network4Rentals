<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admins_only extends CI_Model {

	function admins_only()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	
	function get_tenants_signup_dates()
	{
		$sql = "SELECT DATE(sign_up) AS Created, count(id) AS counter FROM renters GROUP BY DATE(sign_up) ORDER BY id DESC";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row) {
			$results[] = $row;
		}
		return $results;
	}
	
	function count_total_tenants()
	{
		return $this->db->count_all_results('renters');
	}
	
	function get_landlords_signup_dates()
	{
		$sql = "SELECT DATE(sign_up) AS Created, count(id) AS counter FROM landlords GROUP BY DATE(sign_up) ORDER BY id DESC";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row) {
			$results[] = $row;
		}
		return $results;
	}
	
	function count_total_landlords()
	{
		return $this->db->count_all_results('landlords');
	}
	
	function get_tenants_by_zip()
	{
		$query = $this->db->query("SELECT count(rental_zip) AS counter, rental_zip FROM renter_history WHERE current_residence = 'y' GROUP BY rental_zip ORDER BY counter DESC");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$results[] = $row;
			}
		}
		return $results;
	}

	function get_landlords_by_zip()
	{
		$query = $this->db->query("SELECT count(zip) AS counter, zip FROM landlords GROUP BY zip ORDER BY counter DESC");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$results[] = $row;
			}
		}
		return $results;
	}
	
	function get_inactive_tenants()
	{
		$this->db->select('id, sign_up');
		$this->db->order_by('confirmed', 'desc');
		$query = $this->db->get('renters');
		$inactive_ids = array();
		foreach ($query->result() as $row) {
			$q = $this->db->get_where('renter_history', array('tenant_id'=>$row->id));
			if($q->num_rows()==0) {
				$inactive_ids[] = $row->id;
			}
		}
		
		if(!empty($inactive_ids)) {
			$data = array();
			foreach($inactive_ids as $val) {
				$this->db->select('id, name, email, confirmed');
				$query = $this->db->get_where('renters', array('id'=>$val));
				if($query->num_rows()>0) {
					$data[] = $query->result();
				}
			}	
		} else {
			$data = '';
		}
		return $data;
		
		
	}
	
	function get_inactive_landlords()
	{
		$results = $this->db->get_where('landlords', array('confirmed'=>'n'));
		return($results->result());
	}
	
}