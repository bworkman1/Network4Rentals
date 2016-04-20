<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Update_payment_details extends CI_Model {	
			
        public function __construct()
		{
            parent::__construct();
        }
		
		public function update_payment($data) 
		{
			/*
				CREATE NEW ACCOUNT TO TEST THIS DATA
			
			*/
			$landlord = $this->get_user_data();
			$this->load->library('authorize_arb');
			
			$this->authorize_arb->startData('update');
			echo $landlord->sub_id;
			$this->authorize_arb->addData('subscriptionId', $landlord->sub_id);
			$subscription_data = array(
					'payment' => array(
						'creditCard' => array(
							'cardNumber' => '4111111111111111',
							'expirationDate' => '2016-08',
							'cardCode' => '123',
							),
						),
					'billTo' => array(
						'firstName' => 'Bill',
						'lastName' => 'Gates',
						'address' => '123 Green St',
						'city' => 'Lexington',
						'state' => 'KY',
						'zip' => '40502',
						'country' => 'US',
						),
					);
			$this->authorize_arb->addData('subscription', $subscription_data);
			$this->authorize_arb->send();
			
			$error = $this->authorize_arb->getError();
			$subscription_id = $this->authorize_arb->getId();
			
			if (empty($error)) {
				if(!empty($subscription_id)) {
					$test = array('success'=>$subscription_id);
				} else {
					$test = array('error'=>'Failed to create subscription');
				}
			} else {
				$test = array('error'=>$error);
			}
			echo '<pre';
			print_r($test);
			echo '</pre>';
		}
						
		private function get_user_data()
		{
			$this->db->select('baddress, bcity,	bstate,	bzip, phone, sub_id');
			$results = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}
		}
		
	} //EOF