<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Login_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        }

		function login($data)
		{
			$results = $this->db->get_where('contractors', $data);
			if($results->num_rows()>0) {
				$row = $results->row();
				if($row->promo === 'y' && $row->active === 'n') {
					$this->session->set_userdata('terms', true);
				} elseif($row->promo === 'n' && $row->active === 'n') {
					return false;
				}
				
				//get ip address
				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $_SERVER['REMOTE_ADDR'];
				}
				$this->db->where('id', $row->id);
				$this->db->update('contractors', array('ip'=>$ip));
				$this->session->set_userdata('logged_in', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('side_logged_in', '203020320389822');
				
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
			$results = $this->db->get_where('contractors', array('id'=>$key[0], 'email_hash'=>$data['hash']));
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->session->set_userdata('logged_in', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('side_logged_in', '203020320389822');
				return true;
			} else {
				return false;
			}
		}
		
    }


