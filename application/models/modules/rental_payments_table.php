<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rental_payments_table extends CI_Model { 
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	// userColumn will either be tenant_id or landlord_id
	public function getRentalPaymentsByRentalId($rental_id, $userColumn, $limit = null) 
	{
		if(!empty($limit)) {
			$limit = 10;
		}
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('payment_history', array(
			'ref_id'=>$rental_id,
			$userColumn => $this->session->userdata('user_id')
		));
		return $query->result();
	}
	
	
}