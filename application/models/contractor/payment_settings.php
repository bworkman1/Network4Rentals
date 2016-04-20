<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Payment_settings extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
		
		public function getUserSettings($id)
		{
			$this->load->library('encrypt');
			$result = $this->db->get_where('local_payment_settings', array('user_id'=>$id, 'type'=>'contractor'));
			
			$data = $result->row();
			if($data) {
				$data->net_api = $this->encrypt->decode($data->net_api);
				$data->net_key = $this->encrypt->decode($data->net_key);
			}
			return $data;
		}
		
		public function saveUserSettings($data) 
		{
			//credit_card_discount	e_check_discount
			$this->form_validation->set_rules('net_api', 'API Login Id', 'min_length[2]|max_length[100]|xss_clean|required');
			$this->form_validation->set_rules('net_key', 'API Key', 'min_length[2]|max_length[100]|xss_clean|required');
			$this->form_validation->set_rules('allow_payments', 'Allow Payments', 'min_length[1]|max_length[1]|xss_clean|required');
			
			$this->form_validation->set_rules('accept_cc', 'Accept Credit Card', 'min_length[1]|max_length[1]|xss_clean');
			$this->form_validation->set_rules('accept_echeck', 'Accept E-Check', 'min_length[1]|max_length[1]|xss_clean');
			$this->form_validation->set_rules('min_payment', 'Min Payment', 'min_length[1]|max_length[10]|xss_clean');
			
			$this->form_validation->set_rules('credit_card_discount', 'Credit Card Discount', 'min_length[1]|max_length[10]|xss_clean');
			$this->form_validation->set_rules('e_check_discount', 'E Check Discount', 'min_length[1]|max_length[10]|xss_clean');
			
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				extract($_POST);
				$this->load->library('encrypt');
				$settings = array(
					'net_api'				=> $this->encrypt->encode($net_api),
					'net_key'				=> $this->encrypt->encode($net_key),
					'allow_payments'		=> $allow_payments == 'y'?'y':'n',
					'accept_cc'				=> $accept_cc == 'y'?'y':'n',
					'accept_echeck'			=> $accept_echeck == 'y'?'y':'n',
					'user_id'				=> $this->session->userdata('user_id'),
					'type'					=> 'contractor',
					'min_payment'			=> $min_payment == ''?'0':$min_payment,
					'credit_card_discount'	=> $credit_card_discount == ''?'0':$credit_card_discount,
					'e_check_discount'		=> $e_check_discount == ''?'0':$e_check_discount,
				);
		
				$result = $this->db->get_where('local_payment_settings', array('type'=>'contractor', 'user_id'=>$this->session->userdata('user_id')));
				if($result->num_rows()>0) {
					$this->db->where('user_id', $this->session->userdata('user_id'));
					$this->db->where('type', 'contractor');
					$this->db->update('local_payment_settings', $settings);
				} else {
					$this->db->insert('local_payment_settings', $settings);
				}
				if($this->db->affected_rows()>0) {
					$this->session->set_flashdata('success', 'Settings saved successfully');
					return true;
				}
				$this->session->set_flashdata('error', 'Settings failed to save');
				return false;
			}
		}
			
	}