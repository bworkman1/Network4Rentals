<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Login extends CI_Model {
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function check_login_details($email, $password)
		{
			
			$salt = 'Z|ukCY8Ue3csIGy1w+sO2WTlcH%HKIoRx EFRBxD7hfONEyt=PGbJ_=aKNl';
			$this->db->limit(1);
			$results = $this->db->get_where('admins', array('email'=>$email, 'password'=>md5($password.$salt), 'active'=>'y'));
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			$ip = $_SERVER['REMOTE_ADDR'];
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->db->where('id', $row->id);
				$this->db->update('admins', array('ip'=>$ip, 'proxy_ip'=>$proxy_ip, 'last_login'=>date('Y-m-d H:i:s')));
				$this->session->set_userdata('user_id', $row->id);
				return array('super'=>$row->super_admin, 'name'=>$row->name);
			} else {
				return false;
			}
		}
		
		function check_email($email)
		{
			$results = $this->db->get_where('admins', array('ip'=>$_SERVER['REMOTE_ADDR'], 'email'=>$email));
			if($results->num_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
	}