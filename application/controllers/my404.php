<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class my404 extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct(); 
		} 

		public function index() 
		{ 
			$this->output->set_status_header('404'); 
			if($this->session->userdata('side_logged_in') == '8468086465404') { //landlord
				$this->output->set_template('logged-in-landlord');	
			} else if($this->session->userdata('side_logged_in') == '898465406540564') { //tenants
				$this->output->set_template('logged-in');	
			} else if($this->session->userdata('side_logged_in') == '54986544688') {
				$this->output->set_template('associations/landlord-associations');
			} else {
				$this->output->set_template('landlord-not-logged-in');
			}
			
			
			$this->load->view('error_404');//loading in my template 
		} 
	} 
?> 