<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Sms_handler extends CI_Model {
		
		function send_sms($landlord_id, $message) 
		{
			$landlord_details = $this->grab_landlord_settings($landlord_id);
			if($landlord_details !== FALSE) {
				if($landlord_details->sms_msgs == 'y') {
					$landlord_details->message = $message;
					if($this->send_data_message($landlord_details)) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			
			} else {
				return false;
			}
		}
	
		
	
		function grab_landlord_settings($id)
		{
			$this->db->limit(1);
			$this->db->select('cell_phone, sms_msgs, forwarding_sms_msgs, forwarding_cell');
			$results = $this->db->get_where('landlords', array('id'=>$id));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return FALSE;
			}
		}
	
		public function send_data_message($data)
		{			
			$this->load->library('plivo');
			$sms_data = array(
				'src' => '13525593099',
				'dst' => '1'.$data->cell_phone,
				'text' => $data->message,
				'type' => 'sms', 
				'url' => base_url().'landlords/',
				'method' => 'POST',
			);

			$response_array = $this->plivo->send_sms($sms_data);
			if ($response_array[0] == '200' || $response_array[0] == '202') {
				//$data["response"] = json_decode($response_array[1], TRUE);
				return true;
			} else {
				return false;
			}
		}
		
		public function send_sms_reminders() 
		{
			$curdate = date('Y-m-d');
			$mydate=getdate(strtotime($curdate));
			switch($mydate['wday']){
				case 0: // sun
					$days = 6;
					break;
				case 1: // mon
					$days = 7;
					break;
				case 2: // tues  
					$days = 7;
					break;
				case 3: // wed
					$days = 7;
					break;
				case 4: // thur
					$days = 7;
					break;
				case 5: // fri
					$days = 7;
					break;
				case 6: // sat
					$days = 6;
					break;
			}
			$day_reminder = date('d', strtotime("$curdate +$days days"));

			$this->db->select('tenant_id');
			$results = $this->db->get_where('renter_history', array('current_residence'=>'y', 'day_rent_due'=>$day_reminder));
			if($results->num_rows()>0) {
				$rental_info = $results->result();
				foreach($rental_info as $key => $val) {
					$this->db->select('cell_phone', 'sms_msgs');
					$data = $this->db->get_where('renters', array('id'=>$val->tenant_id, 'sms_msgs'=>'y'));
					if($data->num_rows()>0) {
						$row = $data->row();
				
						if($row->cell_phone !='') {
							$link = base_url().'renters/pay-rent/';
							$sms_data = new stdClass();
							$sms_data->cell_phone = $row->cell_phone;
							$sms_data->message = 'Pay/Record Rent Payment @ '.$link;
							$this->send_data_message($sms_data);
						}
					}
					
				}
			}
		}
		
		
		function send_Forwarding_SMS($landlord_id, $message)
		{
		
			
			$landlord_details = $this->grab_landlord_settings($landlord_id);
			
			
			if($landlord_details !== FALSE) {
				if($landlord_details->forwarding_sms_msgs == 'y') {
			
					$data = new stdClass();
					$data->message = $message;
					$data->cell_phone = $landlord_details->forwarding_cell;
					
					if($this->send_data_message($data)) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
				
			} else {
				return false;
			}
		}
		
	}