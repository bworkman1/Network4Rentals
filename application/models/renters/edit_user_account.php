<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Edit_user_account extends CI_Model {
		function Edit_user()
		{
			// Call the Model constructor
			parent::__construct();
		}		
		
		function edit_forwarding_address($email) 
		{
			$sql = "UPDATE renters SET forwarding_email = ? WHERE id = ? LIMIT 1";
			$result = $this->db->query($sql, array($email, $this->session->userdata('user_id')));
			if($result) {
				return true;
			} else {
				return false;
			}
		}
		
		function update_password($pwd)
		{	
			$sql = "UPDATE renters SET pwd = ? WHERE id = ? LIMIT 1";
			$result = $this->db->query($sql, array(md5($pwd), $this->session->userdata('user_id')));
			if($result) {
				return true;
			} else {
				return false;
			}			
		}
		
		function update_personal_info($fullname, $email, $phone, $cell_phone, $sms_msgs) 
		{
			$sql = "UPDATE renters SET email = ?, name = ?, phone = ?, cell_phone = ?, sms_msgs = ? WHERE id = ? LIMIT 1";
			$result = $this->db->query($sql, array($email, $fullname, $phone, $cell_phone, $sms_msgs, $this->session->userdata('user_id')));
			if($result) {
				return true;
			} else {
				return false;
			}
		}	
	}