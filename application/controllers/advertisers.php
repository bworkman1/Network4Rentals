<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Advertisers extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			$this->load->helper('url');
			$this->_init();
		}
			
		function check_if_loggedin() //changed
		{
			if($this->session->userdata('logged_in') == false)
			{
				redirect('advertisers/login');
				exit;
			}
			if($this->session->userdata('side_logged_in') != '54688486846464') {
				$this->session->sess_destroy();
				redirect('advertisers/login');
				exit;
			}
		}	
		
		private function _init() //changed
		{
			redirect('local-partner/');
			exit;
			
			
			$this->load->model('special/ads_output');
			$data['result'] = $this->ads_output->get_ads_in_location();
			$this->load->vars($data);
			
			$this->output->set_template('advertisers');
			$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
			$this->load->js('assets/themes/default/js/bootstrap.min.js');
			$this->load->js('assets/themes/default/js/advertisers.js'); 
			$this->load->css('assets/themes/default/css/bootstrap-theme-advertisers.css'); 
		}
		
		public function index() //changed
		{
			$this->load->js('assets/themes/default/js/fitvids.js');
			$this->load->view('advertisers/home');
		}
		
		public function login()   //changed
		{
			$this->load->view('advertisers/login');
		}
		
		public function check_login() //changed
		{
			$this->form_validation->set_rules('username', 'Username', 'required|min_length[6]|max_length[20]|xss_clean');		
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]|xss_clean');		
			
			if ($this->form_validation->run() == FALSE) {
				
			} else {
				extract($_POST);
				$data = array('user'=>$username, 'password'=>md5($password), 'active'=>'y');
				$this->load->model('advertisers/login_handler');
				$results = $this->login_handler->login($data);
				if($results) {
					redirect('advertisers/home');
					exit;
				} else {
					$this->session->set_flashdata('error', 'Incorrect Username Or Password, Try Again');
					redirect('advertisers/login');
					exit;
				}
			}
		}
		
		public function create_account() //changed
		{
			$this->load->js('assets/themes/default/js/steps-advertisers.js'); 
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->js('assets/themes/default/js/bootbox.js'); 
			$this->load->js('assets/themes/default/js/jquery.creditCardValidator.js'); 
			$this->load->view('advertisers/create-account');
			
			//Personal Details
			$this->form_validation->set_rules('bName', 'Business Name', 'required|min_length[3]|max_length[70]|xss_clean');
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
			$this->form_validation->set_rules('fax', 'Fax', 'min_length[14]|max_length[18]|xss_clean');
			//Payment Info
			$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[3]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('exp_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
			$this->form_validation->set_rules('exp_year', 'Expiration Year', 'required|min_length[4]|max_length[4]|numeric|xss_clean');
			$this->form_validation->set_rules('ccv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean');
			$this->form_validation->set_rules('name_on_card', 'Name On Credit Card', 'required|min_length[5]|max_length[70]|xss_clean');
			$this->form_validation->set_rules('frequency', 'Payment Frequency', 'required|min_length[1]|max_length[2]|numeric|xss_clean');
			//User Account 
			$this->form_validation->set_rules('email', 'Email', 'required|min_length[3]|max_length[60]|valid_email|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|max_length[20]|matches[password2]|xss_clean');
			$this->form_validation->set_rules('password2', 'Password', 'required|min_length[3]|max_length[20]|matches[password]|xss_clean');
			$this->form_validation->set_rules('terms', 'Terms Of Service', 'required|min_length[1]|max_length[1]|xss_clean');		
			
			
			if ($this->form_validation->run() == FALSE)
			{	
				
			} else {
				extract($_POST);
				//strip phone and fax leaving only numbers
				$phone = preg_replace("/[^0-9]/", '', $phone);
				$fax = preg_replace("/[^0-9]/", '', $fax);
				$this->load->helper('string');
				$data = array(
					'bName'		=> $bName,
					'first_name'=> $first_name,
					'last_name'	=> $last_name,
					'address'	=> $address,
					'baddress'	=> $baddress,
					'bstate'	=> $bstate,
					'bcity'		=> $bcity,
					'state'		=> $state,
					'bzip'		=> $bzip,
					'zip'		=> $zip,
					'city'		=> $city,
					'phone'		=> $phone,
					'fax'		=> $fax,
					'frequency'	=> $frequency,
					'email'		=> $email,
					'user'		=> $email,
					'password'	=> $password,
					'terms'		=> $terms,
					'credit_card'	=> $credit_card,
					'exp_month'		=> $exp_month,
					'exp_year'		=> $exp_year,
					'ccv'			=> $ccv,
					'name_on_card'	=> $name_on_card,
					'hash'			=> random_string('sha1', 25)
				);
	
				$this->load->model('advertisers/process_payment');
			
				$results = $this->process_payment->submit_payment($data);
					
				if($results == '89') { //payment successful
					$this->session->set_flashdata('success', 'Payment Successful And Your Account Has Been Created. You Will Receive An Email With Your Order Details To The Email Address You Provided. In Order For Your Post To Show Up On The Website You Will Need To Create Your Public Page Below.');
					$subject = 'Payment Posted Successfully To N4R';
								
					$zips = $this->session->userdata('zips');
					$price = $this->session->userdata('price');	
					$city = $this->session->userdata('city');	
					$state = $this->session->userdata('state');
					$service = $this->session->userdata('service');
					if($frequency == 1) {
						$billing = 'Monthly';
					} else if($frequency == 3) {
						$billing = 'Quarterly';
					} else {
						$billing = 'Yearly';
					}
					switch($frequency)
					{
						case "1":         $months = 1; break;
						case "3":       $months = 3; break;
						case "6":   $months = 6; break;
						case "12":        $months = 12; break;
						default:                $months = 1; break;
					}
					$today = date('Y-m-d');
					$next_due_date = strtotime($today.' + '.$months.' Months');
					$yearly_contract = 12;
					$services_array = array('', 'Landlords', 'Tenants', 'Contractors', 'Listings');
					$message = '<h3>'.$bName.'</h3>
						<p>Thank you for becoming a sponsored Network4Rentals advertiser. This is to confirm that your credit card payment for your account has been authorized and processed. The details of the transaction are below.</p>
						<h4>Business Info</h4>
						<table cellpadding="4" width="60%" align="left">
							<tr>
								<td><b>Business Name:</b></td>
								<td>'.$bName.'</td>
							</tr>
							<tr>
								<td><b>Contact Name:</b></td>
								<td>'.$first_name.' '.$last_name.'</td>
							</tr>
							<tr>
								<td valign="top"><b>Address:</b></td>
								<td>'.$address.' '.$data['city'].', '.$data['state'].'. '.$zip.'</td>
							</tr>
							<tr>
								<td valign="top"><b>Billing Address:</b></td>
								<td>'.$baddress.' '.$bcity.', '.$bstate.'. '.$bzip.'</td>
							</tr>
							<tr>
								<td><b>Phone:</b></td>
								<td>('.substr($phone, 0, 3).') '.substr($phone, 3, 3).'-'.substr($phone,6).'</td>
							</tr>
							<tr>
								<td><b>Email:</b></td>
								<td>'.$email.'</td>
							</tr>
							<tr>
								<td><b>Date:</b></td>
								<td>'.date('m-d-Y').'</td>
							</tr>
						</table>	
						<table cellpadding="4" width="39%" align="right">
							<tr>
								<td align="right"><b>Sponsored Post:</b></td>
								<td>'.count($zips).'</td>
							</tr>
							<tr>
								<td align="right"><b>Billing Cycle:</b></td>
								<td>'.$billing.'</td>
							</tr>	
							<tr>
								<td align="right"><b>Cost Per Billing Cycle:</b></td>
								<td>$'.array_sum($price)*$frequency.'</td>
							</tr>
							<tr>
								<td align="right"><b>Next Billing Date:</b></td>
								<td>'.date('m-d-Y', $next_due_date).'</td>
							</tr>
							<tr>
								<td align="right"><b>Subscription Term:</b></td>
								<td>1 Year</td>
							</tr>
							<tr>
								<td align="right"><b>Subscription Total Cost:</b></td>
								<td>$'.array_sum($price)*$yearly_contract.'</td>
							</tr>
						</table>		
						<table width="100%">
						<tr>
							<td><h3>Build Your Post</h3>
						<p>You may have already followed the websites request and created your public web page and created your post so that start displaying on landlords service request. If not please do so as soon as possible so that you are taking full advantage of your sponsored space. To edit your public web page go to <a href="https://network4rentals.com/network/advertisers/public-page-settings">https://network4rentals.com/network/advertisers/public-page-settings</a> once you add all the details there go to <a href="https://network4rentals.com/network/advertisers/my-zips">https://network4rentals.com/network/advertisers/my-zips</a> to edit your posts so that they are showing your information to the landlords.</p>
							</td>
						</tr>
						</table>
						<table cellpadding="4" width="100%">
							<tr>
								<th align="left">Zips</th>
								<th align="left">City</th>
								<th align="left">State</th>
								<th align="left">Service</th>
								<th align="left">Price</th>
							</tr>';
							
							for($i=0;$i<count($zips);$i++) {
								if ($i%2===0){ 
									$message .= '<tr bgcolor="#DFDFDF">';
								} else {
									$message .= '<tr>';
								}
								$message .= '
									<td>'.$zips[$i].'</td>
									<td>'.$city[$i].'</td>
									<td>'.$state[$i].'</td>
									<td>'.$services_array[$service[$i]].'</td>
									<td>$'.$price[$i].' <small>per month</small></td>
								</tr>';
							}
							$message .= '</table>';
							$message .= '<br><br><small>"Network4Rentals.com operates on a 1year subscription basis. All accounts not cancelled in writing at least 30 days prior to the subscription renewal date will have their subscription automatically renewed and be billed as per their previously selected billing terms."</small>';
					
					$this->sendEmail($email, $message, $subject);
					$this->session->unset_userdata('zips');
					$this->session->unset_userdata('price');
					$this->session->unset_userdata('city');
					$this->session->unset_userdata('state');
					
					$this->session->set_userdata('fresh', true);
					redirect('advertisers/public-page-settings');
					exit;
				} else {
					switch ($results) {
						case '1':
							$this->session->set_flashdata('error', 'Payment Failed, Try A Different Credit Card.');
							break;
						case '2':
							$this->session->set_flashdata('error', 'Failed To Create User');
							break;
						case '3':
							$this->session->set_flashdata('error', 'Failed To Add Zips');
							break;
						case '4':
							$this->session->set_flashdata('error', 'Failed To Log Payment');
							break;
						case '5':
							$this->session->set_flashdata('error', 'Something Went Wrong While Adding Your Zip Codes And Services, Try Again');
							break;
					} 
					//redirect('advertisers/create-account');
					//exit;
				}
			}
		}
		
		public function show_available_zips() //changed
        {
			$this->output->set_template('json');
			$this->output->set_content_type('application/json');
            $zip = (int)$this->uri->segment(3);
            $service = (int)$this->uri->segment(4);
			if ($this->input->is_ajax_request()) {
                if (strlen($zip) == 5) {
                    $this->load->model('advertisers/zips_handler');
					echo $this->zips_handler->available_zips($zip, $service);
                } else {
					echo '1';	
				}
            } else {
				return false;
			}
		}
		
		public function add_zip() //changed
		{
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$data['zip'] = (int)$this->uri->segment(3);
				$data['service'] = (int)$this->uri->segment(4);
				$data['price'] = (int)$this->uri->segment(5);
				if(!empty($data['zip']) or !empty($data['service']) or !empty($data['price'])) {
					$this->load->model('advertisers/shopping_cart');
					$results = $this->shopping_cart->check_zip($data);
					echo json_encode($results);
				} else {
					$results = array('error', 'Not Found');
					echo json_encode($results);
				}
			}
			
		}
		
		public function remove_zip() //changed
		{
			if ($this->input->is_ajax_request()) {
				$this->output->set_template('json');
				$this->output->set_content_type('application/json');
				$data['zip'] = (int)$this->uri->segment(3);
				$data['s'] = (int)$this->uri->segment(4);
				if(!empty($data['zip']) or !empty($data['service'])) {
					$this->load->model('advertisers/shopping_cart');
					$results = $this->shopping_cart->remove_zip_code_cart($data);
					echo $results;
				} else {
					echo 'fail';
				}
			}
		}
		
		function check_username() //changed
		{	
			$this->output->set_template('json');
			if ($this->input->is_ajax_request()) {
				if(isset($_POST["username"])) {
					$username =  strtolower(trim($_POST["username"]));
					$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
					$this->load->model('advertisers/create_user_handler');
					echo $this->create_user_handler->check_username_avaliable($username);
				}
			}
		}
		
		function check_email() //changed
		{	
			$this->output->set_template('json');
			if ($this->input->is_ajax_request()) {
				if(isset($_POST["email"])) {
					$email =  strtolower(trim($_POST["email"]));
					$email = filter_var($email, FILTER_SANITIZE_EMAIL);
					$this->load->model('advertisers/create_user_handler');
					echo $this->create_user_handler->check_email_avaliable($email);
				}
			}
		}
		
		function home() //changed
		{
			$this->output->set_template('advertisers-logged-in');
			$this->check_if_loggedin();
			$this->load->view('advertisers/home-page');
		}
		
		public function logout() //changed
		{
			$this->session->sess_destroy();
			redirect('advertisers/login');
			exit;
		}
		
		public function public_page_settings() //changed
		{
			$this->output->set_template('advertisers-logged-in');
			$this->check_if_loggedin(); 
			$this->load->js('assets/themes/default/js/masked-input.js'); 
		
			$this->load->model('advertisers/public_page_handler');
			$page_settings = $this->public_page_handler->get_public_page_info();
			
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
			
			$this->form_validation->set_rules('address', 'Address', 'trim|max_length[100]|xss_clean');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'trim|max_length[50]|xss_clean|required');
			$this->form_validation->set_rules('state', 'State', 'trim|max_length[2]|xss_clean|required');
			$this->form_validation->set_rules('zip', 'Zip Code', 'trim|max_length[5]|min_length[5]|xss_clean|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|max_length[100]|xss_clean|valid_email|required');
			$this->form_validation->set_rules('website', 'Website Url', 'trim|max_length[255]|xss_clean');
			$background_select = '';	
			if($this->form_validation->run() == true) {
				extract($_POST);
				
				
				foreach($_POST as $key => $val) {
					if($key == 'unique_name') {
						$val = str_replace('-', ' ', $val);
						$val = preg_replace('/[^\da-z ]/i', '', $val);
						$val = str_replace(' ', '-', $val);
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
								$config['maintain_ratio'] = TRUE;
								$config['width']	 = 2000;
								$config['height']	= 350;

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
							// Resize The Image
							$config['image_library'] = 'GD2';
							$config['source_image']	= FCPATH.'public-images/'.$file;
							$config['maintain_ratio'] = TRUE;
							$config['width']	 = 400;
							$config['height']	= 600;

							$this->load->library('image_lib', $config);
							$this->image_lib->resize($config);					
						} else {
							$error = array('error' => $this->upload->display_errors());
							$file = '';
						}
					} 
				}
				$input['type'] = 'advertiser';
				$updated = $this->public_page_handler->update_settings($input);
				if($updated) {
					$this->session->set_flashdata('success', 'Public Page Settings Added Successfully. To view page as seen publicly, click the "View Public Page" button in the upper right side of this page.');
					$fresh = $this->session->userdata('fresh');
					if($fresh) {
						$this->session->set_flashdata('success', 'Public Page Settings Added Successfully Now You Need To Create Your Ads In Order For Them To Show Up.');
						$this->session->unset_userdata('fresh');
						redirect('advertisers/my-zips');
					}
					redirect('advertisers/public-page-settings');
					exit();
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
					redirect('advertisers/public-page-settings');
					exit();
				}
			}

			$this->load->view('advertisers/public-page-settings', $data);
			
		}
		
		function delete_public_image() //changed
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			$id = (int)$this->uri->segment(3);
			$this->load->model('landlords/public_page_handler');
			$deleted = $this->public_page_handler->delete_public_image($id);
			if($deleted) {
				$this->session->set_flashdata('success', 'Image has been deleted, you can now add a new image');
			} else {
				$this->session->set_flashdata('error', 'Image Not Found, Try Again');
			}
			redirect('advertisers/public-page-settings');
			exit;
		}		
		
		public function send_public_link() //changed
		{
			$this->check_if_loggedin();
			$this->form_validation->set_rules('email', 'Email', 'required|trim|max_length[70]|required|xss_clean');
			if($this->form_validation->run() == true) {
				extract($_POST);
				
				$this->load->model('advertisers/public_page_handler');
				$page_settings = $this->public_page_handler->get_public_page_info();
				
				$link = 'http://n4r.rentals/'.$page_settings->unique_name;
				
				if(!empty($page_settings->bName)) {
					$who = $page_settings->bName;
				} else {
					$who = $page_settings->name;
				}
				$message = '	
					<h3>'.$who.' Has Sent Your Their Rental Listings</h3>
					<p>Here is our public link on Network 4 Rentals that includes our available rental listings as well as our contact information. If you find something you like feel free to contact us to see the house.</p>
					<a href="'.$link.'">Our Listings</a>
				';
				$subject = $who.' Sent Your Their Public Rentals Link';
				$alt_message = $who.' sent you their public link go to '.$link;
				
				if($this->sendEmail($email, $message, $subject, $alt_message = null)) {
					$this->session->set_flashdata('success', 'Your Public Link Has Been Emailed To '.$email);
					redirect('advertisers/public-page-settings');
					exit;
				} else {
					$this->session->set_flashdata('error', 'Email Not Sent, Try Again');
					redirect('advertisers/public-page-settings');
					exit;
				}
			}
			
		} 		
		
		function email_format($message)
		{
			$email_body = '
			<html>
			<head>
			</head>
			<body>
			<center>
				<table width="100%" bgcolor="#428BCA" cellpadding="10">
					<tr>
						<td width="350px">
							<center>
								<a href="https://network4rentals.com"><img src="https://network4rentals.com/wp-content/uploads/2013/07/network_logo.png" border="0" width="300" alt="Network 4 Rentals"></a>
							</center>
						</td>
						<td width="400px" align="center">
							<FONT COLOR="#ffffff"><p><b>Improving Landlord &amp; Tenant Relations Nationwide</b></p></FONT>
						</td>
					</tr>
				</table>
				<table width="750px" cellpadding="10" bgcolor="#ffffff" align="left">
					<tr>
						<tdvalign="top">
							'.$message.'<br><br><h3>Unsure Of What To Do Next?</h3><p>If at any point you find yourself lost or unsure of what to do next there are plenty of resources available at your disposal our on <a href="https://network4rentals.com/fas/">faqs</a> page or our <a href="https://network4rentals.com/blog/">blog</a> page.</p>
						</td>
					</tr>
				</table>
			</center>
			</body>
			</html>';
			return $email_body;
		}			
	
		function sendEmail($email, $message, $subject, $alt_message = null)
		{
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from('no-reply@network4rentals.com', 'No Reply');
			$this->email->to($email);   
			$this->email->subject($subject);
			$message = $this->email_format($message);
			$this->email->message($message);	
			
			if($this->email->send()) {
				return true;
			} else {
				return false;
			}
		}	
		
		function my_zips() //changed
		{
			$this->check_if_loggedin();
			$this->output->set_template('advertisers-logged-in');
			$this->load->model('advertisers/ad_handler');
			$data['page_setting'] = $this->ad_handler->check_public_page();
			$data['my_zips'] = $this->ad_handler->get_my_zips();
			$this->load->view('advertisers/my-zips', $data);
		}
		
		function edit_post() //changed
		{
			$this->check_if_loggedin();	
			$this->output->set_template('advertisers-logged-in');
			$this->load->model('advertisers/public_page_handler');
			$this->load->model('advertisers/post_handler');
			$id = $this->uri->segment(3);
			
			$this->form_validation->set_rules('apply_post', 'Apply To', 'trim|max_length[1]|xss_clean|required|numeric');
			$this->form_validation->set_rules('title', 'Apply To', 'trim|max_length[30]|xss_clean');
			$this->form_validation->set_rules('desc', 'Post Description', 'trim|max_length[145]|xss_clean');
			if($this->form_validation->run() == true) {
				extract($_POST);
				
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
							$input['file'] = $file;
							// Resize The Image
							$config['image_library'] = 'GD2';
							$config['source_image']	= FCPATH.'public-images/'.$file;
							$config['maintain_ratio'] = TRUE;
							$config['width']	 = 500;
							$config['height']	= 500;

							$this->load->library('image_lib', $config);
							$this->image_lib->resize($config);					
						} else {
							$error = array('error' => $this->upload->display_errors());
							$file = '';
						}
					}
				}
		
				$data = array(
					'id'			=> $id,
					'apply_post'	=> $apply_post,
					'title'			=> $title,
					'desc'			=> $desc,
					'ad_image'		=> $file
				);

				$results = $this->post_handler->edit_post($data);
				switch($results) {
					case 1:
						$this->session->set_flashdata('error', 'All Fields Are Required, Try Again');
						break;
					case 2:
						$this->session->set_flashdata('error', 'No Active Ad Found For The Ad You Were Trying To Edit');
						break;
					case 3:
						$this->session->set_flashdata('error', 'Something Went Wrong While Creating/Editing Your Post. Perhaps You Didn\'t Make Any Changes To The Post And That Is Why You See This Error, Please Try Again');
						break;
					case 4:
						$this->session->set_flashdata('success', 'Your Post Has Been Updated And Is Now Showing On Service Requests');
						break;
					case 5:
						$this->session->set_flashdata('error', 'No Active Post Found, Try Again');
						break;
					case 6:
						$this->session->set_flashdata('error', 'Post Not Found, Please Click Edit On One Of The Post Below');
						break;
				}
				redirect('advertisers/my-zips');
				exit;
			}
			
			$data['ad_details'] = $this->post_handler->get_ad_info($id);
			$data['page'] = $this->public_page_handler->get_public_image();
			$this->load->view('advertisers/edit-post', $data);
		}
		
		function my_account()
		{
			$this->check_if_loggedin();	
			$this->output->set_template('advertisers-logged-in');
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->model('advertisers/account_handler');
			$this->load->model('advertisers/authnet_info');
			
			//Payment Info
			$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[3]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('exp_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
			$this->form_validation->set_rules('exp_year', 'Expiration Year', 'required|min_length[4]|max_length[4]|numeric|xss_clean');
			$this->form_validation->set_rules('ccv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean');
			$this->form_validation->set_rules('name_on_card', 'Name On Credit Card', 'required|min_length[5]|max_length[70]|xss_clean');
			
			if ($this->form_validation->run() == FALSE) {
				
			} else {
				
				extract($_POST);
				$credit_card = preg_replace("/[^0-9]/","",$credit_card);
			
				$names = explode(' ', $name_on_card);
				$data = array(
					'credit_card' => $credit_card,
					'exp_month'   => $exp_month,
					'exp_year'    => $exp_year,
					'ccv'         => $ccv,
					'f_name'	  => $names[0],
					'l_name'	  => end($names)
				);
				$this->load->model('advertisers/process_payment');
				$results = $this->process_payment->update_credit_card($data);
				if(isset($results['ref_id'])) {
					$this->session->set_flashdata('success', 'Payment Successfully Updated, Your Next Bill Will Be Charged To The New Card You Added');
				} else {
					$this->session->set_flashdata('error', 'Payment Did Not Update Successfully, '.$results['error']);
				}
				redirect('advertisers/my-account');
				exit;
			}
						
			$data['profile'] = $this->account_handler->profile_info();
			$data['updates'] = $this->account_handler->updates();
			$data['payment'] = $this->account_handler->get_payment_details();
			$data['subscription'] = $this->account_handler->subscription_details();
			$sub_id = $this->account_handler->get_subscription_id();
			if($sub_id>0) {
				$data['status'] = $this->authnet_info->getAuthStatus($sub_id);
			}
	
			if($data['profile'] == false) {
				redirect('advertisers/logout');
				exit;
			}
			$payment_date = $data['payment']->payment_date;

			$this->load->view('advertisers/my-account', $data);
		}
		
		function service_requests()
		{
			$this->check_if_loggedin();	
			$this->output->set_template('advertisers-logged-in');
			
			$this->form_validation->set_rules('complete', 'Complete', 'trim|max_length[1]|xss_clean|required');
			if($this->form_validation->run() == true) {
				extract($_POST);
				$this->session->set_userdata('ser_comp', $complete);
				redirect('advertisers/service-requests');
				exit;
			}
			$this->load->model('advertisers/service_request_handler');
			$data['requests'] = $this->service_request_handler->get_active_service_requests();
			
			$this->load->view('advertisers/service-requests', $data);
		}
		
		function stats()
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			$this->output->set_template('advertisers-logged-in');
			$this->load->css('assets/themes/default/css/TableBarChart.css');
			$this->load->js('assets/themes/default/js/TableBarChart.js');
		
			$this->load->model('advertisers/ad_handler');
			$data['page_setting'] = $this->ad_handler->check_public_page();
			$data['my_zips'] = $this->ad_handler->get_my_zips();
			$this->load->view('advertisers/my-stats', $data);
		}
		
		function cancel_services()
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			$this->load->model('advertisers/process_payment');
			$result = $this->process_payment->cancel_payment();
			if(is_bool($result)) {
				$this->session->set_flashdata('success', 'Payment Successfully Cancelled. You Will No Longer Be Billed');	
			} else {
				$this->session->set_flashdata('error', $result);
			}
			redirect('advertisers/my-account');
			exit;
		}
		
		function add_zip_codes()
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			$this->output->set_template('advertisers-logged-in');
			$this->load->js('assets/themes/default/js/bootbox.js'); 
			$this->load->js('assets/themes/default/js/add-steps-bootstrap.js'); 
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			
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
			$this->form_validation->set_rules('fax', 'Fax', 'min_length[14]|max_length[18]|xss_clean');
			$this->form_validation->set_rules('bName', 'Business Name', 'min_length[5]|max_length[70]|xss_clean|required');
			//Payment Info
			$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[3]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('exp_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
			$this->form_validation->set_rules('exp_year', 'Expiration Year', 'required|min_length[4]|max_length[4]|numeric|xss_clean');
			$this->form_validation->set_rules('ccv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean');
			$this->form_validation->set_rules('name_on_card', 'Name On Credit Card', 'required|min_length[5]|max_length[70]|xss_clean');
			$this->form_validation->set_rules('frequency', 'Payment Frequency', 'min_length[1]|max_length[2]|numeric|xss_clean');
			
			if ($this->form_validation->run() == FALSE) {
				
			} else {
				extract($_POST);
				$phone = preg_replace("/[^0-9]/", '', $phone);
				$fax = preg_replace("/[^0-9]/", '', $fax);
				$input_data['payment_data'] = array(
					'credit_card' => $credit_card,
					'exp_month' => $exp_month,
					'exp_year' => $exp_year,
					'ccv' => $ccv,
					'name_on_card' => $name_on_card,
					'frequency' => $frequency,
				);
				$input_data['personnal_info'] = array(
					'f_name' => $first_name,
					'l_name' => $last_name,
					'address' => $address,
					'city' => $city,
					'state' => $state,
					'zip' => $zip,
					'baddress' => $baddress,
					'bcity' => $bcity,
					'bstate' => $bstate,
					'bzip' => $bzip,
					'fax' => $fax,
					'phone' => $phone,
					'bName' => $bName
				);				
				
				$this->load->model('advertisers/process_payment');
				
				$results = $this->process_payment->add_zips_after_account($input_data);
				if($results) {
					$email = $this->process_payment->get_subscriber_email();
					$subject = 'Payment Posted Successfully To N4R';
					$zips = $this->session->userdata('zips');
					$service = $this->session->userdata('service');	
					$price = $this->session->userdata('price');	
					$city = $this->session->userdata('city');	
					$state = $this->session->userdata('state');
					if($frequency == 1) {
						$billing = 'Monthly';
					} else if($frequency == 3) {
						$billing = 'Quarterly';
					} else {
						$billing = 'Yearly';
					}
					switch($frequency) {
							case "1":         $months = 1; break;
							case "3":       $months = 3; break;
							case "6":   $months = 6; break;
							case "12":        $months = 12; break;
							default:                $months = 1; break;
						}
						$yearly_contract = 12;
						$today = date('Y-m-d');
						$next_due_date = strtotime($today.' + '.$months.' Months');
						$message = '<h3>'.$bName.'</h3>
							<p>Thank you for becoming a sponsored Network4Rentals contractor. This is to confirm that your credit card payment for your account has been authorized and processed. The details of the transaction are below.</p>
							<h4>Business Info</h4>
							<table cellpadding="4" width="60%" align="left">
								<tr>
									<td><b>Business Name:</b></td>
									<td>'.$bName.'</td>
								</tr>
								<tr>
									<td><b>Contact Name:</b></td>
									<td>'.$first_name.' '.$last_name.'</td>
								</tr>
								<tr>
									<td valign="top"><b>Address:</b></td>
									<td>'.$address.' '.$data['city'].', '.$data['state'].'. '.$zip.'</td>
								</tr>
								<tr>
									<td valign="top"><b>Billing Address:</b></td>
									<td>'.$baddress.' '.$bcity.', '.$bstate.'. '.$bzip.'</td>
								</tr>
								<tr>
									<td><b>Phone:</b></td>
									<td>('.substr($phone, 0, 3).') '.substr($phone, 3, 3).'-'.substr($phone,6).'</td>
								</tr>
								<tr>
									<td><b>Email:</b></td>
									<td>'.$email.'</td>
								</tr>
								<tr>
									<td><b>Date:</b></td>
									<td>'.date('m-d-Y').'</td>
								</tr>
							</table>	
							<table cellpadding="4" width="39%" align="right">							
								<tr>
									<td align="right"><b>Sponsored Post:</b></td>
									<td>'.count($zips).'</td>
								</tr>
								<tr>
									<td align="right"><b>Billing Cycle:</b></td>
									<td>'.$billing.'</td>
								</tr>
								<tr>
									<td align="right"><b>Cost Per Billing Cycle::</b></td>
									<td>$'.array_sum($price)*$frequency.'</td>
								</tr>
								<tr>
									<td align="right"><b>Next Billing Date:</b></td>
									<td>'.date('m-d-Y', $next_due_date).'</td>
								</tr>	
								<tr>
									<td align="right"><b>Subscription Term:</b></td>
									<td>1 Year</td>
								</tr>
								<tr>
									<td align="right"><b>Total:</b></td>
									<td>$'.array_sum($price)*$yearly_contract.'</td>
								</tr>
							</table>		
							<table width="100%">
							<tr>
								<td><h3>Build Your Post</h3>
							<p>You may have already followed the websites request and created your public web page and created your post so that start displaying on landlords service request. If not please do so as soon as possible so that you are taking full advantage of your sponsored space. To edit your public web page go to <a href="https://network4rentals.com/network/advertisers/public-page-settings">https://network4rentals.com/network/advertisers/public-page-settings</a> once you add all the details there go to <a href="https://network4rentals.com/network/advertisers/my-zips">https://network4rentals.com/network/advertisers/my-zips</a> to edit your posts so that they are showing your information to the landlords.</p>
								</td>
							</tr>
							</table>
							<table cellpadding="4" width="100%">
								<tr>
									<th align="left">Zips</th>
									<th align="left">Service Type</th>
									<th align="left">City</th>
									<th align="left">State</th>
									<th align="left">Price</th>
								</tr>';
								
								$services_array = array('', 'Landlords', 'Tenants', 'Contractors', 'Listings');
								for($i=0;$i<count($zips);$i++) {
									if ($i%2===0){ 
										$message .= '<tr bgcolor="#DFDFDF">';
									} else {
										$message .= '<tr>';
									}
									$message .= '
										<td>'.$zips[$i].'</td>
										<td>'.$services_array[$service[$i]].'</td>
										<td>'.$city[$i].'</td>
										<td>'.$state[$i].'</td>
										<td>$'.$price[$i].' <small>per month</small></td>
									</tr>';
								}
								$message .= '</table>';
								$mssage .= '<br><br><small>"Network4Rentals.com operates on a 1year subscription basis. All accounts not cancelled in writing at least 30 days prior to the subscription renewal date will have their subscription automatically renewed and be billed as per their previously selected billing terms."</small>';
						
						$this->sendEmail($email, $message, $subject);
						redirect('advertisers/my-zips');
						exit;
				} else {
					redirect('advertisers/add-zip-codes');
					exit;
				}			
			}
			
			
			$this->session->unset_userdata('zips');
			$this->session->unset_userdata('service');
			$this->session->unset_userdata('price');
			$this->session->unset_userdata('city');
			$this->session->unset_userdata('state');

			$this->load->model('advertisers/account_handler');

			$data['user_info'] = $this->account_handler->profile_info();
			$this->load->view('advertisers/add-zips', $data);
		}
		
		function update_password()
		{
			$this->check_if_loggedin(); // Checks if user is logged in
			$this->form_validation->set_rules('password', 'Passwords', 'min_length[6]|max_length[20]|required|xss_clean|matches[password_2]');
			$this->form_validation->set_rules('password_2', 'Passwords', 'min_length[6]|max_length[20]|required|xss_clean|matches[password]');
			
			if ($this->form_validation->run() == FALSE) {
				
			} else {
				extract($_POST);
				$this->load->model('advertisers/account_handler');
				if($this->account_handler->update_password(md5($password))) {
					$this->session->set_flashdata('success', 'Your Password Has Been Changed');
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Try Again');
				}
			}
			redirect('advertisers/my-account');
			exit;
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
			$this->form_validation->set_rules('fax', 'Fax', 'min_length[14]|max_length[18]|xss_clean');
			$this->form_validation->set_rules('bName', 'Business', 'min_length[5]|max_length[70]|xss_clean|required');
			$this->form_validation->set_rules('email', 'Email', 'min_length[5]|max_length[70]|xss_clean|required|valid_email');
			
			if ($this->form_validation->run() == FALSE) {
				
			} else {
				extract($_POST);
				$phone = preg_replace("/[^0-9]/", '', $phone);
				$fax = preg_replace("/[^0-9]/", '', $fax);
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
					'bName'	   => $bName
				);
		
				$this->load->model('advertisers/account_handler');
				$results = $this->account_handler->update_personal_info($data);
			
				if($results) {
					$this->session->set_flashdata('success', 'Your Account Has Been Updated');
				} else {
					$this->session->set_flashdata('error', 'Something Went Wrong, Or You Didn\'t Change Any Of Your Info, Try Again');
				}
			}
			redirect('advertisers/my-account');
			exit;
			
		}
		
		public function create_account_not_present() 
		{
			$this->load->js('assets/themes/default/js/steps-advertisers.js'); 
			$this->load->js('assets/themes/default/js/masked-input.js'); 
			$this->load->js('assets/themes/default/js/bootbox.js'); 
			$this->load->js('assets/themes/default/js/jquery.creditCardValidator.js'); 
			$this->load->view('advertisers/create-account-not-present');
			
			//Personal Details
			$this->form_validation->set_rules('bName', 'Business Name', 'required|min_length[3]|max_length[70]|xss_clean');
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
			$this->form_validation->set_rules('fax', 'Fax', 'min_length[14]|max_length[18]|xss_clean');
			//Payment Info
			$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[3]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('exp_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
			$this->form_validation->set_rules('exp_year', 'Expiration Year', 'required|min_length[4]|max_length[4]|numeric|xss_clean');
			$this->form_validation->set_rules('ccv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean');
			$this->form_validation->set_rules('name_on_card', 'Name On Credit Card', 'required|min_length[5]|max_length[70]|xss_clean');
			$this->form_validation->set_rules('frequency', 'Payment Frequency', 'required|min_length[1]|max_length[2]|numeric|xss_clean');
			//User Account 
			$this->form_validation->set_rules('email', 'Email', 'required|min_length[3]|max_length[60]|valid_email|xss_clean');
			$this->form_validation->set_rules('user', 'User Name', 'required|min_length[3]|max_length[20]|is_unique[advertisers.user]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|max_length[20]|matches[password2]|xss_clean');
			$this->form_validation->set_rules('password2', 'Password', 'required|min_length[3]|max_length[20]|matches[password]|xss_clean');
			$this->form_validation->set_rules('terms', 'Terms Of Service', 'required|min_length[1]|max_length[1]|xss_clean');		
			
			
			if ($this->form_validation->run() == FALSE)
			{	
				
			} else {	
				extract($_POST);
				$terms = 'n';
				$this->load->helper('string');
				//strip phone and fax leaving only numbers
				$phone = preg_replace("/[^0-9]/", '', $phone);
				$fax = preg_replace("/[^0-9]/", '', $fax);
				$emailState = $state;
				$emailCity = $city;
				$data = array(
					'bName'		=> $bName,
					'first_name'=> $first_name,
					'last_name'	=> $last_name,
					'address'	=> $address,
					'baddress'	=> $baddress,
					'bstate'	=> $bstate,
					'bcity'		=> $bcity,
					'state'		=> $state,
					'bzip'		=> $bzip,
					'zip'		=> $zip,
					'city'		=> $city,
					'phone'		=> $phone,
					'fax'		=> $fax,
					'frequency'	=> $frequency,
					'email'		=> $email,
					'user'		=> $user,
					'password'	=> $password,
					'terms'		=> $terms,
					'credit_card'	=> $credit_card,
					'exp_month'		=> $exp_month,
					'exp_year'		=> $exp_year,
					'ccv'			=> $ccv,
					'name_on_card'	=> $name_on_card,
					'hash'			=> random_string('sha1', 25)
				);
				
				$this->session->set_userdata('hash', $data['hash']);
				$this->session->set_userdata('pwd', $data['password']);
				
				$this->load->model('advertisers/process_payment');
					
				$results = $this->process_payment->submit_payment($data);
					
				if($results == '89') { //payment successful
					$this->session->set_flashdata('success', 'The advertisers payment has successfully processed, they should receive an email with their payment details along with the link to view the terms of service. Inform them to click on the link they received in the email, under "One More Step" heading. That email will also contain their username and password to login to their account. Once they agree to the terms of service their account will be activated then they can login to create their web site and post');
					$subject = 'Account Authorization Needed';
								
					$zips = $this->session->userdata('zips');
					$service = $this->session->userdata('service');	
					$price = $this->session->userdata('price');	
					$city = $this->session->userdata('city');	
					$state = $this->session->userdata('state');
					if($frequency == 1) {
						$billing = 'Monthly';
					} else if($frequency == 3) {
						$billing = 'Quarterly';
					} else {
						$billing = 'Yearly';
					}
					switch($frequency)
					{
						case "1":         $months = 1; break;
						case "3":       $months = 3; break;
						case "6":   $months = 6; break;
						case "12":        $months = 12; break;
						default:                $months = 1; break;
					}
					$today = date('Y-m-d');
					$yearly_contract = 12;
					$next_due_date = strtotime($today.' + '.$months.' Months');
					$message = '<h3>'.$bName.'</h3>
						<p>Thank you for becoming a sponsored Network4Rentals contractor. This is to confirm that your credit card payment for your account has been authorized. The details of the transaction are below.</p>
						<h4>One More Step To Finalize Your Order:</h4>
						<p>In order to finalize your order you must click the link below to view the terms of service. Once you read the terms of service there will be a button at the bottom of the page that says "I agree to the terms of service", click that button and your account will be all set.</p>
						<table bgcolor="#298AC2" align="center">
							<tr>
								<td>
									<a color="#ffffff" href="'.base_url().'advertisers/terms-of-service-linked/'.$data['hash'].'"><FONT color=#ffffff><h3><h3>>>Click Here To View Terms Of Service<<</h3></h3></FONT></a>
								</td>
							</tr>
						</table>
						<h4>Business Info</h4>
						<table cellpadding="4" width="60%" align="left">
							<tr>
								<td><b>Business Name:</b></td>
								<td>'.$bName.'</td>
							</tr>
							<tr>
								<td><b>Contact Name:</b></td>
								<td>'.$first_name.' '.$last_name.'</td>
							</tr>
							<tr>
								<td valign="top"><b>Address:</b></td>
								<td>'.$address.' '.$emailCity.', '.$emailState.'. '.$zip.'</td>
							</tr>
							<tr>
								<td valign="top"><b>Billing Address:</b></td>
								<td>'.$baddress.' '.$bcity.', '.$bstate.'. '.$bzip.'</td>
							</tr>
							<tr>
								<td><b>Phone:</b></td>
								<td>('.substr($phone, 0, 3).') '.substr($phone, 3, 3).'-'.substr($phone,6).'</td>
							</tr>
							<tr>
								<td><b>Email:</b></td>
								<td>'.$email.'</td>
							</tr>
							<tr>
								<td><b>Date:</b></td>
								<td>'.date('m-d-Y').'</td>
							</tr>
						</table>				
						<table cellpadding="4" width="38%" align="right">
							<tr>
								<td align="right"><b>Sponsored Post:</b></td>
								<td>'.count($zips).'</td>
							</tr>
							<tr>
								<td align="right"><b>Billing Cycle:</b></td>
								<td>'.$billing.'</td>
							</tr>
							<tr>
								<td align="right"><b>Cost Per Billing Cycle:</b></td>
								<td>$'.array_sum($price)*$frequency.'</td>
							</tr>
							<tr>
								<td align="right"><b>Next Billing Date:</b></td>
								<td>'.date('m-d-Y', $next_due_date).'</td>
							</tr>
							<tr>
								<td align="right"><b>Subscription Term:</b></td>
								<td>1 Year</td>
							</tr>
							<tr>
								<td align="right"><b>Subscription Total Cost:</b></td>
								<td>$'.array_sum($price)*$yearly_contract.'</td>
							</tr>
						</table>
			
						<table cellpadding="4" width="100%">
							<tr>
								<td><h3>Login Details:</h3></td>
								<td></td>
							</tr>
							<tr bgcolor="#DFDFDF">
								<td><b>User:</b> '.$user.'</td>
								<td><b>Password: </b>'.$password2.'</td>
							<tr>
						</table>
						<table width="100%">
						<tr>
							<td><h3>Build Your Post</h3>
						<p>You may have already followed the websites request and created your public web page and created your post so that start displaying on landlords service request. If not please do so as soon as possible so that you are taking full advantage of your sponsored space. To edit your public web page go to <a href="https://network4rentals.com/network/advertisers/public-page-settings">https://network4rentals.com/network/advertisers/public-page-settings</a> once you add all the details there go to <a href="https://network4rentals.com/network/advertisers/my-zips">https://network4rentals.com/network/advertisers/my-zips</a> to edit your posts so that they are showing your information to the landlords.</p>
							</td>
						</tr>
						</table>
						
						<table cellpadding="4" width="100%">
							<tr>
								<th align="left">Zips</th>
								<th align="left">Service Type</th>
								<th align="left">City</th>
								<th align="left">State</th>
								<th align="left">Price</th>
							</tr>';
							
							$services_array = array('', 'Landlords', 'Tenants', 'Contractors', 'Listings');
							for($i=0;$i<count($zips);$i++) {
								if ($i%2===0){ 
									$message .= '<tr bgcolor="#DFDFDF">';
								} else {
									$message .= '<tr>';
								}
								$message .= '
									<td>'.$zips[$i].'</td>
									<td>'.$services_array[$service[$i]].'</td>
									<td>'.$city[$i].'</td>
									<td>'.$state[$i].'</td>
									<td>$'.$price[$i].' <small>per month</small></td>
								</tr>';
							}
							$message .= '</table>';
							$message .= '<br><br><small>"Network4Rentals.com operates on a 1year subscription basis. All accounts not cancelled in writing at least 30 days prior to the subscription renewal date will have their subscription automatically renewed and be billed as per their previously selected billing terms."</small>';
					
					$this->sendEmail($email, $message, $subject);
					$this->session->unset_userdata('zips');
					$this->session->unset_userdata('service');
					$this->session->unset_userdata('price');
					$this->session->unset_userdata('city');
					$this->session->unset_userdata('state');

					$this->session->unset_userdata('user_id');
					$this->session->unset_userdata('side_logged_in');
					$this->session->unset_userdata('logged_in');
					redirect('advertisers/final-call-step');
					exit;
				} else {
					switch ($results) {
						case '1':
							$this->session->set_flashdata('error', 'Payment Failed, Try A Different Credit Card.');
							break;
						case '2':
							$this->session->set_flashdata('error', 'Failed To Create User');
							break;
						case '3':
							$this->session->set_flashdata('error', 'Failed To Add Zips');
							break;
						case '4':
							$this->session->set_flashdata('error', 'Failed To Log Payment');
							break;
						case '5':
							$this->session->set_flashdata('error', 'Something Went Wrong While Adding Your Zip Codes And Services, Try Again');
							break;
					} 
					redirect('advertisers/create-account-not-present');
					exit;
				}
				
				
			}
		}
		
		public function final_call_step()
		{	
			$hash = $this->session->userdata('hash');
			if(!empty($hash)) {
				$this->output->set_template('advertisers-final-step');
				
				$this->load->model('advertisers/final_step');
				$data['user'] = $this->final_step->get_user_info();
				
				$this->session->set_userdata('userID', $data['user']->id);
				$this->session->set_userdata('sub_id', $data['user']->sub_id);
				$this->load->view('advertisers/final-call-step', $data);
			} else {
				$this->session->set_flashdata('error', 'User not found');
				redirect('advertisers/create-account'); 
				exit;
			}
		}
		
		public function resend_email()
		{
			$this->output->set_template('blank');
			$this->load->model('advertisers/final_step');
			$user = $this->final_step->get_user_info();

			if(!empty($_POST)) {
				if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					$email = $_POST['email'];
					if(isset($_POST['update'])) {
						$update = $_POST['update'];
						if($update=='y') {
							$this->db->where('id', $this->session->userdata('userID'));
							$this->db->update('advertisers', array('email'=>$email));
						}
					}
				} else {
					exit;
				}
			} else {
				$email = $user->email;
			}
		
			$payment = $this->final_step->get_payment_details();

			$option_array = explode('|', $payment->options);
			$option = array();
			$zips = array();
			$services = array();
			foreach($option_array as $val) {
				$option = explode('-', $val);
				$zips[] = $option[0];
				$services[] = $option[1];
			}

			$payment_details = $this->final_step->line_item_payments($zips);
	
			if($payment->payment_frequency == 1) {
				$billing = 'Monthly';
			} else if($payment->payment_frequency == 3) {
				$billing = 'Quarterly';
			} else {
				$billing = 'Yearly';
			}
			switch($payment->payment_frequency)
			{
				case "1":         $months = 1; break;
				case "3":       $months = 3; break;
				case "6":   $months = 6; break;
				case "12":        $months = 12; break;
				default:                $months = 1; break;
			}

			$price = 0;
			$city_array = array();
			$state_array = array();
			$price_array = array();
			foreach($payment_details as $key => $val) {
				$price = $price+$val->advertiser_price;
				$city_array[] = $val->city;
				$state_array[] = $val->stateAbv;
				$price_array[] = $val->advertiser_price;
			}
			$year_num = 12;
			$today = date('Y-m-d');
			$next_due_date = strtotime($today.' + '.$months.' Months');
			$subject = 'Account Authorization Needed';
			$message = '<h3>'.$user->bName.'</h3>
						<p>Thank you for becoming a sponsored Network4Rentals contractor. This is to confirm that your credit card payment for your account has been authorized. The details of the transaction are below.</p>
						<h4>One More Step To Finalize Your Order:</h4>
						<p>In order to finalize your order you must click the link below to view the terms of service. Once you read the terms of service there will be a button at the bottom of the page that says "I agree to the terms of service", click that button and your account will be all set.</p>
						<table bgcolor="#298AC2" width="100%" align="center">
							<tr>
								<td>
									<br>
									<a color="#ffffff" href="'.base_url().'advertisers/terms-of-service-linked/'.$user->email_hash.'"><FONT color=#ffffff><h3><h3>>>Click Here To View Terms Of Service<<</h3></h3></FONT></a>
									<br>
									<br>
								</td>
							</tr>
						</table>
						<h4>Business Info</h4>
						<table cellpadding="4" width="60%" align="left">
							<tr>
								<td><b>Business Name:</b></td>
								<td>'.$user->bName.'</td>
							</tr>
							<tr>
								<td><b>Contact Name:</b></td>
								<td>'.$user->f_name.' '.$user->l_name.'</td>
							</tr>
							<tr>
								<td valign="top"><b>Address:</b></td>
								<td>'.$user->address.' '.$user->city.', '.$user->state.'. '.$user->zip.'</td>
							</tr>
							<tr>
								<td valign="top"><b>Billing Address:</b></td>
								<td>'.$user->baddress.' '.$user->bcity.', '.$user->bstate.'. '.$user->bzip.'</td>
							</tr>
							<tr>
								<td><b>Phone:</b></td>
								<td>('.substr($user->phone, 0, 3).') '.substr($user->phone, 3, 3).'-'.substr($user->phone,6).'</td>
							</tr>
							<tr>
								<td><b>Email:</b></td>
								<td>'.$user->email.'</td>
							</tr>
							<tr>
								<td><b>Date:</b></td>
								<td>'.date('m-d-Y').'</td>
							</tr>
						</table>	
						
						<table cellpadding="4" width="38%" align="right">
							<tr>
								<td align="right"><b>Sponsored Post:</b></td>
								<td>'.count($zips).'</td>
							</tr>
							<tr>
								<td align="right"><b>Billing Cycle:</b></td>
								<td>'.$billing.'</td>
							</tr>
							<tr>
								<td align="right"><b>Cost Per Billing Cycle:</b></td>
								<td>$'.$price*$payment->payment_frequency.'</td>
							</tr>
							<tr>
								<td align="right"><b>Next Billing Date:</b></td>
								<td>'.date('m-d-Y', $next_due_date).'</td>
							</tr>
							<tr>
								<td align="right"><b>Subscription Term:</b></td>
								<td>1 Year</td>
							</tr>
							<tr>
								<td align="right"><b>Subscription Total Cost:</b></td>
								<td>$'.($price *$payment->payment_frequency)*$year_num.'</td>
							</tr>
						</table>			
						<table cellpadding="4" width="100%">
							<tr>
								<td><h3>Login Details:</h3></td>
								<td></td>
							</tr>
							<tr bgcolor="#DFDFDF">
								<td><b>User:</b> '.$user->user.'</td>
								<td><b>Password: </b>'.$this->session->userdata('pwd').'</td>
							<tr>
						</table>
						<table width="100%">
						<tr>
							<td><h3>Build Your Post</h3>
						<p>You may have already followed the websites request and created your public web page and created your post so that start displaying on landlords service request. If not please do so as soon as possible so that you are taking full advantage of your sponsored space. To edit your public web page go to <a href="https://network4rentals.com/network/advertisers/public-page-settings">https://network4rentals.com/network/advertisers/public-page-settings</a> once you add all the details there go to <a href="https://network4rentals.com/network/advertisers/my-zips">https://network4rentals.com/network/advertisers/my-zips</a> to edit your posts so that they are showing your information to the landlords.</p>
							</td>
						</tr>
						</table>
						
						<table cellpadding="4" width="100%">
							<tr>
								<th align="left">Zips</th>
								<th align="left">Service Type</th>
								<th align="left">City</th>
								<th align="left">State</th>
								<th align="left">Price</th>
							</tr>';
							
							$services_array = array('', 'Landlords', 'Tenants', 'Contractors', 'Listings');
							for($i=0;$i<count($zips);$i++) {
								if ($i%2===0){ 
									$message .= '<tr bgcolor="#DFDFDF">';
								} else {
									$message .= '<tr>';
								}
								$message .= '
									<td>'.$zips[$i].'</td>
									<td>'.$services_array[$services[$i]].'</td>
									<td>'.$city_array[$i].'</td>
									<td>'.$state_array[$i].'</td>
									<td>$'.$price_array[$i].' <small>per month</small></td>
								</tr>';
							}
							$message .= '</table>';
							$message .= '<br><br><small>"Network4Rentals.com operates on a 1year subscription basis. All accounts not cancelled in writing at least 30 days prior to the subscription renewal date will have their subscription automatically renewed and be billed as per their previously selected billing terms."</small>';
				
					if($this->sendEmail($email, $message, $subject)) {
						echo json_encode('1');
					} else {
						echo json_encode('0');
					}
		}
		
		public function terms_of_service_linked()
		{
			$hash = $this->uri->segment(3);
			$this->load->view('advertisers/terms-of-service');
		}
		
		public function terms_of_service()
		{
			$hash = $this->uri->segment(3);
			$this->load->view('advertisers/terms-of-service');
		}		
		
		public function agreed() 
		{
			$hash = $this->uri->segment(3);
			$this->db->select('id');
			$results = $this->db->get_where('advertisers', array('email_hash'=>$hash));
			if($results->num_rows()>0) {
				$row = $results->row();
				$this->db->where('id', $row->id);
				$this->db->update('advertisers', array('active'=>'y', 'email_hash'=>''));
				$this->session->set_flashdata('success', 'Your account has been activated and you can now login');
				redirect('advertisers/login');
			} else {
				$this->session->set_flashdata('error', 'Sorry, User Not Found');
				redirect('advertisers/login');
			}
		}

		public function forgot_password() //changed
		{
			 $this->load->helper('captcha');
			/* Set a few basic form validation rules */
			$this->form_validation->set_rules('email', 'Email', 'required|trim|min_length[3]|max_length[100]|xss_clean');
			$this->form_validation->set_rules('captcha', "Captcha", 'required|callback_check_captcha');
					
			/* Get the actual captcha value that we stored in the session (see below) */
			$word = $this->session->userdata('captchaWord');
			
			extract($_POST);
			
			$this->session->flashdata('userCaptcha', $captcha);
			
			/* Check if form (and captcha) passed validation*/
			if ($this->form_validation->run() == TRUE) {
				if(strcmp(strtoupper($captcha),strtoupper($word)) === 0) {
					$this->session->unset_userdata('captchaWord');
					$this->load->model('advertisers/reset_password');
					$hash = $this->reset_password->check_user_email($email);
					if($hash != false) 
					{
						// DB Updated with hash now need to email user that hash
						$message = '
							<h3>Reset Your Password</h3>
							<p>You have requested to reset your password. If you did not request to have your password reset you can ignore this email. Else click the link below to go through the steps to reset your password.</p><a href="'.base_url().'advertisers/reset_password/'.$hash.'">Reset Password</a>
						';
						$subject = 'N4R | Password Reset Instructions';
						$this->sendEmail($email, $message, $subject);
						$data = array('email' => $email);
						$this->load->view('advertisers/password-reset', $data);
					} 
					else
					{
						redirect('advertisers/password-reset');
					}		  
				} else {
					$this->session->set_flashdata('error', 'Invalid captcha, try again');
					redirect('advertisers/forgot-password');
				}
			} else {
			  /** Validation was not successful - Generate a captcha **/
			  $this->session->flashdata('error', 'Invalid captcha, try again');
			
			  /* Setup vals to pass into the create_captcha function */
			  $vals = array(
					'img_path' 		=> 	'./captcha/',
					'img_url' 		=> 	base_url().'captcha/',
					'img_width'	 	=> 	'180',
					'img_height' 	=> 	'55',
					'experation' 	=> 	'3600',
				);
				
			  /* Generate the captcha */
			  $captcha = create_captcha($vals);
			 
			  /* Store the captcha value (or 'word') in a session to retrieve later */
			  $this->session->set_userdata('captchaWord', $captcha['word']);
			  
			  /* Load the captcha view containing the form (located under the 'views' folder) */
			  $this->load->view('advertisers/forgot-password', $captcha);
			}
		}	
		
		function reset_password() //Handles the view after the link in the email has been clicked
		{
			if($this->session->userdata('token') == '') {
				$token = $this->uri->segment(3);
				$token = $this->security->xss_clean($token);
				$this->load->model('advertisers/reset_password');
				if($this->reset_password->check_token($token) == true) {
					$this->session->set_userdata('token', $token);
					$this->load->view('advertisers/change-password');
				} else {
					$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
					redirect('advertisers/forgot_password');
					exit;
				}
			}
			else 
			{
				$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[50]|xss_clean');
				$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|min_length[6]|max_length[200]|matches[password]|xss_clean');
				$this->form_validation->set_rules('token', 'Link', 'required|trim|max_length[100]|xss_clean');
			
				if($this->form_validation->run() == FALSE) 
				{
					$this->load->view('advertisers/change-password');
				}
				else 
				{
					extract($_POST);
					$this->load->model('advertisers/reset_password');
					if($this->reset_password->check_token($token) == true) {
						if($this->reset_password->change_password($token, $password) == true) {
							$this->session->set_userdata('token', '');
							$this->session->set_flashdata('success', 'Your Password Has Now Been Changed, You Can Login Now With Your New Password');
							redirect('advertisers/login');
							exit;
						} else {
							$this->session->set_userdata('token', '');
							$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
							redirect('advertisers/forgot-password');
							exit;
						}
					} else {
						$this->session->set_userdata('token', '');
						$this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
						redirect('landlords/forgot_password');
						exit;
					}
					$this->session->sess_destroy();
				}
			}
		}
		
		public function resources()
		{
			$this->load->view('advertisers/resources');
		}
		
		public function cancel_nonverified_users()
		{
			$this->output->set_template('blank');
			$this->load->model('advertisers/process_payment');
			$data = $this->process_payment->get_unactive_users_cron();
			
			if(!empty($data)) {
				$subject = 'Cron Job Ran On N4R';
				foreach($data as $val) {
					$ids_canceled .= '<b>Subscription Id:</b>: '.$val.'<br>';
				}
				$message = '<h3>Cron Job Ran</h3><p>The cron job we setup has ran and cancelled the following ids below.</p>'.$ids_canceled;
			} else {
				$subject = 'Cron Job Ran On N4R';
				$message = '<h3>Cron Job Ran</h3><p>The cron job we setup has ran but didn\'t find any inactive subscribers.';
			}
			$email = 'brian@emf-websolutions.com';
			$this->sendEmail($email, $message, $subject, $alt_message = null);
		}
		
		public function post_clicked_on()
		{
			$advertiser_id = (int)$this->uri->segment(3);
			$ad_id = (int)$this->uri->segment(4);
			
			if(!empty($advertiser_id) || !empty($ad_id)) {
				
				$this->load->model('advertisers/ad_handler');
				$link = $this->ad_handler->public_link($advertiser_id, $ad_id);
				if($link != false) {
					redirect($link);
					exit;
				} else {
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
			} else {
				redirect($_SERVER['HTTP_REFERER']);
				exit;
			}
		}
		
	} //end class
	