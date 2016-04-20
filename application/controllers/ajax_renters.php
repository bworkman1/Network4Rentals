<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class ajax_Renters extends CI_Controller {
		function __construct()
		{
			parent::__construct();
		}

		public function get_payment_notes()
		{	
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('id', 'id', 'required|trim|min_length[1]|max_length[12]|integer|xss_clean');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('renters/payment_handler');
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
		
		function add_new_payment_note()
		{		
			if ($this->input->is_ajax_request()) {		
				$this->form_validation->set_rules('payment_note', 'Payment Note', 'trim|min_length[10]|max_length[700]|xss_clean|required');
				$this->form_validation->set_rules('payment_id', 'payment id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('altid', 'Alternate Id', 'trim|min_length[1]|max_length[20]|xss_clean|integer|required');
				$this->form_validation->set_rules('type', 'User Type', 'trim|min_length[1]|max_length[20]|xss_clean|required');
				
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('renters/payment_handler');
					
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
					
					$data = array('note'=>strip_tags($payment_note), 'payment_id'=>$payment_id, 'attachment'=>$file, 'tenant_id'=>$this->session->userdata('user_id'), 'sent_by'=>$type, 'landlord_id'=>$altid);
					
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
		
		public function create_account_no_landlords()
		{
			if ($this->input->is_ajax_request()) {	
				
				$this->form_validation->set_rules('fullname', 'Full Name', 'trim|max_length[50]|xss_clean|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|max_length[50]|xss_clean|required|valid_email');
				$this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[16]|xss_clean|required');
				$this->form_validation->set_rules('hear', 'How you heard about us', 'trim|max_length[40]|xss_clean|required');
				$this->form_validation->set_rules('username', 'Username', 'trim|max_length[40]|xss_clean|required|is_unique[renters.user]');
				$this->form_validation->set_rules('password', 'Password', 'trim|max_length[35]|xss_clean|required|matches[password1]');
				$this->form_validation->set_rules('password1', 'Confirm Password', 'trim|max_length[35]|xss_clean|required|matches[password]');
				$this->form_validation->set_rules('terms', 'Agree To Terms', 'trim|max_length[3]|xss_clean|required');
				$this->form_validation->set_rules('sms_msgs', 'SMS Messages', 'trim|max_length[1]|xss_clean|required');
				$this->form_validation->set_rules('cell_phone', 'Cell Phone', 'trim|max_length[15]|xss_clean');
				$this->form_validation->set_message('is_unique', '%s is already being used, try again');
				$this->form_validation->set_message('required', '%s is required');
				if($this->form_validation->run() == TRUE) {
					
					extract($_POST);
					
					$email_hash = md5($_SERVER['REMOTE_ADDR'].$username);
					$phone = preg_replace("/[^0-9,.]/", "", $phone);
					$cell_phone = preg_replace("/[^0-9,.]/", "", $cell_phone);
					
					$data = array('user'=>$username, 'pwd'=>md5($password), 'email'=>$email, 'loginHash'=>$email_hash, 'ip'=>$_SERVER['REMOTE_ADDR'], 'name'=>$fullname, 'phone'=>$phone, 'terms'=>$terms, 'browser_info'=>$_SERVER['HTTP_USER_AGENT'], 'cell_phone'=>$cell_phone, 'sms_msgs'=>$sms_msgs, 'hear'=>$hear); 
					
					$this->load->model('renters/create_user_model');
					$results = $this->create_user_model->add_no_landlord_user($data);
					
					echo json_encode($results);
					
				} else {
					echo json_encode(array('error'=>validation_errors()));
				}
				
			}
		}
		
		public function create_new_account()
		{
			if ($this->input->is_ajax_request()) {
				$this->load->model('renters/create_user_model');
				if($this->create_user_model->check_if_registered($_POST['email'])) {
					echo json_encode(array('registered'=>'unconfirmed')); 
					exit;
				}
				$this->form_validation->set_rules('firstname', 'First name', 'trim|max_length[30]|xss_clean|required|trim');
				$this->form_validation->set_rules('lastname', 'Last name', 'trim|max_length[30]|xss_clean|required|trim');
				$this->form_validation->set_rules('email', 'Email', 'trim|max_length[30]|xss_clean|required|valid_email|is_unique[renters.email]|trim');
				
				$this->form_validation->set_rules('hear', 'How did you hear about us', 'trim|max_length[25]|xss_clean|required|trim');
				$this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]|max_length[20]|xss_clean|required|trim');
				$this->form_validation->set_rules('zip', 'Zip Code', 'trim|min_length[5]|max_length[5]|numeric|xss_clean|required|trim');
				if(!empty($_POST['cell'])) {
					$this->form_validation->set_rules('cell', 'Cell Phone', 'trim|min_length[14]|max_length[14]|xss_clean|required|trim');
				}
				
				$this->form_validation->set_message('is_unique', '%s is already taken');
				$this->form_validation->set_message('min_length', '%s is not long enough');
				
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
			
					$data = array(
						'user'  => $email,
						'email' => $email,
						'hear'  => $hear,
						'name'  => ucwords(strtolower($firstname)).' '.ucwords(strtolower($lastname)),
						'pwd'   => md5($password),
						'cell_phone'  => $cell,
						'zip'   => $zip,
						'confirmed'=> 'n',
						'terms' => 'Yes',
						'text_msg_code' => $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6)
					);
					
					$affiliateId = $this->session->userdata('affiliate_id');
					if(!empty($affiliateId)) {
						$data['affiliate_id'] = $affiliateId;
					}
					
					$result = $this->create_user_model->create_simple_user($data);
					if($result) {
						if(!empty($cell)) {
							echo json_encode(array('success'=>'cell'));	
						} else {
							echo json_encode(array('success'=>'email'));
						}
					} else {
						echo json_encode(array('error'=>'Something went wrong, try again'));
					}
				} else {
					echo json_encode(array('error'=>validation_errors('<span>', '</span>')));
				}
			} 
		}
		
		function confirm_new_account() 
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('confirm', 'Conformation Code', 'trim|min_length[2]|max_length[15]|xss_clean|required|alpha_numeric|trim');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('renters/create_user_model');
					$result = $this->create_user_model->confirmation_code_check($confirm);
					if($result) {
						echo json_encode(array('success'=>'success'));	
					} else {
						echo json_encode(array('error'=>'Invalid confirmation code'));
					}
				} else {
					echo json_encode(array('error'=>validation_errors('<span>', '</span>')));
				}
			}
		}
		
		function switch_to_sms()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('cell', 'Cell Phone', 'trim|min_length[14]|max_length[14]|xss_clean|required|trim');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$cell = preg_replace("/[^0-9]/","",$cell);
					$this->session->set_userdata('confirm-cell', $cell);
					$this->load->model('renters/create_user_model');
					$this->create_user_model->send_confirmation_text_simple();
					echo json_encode(array('success'=>'success'));
				} else {
					echo json_encode(array('error'=>validation_errors('<span>', '</span>')));
				}
			}
		}
		
		function resend_confirm_code()
		{
			if ($this->input->is_ajax_request()) {
				$cell = $this->session->userdata('confirm-cell');
				$code = $this->session->userdata('confirm-code');	
				$email = $this->session->userdata('confirm-email');
				if(!empty($code) && !empty($email)) {
					$this->load->model('renters/create_user_model');
					$this->create_user_model->send_confirmation_email_simple();
					if(!empty($cell)) {
						$this->create_user_model->send_confirmation_text_simple();
					}
					echo json_encode(array('success'=>'success'));
				} else {
					echo json_encode(array('error'=>'Something went wrong, contact support'));
				}
			}
		}
		
	}