<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Renter_history_handler extends CI_Model {
		function Renter_history_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function check_for_landlord_registration($email) 
		{
			$table = 'landlords';
			$this->db->where("email",$email);
			$q = $this->db->get($table);
			if($q->num_rows() > 0)
			{
				return $q->row();
			}
			return false;
		}
		
		function reset_current_residences() 
		{
			$sql = "UPDATE renter_history SET current_residence = 'n' WHERE tenant_id = ?";
			$this->db->query($sql, array($this->session->userdata('user_id')));
		}
		
		function add_landlord($data) 
		{
			if($data['current_residence'] == 'y') {
				$this->reset_current_residences();
			}
			if(!empty($data['landlord_phone'])) { // Remove all non-numeric characters from the phone number
				$data['landlord_phone'] = preg_replace("/[^0-9]/", '', $data['landlord_phone']);
			}
			$landlord_data = array('bName', 'landlord_name', 'landlord_email', 'landlord_phone', 'landlord_address', 'landlord_city', 'state', 'zip');
			foreach($data as $key => $val) { // Loop through data values and remove landlord data for the insert
				if (!in_array($key, $landlord_data)) {
					$rental_details[$key] = $val;
				} else {
					$landlord_info[$key] = $val;
				}
			}
			foreach($landlord_info as $key => $val) {
				if (strpos($key,'_') !== false) {
					$landlords[ltrim(strstr($key, '_'), '_')] = $val;
				} else {
					$landlords[$key] = $val;
				}
			}
			
			if(!empty($data['link_id'])) { // Landlord already registered
				$this->db->insert('renter_history',$rental_details);
				$last_id = $this->db->insert_id();
				return $last_id;
			} else { // Landlord Not Registered
				$email_registered = $this->check_for_landlord_registration($data['landlord_email']);
				
				if(!empty($email_registered)) {
					$rental_details['link_id'] = $email_registered->id;
					
					$this->db->insert('renter_history',$rental_details);
					$last_id = $this->db->insert_id();
					return $last_id;	
					
				} else {	
					// Create account for landlord and get the id
					$this->db->insert('landlords' ,$landlords);
					$last_id = $this->db->insert_id();
					$rental_details['link_id'] = $last_id;
					// Add the rental info to the rental_history table
					$this->db->insert('renter_history' ,$rental_details);
					$last_id = $this->db->insert_id();
					
					if($last_id>0) {
						return $last_id;
					} else {
						return false;
					}
				}	
			}
		}
		
		
		
		function get_link_id() 
		{
			$this->session->userdata('edit_rental_id');
			$sql = "SELECT link_id, lease_upload, address_locked FROM renter_history WHERE id = ? AND tenant_id = ? LIMIT 1";
			$query = $this->db->query($sql, array($this->session->userdata('edit_rental_id'), $this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {
				   $row = $query->row_array(); 
				   return $row;
			} else {
				return false;
			}
		}
		
		function check_for_landlord_with_username($id) 
		{
			$table = 'landlords';
			$this->db->where("id",$id);
			$this->db->where("user !=","");
			$q = $this->db->get($table);
			if($q->num_rows() > 0)
			{
				return true;
			}
			return false;
		}
		
		function edit_landlord($data) 
		{
			$update = true;
			if($data['current_residence'] == 'y') { 
 				$this->reset_current_residences();
			}
			
			$landlord_data = array('bName', 'landlord_name', 'landlord_email', 'landlord_phone', 'landlord_address', 'landlord_city', 'state', 'zip');
			foreach($data as $key => $val) { // Loop through data values and remove landlord data for the insert
				if (!in_array($key, $landlord_data)) {
					$rental_details[$key] = $val;
				} else {
					$landlord_info[$key] = $val;
				}
			}
			foreach($landlord_info as $key => $val) {
				if (strpos($key,'_') !== false) {
					$landlords[ltrim(strstr($key, '_'), '_')] = $val;
				} else {
					$landlords[$key] = $val;
				}
			}
			$landlords_settings = $this->get_link_id();
			if(empty($rental_details['lease_upload'])) {
				$rental_details['lease_upload'] = $landlords_settings['lease_upload'];
			}
			if($landlords_settings['address_locked'] == 1) {
				$sql = "UPDATE renter_history SET move_out = ?, current_residence = ?, lease_upload = ? WHERE id = ? AND tenant_id = ? LIMIT 1";

				$update = $this->db->query($sql, array($rental_details['move_out'], $rental_details['current_residence'], $rental_details['lease_upload'], $this->session->userdata('edit_rental_id'), $this->session->userdata('user_id')));
			} else {
				$check_if_registered = $this->check_for_landlord_with_username($landlords_settings['link_id']);
				$sql = "UPDATE renter_history SET move_out = ?, current_residence = ?, lease_upload = ?, rental_address = ?, rental_city = ?, rental_state = ?, rental_zip = ?, move_in = ?, move_out = ?, lease = ?, payments = ?, deposit = ?,  day_rent_due = ? WHERE id = ? AND tenant_id = ? LIMIT 1";
			
				$query = $this->db->query($sql, 
				array(
					$rental_details['move_out'], 
					$rental_details['current_residence'], 
					$rental_details['lease_upload'], 
					$rental_details['rental_address'], 
					$rental_details['rental_city'], 
					$rental_details['rental_state'], 
					$rental_details['rental_zip'], 
					$rental_details['move_in'], 
					$rental_details['move_out'], 
					$rental_details['lease'], 
					$rental_details['payments'],
					$rental_details['deposit'],
					$rental_details['day_rent_due'],
					$this->session->userdata('edit_rental_id'), 
					$this->session->userdata('user_id')
				));
				
				if($query == false) {
					$update = false;
				}
				
				if($check_if_registered == false) {
					$sql = "UPDATE landlords SET email = ?, name = ?, address = ?, city = ?, state = ?, zip = ?, phone = ? WHERE id = ? LIMIT 1";
					$query = $this->db->query($sql, array($landlords['email'], $landlords['name'], $landlords['address'], $landlords['city'], $landlords['state'], $landlords['zip'], $landlords['phone'], $landlords_settings['link_id']));
					if($query == false) {
						$update = false;
					}
					
				}
			}
			if($update == true) {
				return true;
			}
		}
		
	}