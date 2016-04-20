<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Contractor extends CI_Controller {
	
		function __construct()
		{
			parent::__construct();
			$this->load->helper('url');
            $this->load->section('sidebar', 'advertiser-sidebar');
			$this->_init();
		}
		
		private function _init()
		{
			$this->output->set_template('contractors/contractors');
			$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
			$this->load->js('assets/themes/default/js/bootstrap.min.js');
			$this->load->js('assets/themes/default/js/contractors/common.js');
		}
		
		public function index()
		{
			if($this->session->userdata('logged_in') == true) {
				redirect('contractor/notifications');
				exit;
			}
			$this->output->set_template('contractors/contractors-home-page');
			
		}
		
		function check_if_loggedin() 
		{
			if($this->session->userdata('logged_in') !== true)
			{
				$this->session->sess_destroy();
				redirect('contractor/logout');
				exit;
			}
			if($this->session->userdata('side_logged_in') != '203020320389822') {
				$this->session->sess_destroy();
				redirect('contractor/logout');
				exit;
			}

			$user_id = $this->session->userdata('user_id');
			if(empty($user_id)) {
				$this->session->sess_destroy();
				redirect('contractor/logout');
				exit;	
			}
			
		}	
			
		public function create_account_new() 
		{			
			if($this->uri->segment(3)) {
				if (ctype_alnum($this->uri->segment(3))) {
					$this->session->set_userdata('affiliate_id', $this->uri->segment(3));
					redirect('contractor/create-account');
					exit;
				}
			}
			
			if(isset($_COOKIE['affiiliate'])) {
				if (ctype_alnum($_COOKIE['affiiliate'])) {
					$this->session->set_userdata('affiliate_id', $_COOKIE['affiiliate']);
				}
			}
			
			if($this->session->userdata('logged_in') == true) {
				redirect('contractor/notifications');
				exit;
			}
			$this->load->js('assets/themes/default/js/bootbox.js'); 
			$this->load->js('assets/themes/default/js/jquery.creditCardValidator.js');
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->js('assets/themes/default/js/contractors/create-account.js');
			
			$this->load->model('special/admin_settings');
			$data['settings'] = $this->admin_settings->getAdminSettings(array('contractor_quarterly_payment', 'contractor_biyearly_payment', 'contractor_yearly_payment')); 
			
			$this->load->view('contractors/create-account', $data);
		}
		
		public function create_account() 
		{
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->js('assets/themes/default/js/jquery.creditCardValidator.js'); 
			$this->load->css('assets/themes/default/css/alertify.core.css'); 
			$this->load->css('assets/themes/default/css/contractors/payment-page-styles.css'); 
			$this->load->js('assets/themes/default/js/alertify.min.js'); 
			$this->load->js('assets/themes/default/js/contractors/create-new-account.js'); 
			
			$affiliate_id = $this->session->userdata('affiliate_id');
			
			//Get payment settings
			$this->load->model('special/admin_settings');
			$getSettings = array('contractor_quarterly_payment', 'contractor_biyearly_payment', 'contractor_yearly_payment');
			$data['payment_settings'] = $this->admin_settings->getAdminSettings($getSettings);
			
			if($affiliate_id) {
				$this->load->model('modules/user_affiliates');
				$data['affliate'] = $this->user_affiliates->searchForUser($affiliate_id, 'unique_id');
			}
			
			$this->load->view('contractors/create-new-account', $data);
		}
		
		public function login()
		{
			if($this->session->userdata('logged_in') == true) {
				redirect('contractor/notifications');
				exit;
			}
	
			$this->form_validation->set_rules('username', 'Username', 'trim|min_length[6]|max_length[50]|xss_clean|trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]|max_length[50]|xss_clean|trim|required');
			if($this->form_validation->run() == true) {
				extract($_POST);
				$this->load->model('contractor/login');
				$results = $this->login->check_creditials(array('user'=>$username, 'password'=>md5($password)));
				$this->session->set_userdata('side', 'Contractor');
				if($results) {
					redirect('contractor/notifications');
					exit;
				} else {
					$this->session->set_flashdata('error', 'Invalid username and/or password');
					redirect('contractor/login');
					exit;
				}
			} else {
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					$this->session->set_flashdata('error', 'Invalid username and/or password');
					redirect('contractor/login');
					exit;
				}
			}
			
		
			$this->load->view('contractors/login');
		}
			
		public function public_page()
		{
			$this->output->set_template('contractors/contractor-logged-in');
			$this->check_if_loggedin(); 
			$this->load->css('assets/themes/default/css/contractors/bootstrap-tagsinput.css');
			$this->load->css('assets/themes/default/colorpicker/css/bootstrap-colorpicker.min.css');
			$this->load->js('https://code.jquery.com/ui/1.11.2/jquery-ui.js');
			$this->load->css('https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->js('assets/themes/default/js/contractors/bootstrap-tagsinput.min.js'); 
			$this->load->js('assets/themes/default/js/contractors/public-page-settings.js'); 
			$this->load->js('assets/themes/default/colorpicker/js/bootstrap-colorpicker.min.js'); 
			$this->load->model('contractor/public_page_handler');
			$page_settings = $this->public_page_handler->get_public_page_info();
			$data['web_pages'] = $this->public_page_handler->get_pages();
			if($page_settings == false) {
				$data['settings'] = $this->public_page_handler->contractor_details();
			} else {
				$data['settings'] = $page_settings;
			}

			if(isset($_POST)) {
				if(empty($page_settings->unique_name)) {
					$this->form_validation->set_rules('unique_name', 'Unique Name', 'trim|max_length[100]|xss_clean|required|is_unique[landlord_page_settings.unique_name]');
				} else {
					$this->form_validation->set_rules('unique_name', 'Unique Name', 'trim|max_length[100]|xss_clean|required');
				}
			} 
		 
			$this->form_validation->set_rules('bName', 'Business Name', 'trim|max_length[100]|xss_clean|required');
			$this->form_validation->set_rules('desc', 'Business Description', 'trim|max_length[500]|xss_clean|required');
			
			$this->form_validation->set_rules('facebook', 'Facebook Url', 'trim|max_length[255]|xss_clean|prep_url');
			$this->form_validation->set_rules('google', 'Google Url', 'trim|max_length[255]|xss_clean|prep_url');
			$this->form_validation->set_rules('twitter', 'Twitter Url', 'trim|max_length[255]|xss_clean|prep_url');
			$this->form_validation->set_rules('linkedin', 'Linkedin Url', 'trim|max_length[255]|xss_clean|prep_url');
			$this->form_validation->set_rules('youtube', 'Youtube Url', 'trim|max_length[255]|xss_clean|prep_url');
			$this->form_validation->set_rules('web_link', 'Website Link', 'trim|max_length[100]|xss_clean|prep_url');
			$this->form_validation->set_rules('link_title', 'Link Title', 'trim|max_length[30]|xss_clean');
		
			$this->form_validation->set_rules('address', 'Address', 'trim|max_length[100]|xss_clean');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'trim|max_length[50]|xss_clean|required');
			$this->form_validation->set_rules('state', 'State', 'trim|max_length[2]|xss_clean|required');
			$this->form_validation->set_rules('zip', 'Zip Code', 'trim|max_length[5]|min_length[5]|xss_clean|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|max_length[100]|xss_clean|valid_email|required');
			$this->form_validation->set_rules('website', 'Website Url', 'trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('newsletter', 'Newsletter', 'trim|max_length[100]|xss_clean');
			
			$this->form_validation->set_rules('seo_analytics', 'Newsletter', 'trim|max_length[25]|xss_clean');
			$this->form_validation->set_rules('seo_description', 'SEO Description', 'trim|max_length[160]|xss_clean');
			$this->form_validation->set_rules('website_color', 'Website Color', 'trim|min_length[7]|max_length[7]|xss_clean');
			$this->form_validation->set_rules('seo_keywords', 'SEO Keywords', 'trim|max_length[255]|xss_clean');
			
			$background_select = '';	
			if($this->form_validation->run() == true) {
				extract($_POST);
				
				
				foreach($_POST as $key => $val) {
					if($key == 'unique_name') {
						$val = str_replace('-', ' ', $val);
						$val = preg_replace('/[^\da-z ]/i', '', $val);
						$val = str_replace(' ', '-', $val);
					}
					if($key == 'phone') {
						$val = preg_replace("/[^0-9]/","",$val);
					}
					if($key != 'background_select') {
						$input[$key] = $val;
					}
					
				}				
			
				//Image Upload
				if($background_select=="na") {
					if(isset($_FILES)) {
						if(!empty($_FILES['background']['name'])) {
							$config['upload_path'] = './public-images/';
							$config['allowed_types'] = 'gif|jpg|png|jpeg';
							$config['max_size'] = '5000KB';
							$this->load->library('upload', $config);
							
							$background = "background";
							
							if($this->upload->do_upload($background)) {
								$upload = $this->upload->data();
								$background = $upload['file_name'];
								$input['background'] = $background;
								// Resize The Image
								$config['image_library'] = 'GD2';
								$config['source_image']	= FCPATH.'public-images/'.$background;
								$config['maintain_ratio'] = FALSE;
								$config['width']	 = 1140;
								$config['height']	= 370;

								$this->load->library('image_lib', $config);
								$this->image_lib->resize($config);					
							
							} else {
								$error = array('error' => $this->upload->display_errors());
								$background = '';
							}
						}
					}
		
				} elseif($background_select>0) {			
					switch($background_select) {
						case '1':
							$background_select = 'default-1-bkg.jpg';
							break;
						case '2':
							$background_select = 'default-2-bkg.jpg';
							break;
						case '3':
							$background_select = 'default-3-bkg.jpg';
							break;
					}
					
					$background = $background_select;
					$input['background'] = $background;
				}

			
			
				if(isset($_FILES)) {
					if(!empty($_FILES['file']['name'])) {
						$config['upload_path'] = './public-images/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg';
						$config['max_size'] = '5000KB';
						$this->load->library('upload', $config);
						
						$file = "file";
						
						if($this->upload->do_upload($file)) {
							
							$upload = $this->upload->data();
							$file = $upload['file_name'];
							$input['image'] = $file;
							$max_height = 400;
							$max_width = 600;
							if ($upload['image_width']>$max_width || $upload['image_height']>$max_height) {
								// Resize The Image
								$config['image_library'] = 'GD2';
								$config['source_image']	= FCPATH.'public-images/'.$file;
								$config['maintain_ratio'] = TRUE;
								$config['width']	 = 400;
								$config['height']	= 600;

								$this->load->library('image_lib', $config);
								$this->image_lib->resize($config);			
							}
						} else {
							$error = array('error' => $this->upload->display_errors());
							$file = '';
						}
					} 
				}
				$input['type'] = 'contractor';
			
				$updated = $this->public_page_handler->update_settings($input);
				if($updated) {
					$this->session->set_flashdata('success', 'Public Page Settings Added Successfully. To view page as seen publicly, click the "View Public Page" button in the upper right side of this page.');
					$fresh = $this->session->userdata('fresh');
					if($fresh) {
						$this->session->set_flashdata('success', 'Public Page Settings Added Successfully Now You Need To Create Your Ads In Order For Them To Show Up.');
						$this->session->unset_userdata('fresh');
						redirect('contractor/public-page');
					}
					redirect('contractor/public-page');
					exit();
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
					redirect('contractor/public-page');
					exit();
				}
			}

			$this->load->view('contractors/public-pageNEW', $data);
			
		}
		
		public function manage_zips()
		{
			$this->check_if_loggedin();
			$this->load->js('assets/themes/default/js/bootbox.js');
			$this->load->js('assets/themes/default/js/contractors/add-zips.js');
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('contractor/manage_zip_codes');
			$data['zips'] = $this->manage_zip_codes->get_current_zips();
			
			$this->load->view('contractors/add-zips', $data);
		}
		
		public function my_account()
		{
			$this->output->set_template('contractors/contractor-logged-in');
			$this->check_if_loggedin(); 
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->js('assets/themes/default/js/contractors/edit-account.js'); 
			$this->load->model('contractor/user_details');
			$data['subscription'] = $this->user_details->get_payment_details();
			$data['add_ons'] = $this->user_details->get_addon_payments();
			$data['profile'] = $this->user_details->get_user_details();

			$this->load->view('contractors/my-account', $data);
		}
		
		public function update_password()
		{
			$this->check_if_loggedin(); 
			$this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]|max_length[50]|xss_clean|required|matches[password_2]');
			$this->form_validation->set_rules('password_2', 'Confirm password', 'trim|min_length[6]|max_length[50]|xss_clean|required');
			if($this->form_validation->run() == true) {
				extract($_POST);
				$this->load->model('contractor/user_details');
				$updated = $this->user_details->update_password($password);
				if($updated) {
					$this->session->set_flashdata('success', 'Your password has been changed');
				} else {
					$this->session->set_flashdata('error', 'Something went wrong trying to change your password, try again');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect('contractor/my-account');
			exit;
		}
		
		public function service_requests()
		{
			$this->check_if_loggedin(); 
			$this->output->set_template('contractors/contractor-logged-in');
			$offset = $this->uri->segment(3);
			$this->load->model('contractor/service_request');
			
			$this->load->library('pagination');
			$config = $this->paginationTemplate();
			$config['per_page'] = 20;
			$config['base_url'] = base_url().'contractor/service-requests';
			$config['total_rows'] = $this->service_request->total_requests();

			$this->pagination->initialize($config); 
			
			$data['requests'] = $this->service_request->grab_service_requests($config['per_page'], $offset);
			$data['links'] = $this->pagination->create_links();
			
			$this->load->view('contractors/service-requests', $data);
		}

        public function sort_requests()
        {
            $options = array("Showing All", "Complete", "Incomplete");
            $sortBy = $_POST['sort'];
            if(in_array($sortBy, $options)) {

                switch ($sortBy) {
                    case 'Showing All':
                        $this->session->unset_userdata('sort_request');
                        $this->session->set_flashdata('success', 'Removed Sort Option');
                        break;
                    case 'Complete':
                        $this->session->set_flashdata('success', 'Only Showing Completed Requests');
                        $this->session->set_userdata('sort_request', 'y');
                        break;
                    case 'Incomplete':
                        $this->session->set_flashdata('success', 'Only Showing Incomplete Requests');
                        $this->session->set_userdata('sort_request', 'n');
                        break;
                    default:
                        $this->session->unset_userdata('sort_request');
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid Selection');
            }
            redirect('contractor/service-requests');
        }

		public function view_service_request()
		{
			$this->check_if_loggedin();
			
			$id = (int)$this->uri->segment(3);
			if(empty($id)) {
				redirect('contractor/notifications');
				exit;
			}
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->js('assets/themes/default/js/lightboxdistrib.min.js');
			$this->load->css('assets/themes/default/css/easybox.min.css');
			$this->load->js('assets/themes/default/js/easybox.js');
			$this->load->css('assets/themes/default/css/alertify.core.css');
			$this->load->js('assets/themes/default/js/alertify.min.js');
			
			$this->load->js('assets/themes/default/js/payment-search.js');
			
			$this->load->js('assets/themes/default/js/masked-input.js');
			$this->load->js('assets/themes/default/js/jquery.formatCurrency-1.4.0.min.js');
			$this->load->js('assets/themes/default/js/contractors/service-requests.js');
			
			$this->load->model('special/ad_handler');
			$this->load->model('special/service_request_handler');
			$this->load->model('contractor/service_request');
			$this->load->model('contractor/employees');
			$this->load->model('special/schedule_calendar');
			$this->load->model('modules/invoice');
			
			$data = $this->service_request_handler->build_service_request($id);
			
			if(empty($data)) {
				redirect('contractor/notifications');
				exit;
			}		
			
			if($data['request']->page_submit == 'y') {
				$zip = substr($data['request']->address, -5);
				$data['supply'] = $this->ad_handler->getSupplyHouses($zip, $data['request']->service_type);
			} else {
				$data['supply'] = $this->ad_handler->getSupplyHouses($data['rental']->rental_zip, $data['request']->service_type);
			}
			$data['isScheduled'] = $this->schedule_calendar->eventScheduled(current_url(), 'contractor');
			$data['notes'] = $this->service_request->get_request_notes($id);
			$data['invoices'] = $this->invoice->getRequestInvoices($id);
			$data['employees'] = $this->employees->get_employees();
			
			$this->load->view('contractors/view-new-service-request', $data);
		}
		
		public function resources()
		{
			$this->output->set_template('contractors/contractor-logged-in');
			$this->check_if_loggedin(); // Checks if user is logged in
			$this->load->view('contractors/resources');
		}
		
		public function supply_house_search()
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			$this->output->set_template('contractors/contractor-logged-in');
			$this->form_validation->set_rules('zip', 'Zip Code', 'required|trim|min_length[5]|max_length[5]|xss_clean|integer');
			$this->form_validation->set_rules('radius', 'Radius', 'required|trim|min-length[1]|max_length[2]|xss_clean|integer');
			$this->form_validation->set_rules('serviceType', 'Service Type', 'trim|min-length[1]|max_length[2]|xss_clean|integer');
			
			$this->load->js('assets/themes/default/js/landlords/resource.js');
			$this->load->js('https://maps.googleapis.com/maps/api/js?sensor=false');
			
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('landlords/resources');
				exit;
			} else {
				extract($_POST);
				$data = array('zip'=>$zip, 'radius'=>$radius, 'service'=>$serviceType);
				$this->load->model('landlords/resources_handler');
				$data['results'] = $this->resources_handler->searchSupplyHouses($data);
				
				
				$this->load->view('landlords/supply-house-search', $data);
			}
		}
		
		function add_note_to_request()
		{
			$id = $this->uri->segment(3);
			$this->form_validation->set_rules('note', 'Note', 'trim|min_length[10]|max_length[600]|xss_clean');	
			$this->form_validation->set_rules('id', 'Id', 'trim|min_length[1]|max_length[11]|xss_clean|integer');	
			if($this->form_validation->run() == true) {
				$data = $_POST;
				$data['image'] = $_FILES;
				$this->load->model('contractor/service_request');

				$results = $this->service_request->save_service_request_note($data);
				
				$this->session->set_flashdata($results);
			} else {
				$this->session->set_flashdata('error', validation_errors('<span>','</span>'));
			}
			redirect('contractor/view-service-request/'.$_POST['id']);
			exit;
		}
		
		public function terms_of_service()
		{
			$hash = $this->uri->segment(3);
			$this->load->view('contractor/terms-of-service');
		}
		
		function print_service_request()  
		{
			$this->check_if_loggedin(); 
			$id = (int)$this->uri->segment(3);		
			
			if(empty($id)) {
				redirect('contractor/view-service-request/'.$id);
				exit;
			}
			
			$this->output->set_template('blank');
			$this->load->helper(array('dompdf', 'file'));
			
			$this->load->model('special/ad_handler');
			$this->load->model('special/service_request_handler');
			$data = $this->service_request_handler->build_service_request($id);
			
			$ad_specs = array('service'=>$data['request']->service_type, 'zip'=>$data['rental']->zip, 'current_ads'=>$data['request']->ad_ids, 'request_id'=>$data['request']->id);
			$data['ad_post'] = $this->ad_handler->get_service_request_ads($ad_specs);	
			$data['suppliers'] = $this->ad_handler->getSupplyHouses($data['rental']->rental_zip, $data['request']->service_type); 
			
			$this->load->view('prints/service-request', $data); // Add Argument true after data
			$html = $this->load->view('prints/service-request', $data, true); // Add Argument true after data
			pdf_create($html, 'Service_Reqeust_'.$data['rental']->address);  
		}
		
		function add_item_to_property()
		{
			$id = (int)$this->uri->segment(3);
			$this->check_if_loggedin(); 
			$this->form_validation->set_rules('desc', 'Item Name', 'trim|max_length[70]|xss_clean|required'); 
			$this->form_validation->set_rules('modal_num', 'Model Number', 'trim|max_length[70]|xss_clean'); 
			$this->form_validation->set_rules('serial', 'Serial', 'trim|max_length[70]|xss_clean'); 
			$this->form_validation->set_rules('brand', 'Brand', 'trim|max_length[70]|xss_clean'); 
			$this->form_validation->set_rules('service_type', 'Service Type', 'trim|max_length[70]|xss_clean|required'); 
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				$this->load->model('contractor/service_request');
				$data = $_POST;
				$data['request_id'] = $id;
				$results = $this->service_request->add_item($data);
				$this->session->set_flashdata($results);
			}
			redirect('contractor/view-service-request/'.$id);
			exit;
		}
		
		function mark_service_request_complete()
		{
			$id = (int)$this->uri->segment(3);
			$this->check_if_loggedin(); 
			$this->form_validation->set_rules('cost', 'Cost of Repair', 'trim|min_length[1]|max_length[15]|xss_clean|required');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				if(!empty($id)) {
					$this->load->model('contractor/service_request');
					extract($_POST);
					$feedback = $this->service_request->request_complete($id, $cost);
					$this->session->set_flashdata($feedback);
				} else {
					$this->session->set_flashdata('error', 'Invalid data inserted into form, try again');
				}
			}
			redirect('contractor/view-service-request/'.$id);
			exit;
		}
		
		function current_ads()
		{
			$this->check_if_loggedin(); 
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('contractor/ad_handler');
			$data['ads'] = $this->ad_handler->get_ads();
			
			$this->load->view('contractors/current-ads', $data);
		}
				
		function purchase_ads()
		{
			$this->load->js('assets/themes/default/js/bootbox.js'); 
			$this->load->js('assets/themes/default/js/jquery.creditCardValidator.js');
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->js('assets/themes/default/js/contractors/purchase-ads.js'); 
			$this->check_if_loggedin(); 
			$this->output->set_template('contractors/contractor-logged-in');
			
			$this->load->model('contractors/ad_handler');
			$data['billing'] = $this->ad_handler->get_user_billing_details();
			
			$this->load->view('contractors/purchase-ads', $data);
		}
		
		function past_ads()
		{
			$this->check_if_loggedin(); 
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('contractor/ad_handler');
			$data['ads'] = $this->ad_handler->past_ads();
			
			$this->load->view('contractors/past-ads', $data);
		}
		
		function edit_ad()
		{
			$id = (int)$this->uri->segment(3); 
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->js('assets/themes/default/js/contractors/edit-ads.js'); 
			$this->check_if_loggedin(); 
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('contractor/ad_handler');
			
			$data['ad_details'] = $this->ad_handler->pull_ad($id);
			
			//apply_post title bName phone description file
			$this->form_validation->set_rules('apply_post', 'Apply to post', 'trim|min_length[1]|max_length[1]|xss_clean|required|integer');
			$this->form_validation->set_rules('title', 'Title', 'trim|min_length[5]|max_length[30]|xss_clean|required');
			$this->form_validation->set_rules('bName', 'Business Name', 'trim|min_length[4]|max_length[30]|xss_clean|required');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|min_length[14]|max_length[14]|xss_clean|required');
			$this->form_validation->set_rules('description', 'Description', 'trim|min_length[3]|max_length[145]|xss_clean');
			if($this->form_validation->run() == true) {
				$d = $_POST;
				$d['file'] = $_FILES['file'];
				$data['feedback'] = $this->ad_handler->edit_ad($d); 
				
				if($data['feedback'] === true) {
					$this->session->set_flashdata('success', 'Your ad has been updated');
					redirect('https://network4rentals.com/network/contractor/current-ads');
					exit;
				}
			} else {
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					$data['ad_details']->title = $_POST['title'];
					$data['ad_details']->bName = $_POST['bName'];
					$data['ad_details']->phone = $_POST['phone'];
					$data['ad_details']->description = $_POST['image'];
					$data['feedback'] = array('error'=>validation_errors());
				}
			}
			
			
			if($data===false) {
				$this->session->set_flashdata('error', 'No ad found for the one you selected');
				redirect();
				exit;
			}
			$this->load->view('contractors/edit-ads', $data);			
		}
		
		function stats()
		{
			$this->check_if_loggedin(); 
			$this->load->js('assets/themes/default/js/contractors/Chart.js'); 
			$this->load->js('assets/themes/default/js/contractors/Chart.Bar.js'); 
			$this->load->js('assets/themes/default/js/contractors/stats.js'); 
			$this->output->set_template('contractors/contractor-logged-in');
			
			$this->load->model('contractor/stats_handler');
			$data['stats'] = $this->stats_handler->stats_data();
			$data['bottom_stats'] = $this->stats_handler->other_stats();
			$this->load->view('contractors/stats', $data);		
		}
		
		public function logout()
		{
			$this->session->sess_destroy();
			redirect('contractor/login');
			exit;
		}
		
		public function notifications() 
		{
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('special/user_activity_page');
			
			$this->form_validation->set_rules('date_to', 'Date To', 'required|trim|max_length[15]|xss_clean');
			$this->form_validation->set_rules('date_from', 'Date From', 'required|trim|max_length[15]|xss_clean');
			if($this->form_validation->run() == TRUE)
			{
				extract($_POST);
				$this->session->set_userdata('date_to', $date_to);
				$this->session->set_userdata('date_from', $date_from);
			} 
			
			$offset = $this->uri->segment(3);
			
			$data = $this->user_activity_page->activity('contractor', $offset);
			$data['date_to'] = $this->session->userdata('date_to');
			$data['date_from'] = $this->session->userdata('date_from');
			$data['reset'] = $this->session->flashdata('reset');
			//$this->output->enable_profiler(TRUE);
			$this->load->view('renters/activity', $data);
		}
		
		public function old_remove_notifications()
		{		
			$id = $this->session->userdata('user_id');
			
			$this->load->js('assets/themes/default/js/bootstrap-datepicker.js'); 
			$this->load->js('assets/themes/default/js/contractors/notifications.js'); 
			$this->load->css('assets/themes/default/css/datepicker.css'); 
			$this->form_validation->set_rules('date_to', 'Date To', 'required|trim|max_length[15]|xss_clean');
			$this->form_validation->set_rules('date_from', 'Date From', 'required|trim|max_length[15]|xss_clean');
			if($this->form_validation->run() == TRUE)
			{
				extract($_POST);
				$this->session->set_userdata('date_to', $date_to);
				$this->session->set_userdata('date_from', $date_from);
			} 
			
			$this->load->model('contractor/activity_handler');
			$this->load->library('pagination');
			
			$config['base_url'] = base_url().'contractor/notifications';
			
			$config['total_rows'] = $this->activity_handler->record_count($id,  $this->session->userdata('date_to'), $this->session->userdata('date_from'));
			$config['per_page'] = 20; 
			$config['full_tag_open'] = '<div><ul class="pagination">';
			$config['full_tag_close'] = '</ul></div><!--pagination-->';
			 
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			 
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			 
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			 
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			 
			$config['cur_tag_open'] = '<li class="active text-warning"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			 
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			
			$this->pagination->initialize($config); 
			
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			
			$data["results"] = $this->activity_handler->fetch_recent_activity($config["per_page"], $page, $id, $this->session->userdata('date_to'), $this->session->userdata('date_from'));
		
			$data['date_to'] = $this->session->userdata('date_to');
			$data['date_from'] = $this->session->userdata('date_from');
			$data['reset'] = $this->session->flashdata('reset');
			
			$data["links"] = $this->pagination->create_links();
			
			$this->output->set_template('contractors/contractor-logged-in');
			$this->check_if_loggedin(); 
			$this->load->view('contractors/activity-page', $data);
		}
		
		function update_personal_info()
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			//Personal Details
			$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[3]|max_length[50]|alpha|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[3]|max_length[50]|alpha|xss_clean');
			$this->form_validation->set_rules('address', 'Address', 'required|min_length[3]|max_length[70]|xss_clean');
			$this->form_validation->set_rules('baddress', 'Billing Address', 'required|min_length[3]|max_length[70]|xss_clean');
			$this->form_validation->set_rules('bstate', 'Billing State', 'required|min_length[2]|max_length[2]|alpha|xss_clean');
			$this->form_validation->set_rules('state', 'State', 'required|min_length[2]|max_length[2]|alpha|xss_clean');
			$this->form_validation->set_rules('bzip', 'Billing Zip', 'required|min_length[5]|max_length[5]|numeric|xss_clean');
			$this->form_validation->set_rules('zip', 'Zip', 'required|min_length[5]|max_length[5]|numeric|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'required|min_length[3]|max_length[70]|xss_clean');
			$this->form_validation->set_rules('bcity', 'Billing City', 'required|min_length[3]|max_length[70]|xss_clean');
			$this->form_validation->set_rules('phone', 'Phone', 'required|min_length[14]|max_length[18]|xss_clean');
			$this->form_validation->set_rules('cell', 'Cell', 'min_length[14]|max_length[18]|xss_clean');
			$this->form_validation->set_rules('fax', 'Fax', 'min_length[14]|max_length[18]|xss_clean');
			$this->form_validation->set_rules('bName', 'Business', 'min_length[5]|max_length[70]|xss_clean|required');
			$this->form_validation->set_rules('email', 'Email', 'min_length[5]|max_length[70]|xss_clean|required|valid_email');
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				extract($_POST);
				$phone = preg_replace("/[^0-9]/", '', $phone);
				$fax = preg_replace("/[^0-9]/", '', $fax);
				$cell = preg_replace("/[^0-9]/", '', $cell);
				$data = array(
					'email'	   => $email,		
					'address'  => $address,
					'city'     => $city,
					'state'	   => $state,
					'zip'	   => $zip,
					'bName'    => $email,
					'f_name'   => $first_name,	
					'l_name'   => $last_name,	
					'baddress' => $baddress,	
					'bcity'	   => $bcity,
					'bstate'   => $bstate,
					'bzip'	   => $bzip,
					'phone'	   => $phone,
					'fax'      => $fax,
					'email'	   => $email,
					'bName'	   => $bName,
					'cell' 	   => $cell
				);
		
				$this->load->model('contractor/user_details');
				$results = $this->user_details->update_personal_info($data);
				if($results) {
					$this->session->set_flashdata('success', 'Your Account Has Been Updated');
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
				}
			}
			redirect('contractor/my-account');
			exit;
			
		}
		
		public function add_service_request()
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			$this->load->css('assets/themes/default/css/alertify.core.css');
			$this->load->js('assets/themes/default/js/alertify.min.js');
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->js('assets/themes/default/js/contractors/add-service-request.js'); 
			$this->load->view('contractors/add-service-request');
		}
		
		public function reset_dates() // Resets Dates In The Activity Page
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			$this->session->unset_userdata('date_to');
			$this->session->unset_userdata('date_from');
			$this->session->set_flashdata('reset', '<div class="alert alert-success"><p><b>Success:</b> Dates have been reset</p></div>');
			redirect('contractor/notifications');
			exit; 
		}
		
		public function forgot_password() 
		{
			
			/* Set a few basic form validation rules */
			$this->form_validation->set_rules('email', 'Email', 'required|trim|min_length[3]|max_length[100]|xss_clean');
			$this->load->library('recaptcha');
			$this->recaptcha->recaptcha_check_answer();
			extract($_POST);
			
			/* Check if form (and captcha) passed validation*/
			if ($this->form_validation->run() == TRUE) {
				if($this->recaptcha->getIsValid()) {
					$this->session->unset_userdata('captchaWord');
					$this->load->model('contractor/reset_password');
					$hash = $this->reset_password->check_user_email($email);

					if($hash != false) 
					{
						// DB Updated with hash now need to email user that hash
						$this->load->model('special/send_email');
						$message = '
							<h3>Reset Your Password</h3>
							<p>You have requested to reset your password. If you did not request to have your password reset you can ignore this email. Else click the link below to go through the steps to reset your password.</p><a href="'.base_url().'contractor/reset-password/'.$hash.'">Reset Password</a>
						';
						$subject = 'N4R | Password Reset Instructions';
						$this->send_email->sendEmail($email, $message, $subject);
						$this->session->set_flashdata('success', 'An email has been sent to '.$email.' with instructions on how to reset your password. If for some reason you don\'t receive the email please contact us.');
						redirect('contractor/forgot-password');
					} 
					else
					{
						$this->session->set_flashdata('error', 'Invalid Email');
						redirect('contractor/forgot-password');
					}		  
				} else {
					$this->session->set_flashdata('error', 'Invalid captcha, try again');
					redirect('contractor/forgot-password');
				}
			}
			$data['recaptcha_html'] = $this->recaptcha->recaptcha_get_html();
			$this->load->view('contractors/forgot-password', $data);
		}	

		function reset_password() //Handles the view after the link in the email has been clicked
		{
			if($this->session->userdata('token') == '') {
				$token = $this->uri->segment(3);
				$token = $this->security->xss_clean($token);
				$this->load->model('contractor/reset_password');
				if($this->reset_password->check_token($token) == true) {
					$this->session->set_userdata('token', $token);
					$this->load->view('contractors/change-password');
				} else {
					$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
					redirect('contractor/forgot-password');
					exit;
				}
			} else {
				$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[50]|xss_clean');
				$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|min_length[6]|max_length[200]|matches[password]|xss_clean');
				$this->form_validation->set_rules('token', 'Link', 'required|trim|max_length[100]|xss_clean');
			
				if($this->form_validation->run() == FALSE) 
				{
					$this->load->view('contractors/change-password');
				}
				else 
				{
					extract($_POST);
					$this->load->model('contractor/reset_password');
					if($this->reset_password->check_token($token) == true) {
						if($this->reset_password->change_password($token, $password) == true) {
							$this->session->set_userdata('token', '');
							$this->session->set_flashdata('success', 'Your Password Has Now Been Changed, You Can Login Now With Your New Password');
							redirect('contractor/login');
							exit;
						} else {
							$this->session->set_userdata('token', '');
							$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
							redirect('contractor/forgot-password');
							exit;
						}
					} else {
						$this->session->set_userdata('token', '');
						$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
						redirect('contractor/forgot_password');
						exit;
					}
					$this->session->sess_destroy();
				}
			}
		}
		
		public function edit_page()
		{
			$id = $this->uri->segment(3);
			$this->check_if_loggedin(); 
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('contractors/edit_page');
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$this->edit_page->update($id);
				redirect('contractor/edit-page/'.$id);
				exit;
			}
		
			$this->load->css('assets/themes/default/js/summernote/summernote.css');
			$this->load->css('assets/themes/default/css/contractors/bootstrap-tagsinput.css');
			$this->load->js('assets/themes/default/js/contractors/bootstrap-tagsinput.min.js'); 
			$this->load->css('assets/themes/default/js/summernote/summernote-bs3.css');
			$this->load->js('assets/themes/default/js/summernote/summernote.min.js');
			$this->load->js('assets/themes/default/js/assoc/wysiwyg.js');
			$this->load->js('assets/themes/default/js/notify.min.js'); 
			$this->load->js('assets/themes/default/js/contractors/edit-page.js'); 
			
			$data['details'] = $this->edit_page->page_details($id);
			if(empty($data['details'])) {
				redirect('contractor/public-page');
				exit;
			}
			$this->load->view('contractors/edit-page', $data);
		}
		
		public function add_new_page()
		{
			$this->form_validation->set_rules('pagename', 'Page Name', 'required|trim|min_length[2]|max_length[50]|xss_clean');
			
			extract($_POST);
			
			if(strtolower($pagename) == 'work request') {
				$this->session->set_flashdata('error', 'Work Request is a reserved page name, try a different one');
				redirect('contractor/public-page');
				exit;
			}
			
			if ($this->form_validation->run() == TRUE) {
				$this->load->model('contractor/public_pages');
				$this->public_pages->add_page($pagename);
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
			redirect('contractor/public-page');
			exit;
		}
		
		/* PRIVATE FUNCTIONS */
		
		private function paginationTemplate()
		{
			$config['full_tag_open'] = '<div><ul class="pagination">';
			$config['full_tag_close'] = '</ul></div>';
			 
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			 
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			 
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			 
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			 
			$config['cur_tag_open'] = '<li class="active text-warning"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			 
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			return $config;
		}
		
		public function delete_page()
		{
			$page_id = (int)$this->uri->segment(3);
			if($page_id>0) {
				$this->load->model('contractor/public_page_handler');
				$results = $this->public_page_handler->delete_page($page_id);
				if($results) {
					$this->session->set_flashdata('success', 'The page has been deleted successfully');
				} else {
					$this->session->set_flashdata('error', 'Page not deleted, something went wrong. Try again');
				}
			} else {
				$this->session->set_flashdata('error', 'Page could not be deleted, try using the button below.');
			}
			redirect('contractor/public-page');
			exit;
		}
		
		public function my_employees()
		{
			$this->check_if_loggedin();
			$this->load->css('assets/themes/default/colorpicker/css/bootstrap-colorpicker.min.css');
			$this->load->js('assets/themes/default/colorpicker/js/bootstrap-colorpicker.min.js'); 
			$this->load->js('assets/themes/default/js/contractors/add-employee.js'); 
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			
			$this->load->model('contractor/employees');
			
			$data['employees'] = $this->employees->get_employees();
			$this->load->view('contractors/add-employee', $data);
		}
		
		public function add_employee()
		{
			$this->check_if_loggedin();
			$this->form_validation->set_rules('name', 'Employee Name', 'min_length[2]|max_length[30]|xss_clean|required');
			$this->form_validation->set_rules('email', 'Employee Email', 'min_length[5]|max_length[60]|xss_clean|required|valid_email');
			$this->form_validation->set_rules('color', 'Employee Color', 'min_length[7]|max_length[7]|xss_clean|required');
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				extract($_POST);
				$this->load->model('contractor/employees');
				$data = array(
					'name'=>$name,
					'email'=>$email,
					'color'=>$color
				);
				if($this->employees->add_employee($data)) {
					$this->session->set_flashdata('success', 'Employee added successfully');
				} else {
					$this->session->set_flashdata('error', 'Something went wrong adding the employee, try again.');
				}
			}
			redirect('contractor/my-employees');
			exit;
		}
		
		public function edit_employee() 
		{
			$this->check_if_loggedin();
			$this->form_validation->set_rules('name', 'Employee Name', 'min_length[2]|max_length[30]|xss_clean|required');
			$this->form_validation->set_rules('email', 'Employee Email', 'min_length[5]|max_length[60]|xss_clean|required|valid_email');
			$this->form_validation->set_rules('color', 'Employee Color', 'min_length[7]|max_length[7]|xss_clean|required');
			$this->form_validation->set_rules('id', 'Employee Id', 'min_length[1]|max_length[15]|xss_clean|required|integer');
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				extract($_POST);
				$this->load->model('contractor/employees');
				$data = array(
					'name'=>$name,
					'email'=>$email,
					'color'=>$color,
				);
				if($this->employees->edit_employee($data, $id)) {
					$this->session->set_flashdata('success', 'Employee info saved successfully');
				} else {
					$this->session->set_flashdata('error', 'Something went wrong saving the employee data, try again.');
				}
			}
			redirect('contractor/my-employees');
			exit;			
			
		}
		
		public function delete_employee()
		{
			$this->check_if_loggedin();
			$id = (int)$this->uri->segment(3);	
			if(!empty($id)) {
				$this->load->model('contractor/employees');
				if($this->employees->delete_employee($id)) {
					$this->session->set_flashdata('success', 'Employee deleted successfully');
				} else {
					$this->session->set_flashdata('error', 'Something went wrong deleting the employee, try again.');
				}
			}
			redirect('contractor/my-employees');
			exit;
		}
		
		public function my_calendar()
		{
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->css('assets/themes/default/css/fullcalendar.css');
			$this->load->css('assets/themes/default/css/alertify.core.css');
			$this->load->js('assets/themes/default/js/alertify.min.js');
			$this->load->js('assets/themes/default/js/jquery-ui.custom.min.js');
			$this->load->js('assets/themes/default/js/fullcalendar.min.js');
			$this->load->js('assets/themes/default/js/masked-input.js');
			$this->load->js('assets/themes/default/js/contractors/my-calendar.js');
			
			$this->load->model('contractor/employees');
			$data['employees'] = $this->employees->get_employees();
			$this->load->view('contractors/my-calendar', $data);
			
		}
		
		public function payment_settings() 
		{
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			
			$this->load->model('contractor/payment_settings');
			if(!empty($_POST)) {
				$this->payment_settings->saveUserSettings($_POST);
				redirect('contractor/payment-settings?settings=true');
				exit;
			}
			
			$data['settings'] = $this->payment_settings->getUserSettings($this->session->userdata('user_id'));
			
			if($data['settings'] || $_GET['settings']) {
				$this->load->view('common/payment-settings', $data);
			} else {
				$this->load->view('common/payment-intro');
			}
		}
		
		public function mark_as_paid() 
		{
			if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['paid'])) {
				$is_error = false;
				$this->load->model('modules/invoice');
				foreach($_POST['paid'] as $val) {
					if(is_numeric($val)) {
						if(!$this->invoice->markInvoiceAsPaid($val)) {
							$is_error = true;
						}
					} else {
						$is_error = true;
					}
				}
				
				if($is_error) {
					$this->session->set_flashdata('error', 'Some of the invoices failed to marks as complete, try again');
				} else {
					$this->session->set_flashdata('success', 'Invices marked as complete');
				}
			}
			
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		}		
		
		public function view_payments() 
		{
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->js('assets/themes/default/js/payment-search.js');
			$this->load->model('special/local_payments');
			$userdata = array(
				'id' => $this->session->userdata('user_id'),
				'type' => 'contractor',
			);
			$data['payments'] = $this->local_payments->getPayments($userdata);
			$data['sum'] = $this->local_payments->getPaymentsSum($userdata);
			$this->load->view('common/view-payments', $data);
		}
		
		public function view_payment_info() 
		{ 
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('special/local_payments');
			
			$id = $this->uri->segment('3');
		
			$data['user'] = $this->local_payments->getPublicPageSettings($this->session->userdata('user_id'), 'contractor');
			$data['payment'] = $this->local_payments->getSinglePayment($id);
			if(empty($data['payment']->payment)) {
				$this->session->set_flashdata('error', 'Invalid Selection, Try Again');
				redirect('contractor/view-payments');
				exit;
			}
			$this->load->view('common/view-payment-details', $data);
		}
		
		public function print_payment_details() 
		{
			$this->check_if_loggedin();
			$this->load->model('special/local_payments');
			$this->local_payments->printPaymentDetails($this->uri->segment(3), $this->session->userdata('user_id'), 'contractor', $_GET['type']);
		}
		
		public function create_invoice()
		{
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('special/local_payments');
			$this->load->js('assets/themes/default/js/payment-search.js');
			
			
			$this->load->view('common/create-invoice');
			
		}
		
		public function view_invoices() 
		{
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->js('assets/themes/default/js/payment-search.js');
			$this->load->model('special/local_payments');
			$userdata = array(
				'id' => $this->session->userdata('user_id'),
				'type' => 'contractor',
			);
			$data['payments'] = $this->local_payments->getInvoices($userdata);
			$data['sum'] = $this->local_payments->getInvoiceSum($userdata);
			
			$this->load->view('common/view-invoices', $data);
		}
		
		public function view_invoice() 
		{ 
			$this->check_if_loggedin();
			$this->output->set_template('contractors/contractor-logged-in');
			$this->load->model('special/local_payments');
			
			$id = $this->uri->segment('3');
			
			$data['user'] = $this->local_payments->getPublicPageSettings($this->session->userdata('user_id'), 'contractor');
			$data['payment'] = $this->local_payments->getSingleInvoice($id);
		
			if(empty($data['payment']->invoice)) {
				$this->session->set_flashdata('error', 'Invalid Selection, Try Again');
				redirect('contractor/view-invoices');
				exit;
			}
			$this->load->view('common/view-payment-details', $data);
		}
		
		
		
	}