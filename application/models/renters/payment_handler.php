<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Payment_handler extends CI_Model {
		
		function Payment_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function check_payment_settings($landlord_id, $group_id)
		{
			$this->db->limit(1);
			$this->db->select('net_api, net_hash, net_key, allow_payments, accept_cc, accept_echeck');
			$this->db->where('landlord_id', $landlord_id);
			if(!empty($group_id)) {
				$this->db->where('group_id', $group_id);
			} else {
				$this->db->where(array('group_id' => NULL));
			}
		
			$query = $this->db->get('payment_settings');
			if ($query->num_rows() > 0) {
				return $query->row();
			} else {
				return false;
			}
		}
		
		function get_landlord_payment_info($landlord_id)
		{
			$this->db->select('applied_auth, net_api, net_hash, net_key, allow_payments');
			$this->db->where('id', $landlord_id);
			$this->db->where('allow_payments', 'y');
			$query = $this->db->get('payment_settings');
			if ($query->num_rows() > 0) {
				return $query->row_array();
			} else {
				return false;
			}
		}
		
		function get_landlords_payment_data()
		{
			$this->db->select('id, link_id, group_id');
			$query = $this->db->get_where('renter_history', array('tenant_id'=>$this->session->userdata('user_id'), 'current_residence'=>'y'));
			$row = $query->row();
			if(!empty($row->link_id)) {
				if(!empty($row->group_id)) {
					$this->db->where('group_id', $row->group_id);
				}
				$this->db->where('landlord_id', $row->link_id);
				$this->db->limit(1);
				$query = $this->db->get('payment_settings');
				return $query->row();
			} else {
				return false;
			}
		}
		
		function record_payment($data)
		{
			$this->db->insert('payment_history', $data);
			$logId = $this->db->insert_id();
			if($data['recurring_payment'] == 'y') {
				$this->db->limit(1);
				$this->db->where('id', $data['ref_id']);
				$this->db->update('renter_history', array('auto_pay'=>$data['recurring_payment']));
			}
			
			return $logId;
		}
		
		function get_payment_notes($payment_id) 
		{
			$data = array('payment_id'=>$payment_id, 'tenant_id' => $this->session->userdata('user_id'));
			$results = $this->db->get_where('payment_notes', $data);	
			if($results->num_rows()>0) {
				$data = array();
				foreach ($results->result() as $row) {
					$row->ts = date('m-d-Y h:i a', strtotime($row->ts));
					$data[] = $row;
				}
				return $data;
			
			} else {
				return false;
			}
		}
		
		function check_for_valid_connection($data) //THIS FUNCTION WILL CHECK TO MAKE SURE THERE IS AN ACTUAL LINK BETWEEN THE TENANT AND LANDLORD
		{
			$data['ref_id'] = $data['payment_id'];
			$results = $this->db->get_where('renter_history', array('tenant_id'=>$data['tenant_id'], 'link_id'=>$data['landlord_id']));
			if($results->num_rows()>0) {
				$this->db->select('ref_id');
				$results = $this->db->get_where('payment_history', array('id'=>$data['ref_id'], 'landlord_id'=>$data['landlord_id'], 'tenant_id'=>$data['tenant_id']));
				if($results->num_rows()>0) {
					$row = $results->row();
					return $row->ref_id;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
			
		function add_new_payment_note($data)
		{
			$history_row = $this->check_for_valid_connection($data);
			
			if($history_row !== false) {
				$this->db->insert('payment_notes', $data);
				$row = $this->db->affected_rows();
				$action_id = $this->db->insert_id();
				if($row>0) {
					
					$info = array(
						'action'=> 'New note left on payment', 
						'user_id'=> $data['landlord_id'], 
						'type'=> 'landlords', 
						'action_id'=> $history_row.'/'.$data['payment_id']
					); 
					$this->add_activity_feed($info);
					
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		// add activity to activity feed
		function add_activity_feed($data) 
		{	
			//Required: action (what was the action) - user_id (who it belongs to) - type (renters / landlords) - action_id (id to link the activity to the action)
			$data['created'] = date('Y-m-d H:i:s');
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$this->db->insert('activity', $data); 
		}
		
		function cancel_rent_auto_pay($payment_id) 
		{
			$this->db->select('trans_id, landlord_id');
			$this->db->limit(1);
			$result = $this->db->get_where('payment_history', array('id'=>$payment_id, 'tenant_id'=>$this->session->userdata('user_id'), 'recurring_payment' => 'y'));
			if($result->num_rows()>0) {
				$payment_row = $result->row();
				$this->db->select('net_api, net_key'); //GET LANDLORDS AUTH SETTINGS
				$result = $this->db->get_where('payment_settings', array('landlord_id'=>$payment_row->landlord_id));
				if($result->num_rows()>0) {
					$row = $result->row();
					
					$this->load->library('encrypt');
					$data['net_api'] = $this->encrypt->decode($row->net_api); //LANDLORD PAYMENT SETTINGS
					$data['net_key'] = $this->encrypt->decode($row->net_key); //LANDLORD PAYMENT SETTINGS
					$data['refId'] = substr(md5( microtime() . 'ref' ), 0, 20);
					$data['subscriptionId'] = $payment_row->trans_id;
			
					$cancel = $this->cancel($data); // Send cancel request
				
					if(isset($cancel['success'])) {
						$refId = $this->authorize_arb->getRefId();
						$this->db->limit(1);
						$this->db->where('id', $payment_id);
						$refId = (string)$cancel['success'];
						$this->db->update('payment_history', array('recurring_payment'=>'n', 'cancel_ref_id'=>$refId));
						
						$this->db->where('tenant_id', $this->session->userdata('user_id'));
						$this->db->where('auto_pay', 'y');
						$this->db->update('renter_history', array('auto_pay'=>'n'));
						
						$this->add_activity_feed(array('action'=>'Cancelled Auto Payments', 'user_id'=>$this->session->userdata('user_id'), 'type'=>'renters', 'action_id'=>$payment_id)); //ACTIVITY TO RENTER
						
						$actionData = array('action'=>'Tenant Cancelled Auto Payments', 'user_id'=>$payment_row->landlord_id, 	'type'=>'landlords', 'action_id'=>$payment_id);
						
						$this->add_activity_feed($actionData); //ACTIVITY TO LANDLORD
						
						return array('success'=>'Your auto payments have been cancelled');
					} else {
						return array('error'=>$cancel['error']);
					}
					
				} else {
					return array('error'=>'Problem finding your landlord');
				}				
			} else {
				return array('error' => 'No auto payment found');
			}
		}
			
		function create_auto_payment($data, $user_payment_settings) 
		{
			$this->load->library('authorize_arb');	
			
			$this->authorize_arb->api_login_id = $user_payment_settings['id'];			// API Login ID
			$this->authorize_arb->api_transaction_key = $user_payment_settings['key'];	// API Transation Key
			//$this->authorize_arb->arb_api_url = 'test.authorize.net';	
			
			$this->authorize_arb->arb_api_url = 'https://api.authorize.net/xml/v1/request.api';
			
			$this->authorize_arb->startData('create');
			
			$name_array = explode(' ',$data['nameOnAccount']);
		
			$subscription_data = array(
				'name' => 'N4R Rental Payment Subscription',
				'paymentSchedule' => array(
					'interval' => array(
						'length' => 1,
						'unit' => 'months',
						),
					'startDate' => $data['startDate'],
					'totalOccurrences' => 9999, // Unlimited
				),
				'amount' => $data['amount'],
				'payment' => array(
					'bankAccount' => array(
						'accountType' => $data['accountType'],
						'routingNumber' => $data['routingNumber'],
						'accountNumber' => $data['accountNumber'],
						'nameOnAccount' => substr($data['nameOnAccount'], 0, 20),
						'echeckType' => $data['echeckType'],
						'bankName' => $data['bankName'],
						),
				),
				'billTo' => array(
					'firstName' => $name_array[0],
					'lastName' => end($name_array),
					'address' => $data['address'],
					'city' => $data['city'],
					'state' => $data['state'],
					'zip' => $data['zip'],
					'country' => 'US',
				),
			);
			
			
			$this->authorize_arb->addData('subscription', $subscription_data);
			
			
			if( $this->authorize_arb->send() ) { //SEND PAYMENT
				return array('success'=>$this->authorize_arb->getId());
			} else {
				return array('error'=>$this->authorize_arb->getError());
			}
			
		}
				
		function create_one_time_payment($payment_values)
		{
			$this->load->library('authorize_net');
			$this->authorize_net->setData($payment_values);
			if($this->authorize_net->authorizeAndCapture()) {
				return array('success'=>$this->authorize_net->getTransactionId());
			} else {
				return array('error'=>$this->authorize_net->getError());
			}
		}
		
		function cancel($data)
		{	
			// Load the ARB lib
			$this->load->library('authorize_arb');	
			
			$this->authorize_arb->api_login_id = $data['net_api'];
			$this->authorize_arb->api_transaction_key = $data['net_key'];
			$this->authorize_arb->arb_api_url = 'https://api.authorize.net/xml/v1/request.api';
			
			// Start with a cancel object
			$this->authorize_arb->startData('cancel');
			
			$this->authorize_arb->addData('refId', $data['refId']);
			$this->authorize_arb->addData('subscriptionId', (int)$data['subscriptionId']);
			
			
			
			// Send request
			if( $this->authorize_arb->send() ) {
				return array('success'=>$this->authorize_arb->getRefId());
			} else {
				return array('error'=>$this->authorize_arb->getError());
			}
		}
		
	}