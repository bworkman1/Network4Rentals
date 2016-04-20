<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Action_needed extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

	public function run_check()
	{
		$data = array(
			'public_page_set' => $this->checkForPublicPage(),
			'password_set' => $this->checkForPasswordSet(),
			'analytics_set' => $this->checkForAnalyticsSetup()
		);
		return $data;
	}	
	
	private function checkForPublicPage()
	{
		$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'affiliate'));
		if($query->num_rows()>0) {
			return true;
		}
		return false;
	}
	
	private function checkForPasswordSet() 
	{
		$query = $this->db->get_where('affiliate_users', array('password'=>'PASSWORDNEEDSSET', 'id'=>$this->session->userdata('user_id')));
		if($query->num_rows()>0) {
			return false;
		}
		return true;
	}
	
	public function checkForAnalyticsSetup() 
	{
		$query = $this->db->get_where('affiliate_users', array('id'=>$this->session->userdata('user_id')));
		$row = $query->row();
		if( !empty($row->analytics_id) && !empty($row->account_id) ) {
			return true;
		}
		return false;
	}
	
}