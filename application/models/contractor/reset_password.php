<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class reset_password extends CI_Model {
		function User_model()
		{
			// Call the Model constructor
			parent::__construct();
		}
		function set_email_hash($user) // Updates database with a hash of the users username
		{
			$md5_user = md5($user.rand(100, 10000000).date('Y-m-d'));
			$sql = "UPDATE contractors SET email_hash = ? WHERE user = ? AND active = 'y' LIMIT 1";
			$result = $this->db->query($sql, array($md5_user, $user));
			if($this->db->affected_rows() != 0) 
			{
				return $md5_user;
			}
			else 
			{
				return false;	
			}
		}
		function check_user_email($email) 
		{
			$query_str = "SELECT user FROM contractors WHERE email = ? AND active = 'y' LIMIT 1";
			$result = $this->db->query($query_str, $email);
			if($result->num_rows() == 1) 
			{	
				$row = $result->row(); 
				$result = $this->set_email_hash($row->user);
				if($result != false) {
					return $result;
				} else {
					return false;
				}
			}
			else 
			{
				return false;	
			}
		}
		function check_token($token)
		{
			$query_str = "SELECT id FROM contractors WHERE email_hash = ? AND active = 'y' LIMIT 1";
			$result = $this->db->query($query_str, $token);
			if($result->num_rows() == 1) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function change_password($token, $pwd) 
		{
			$query_str = "UPDATE contractors SET password = ?, email_hash = ? WHERE email_hash = ? AND active = 'y' LIMIT 1";
			$result = $this->db->query($query_str, array(md5($pwd), NULL, $token));
			if($result) 
			{
				return true;
			}
			else
			{
				return false;
			}	
		}

	}