<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Sms_handler extends CI_Model {
		
		function send_sms($tenant_id, $message) 
		{
			$tenant_details = $this->grab_tenant_settings($tenant_id);
			if($tenant_details !== FALSE) {
				if($tenant_details->sms_msgs == 'y') {
					$tenant_details->message = $message;
					if($this->send_data_message($tenant_details)) {
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
	
	
		function grab_tenant_settings($id)
		{
			$this->db->limit(1);
			$this->db->select('cell_phone, sms_msgs');
			$results = $this->db->get_where('renters', array('id'=>$id));
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
		
		public function send_data_message_by_array($data)
		{			
			$this->load->library('plivo');
			$sms_data = array(
				'src' => '13525593099',
				'dst' => '1'.$data['cell_phone'],
				'text' => $data['message'],
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
	}