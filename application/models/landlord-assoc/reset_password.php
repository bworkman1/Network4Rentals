<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class reset_password extends CI_Model {
		
		function Reset_password()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function reset($email) 
		{
			$value = $this->check_email_exists(urldecode($email));
			return $value;
		}
		
		function check_email_exists($email) 
		{
	
			$results = $this->db->get_where('landlord_assoc', array('email'=>$email));
			if($results->num_rows()>0) {
				$hash = $this->set_hash($email);
				if($hash != false) {
					//Send Email
					$result = $this->send_user_email($email, $hash);
					if($result) {
						return array('success'=>'Email sent to '.$email.' with password reset instructions');
					} else {
						return array('error'=>'Email failed to send, try again');
					}
				} else {
					return array('error'=>'Invalid email, try again');
				}
			} else {
				return array('error'=>'Invalid email, try again');
			}
		}
		
		function set_hash($email) 
		{
			$hash = md5($_SERVER['REMOTE_ADDR'].$email.rand(100, 10000000));
			$this->db->where('email', $email);
			$this->db->update('landlord_assoc', array('hash'=>$hash));
			if($this->db->affected_rows()>0) {
				return $hash;
			} else {
				return false;
			}
		}
			
		function send_user_email($email, $hash) 
		{
			$link = 'http://network4rentals.com/network/landlord-associations/reset-password/'.$hash;
			$this->load->model('landlord-assoc/email_handler');
			$subject = 'Password Reset N4R';
			$message = '<h3>Password Reset Attempt</h3><p>Your password has been requested to be reset. If you didn\'t request this then disregard this email otherwise click on the link below and to reset your password.</p><p><a href="'.$link.'">Reset Password</a></p>';
			if($this->email_handler->sendEmail($email, $message, $subject)) {
				return true;
			} else {
				return false;
			}
		}
		
		function update_password($data) 
		{	
			$this->db->limit(1);
			$this->db->where('hash', $data['hash']);
			$results = $this->db->get_where('landlord_assoc', array('hash'=>$data['hash']));
			if($results->num_rows()>0) {
				$this->db->limit(1);
				$results = $this->db->update('landlord_assoc', array('password'=>md5($data['pwd'])));
				if($this->db->affected_rows()>0) {
					$this->remove_hash($data['hash']);
					$this->session->unset_userdata('failed_password_attempts');
				} else {
					return array('error'=>'This is already your password, login with this password or enter a different password');
				}
			} else {
				$failed = $this->session->userdata('failed_password_attempts');
				$this->session->set_userdata('failed_password_attempts', $failed+1);
				return array('error'=>'No user found');
			}
		}
		
		function remove_hash($hash) 
		{
			$this->db->limit(1);
			$this->db->where('hash', $hash);
			$this->db->update('landlord_assoc', array('hash'=>''));
		}
		
	}