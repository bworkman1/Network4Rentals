<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Account_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        } 

		public function profile_info()
		{
			$results = $this->db->get_where('advertisers', array('id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}
		}

		public function updates()
		{
			$results = $this->db->get('updates_version');
			if($results->num_rows()>0) {
				return $results->result();
			} else {
				return false;
			}	
		}
		
		public function get_payment_details()
		{
			$results = $this->db->get_where('payments', array('user_id'=>$this->session->userdata('user_id'), 'type'=>'advertiser', 'active'=>'y')); //Will need updated - might return multiple rows
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}
		}
		
		public function subscription_details()
		{
			$results = $this->db->get_where('advertiser_zips', array('advertiser_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				foreach ($results->result() as $row) {
					$zip = $row->zip_purchased;
					$this->db->select('advertiser_price');
					$query = $this->db->get_where('zips', array('zipCode'=>$zip));
					$r = $query->row();
					$price = $r->advertiser_price;
					$row->price = $price;
					$data[] = $row;
				}
				return $data;
			} else {
				return false;
			}	
		}
		
		public function get_subscription_id()
		{
			$this->db->select('sub_id');
			$results = $this->db->get_where('payments', array('user_id'=>$this->session->userdata('user_id'), 'type'=>'advertiser', 'active'=>'y'));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row->sub_id;
			} else {
				return false;
			}
		}
		
		public function set_order_sessions()
		{				
			$array_items = array('zips' => '', 'service' => '', 'price' => '', 'city' => '', 'state' => '');
			$this->session->unset_userdata($array_items);
				
			$this->db->select('options');
			$results = $this->db->get_where('payments', array('user_id'=>$this->session->userdata('user_id'), 'active'=>'y', 'type'=>'advertiser'));
			if($results->num_rows()>0) {
				$row = $results->row();
				$zips = array();
				$service = array();
				$price = array();
				$city = array();
				$state = array();
				
				$options = $row->options;
				$options_split = explode('|',$options); //43025-1
				foreach($options_split as $val) {
					$item = explode('-',$val);	
					$zips[] = $item[0];
					$service[] = $item[1];
					$this->db->select('contractor_price, stateAbv, city');
					$results = $this->db->get_where('zips', array('zipCode'=>$item[0]));
					if ($results->num_rows() > 0) {
						$row = $results->row();
						$price[] = $row->contractor_price;
						$city[] = $row->city;
						$state[] = $row->stateAbv;
					}
				}
				$this->session->set_userdata('zips', $zips);
				$this->session->set_userdata('service', $service);
				$this->session->set_userdata('price', $price);
				$this->session->set_userdata('city', $city);
				$this->session->set_userdata('state', $state);

				return true;
			} else {
				return false;
			}
			
		}
		
		public function update_password($password) 
		{
			$this->db->where('id', $this->session->userdata('user_id'));
			$results = $this->db->update('advertisers', array('password'=> $password));
			$updated = $this->db->affected_rows();

			if($updated>0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function update_personal_info($data)
		{
			$this->db->where('id', $this->session->userdata('user_id'));
			$results = $this->db->update('advertisers', $data);
			$updated = $this->db->affected_rows();
			if($updated>0) {
				return true;
			} else {
				return false;
			}
		}
		
    }


