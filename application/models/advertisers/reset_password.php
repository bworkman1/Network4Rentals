<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class reset_password extends CI_Model {
		function User_model()
		{
			// Call the Model constructor
			parent::__construct();
		}
		function set_email_hash($id) // Updates database with a hash of the users username
		{
			$md5_user = md5($id.rand(100, 10000000).date('Y-m-d'));
			$sql = "UPDATE advertisers SET email_hash = ? WHERE id = ? AND active = 'y' LIMIT 1";

			$result = $this->db->query($sql, array($md5_user, $id));
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
			$query_str = "SELECT id FROM advertisers WHERE email = ? AND active = 'y' LIMIT 1";
			$result = $this->db->query($query_str, $email);
			if($result->num_rows()>0)
			{
				$row = $result->row(); 
				$result = $this->set_email_hash($row->id);
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
			$query_str = "SELECT id FROM advertisers WHERE email_hash = ? AND active = 'y' LIMIT 1";
			$result = $this->db->query($query_str, $token);
			if($result->num_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function change_password($token, $pwd) 
		{
			$query_str = "UPDATE advertisers SET password = ?, email_hash = ? WHERE email_hash = ? AND active = 'y' LIMIT 1";
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