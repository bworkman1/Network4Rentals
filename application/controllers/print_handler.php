<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_handler extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->_init();
	}
	
	function _init() 
	{
		$this->output->set_template('blank');
		$this->load->helper(array('dompdf', 'file'));
	}
	
	function print_service_request()
	{
		//https://network4rentals.com/network/print_handler/print_service_request/472
		$id = $this->uri->segment(3);		
		if(empty($id)) {
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
		if(strlen($id)>10) {
			$hash = true;
		} else {
			$hash = false;
		}
		
		
		$this->output->set_template('blank');
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('special/ad_handler');
		$this->load->model('special/service_request_handler');
		$data = $this->service_request_handler->build_service_request($id, $hash);
		
		$ad_specs = array('service'=>$data['request']->service_type, 'zip'=>$data['rental']->rental_zip, 'current_ads'=>$data['request']->ad_ids, 'request_id'=>$data['request']->id);
		
		$data['ad_post'] = $this->ad_handler->get_service_request_ads($ad_specs);	
		$data['suppliers'] = $this->ad_handler->getSupplyHouses($data['rental']->rental_zip, $data['request']->service_type); 
		
		if(empty($data['request'])) {
			$this->session->set_flashdata('error', 'Invalid selection try again');
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		} else {
			$this->load->view('prints/service-request', $data); // Add Argument true after data
			$html = $this->load->view('prints/service-request', $data, true); // Add Argument true after data
			pdf_create($html, 'Service_Reqeust_'.$data['rental']->address);
		}
		
	}
	
}