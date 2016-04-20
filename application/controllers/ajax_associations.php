<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Ajax_associations extends CI_Controller {
		function __construct()
		{
			parent::__construct();
		}
		
		private function check_login()
		{
			$logged_in = $this->session->userdata('logged_in');
			$user_id = $this->session->userdata('user_id');
			if(!$logged_in) {
				redirect('landlord-associations');
				exit;
			}
			if($this->session->userdata('side_logged_in')!=='54986544688') {
				redirect('landlord-associations');
				exit;
			}
			if($user_id<1) {
				redirect('landlord-associations');
				exit;
			}
		}
			
		public function check_coupon_code()
		{
			if ($this->input->is_ajax_request()) {	
				$this->form_validation->set_rules('coupon', 'Coupon', 'required|trim|min_length[4]|max_length[10]|xss_clean|required');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('landlord-assoc/account_handler');
					$coupon_array = $this->account_handler->getCoupons();
					if(in_array($coupon, $coupon_array)) { 
						$year = date('Y');
						echo json_encode(array('success'=>'<b>Free for 1 year</b>, you will not be billed until '.date('m/d').'/'.($year+1)));
					} else {
						echo json_encode(array('error', 'Tester'));
					}
				} else {
					echo json_encode(array('error', 'tester'));
				}
			} 
		}
		
		public function add_new_landlord_assoc_event()
		{
			$this->check_login();
			if ($this->input->is_ajax_request()) {				
				$this->form_validation->set_rules('starts', 'Starts', 'required|trim|min_length[10]|max_length[20]|xss_clean|required');
				$this->form_validation->set_rules('ends', 'ends', 'required|trim|min_length[10]|max_length[20]|xss_clean|required');
				$this->form_validation->set_rules('what', 'What', 'required|trim|min_length[3]|max_length[30]|xss_clean|required');
				$this->form_validation->set_rules('where', 'Where', 'required|trim|min_length[2]|max_length[30]|xss_clean');
				
				$this->form_validation->set_rules('map', 'Google Map', 'trim|min_length[1]|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('address', 'Address', 'trim|min_length[2]|max_length[50]|xss_clean');
				$this->form_validation->set_rules('public', 'Public', 'trim|min_length[1]|max_length[1]|xss_clean|alpha');
				
				if($this->form_validation->run() == TRUE) {
					if($this->session->userdata('user_id')) {
						extract($_POST);
						if(empty($map)) {
							$map = 'n';
						}
						if(empty($public)) {
							$public = 'n';
						}
						$what = ucwords(strtolower($what));
						$where = ucwords(strtolower($where));
					
						$data = array(
							'start' => date('Y-m-d H:i:s', strtotime($starts)),
							'end' => date('Y-m-d H:i:s', strtotime($ends)),
							'what' => $what,
							'where' => $where,
							'map' => $map,
							'address' => $address,
							'public' => $public,
							'user_id' => $this->session->userdata('user_id')
						);
						
						$this->load->model('landlord-assoc/calendar_handler');
						$results = $this->calendar_handler->add_event($data);
						if($results>0) {
							echo '1'; // event added successfully // insert id is greater then 0
						} else {
							echo '2'; // event failed to add
						} 
					} else {
						echo '3'; // user id is not set
					}
				} else {
					echo validation_errors();
				}
			}
			
		}
		
		function fetch_landlord_assoc_event()
		{
			if ($this->input->is_ajax_request()) {	
				$this->form_validation->set_rules('id', 'Event Id', 'trim|min_length[1]|max_length[11]|xss_clean|numeric');
				if($this->form_validation->run() == TRUE) {
					$id = (int)$_POST['id'];
					$this->load->model('landlord-assoc/calendar_handler');
					$results = $this->calendar_handler->event_details($id);
					echo json_encode($results);
				} else {
					echo validation_errors();
				}
			}
		}
		
		public function edit_calendar_event()
		{	
			$this->check_login();
			if ($this->input->is_ajax_request()) {		
				$this->form_validation->set_rules('starts', 'Starts', 'required|trim|min_length[10]|max_length[20]|xss_clean|required');
				$this->form_validation->set_rules('ends', 'ends', 'required|trim|min_length[10]|max_length[20]|xss_clean|required');
				$this->form_validation->set_rules('what', 'What', 'required|trim|min_length[3]|max_length[30]|xss_clean|required');
				$this->form_validation->set_rules('where', 'Where', 'required|trim|min_length[2]|max_length[30]|xss_clean');
				$this->form_validation->set_rules('map', 'Google Map', 'trim|min_length[1]|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('address', 'Address', 'trim|min_length[2]|max_length[50]|xss_clean');
				$this->form_validation->set_rules('public', 'Public', 'trim|min_length[1]|max_length[1]|xss_clean|alpha');
				$this->form_validation->set_rules('id', 'Calendar Id', 'trim|min_length[1]|max_length[11]|xss_clean|numeric');
				
				if($this->form_validation->run() == FALSE) {
					echo validation_errors('');
				} else {
					extract($_POST);
					if(empty($map)) {
						$map='n';
					}
					if(empty($public)) {
						$public='n';
					}
			
					$data = array('id'=>$id, 'start'=>date('Y-m-d H:i:s', strtotime($starts)), 'end'=>date('Y-m-d H:i:s', strtotime($ends)), 'what'=>$what, 'where'=>$where, 'map'=>$map, 'address'=>$address, 'public'=>$public);
					
					$this->load->model('landlord-assoc/calendar_handler');
				
					if($this->calendar_handler->edit_calendar_event($data)) {
						echo '1'; // event added successfully // insert id is greater then 0
					} else {
						echo '2'; // event failed to add
					}
				}
			}
		}
		
		function search_for_landlord_assoc()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('searchBy', 'Search By', 'trim|min_length[1]|max_length[20]|xss_clean');
				if($_POST['searchBy'] == 'Phone') {
					$this->form_validation->set_rules('searchFor', 'Search For', 'trim|min_length[1]|max_length[16]|xss_clean');
				} else if($_POST['searchBy'] == 'Name') {
					$this->form_validation->set_rules('searchFor', 'Search For', 'trim|min_length[1]|max_length[40]|xss_clean');
				} else if($_POST['searchBy'] == 'Email') {
					$this->form_validation->set_rules('searchFor', 'Search For', 'trim|min_length[1]|max_length[50]|xss_clean|valid_email');
				} else if($_POST['searchBy'] === 'bName') {
					$this->form_validation->set_rules('searchFor', 'Business Name', 'trim|min_length[5]|max_length[50]|xss_clean');
				} else {
					echo json_encode(array('error'=>'Search by field was invalid, please select on from the list'));
					exit;
				}
				
				if($this->form_validation->run() === TRUE) {
					extract($_POST);
					if($searchBy == 'Phone') {
						$searchFor = preg_replace("/[^0-9,.]/", "", $searchFor);
					}
					
					$data = array(
						'searchBy'=>strtolower($searchBy), 
						'searchFor'=>$searchFor
					);
				
					$this->load->model('landlord-assoc/search_landlords');
					$results = $this->search_landlords->search_if_user_exists($data);
					echo json_encode($results);
					
				} else {
					echo json_encode(array('error'=>validation_errors()));
				}
			}
		}
		
		function add_page_website()
		{
			if ($this->input->is_ajax_request()) {	
				$this->form_validation->set_rules('name', 'Page Name', 'trim|min_length[1]|max_length[20]|xss_clean');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('landlord-assoc/post_page_handler');
				
					$results = $this->post_page_handler->add_new_page($name);
					if($results=='0') {
						echo 'Page already created';
					} elseif($results>5) {
						echo $results;
					} elseif($results=='2') {
						echo 'Error creating page';
					} elseif($results=='4') {
						echo 'Only 5 pages are allowed, delete one of your existing pages to create a new one';
					}
				} else {
					echo validation_errors();
				}
			}
		}		
		
		function update_page_stack()
		{
			if ($this->input->is_ajax_request()) {
				$this->load->model('landlord-assoc/public_page_handler');
				$positions = json_decode($_POST['jsonData']);
				
				foreach($positions as $key => $val) {  // KEY IS THE ID OF THE MEMBER AND VAL IS THE POSITION
					$data = array(
						'id'=>$key,
						'stack_order'=>$val
					);
					$this->public_page_handler->reorder_pages($data);
				}
			}
		}
		
		function update_assoc_members_list()
		{
	
			if ($this->input->is_ajax_request()) {	
				$this->load->model('landlord-assoc/member_handler');
				$positions = json_decode($_POST['jsonData']);
				foreach($positions as $key => $val) {  // KEY IS THE ID OF THE MEMBER AND VAL IS THE POSITION
					$data = array(
						'id'=>$key,
						'stack_number'=>$val
					);
					$this->member_handler->reorder_members($data);
				}
			}
		}
		
		function landlord_assocations_get_memeber_details()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('id', 'id', 'trim|min_length[1]|max_length[20]|xss_clean|integer');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					if($this->session->userdata('logged_in')) {
						$this->load->model('landlord-assoc/member_handler');
						$data = $this->member_handler->show_member_details($id);
					} else {
						$data = array('error'=>'2');
					}
				} else {
					$data = array('error'=>'1');
				}
				echo json_encode($data);
			}
		}
		
		function forgotpass_assocation()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('email', 'Email', 'trim|min_length[1]|max_length[50]|xss_clean|required|valid_email');
				$this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha Response', 'trim|min_length[1]|max_length[500]|xss_clean|required');
				if($this->form_validation->run() == TRUE) {
						extract($_POST);
						$this->load->model('landlord-assoc/reset_password');
						$result = $this->reset_password->reset($email);
						echo json_encode($result);
				} else {
					echo json_encode( array( 'error'=>validation_errors(' ') ) );
				}
			}
		}
		
		function summernote_image_uploader()
		{
			
			if ($this->input->is_ajax_request()) {
			
				$path = "./uploads/";
				
				$year_folder = $path . date("Y");
				$month_folder = $year_folder . '/' . date("m");

				!file_exists($year_folder) && mkdir($year_folder , 0777);
				!file_exists($month_folder) && mkdir($month_folder, 0777);

				$path = $month_folder . '/' . $new_file_name;
				
				$config['upload_path'] = './uploads/'.date('Y').'/'.date('m').'/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '0';
				$config['encrypt_name'] = true; 
				
				$this->load->library('upload', $config);
				$file = 'file';
				if (!$this->upload->do_upload($file)) {
					$error = array('error'=>$this->upload->display_errors('', ''));
					echo json_encode($error);
				} else {
					$img_data = $this->upload->data();
					if($img_data['image_width']>1000 || $img_data['image_height']>1000) {
						$this->load->library('image_lib');
						$config['width'] = $img_data['image_width']/2.5;
						$config['height'] = $img_data['image_height']/2.5;
						$config['quality'] = 70;
						
						$config['source_image'] = './uploads/'.date('Y').'/'.date('m').'/'.$img_data['file_name'];
						$config['maintain_ratio'] = true;
						$this->image_lib->initialize($config); 
						
						if(!$this->image_lib->resize()) {
							$error = array('error'=>$this->image_lib->display_errors(' ',' '));
							echo json_encode($error);
							exit;
						}
					}
					
					$img = base_url().'uploads/'.date('Y').'/'.date('m').'/'.$img_data['file_name'];
					echo json_encode(array('success'=>$img));
				}
			} 
		}
		
		function grab_landlord_details()
		{
			$id = (int)$_POST['id'];
			$this->load->model('landlord-assoc/member_handler');
			$data = $this->member_handler->get_registered_member($id);
			echo json_encode($data);
		}
		
	}