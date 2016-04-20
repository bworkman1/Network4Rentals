<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_security extends CI_Model { 
	
	private $table;
	private $user;
	private $password;
	private $securityCode;
	private $salt = 'MR7RyUIi5WQiHZj_|EK_foTfyi2%nXa6EIlGRGZEnRtFoUMY 3MDS=XIqd3D';
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function runLogin($table, $user, $password, $remember) 
	{
		$this->table = $table;
		$this->user = $user;
		$this->password = $password;
		
		$userDetails = $this->checkForUser();
		if($userDetails) {
			
			if($remember=='y') {
				$this->getSecurityCode();
			}
			$this->logUserIn($userDetails);
			return true;
		} else {
			return false;
		}
	}
	
	/*
	*	Sets all the needed login cookies for the backend to run correctly
	*/
	private function logUserIn($user) 
	{
		$this->session->set_userdata('side', 'Renters');
		$this->session->set_userdata('user_id', $user->id);
		$this->session->set_userdata('logged_in', TRUE);

		$this->session->set_userdata('user_email', $user->email);
		$this->session->set_userdata('full_name', $user->name);
		$this->session->set_userdata('user_created', strtotime($user->sign_up));
		
		if($this->securityCode) {
			$keepUser = array(
				'name'   => 'logged_in',
				'value'  => $this->securityCode,
				'expire' => '31536000',
				'domain' => '.network4rentals.com',
				'path'   => '/',
				'secure' => TRUE
			);
			$this->input->set_cookie($keepUser);
		}
		
		if(empty($user->zip)) {
			if($this->type == 'renters') {
				$user->zip = $this->getRentalZip($user->id);
			} else {
				$user->zip = '43055';
			}
		}
		
		$cords = $this->getCords($user->zip);
		$this->session->set_userdata('lat', $cords['lat']);
		$this->session->set_userdata('long', $cords['lng']);
		
	}
	
	/*
	*	Retrives the zip code of the renter since their zip code is not stored with their account details
	*/
	private function getRentalZip($renterId)
	{
		$this->select('rental_zip');
		$query = $this->db->get_where('renter_history', array('tenant_id'=>$renterid, 'current_residence'=>'y'));
		$row = $query->row();
		return $row->rental_zip;
	}
	
	/*
	* 	Creates a securtity string for persistant logins
	*/
	private function getSecurityCode($userId) 
	{
		$this->securityCode = substr(md5(uniqid(rand(), true)), 0, 9);
		
		$this->db->where('id', $userId);
		$this->db->update($this->table, array('loginHash'=>$this->securityCode));
	}
	
	/*
	*	Checks the database for a match to the login details
	*/
	private function checkForUser() 
	{
		if($this->isEmail()) {
			$this->db->where('email', $this->user);
		} else {
			$this->db->where('user', $this->user);
		}
		if($this->table == 'landlords' || $this->table == 'renters') {
			$query = $this->db->get_where($this->table, array('pwd'=>md5($salt.$this->password)));
		} else {
			$query = $this->db->get_where($this->table, array('password'=>md5($salt.$this->password)));
		}
		
		if($query->num_rows()>0) {
			return $query->row();
		}
		return false;
	}
	
	/*
	*	Checks if username is email or username so users can login with username or email
	*/	
	private function isEmail() 
	{
		if (!filter_var($this->user, FILTER_VALIDATE_EMAIL) === false) {
			return true;
		} else {
			return false;
		}
	}
	
	private function getCords($zip)
	{
		$this->db->where('latitude !=', '');
		$query = $this->db->get_where('zips', array('zipCode'=>$zip));
		$row = $query->row();
		if(!empty($row->latitude) && !empty($row->longitude)) {
			$lat = $row->latitude;
			$lng = $row->longitude;
		} else {
			$lat = '40.079117';
			$lng = '-82.400543';
		}
		return array(
			'lat' => $lat,
			'lng' => $lng,
		);
	}
		
}
