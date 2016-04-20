<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Login_handler extends CI_Model {

		private $salt;
		private $user;
		private $pass;
		private $appId;
		private $maxTries = 8;
		private $tries;
		
        public function __construct() {
            parent::__construct();
        }
		
		public function userLogin($username, $password, $app_id, $salt)
		{
			$this->user = $username;
			$this->password = $password;
			$this->salt = $salt;
			$this->appId = $app_id;
	
			return json_encode($this->checkIfUserExists());
		}
		
		private function createSecureAppId($id, $email) 
		{
			$groupString = $id.'|'.$email.'|'.$this->salt;
			$this->appId = $this->encrypt->encode($groupString);
		}
		
		private function decreyptSecureAppId($id)
		{
			$plainText = $this->encrypt->decode($id);
			$userArray = explode('|', $plainText);
			if(!end($userArray) == $this->salt) {
				return json_encode(array(
					"error" => "Incorrect App Id Detected"
				));
			} else {
				return $userArray;
			}
		}
		
		private function checkIfUserExists()
		{
			if(!$this->validateEmail()) {
				return array(
					"error" => "Invalid email address ".$this->user
				);
			}
			
			if(!$this->validPassword()) {
				return array(
					"error" => "Invalid password"
				);
			}
			
			$query = $this->db->get_where("renters", array(
				"email" => $this->user,
				"pwd" => md5($this->password),
			));
			
			if($query->num_rows()>0) {
				$row = $query->row();
				$this->session->set_userdata("user_id", $row->id);
				$this->session->set_userdata("email", $row->email);
				$this->session->set_userdata("side", "renters");
				
				if(empty($row->androidId)) {
					$this->createSecureAppId($row->id, $row->email);
					$this->db->where('id', $row->id);
					$this->db->update('renters', array('androidId' => $this->appId));
				} else {
					$this->appId = $row->androidId;
				}
				
				$this->session->set_userdata("app_id", $this->appId);
				
				
				return array(
					"success" => $this->appId
				);
			}
			
			return array("error" => "Invalid email or password");
		}
		
		private function validateEmail() 
		{
			return true;
			if (!filter_var($this->user, FILTER_VALIDATE_EMAIL)) {
				return false;
			}
		}
		
		private function validPassword() 
		{
			return true;
		}
		
	}