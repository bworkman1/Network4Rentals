<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class User_account_handler extends CI_Model {
		
		var $salt = 'Z|ukCY8Ue3csIGy1w+sO2WTlcH%HKIoRx EFRBxD7hfONEyt=PGbJ_=aKNl';
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		public function add_user()
		{
			
			$this->form_validation->set_rules('name', 'Name', 'required|xss_clean|min_length[4]|max_length[30]');
			$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|min_length[4]|max_length[40]|valid_email|is_unique[admins.email]');	
			$this->form_validation->set_rules('password', 'Password', 'required|xss_clean|min_length[7]|max_length[20]');
			$this->form_validation->set_rules('super_admin', 'Super Admin', 'required|xss_clean|min_length[1]|max_length[1]|alpha');
			$this->form_validation->set_rules('email_user', 'Email User', 'xss_clean|min_length[1]|max_length[1]|alpha');
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error',validation_errors());
			} else {
				extract($_POST);
				$this->db->insert('admins', array('name'=>$name, 'email'=>$email, 'password'=>md5($password.$this->salt), 'super_admin'=>$super_admin, 'created'=>date('Y-m-d')));
				if($this->db->insert_id()>0) {
					$ifEmailed = '';
					if($email_user=='y') {
						$this->load->model('special/send_email');
						$subject = 'Network 4 Rentals | Admin Account Created';
						$message = '<h4>Hello '.$name.'</h4>';
						$message .= '<p>Your account at Network4Rentals | Admins has been created and you can login with the details below.</p>
						<p><b>Link:</b> <a href="https://network4rentals.com/network/n4radmin/login">https://network4rentals.com/network/n4radmin/login</a></p>
						<p><b>Username: </b>'.$email.'</p>
						<p><b>Password: </b>'.$password.'</p>
						<p>Please bookmark the link to login because this is not a public address and no links can be found on our website that will take you there. If you have any problems logging into the admin section please contact us immediately. Once you login please change your password by clicking on your name in the upper right hand corner and click on settings.</p>';
						
						if($this->send_email->sendEmail($email, $message, $subject)) {
							$ifEmailed = 'and their details have been emailed to them @ '.$email;
						} else {
							$ifEmailed = 'but the email did not send to the user';
						}
					}
					$this->session->set_flashdata('success', 'User created successfully '.$ifEmailed);
				} else {
					$this->session->set_flashdata('error', 'Something went wrong when inserting the data, try again ');
				}
			}
		}
		
		public function get_user_details()
		{
			$this->db->limit(1);
			$this->db->select('name, email, super_admin');
			$results = $this->db->get_where('admins', array('id'=>$this->session->userdata('user_id')));
			return $results->row();
		}
		
		public function update_user_details()
		{
			$this->form_validation->set_rules('name', 'Name', 'required|xss_clean|min_length[4]|max_length[30]');

			if($this->session->userdata('n4rlanreq') != $_POST['email']) {
				$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|min_length[4]|max_length[40]|valid_email|is_unique[admins.email]|trim');
			} else {
				$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|min_length[4]|max_length[40]|valid_email|trim');
			}
			
			if(!empty($_POST['password'])) {
				$this->form_validation->set_rules('password', 'Password', 'required|xss_clean|min_length[7]|max_length[20]|matches[password-confirm]');
			}
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error',validation_errors('<span> ', '</span>'));
			} else {
				extract($_POST);
				$data = array(
					'name' => $name,
					'email' => $email,
				);
				if(!empty($password)) {
					$data['password'] = md5($password.$this->salt);
				}
				$this->db->limit(1);
				$this->db->where('id', $this->session->userdata('user_id'));
				$this->db->update('admins', $data);
				if($this->db->affected_rows()>0) {
					$this->session->set_userdata('n4rlanreq', $data['email']);
					$this->session->set_flashdata('success', 'Your details have been updated');
				} else {
					$this->session->set_flashdata('error', 'Your details failed to update, maybe it\'s because you didn\'t change any values?');
				}
			}
		}
		
		public function delete_user()
		{
			$this->form_validation->set_rules('user_id', 'User Id', 'required|xss_clean|min_length[1]|max_length[15]|numeric');
			$this->form_validation->set_rules('user_type', 'User Type', 'required|xss_clean|min_length[4]|max_length[30]|alpha');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error',validation_errors('<span> ', '</span>'));
			} else {
				extract($_POST);
				switch($user_type) {
					case 'renter':
						$this->delete_renter_account($user_id);
						break;
					case 'landlord':
						$this->delete_landlord_account($user_id);
						break;
					default:
						$this->session->set_flashdata('error', 'Invalid selection, try again');
						break;
				}
			}
		}
		
		private function delete_renter_account($id)
		{
			$this->db->limit(1);
			$this->db->where('id', $id);
			$this->db->delete('renters');
			if($this->db->affected_rows()>0) {
				$this->session->set_flashdata('success', 'The renter account has been deleted');
			} else {
				$this->session->set_flashdata('error', 'The renter account failed to delete, try again');
			}
		}
		
		private function delete_landlord_account($id)
		{
			$this->db->limit(1);
			$this->db->where('id', $id);
			$this->db->delete('landlords');
			if($this->db->affected_rows()>0) {
				
				$this->db->where('owner', $id);
				$this->db->delete('listings');
				if($this->db->affected_rows()>0) {
					$listings = $this->db->affected_rows();
				}
				$this->db->where('type', 'landlord');
				$this->db->where('landlord_id', $id);
				$this->db->delete('landlord_page_settings');
				
				if($listings>0) {
					$this->session->set_flashdata('success', 'The landlord account has been deleted along with '.$listings.' rental listings');
				} else {
					$this->session->set_flashdata('success', 'The landlord account has been deleted');
				}
			} else {
				$this->session->set_flashdata('error', 'The renter account failed to delete, try again');
			}				
		}
		
	}