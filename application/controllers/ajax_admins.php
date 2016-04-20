<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Ajax_admins extends CI_Controller {
		
		function __construct()
		{
			parent::__construct();
			$this->output->set_template('json');
		}
		
		function checkLogin()  
		{
			$found = false;
			$loggedIn = $this->session->userdata('adminLoggedIn');
			$n4rlanreq = $this->session->userdata('n4rlanreq');
			if($loggedIn) {
				$this->load->model('admin/login');
				$found = $this->login->check_email($n4rlanreq);
			}
			
			
			if($found === false) {
				$this->session->sess_destroy();
				exit;
			}
		}
		
		function get_user_details_chart()
		{
			$this->checkLogin();
			$data = array();
			for($i=0;$i<10;$i++) {
				$row = array();
				$signedUp = date('Y-m-d', strtotime(date('Y-m-d H:i:s') . ' -'.$i.' day'));
				$userTypes = array('landlords', 'renters', 'contractors', 'advertisers');
				
				foreach($userTypes as $val) {
					if($val == 'contractors') {
						$sel = 'created';
					} else {
						$sel = 'sign_up';
					}
					$sql = 'SELECT id FROM '.$val.' WHERE '.$sel.' BETWEEN "'.$signedUp.' 00:00:00" AND "'.$signedUp.' 23:59:59"';
					$results = $this->db->query($sql);
					$row[$val] = $results->num_rows();
				}$row['period'] = $signedUp;
				$data[] = $row;
			}
			
			echo json_encode(array_values($data));
			
		}
		
		public function add_supply_house() 
		{	
			$this->checkLogin();
			$this->form_validation->set_rules('name', 'Business Name', 'required|trim|max_length[50]|xss_clean');
			$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[50]|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'required|trim|max_length[30]|xss_clean');
			$this->form_validation->set_rules('state', 'State', 'required|trim|max_length[2]|xss_clean|alpha');
			$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[10]|max_length[14]|xss_clean');
			$this->form_validation->set_rules('ad_areas', 'Service Area Zips', 'required|trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('ad_services', 'Ad Service Types', 'required|trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('url', 'Url', 'prep_url|trim|max_length[200]|xss_clean');
			$this->form_validation->set_rules('resource_types', 'Resource Service Types', 'required|trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('affiliate', 'User Affliate Account', 'trim|min_length[1]|max_length[1]|xss_clean');
			$this->form_validation->set_rules('unique', 'Unique Link Name', 'trim|min_length[5]|max_length[40]|xss_clean|is_unique[landlord_page_settings.unique_name]');
			$this->form_validation->set_rules('email', 'Valid Email', 'trim|min_length[5]|max_length[60]|xss_clean|valid_email');
			$this->form_validation->set_rules('affiliate_id', 'Affiliate Id', 'trim|min_length[20]|max_length[45]|xss_clean');

			if($this->form_validation->run() == FALSE) {
				$feedback = array('error'=>validation_errors());
			} else { 
				// process user input and login the user
				extract($_POST);
				
				$this->load->model('admin/supply_houses');
				$this->load->model('special/user_uploads');
				
				$data = array(
					'business'=>ucwords($name),
					'address'=>ucwords($address),
					'city'=>ucwords($city),
					'state'=>$state,
					'phone'=>$phone,
					'ad_service_types'=>$resource_types,
					'ad_areas'=>$ad_areas,
					'resource_service_types'=>$ad_services,
					'url'=>$url,
					'affiliate'=>$affiliate,
					'unique' => $unique,
					'email' => $email,
					'affiliate_id' => $affiliate_id,
				);
			
				$uploadLogo = $this->user_uploads->upload_image($_FILES['file_0'], 'file_0');
				
				if($affiliate=='y') {
					$uploadBackground = $this->user_uploads->upload_image($_FILES['file_1'], 'file_1', null, $args['resize']=true);
				}
				
				if(isset($uploadLogo['error']) || isset($uploadBackground['error'])) {
					$feedback = array('error'=>$uploadBackground['error']);
					$feedback = array('error'=>$uploadLogo['error']);
				} else {
					$data['logo'] = $uploadLogo['success']['system_path'];
					$data['background'] = $uploadBackground['success']['system_path'];
				
					$created = $this->supply_houses->addSupplyHouse($data);
					if($created === true) {
						$this->session->set_flashdata('success', '<div class="alert alert-success"><b>Success:</b> Supply House Created Successfully</div>');
						$feedback = array('success'=>'created');
					} else {
						$feedback = array('error'=>$created);
					}
				}
			}
			
			echo json_encode($feedback);
			exit; 
			
		} 
		
		public function edit_supply_house() 
		{	
			$this->checkLogin();
			$this->form_validation->set_rules('name', 'Business Name', 'required|trim|max_length[50]|xss_clean');
			$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[50]|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'required|trim|max_length[30]|xss_clean');
			$this->form_validation->set_rules('state', 'State', 'required|trim|max_length[2]|xss_clean|alpha');
			$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[10]|max_length[14]|xss_clean');
			$this->form_validation->set_rules('ad_areas', 'Service Area Zips', 'required|trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('ad_services', 'Ad Service Types', 'required|trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('url', 'Url', 'prep_url|trim|max_length[200]|xss_clean');
			$this->form_validation->set_rules('resource_types', 'Resource Service Types', 'required|trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('affiliate', 'User Affliate Account', 'trim|min_length[1]|max_length[1]|xss_clean');
			$this->form_validation->set_rules('unique', 'Unique Link Name', 'trim|min_length[5]|max_length[40]|xss_clean|');
			$this->form_validation->set_rules('email', 'Valid Email', 'trim|min_length[5]|max_length[60]|xss_clean|valid_email');
			$this->form_validation->set_rules('id', 'Supply House Account Id', 'trim|required|min_length[1]|max_length[60]|xss_clean|intger');
			$this->form_validation->set_rules('affiliate_id', 'Affiliate Id', 'trim|min_length[20]|max_length[45]|xss_clean');
			
			if($this->form_validation->run() == FALSE) {
				$feedback = array('error'=>validation_errors());
			} else {  
				// process user input and login the user
				extract($_POST);
				
				$this->load->model('admin/supply_houses');
				$this->load->model('special/user_uploads');
				
				
				//ad_service_types 
				/*
				$ad_areas= "43055|43056|43830";
				$ad_services= "6";
				$address= "83 Dayton Rd";
				$affiliate= "y";
				$affiliate_id= "7a8951d5f54d5c5da63a262726dfd994";
				$city= "Newark";
				$email= "moore.1666@gmail.com";
				$id= "24";
				$name= "American Integrity Electric";
				$phone= "7403451243";
				$resource_types= "6";
				$state= "OH";
				$unique= "americanintegrityelectric";
				$url= "http=//americanintegrityelectricsupply.webs.com/"; */
				
				if($this->supply_houses->checkUniuqeName($unique, $id)) {
					echo json_encode(array('error'=>'Unique link name already taken, try another one'));
					exit; 
				}
				
				$data = array(
					'business'=>ucwords($name),
					'address'=>ucwords($address),
					'city'=>ucwords($city),
					'state'=>$state,
					'phone'=>$phone,
					'ad_service_types'=>$ad_services,
					'ad_areas'=>$ad_areas,
					'resource_service_types'=>$resource_types,
					'url'=>$url,
					'affiliate'=>$affiliate,
					'unique' => $unique,
					'email' => $email,
					'affiliate_id' => $affiliate_id,
				); 
				
				if(!empty($_FILES['file_0'])) {
					$uploadLogo = $this->user_uploads->upload_image($_FILES['file_0'], 'file_0');
				}
				
				if($affiliate=='y') {
					if(!empty($_FILES['file_1'])) {
						$uploadBackground = $this->user_uploads->upload_image($_FILES['file_1'], 'file_1', null, $args['resize']=true);
					}
				}
				
				if(isset($uploadLogo['error']) || isset($uploadBackground['error'])) {
					$feedback = array('error'=>$uploadBackground['error']);
					$feedback = array('error'=>$uploadLogo['error']);
				} else {
					if(isset($uploadLogo)) {
						$data['logo'] = $uploadLogo['success']['system_path'];
					}
					if(isset($uploadBackground)) {
						$data['background'] = $uploadBackground['success']['system_path'];
					}
				
					$updated = $this->supply_houses->editSupplyHouse($data, $id);
					if($updated === true) {
						$this->session->set_flashdata('success', '<div class="alert alert-success"><b>Success:</b> Supply House Saved Successfully</div>');
						$feedback = array('success'=>$data['logo']);
					} else {
						$feedback = array('error'=>$updated);
					}
				}
			}
			
			echo json_encode($feedback);
			exit; 
			
		} 
		
		
		
	
	}
	
	