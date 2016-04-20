<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_handler extends CI_Model {
	
	var $table = 'landlords';
	
	function login_handler()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function check_login($username, $password) 
	{
		$md5_password = md5($password);
		if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
			$column = 'user';
		} else {
			$column = 'email';
		}
		
		$query = $this->db->get_where('landlords', array(
			$column => $username,
			'pwd' => $md5_password,
		));
		
		if($query->num_rows()>0) {
			$row = $query->row();

            $this->session->set_userdata('side_logged_in', '8468086465404');
            $this->session->set_userdata('user_id', $row->id);
            $this->session->set_userdata('logged_in', TRUE);
            $this->session->set_userdata('username', $row->user);
            $this->session->set_userdata('side', 'Landlord');
            $this->session->set_userdata('user_email', $row->email);
            $this->session->set_userdata('full_name', $row->name);
            $this->session->set_userdata('user_created', strtotime($row->sign_up));

            $cookie = array(
                'name'   => 'logged_in',
                'value'  => '1',
                'expire' => '86500',
                'domain' => '.network4rentals.com',
                'path'   => '/',
                'secure' => TRUE
            );
            $this->input->set_cookie($cookie);

            $cords = $this->getCords($row->zip);
            $this->session->set_userdata('lat', $cords['lat']);
            $this->session->set_userdata('long', $cords['lng']);
            $this->session->set_userdata('name', $row->f_name.' '.$row->l_name);

            return $row;
		}
		return false;
	
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