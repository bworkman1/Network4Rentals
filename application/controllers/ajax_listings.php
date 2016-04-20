<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class ajax_Listings extends CI_Controller {
		function __construct()
		{
			parent::__construct();
		}
		
		
		function contact_landlord() 
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[60]|valid_email|xss_clean');
				$this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[14]|xss_clean|min_length[10]');
				$this->form_validation->set_rules('details', 'Question', 'trim|max_length[500]|xss_clean|min_length[15]');
				$this->form_validation->set_rules('name', 'Name', 'trim|max_length[40]|xss_clean|min_length[5]');
				$this->form_validation->set_rules('listing_id', 'id', 'trim|max_length[15]|xss_clean|min_length[1]|integer');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$phone = (int)$phone;
	
					if(!$this->valid_name($name)) {
						echo json_encode(array('errors'=>'Must use first and last name'));
						exit;
					}
				
					if(strlen($phone)!=10 && $phone != '') {
						echo json_encode(array('errors'=>'Phone must be 10 digits long'));
						exit;
					}
					
					$sentOne = $this->session->userdata('landlordMsg');
				
					$this->load->library('recaptcha');
					$this->recaptcha->recaptcha_check_answer();
				
					if(!$this->recaptcha->getIsValid()) {
						echo json_encode(array('errors'=>'Invalid recaptcha field'));
						exit;
					}
					
					$data = array(
						'email' => $email, 
						'phone' => $phone, 
						'details' => $details, 
						'name' => $name, 
						'id' => $listing_id
					);
					$this->load->model('listings/contact_landlord');
					if($this->contact_landlord->send_landlord_contact_form($data)) {
						echo json_encode(array('success'=>'Your information has been sent'));
						exit;
					} else {
						echo json_encode(array('errors'=>'Contact form failed to send, try again'));
						exit;
					}
					
				} else {
					echo json_encode(array('errors'=>validation_errors()));
				}
			}
		}
		
		private function valid_name($name)
		{
			$name_array = explode(' ', $name);
			if(count($name_array)<2) {
				return false;
			}
			return true;
		}
		
	}