<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class ajax extends CI_Controller {
		function __construct()
		{
			parent::__construct();
		}
		

		function get_landlords_info()  
		{
			$landlord_id = (int)$this->uri->segment(3);
			$group_id = (int)$this->uri->segment(4);
			$this->load->model('renters/user_model');
			$data = array();
			if(!empty($group_id)) {
				$contact_id = $this->user_model->get_sub_admin_id($group_id);
				$data = $this->user_model->get_landlords_info($contact_id);
				$data['group_id'] = $group_id;
				$data['link_id'] = $landlord_id;
				$data['display_name'] = $this->user_model->get_sub_group_bname($group_id);
			} else {
				$data = $this->user_model->get_landlords_info($landlord_id);
				$data['link_id'] = $landlord_id;
				$data['group_id'] = '';
				$data['display_name'] = $data['bName'];
			}

			echo json_encode($data); 
		}
		
		function get_landlords_search() {
			$searched = $this->uri->segment(3);
			$searched = preg_replace("/[^a-zA-Z0-9 ]/", "_", $searched);
			if(!empty($searched)) {
				$this->load->model('renters/landlord_handler');
				$results = $this->landlord_handler->searchForLandlord($searched);
				echo json_encode($results);
			}
		}
		
		function update_time() {
			$id = (int)$this->uri->segment(3);
			if(!empty($id)) {
				$this->load->model('renters/message_user');
				$this->message_user->update_viewied_time($id);
			}
		}		
		
		public function getLatLong($address){
			if (!is_string($address))die("All Addresses must be passed as a string");
			$_url = sprintf('http://maps.google.com/maps?output=js&q=%s',rawurlencode($address));
			$_result = false;
			if($_result = file_get_contents($_url)) {
				if(strpos($_result,'errortips') > 1 || strpos($_result,'Did you mean:') !== false) return false;
				preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
				$_coords['lat'] = $_match[1];
				$_coords['long'] = $_match[2];
			}
			return $_coords;
		}
		
		public function toogle_rental_listing() {
			$id = (int)$this->uri->segment(3);
			$state = (int)$this->uri->segment(4);
			$state = preg_replace("/[^a-zA-Z0-9 ]/", "_", $state);
			if($state != 1) {
				$state == 2;
			}
			$id = preg_replace("/[^a-zA-Z0-9 ]/", "_", $id);
			if(!empty($id) && !empty($state)) {
				$this->load->model('landlords/listings_handler');
				$updated = $this->listings_handler->change_listing_status($id, $state); 
				if($updated == 'y') {
					echo 1;
				} else {
					echo 2;
				}
			}			
		}
		
		public function check_unique_url()
		{
			$string = (string)$this->uri->segment(3);
			$string = str_replace(' ', '-', $string);
			$this->load->model('landlords/public_page_handler');
			$check = $this->public_page_handler->check_for_unique_ajax($string);
			if($check == 1) {
				echo 1;
			} else {
				echo 2;
			}
		}
		
		public function get_property_details() 
		{
			$id = (int)$this->uri->segment(3);
			$this->load->model('landlords/admin_switch_handler');
			$info = $this->admin_switch_handler->property_details_ajax($id);
			echo json_encode($info);
		}
		
		public function get_email_messages() {	
			if($this->session->userdata('logged_in') != false) {
				$id = (int)$this->uri->segment(3);
				$this->load->model('landlords/internal_email_handler');
				$results = $this->internal_email_handler->get_emails($id);
				if($results) {
					echo json_encode($results);
				} else {
					echo false;
				}
			}
		}
		
		public function search_by_email() {
			$email = $this->uri->segment(3);
			$email_test = str_replace("%7c","@",$email);
			if(filter_var($email_test, FILTER_VALIDATE_EMAIL)) {
				$this->load->model('renters/landlord_handler');
				$results = $this->landlord_handler->search_by_email($email);
				if($results) {
					echo json_encode($results);
				} else {
					echo false;
				}
			} else {
				return false;
			}
		}
		
		public function get_tenants_select() {
			$id = (int)$this->uri->segment(3);
			$this->load->model('landlords/internal_email_handler');
			$results = $this->internal_email_handler->get_tenants_select($id);
			if($results) {
				echo json_encode($results);
			} else {
				echo false;
			}
		}
		
		public function show_listing_items() 
		{
			if ($this->input->is_ajax_request()) {
				$id = (int)$this->uri->segment(3);
				$this->output->set_template('json');
				$this->load->model('landlords/listings_handler');
				$results = $this->listings_handler->get_items_at_property_json($id);
				echo json_encode($results);
			}
		}
		
		public function add_landlord_info()
		{
			$id = (int)$this->uri->segment(3);
			if($id>0) {
				$this->load->model('renters/landlord_handler');
				$results = $this->landlord_handler->get_landlord_by_id($id);
				if(!empty($results)) {
					echo json_encode($results);
				} else {
					echo false;
				}
			}
		}
		
		public function search_by_phone()
		{
			$phone = (int)$this->uri->segment(3);
			if($phone>0) {
				$this->load->model('renters/landlord_handler');
				$results = $this->landlord_handler->get_landlord_by_phone($phone);
				if(!empty($results)) {
					echo json_encode($results);
				} else {
					echo false;
				}
			}
		}
		
		public function check_current_residence()
		{
			$this->load->model('renters/landlord_handler');
			$results = $this->landlord_handler->check_for_current_residences();
			if(!empty($results)) {
				echo json_encode($results);
			}
		}
		
		public function check_cell_phone() 
		{
			$phone = (int)$this->uri->segment(3);
			if(strlen($phone)==10) {
				$check = $this->turn_phone_into_email($phone);
				if($check != FALSE) {
					echo json_encode($check);
				} else {
					echo json_encode('1');
				}
			} else {
				echo json_encode('2');
			}
		}
	
		public function turn_phone_into_email($phone) 
		{
			if(strlen($phone) == 10) {
				$link = 'http://www.carrierlookup.com/index.php/api/lookup?key=66ed44b57ad050d6b2d5eb14c366871dd0afb5d6&number='.$phone;
				$return = file_get_contents($link);
				$object = json_decode($return, true);

				$response_carriers = array(
					'sprint' 		=> '@messaging.sprintpcs.com', 
					'AT&T' 			=> '@cingularme.com', 
					'verizon'		=> '@vtext.com', 
					't-mobile' 		=> '@tmomail.net',
					'bell' 			=> 'txt.bellmobility.ca',
					'bluegrass_cellular' => '@sms.bluecell.com',
					'carolina_west'	=> '@cwwsms.com',
					'cellular_south'=>	'@csouth1.com',
					'centennial'	=> '@cwemail.com',
					'dobson'		=> '@mobile.dobson.net',
					'fido'			=> '@fido.ca',
					'inland'		=> '@inlandlink.com',
					'mts'			=> '@mobilecomm.net',
					'nextel'		=> '@messaging.nextel.com',
					'cricket'		=> '@mms.mycricket.com',
					'alaska'		=> '@msg.acsalaska.com',
					'arch'			=> '@archwireless.net',
					'alltel'		=> '@message.alltel.com');  
				foreach($response_carriers as $key => $val) {
					if($key == $object['Response']['carrier']) {
						$new_phone = array('number'=>$phone, 'domain'=>$val);
					}
				}
				if(!empty($new_phone)) {
					return json_encode($new_phone);
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		public function tenants_signed_up_on() 
		{	
			if ($this->input->is_ajax_request()) {
				$date = $this->uri->segment(3);
				$type = $this->uri->segment(4);
				$this->load->model('landlords/user_account_handler');
				$results = $this->user_account_handler->get_tenants_signup_date($date, $type);
				echo json_encode($results);
			}
		}
	
		public function landlords_signed_up_on()
		{
			if ($this->input->is_ajax_request()) {
				$date = $this->uri->segment(3);
				$type = $this->uri->segment(4);
				$this->load->model('landlords/user_account_handler');
				$results = $this->user_account_handler->get_landlords_signup_date($date, $type);
				echo json_encode($results);
			}
		}
		
		public function get_property_info()
		{
			$id = (int)$this->uri->segment(3);
			if ($this->input->is_ajax_request()) {
				$this->load->model('renters/landlord_handler');
				$results = $this->landlord_handler->get_property_details($id);
				echo json_encode($results);
			}
		}
		
		public function check_username_renter() 
		{
			$username = $this->uri->segment(3);
			if ($this->input->is_ajax_request()) {
				$this->load->model('renters/create_user_model');
				if($this->create_user_model->check_username($username)) {
					echo '1';
				} else {
					echo '0';
				}
			}
		}
		
		public function searchSponsoredContractors()
		{
			$id = $this->uri->segment(3);
			if ($this->input->is_ajax_request()) {
				$this->load->model('landlords/suggested_contractors');	
				$results = $this->suggested_contractors->pull_suggestions($id);
				echo json_encode($results);
			}
		}
		
		function get_landlords_search_steps() {
			if ($this->input->is_ajax_request()) {
				$searched = $this->uri->segment(3);				
				$searched = preg_replace("/[^a-zA-Z0-9 ]/", "_", $searched);
				if(!empty($searched)) {
					
					$this->load->model('renters/landlord_handler');
					$results = $this->landlord_handler->group_search_data($searched);
					echo json_encode($results);
				} else {
					echo false;
				}
			}
		}
		
		public function checkForTerms() //contractor function to check to see if user checked the terms button
		{
			
				$this->load->model('contractors/final_step');	
				$results = $this->final_step->check_for_terms();
				if($results) {
					$results = '1';
				} else {
					$results = '0';
				}
				echo $results;
			
		}
		
		public function checkForTermsAdvertisers() //contractor function to check to see if user checked the terms button
		{
			if ($this->input->is_ajax_request()) {
				$this->load->model('advertisers/final_step');	
				$results = $this->final_step->check_for_terms();
				if($results) {
					$results = '1';
				} else {
					$results = '0';
				}
				echo $results;
			}
		}
		
		public function check_if_user_exists()
		{
			if ($this->input->is_ajax_request()) {
				$username = $this->uri->segment(3);
				$this->load->model('landlords/user_account_handler');
				$results = $this->user_account_handler->check_unique_user($username);
				if($results) {
					echo 1;
				} else {
					echo 2;
				}
			}
		}
		
		public function check_if_email_exists()
		{
			if ($this->input->is_ajax_request()) {
				$email = $_POST['email'];
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					echo '3';
				} else {
					$this->load->model('renters/create_user_model');
					$results = $this->create_user_model->check_unique_email($email);
					if($results) {
						echo '1';
					} else {
						echo '2';
					}
				}
				
			}
		}
		
		public function check_if_phone_is_cell()
		{
			if ($this->input->is_ajax_request()) {
				$phone = $_POST['phone'];
				$phone = preg_replace("/[^0-9,.]/", "", $phone);
				$link = 'http://www.carrierlookup.com/index.php/api/lookup?key=66ed44b57ad050d6b2d5eb14c366871dd0afb5d6&number='.$phone;
				$return =  file_get_contents($link);
				$object = json_decode($return, true);
				echo json_encode($object);
			}
		}
		
		public function get_payment_notes()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('id', 'id', 'required|trim|min_length[1]|max_length[12]|integer|xss_clean');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('landlords/payment_handler');
					$data = $this->payment_handler->get_payment_notes($id);
					if($data !== FALSE) {
						echo json_encode($data);
					} else {
						echo json_encode(array('error'=>'error 1'));
					}
				} else {
					echo json_encode(array('error'=>validation_errors()));
				}
			}
		}
		
		//function for the new steps create account process when the user leaves the box
		public function search_landlandlord_email()
		{
			if ($this->input->is_ajax_request()) {
				$email = $_POST['email'];
				if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$this->load->model('renters/create_user_model');
					$data = $this->create_user_model->search_for_registered_landlord_email($email);
					if(!empty($data)) {
						echo json_encode($data);
					}
				}
			}
		}
		
		public function search_landlandlord_phone()
		{
			if ($this->input->is_ajax_request()) {
				$phone = $_POST['phone'];
				if(strlen($phone)==10) {
					$this->load->model('renters/create_user_model');
					$data = $this->create_user_model->search_for_registered_landlord_phone($phone);
					if(!empty($data)) {
						echo json_encode($data); 
					}
				}
			}
		}
		
		public function find_landlord_properties()
		{
			if ($this->input->is_ajax_request()) {
				$group_id = (int)$_POST['group_id'];
				$id = (int)$_POST['id'];
				$this->load->model('renters/create_user_model');
				$data = $this->create_user_model->getLandlordProperties($id, $group_id);
				if(!empty($data)) {
					echo json_encode($data);
				}
			}
		}
		
		public function check_unique_landlord_association()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('username', 'User', 'required|trim|min_length[6]|max_length[20]|xss_clean|alphanumeric');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('landlord-assoc/account_handler');
					echo $this->account_handler->check_unique_username($username);
				} else {
					echo '3';
				}
			}
		}
		
		public function check_unique_email_landlord_association()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('email', 'email', 'required|trim|min_length[6]|max_length[60]|xss_clean|valid_email');
				if($this->form_validation->run() === TRUE) {
					extract($_POST);
					$this->load->model('landlord-assoc/account_handler');
					echo $this->account_handler->check_unique_email($email);
				} else {
					echo '3';
				}
			}
		}

		public function check_unique_email_edit_landlord_association()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('email', 'email', 'required|trim|min_length[6]|max_length[60]|xss_clean|valid_email');
				if($this->form_validation->run() === TRUE) {
					extract($_POST);
					$this->load->model('landlord-assoc/account_handler');
					echo $this->account_handler->check_unique_email_edit($email);
				} else {
					echo '3';
				}
			}
		}
		
		public function login_association()
		{

			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('user', 'Username', 'required|trim|min_length[6]|max_length[20]|xss_clean|alphanumeric');
				$this->form_validation->set_rules('pass', 'Password', 'required|trim|min_length[6]|max_length[60]|xss_clean');
				
				if($this->form_validation->run() == TRUE) {
					
					if(count($_POST)!==2) {
						// Error should only be username and password nothing else
						echo json_encode(array('error'=>'02'));
						
					} else {
						extract($_POST);
						$data = array(
							'username'=>$_POST['user'],
							'password'=>$_POST['pass']
						);
					
						$this->load->model('landlord-assoc/account_handler');
						
						$results = $this->account_handler->check_login_details($data);					
						if($results>0) {
							$this->account_handler->check_payment_details($results);
							$this->session->set_userdata('side_logged_in', '54986544688');
							$this->session->set_userdata('user_id', $results);
							$this->session->set_userdata('logged_in', TRUE);
							$this->session->set_userdata('username', $data['username']);
							echo json_encode(array('success'=>'1'));
						} else {
							echo json_encode(array('error'=>'02'));
						}
					}
				} else {
					echo json_encode(array('error'=>'02'));
				}
			}
		}
		
		
		public function landlord_assocation_page_upload()
		{
			if ($this->input->is_ajax_request()) {
				if(isset($_FILES)) {
					if(!empty($_FILES['photo']['name'])) {
						$userDir = md5($this->session->userdata('user_id').$this->session->userdata('username'));
						if (!is_dir('./public-images/'.$userDir)) {
							mkdir('./public-images/'.$userDir, 0777, true);
						}						
						
						$config['upload_path'] = './public-images/'.$userDir;
						$config['allowed_types'] = 'gif|jpg|png|jpeg';
						$config['max_size'] = '5000KB';
						$config['file_name'] = strtolower($_FILES['photo']['name']);
						$this->load->library('upload', $config);
						
						$file = "photo";
						
						if($this->upload->do_upload($file)) {
							$upload = $this->upload->data();
							$file = $upload['file_name'];
							
							$this->load->library('image_lib');
							// Resize The Image
							$config['image_library'] = 'GD2'; 
							$config['source_image']	= FCPATH.'public-images/'.$userDir.'/'.$file;
							$config['maintain_ratio'] = TRUE;
							$config['width']	 = 200;
							$config['height']	= 200;

							$this->image_lib->clear();
							$this->image_lib->initialize($config);
							$this->image_lib->resize();
						
							
							
							echo json_encode(array('success'=>$userDir.'/'.$file));
						} else {
							echo json_encode(array('error'=>$this->upload->display_errors()));
						}
					} else {
						echo json_encode(array('error'=>'Empty file name, try again'));
					}
				} else {
					echo json_encode(array('error'=>'Invalid file type, try again'));
				}
			}
		}	
		
		public function landlord_assocation_delete_img()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('image', 'Image', 'required|trim|min_length[5]|max_length[100]|xss_clean');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$dir = md5($this->session->userdata('user_id').$this->session->userdata('username'));
	
					$path = './public-images/'.$dir.'/'.$image;
					if (file_exists($path)) {
						unlink($path);
						if (file_exists($path)) {
							echo 'error';
						} else {
							echo 'success';
						}
					} else {
						echo 'error';
					}
				}
				
			}
		}
		
		function delete_landlord_assoc_event()
		{
			if ($this->input->is_ajax_request()) {	
				$this->form_validation->set_rules('id', 'Event Id', 'trim|min_length[1]|max_length[11]|xss_clean|numeric');
				if($this->form_validation->run() == TRUE) {
					$id = (int)$_POST['id'];
					$this->load->model('landlord-assoc/calendar_handler');
					$results = $this->calendar_handler->delete_event_details($id);
					if($results) {
						echo '1'; //deleted
					} else {
						echo '2'; //not deleted
					}
				} else {
					echo '2';
				}
			}
		}

		function get_landlord_details_assoc()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('id', 'id', 'trim|min_length[1]|max_length[20]|xss_clean|integer');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					if($this->session->userdata('logged_in')) {
						$this->load->model('renters/user_model');
						$data = $this->user_model->get_landlords_info($id);
					} else {
						$data = array('error'=>'2');
					}
				} else {
					$data = array('error'=>'1');
				}
				echo json_encode($data);
			}
		}
		
		
		
		function toggle_tenant_payment_settings()
		{
			 if ($this->input->is_ajax_request()) {
				 
				$this->form_validation->set_rules('id', 'id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('discount_payment', 'Discount Payment', 'trim|min_length[1]|max_length[7]|xss_clean');
				$this->form_validation->set_rules('min_payment', 'Min. Payment', 'trim|min_length[1]|max_length[7]|xss_clean');
				$this->form_validation->set_rules('auto_pay_discount', 'AutoPay Discount', 'trim|min_length[1]|max_length[7]|xss_clean|required');
				$this->form_validation->set_rules('payments_allowed', 'Accept Online Payments', 'trim|min_length[1]|max_length[1]|xss_clean|required
				alpha');
				$this->form_validation->set_rules('partial_payments', 'Accept Partial Payments', 'trim|min_length[1]|max_length[1]|xss_clean|required|alpha');
				
				if($this->form_validation->run() == TRUE) {
					
					$this->load->model('landlords/tenants_handler');
					echo json_encode($this->tenants_handler->savePaymentData($_POST));
					
				} else {
					echo json_encode(array('error', validation_errors()));
				}
			}
		}
		
		function check_if_number_can_accept_text()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('cell', 'cell', 'trim|min_length[10]|max_length[10]|integer|xss_clean|required');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					if(strlen($cell) == '10') {
						$link = 'http://www.carrierlookup.com/index.php/api/lookup?key=66ed44b57ad050d6b2d5eb14c366871dd0afb5d6&number='.$cell;
						$return = file_get_contents($link);
						$object = json_decode($return, true);
						echo json_encode($object);
						exit;
					} else {
						$array = array('error'=>'invalid cell phone number');
					}
				} else {
					$array = array('error'=>'invalid cell phone number');
				}
			} else {
				$array = array('error'=>'Request not allowed');
			}
			echo json_encode($array);
		}
		
		function add_new_payment_note()
		{$this->load->model('landlords/payment_handler');
			if ($this->input->is_ajax_request()) {		
				$this->form_validation->set_rules('payment_note', 'Payment Note', 'trim|min_length[10]|max_length[700]|xss_clean|required');
				$this->form_validation->set_rules('payment_id', 'payment id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('altid', 'Alternate Id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('type', 'User Type', 'trim|min_length[1]|max_length[20]|xss_clean|required');
				
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					
					
					if($type=='renter') {
						$type='renter';
					} else {
						$type='landlord';
					}
					
					if(isset($_FILES)) {
						if(!empty($_FILES['attach_file']['name'])) {
							$config['upload_path'] = './message-uploads/p_notes/';
							$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|PNG|JPG|JPEG|docx|doc';
							$config['max_size'] = '5000KB';
							$this->load->library('upload', $config);
							
							$file = "attach_file";
							
							if($this->upload->do_upload($file)) {
								$upload = $this->upload->data();
								$file = $upload['file_name'];
							} else {
								$feedback = array('error' => $this->upload->display_errors());
								$file = '';
							}
						} else {
							$file = ''; 
						}
					}
					
					$data = array('note'=>strip_tags($payment_note), 'payment_id'=>$payment_id, 'attachment'=>$file, 'tenant_id'=>$altid, 'sent_by'=>$type, 'landlord_id'=>$this->session->userdata('user_id'));
					
					$notes = $this->payment_handler->add_new_payment_note($data);
					
					if($notes) {
						echo json_encode(array('success'=>'', 'file'=>$file, 'note'=>$payment_note, 'time'=>date('m-d-Y h:i a')));
					} else {
						echo json_encode(array('error'=>'Failed to add note, try again'));
					}
				} else {
					echo json_encode(array('error'=>strip_tags(validation_errors())));
				}
			}
		}

		function add_dispute_to_payment() // LANDLORD SIDE
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('note', 'Payment Note', 'trim|min_length[10]|max_length[700]|xss_clean|required');
				$this->form_validation->set_rules('id', 'payment id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('altid', 'altid', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('type', 'type', 'trim|min_length[5]|max_length[20]|xss_clean|required');
				
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('landlords/payment_handler');
					
					$data = array('tenant_id'=>$altid, 'note'=>strip_tags($note), 'payment_id'=>$id, 'landlord_id'=>$this->session->userdata('user_id'), 'sent_by'=>$type);
					$note = $this->payment_handler->add_payment_note($data);
					
					
					echo json_encode(array('success'=>''));
				} else {
					echo json_encode(array('error'=>strip_tags(validation_errors())));
				}
			}
		}
		
		function dispute_payment() //LANDLORD SIDE
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('note', 'Dispute Note', 'trim|min_length[10]|max_length[700]|xss_clean|required');
				$this->form_validation->set_rules('id', 'payment id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('altid', 'altid', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('type', 'type', 'trim|min_length[5]|max_length[20]|xss_clean|required');
				
				if($this->form_validation->run() == TRUE) {
					extract($_POST);					

					$this->load->model('landlords/payment_handler');
					
					$data = array('tenant_id'=>$altid, 'note'=>strip_tags($note), 'payment_id'=>$id, 'landlord_id'=>$this->session->userdata('user_id'), 'sent_by'=>$type);
	
					$notes = $this->payment_handler->add_payment_note($data);
					if($notes === true) {
						$this->payment_handler->dispute_payment($id, $altid);
						echo json_encode(array('success'=>''));
					} else {
						echo json_encode(array('error'=>'Dispute failed to add, try again'));
					}
					
					
				} else {
					echo json_encode(array('error'=>strip_tags(validation_errors())));
				}
			}
		}
		
		function resolve_payment_dispute()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('id', 'Id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('alt_id', 'Alt Id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('landlords/payment_handler');
					if($this->payment_handler->resolve_dispute($id, $alt_id)) {
						echo json_encode(array('success'=>'success'));
					} else {
						echo json_encode(array('error'=>'error'));
					}
				} else {
					echo json_encode(array('error'=>'error'));
				}
			}
		}
		
		function paymentSearch()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('search', 'Search', 'trim|min_length[2]|max_length[20]|xss_clean|required');
			
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('special/local_payments');
					echo json_encode(array($this->local_payments->searchPayments($search, true)), JSON_UNESCAPED_SLASHES);
				}
			}
		}
		
		function invoicesearch()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('search', 'Search', 'trim|min_length[2]|max_length[20]|xss_clean|required');
			
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('special/local_payments');
					echo json_encode(array($this->local_payments->searchInvoices($search, true)), JSON_UNESCAPED_SLASHES);
				}
			}
		}
		
		public function create_invoice() 
		{
			if ($this->input->is_ajax_request()) {
				$this->load->model('special/local_payments');

				$feedback = $this->local_payments->create_invoice($_POST, $_FILES);
				echo json_encode($feedback);
			}
		}
		
		public function addOfflinePayment()
		{
			if ($this->input->is_ajax_request()) {
				$this->load->model('special/local_payments');

				$feedback = $this->local_payments->addOfflinePayment($_POST);
				echo json_encode($feedback);
			}
		}
		
	}