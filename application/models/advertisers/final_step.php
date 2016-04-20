<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Final_step extends CI_Model {

	public function get_user_info() {
		$result = $this->db->get_where('advertisers', array('email_hash'=>$this->session->userdata('hash')));
		return $result->row();
	}
	
	public function check_for_terms()
	{
		$result = $this->db->get_where('advertisers', array('id'=>$this->session->userdata('userID'), 'active'=>'y'));
		if($result->num_rows()>0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function get_payment_details()
	{
		$results = $this->db->get_where('payments', array('user_id'=>$this->session->userdata('userID'), 'type'=>'advertiser', 'sub_id'=>$this->session->userdata('sub_id')));
		return $results->row();
		
	}
	
	public function line_item_payments($zips) //accepts array as arg of zips
	{
		$datas = array();
		foreach($zips as $val) {
			
			$this->db->select('city, stateAbv, city, advertiser_price');
			$results = $this->db->get_where('zips', array('zipCode'=>$val));
			if($results->num_rows()>0) {
				$datas[] = $results->row();
			}
		}
		return $datas;
	}
}