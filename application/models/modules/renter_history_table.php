<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Renter_history_table extends CI_Model { 
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getCurrentRentalByRow($id) 
	{
		$query = $this->db->get_where('renter_history', array('tenant_id'=>$id, 'current_residence'=>'y'));
		return $query->row();
	}
	

	
}