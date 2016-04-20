<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Login_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        }

		function login($data)
		{
			$results = $this->db->get_where('advertisers', $data);
			if($results->num_rows()>0) {
				$row = $results->row();
				//get ip address
				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $_SERVER['REMOTE_ADDR'];
				}
				$this->db->where('id', $row->id);
				$this->db->update('advertisers', array('ip'=>$ip));

				$this->session->set_userdata('logged_in', true);
				$this->session->set_userdata('ad_zipCode', $row->zip);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('email', $row->email);
				$this->session->set_userdata('side_logged_in', 'local-partner');
				$this->session->set_userdata('side', 'advertiser');
				$this->session->set_userdata('affiliate_id', $row->affiliate_id);
				
				$this->session->set_userdata('created', strtotime($row->sign_up));
				
				$cords = $this->getCords($row->zip);
				$this->session->set_userdata('lat', $cords['lat']);
				$this->session->set_userdata('long', $cords['lng']);
				$this->session->set_userdata('name', $row->f_name.' '.$row->l_name);
				
				return true;
				
			} else {
				return false;
			}
		}
		
		function check_persistant_login($data) 
		{
			$this->load->library('encrypt');
			$key = $this->encrypt->decode($data['key']);
			$key = explode($key, '/');
			$results = $this->db->get_where('advertisers', array('id'=>$key[0], 'email_hash'=>$data['hash']));
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->session->set_userdata('logged_in', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('side_logged_in', '54688486846464');
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


