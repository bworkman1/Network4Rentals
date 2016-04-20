<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class N4radmin extends CI_Controller {
			
		function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->helper('url');
			$this->load->helper('cookie');
			$this->_init();
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
				redirect('n4radmin/login');
				exit;
			}
		}
		
		function login()
		{
			$this->load->css('assets/themes/admin/css/login.css');
			$this->load->js('assets/themes/admin/js/tweenlite.min.js');
			$this->load->js('assets/themes/admin/js/login.js');
			$this->output->set_template('admin/not-logged-in');
			
			$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|valid_email|min_length[7]|max_length[40]');
			$this->form_validation->set_rules('password', 'Password', 'required|xss_clean|min_length[7]|max_length[15]');

			if ($this->form_validation->run() == FALSE) {
				$error = validation_errors();
				if(!empty($error)) {
					$this->session->set_flashdata('error', $error);
					redirect('n4radmin/login');
					exit;
				}
			} else  {
				extract($_POST);
				
				$this->load->model('admin/login');
				$results = $this->login->check_login_details($email, $password);
				
				if($results !== false) {
					if($results['super'] == 'y') {
						$this->session->set_userdata('superadmin', true);
					}
					$this->load->library('encrypt');
					$this->session->set_userdata('adminLoggedIn', true);
					$this->session->set_userdata('userName', $results['name']);
					$this->session->set_userdata('n4rlanreq', $email);
					redirect('n4radmin/index');
					exit;
				}
				$this->session->set_flashdata('error', 'Invalid email or password');
				redirect('n4radmin/login');
				exit;
			}
		
			$this->load->view('admin/login');
		}
		
		private function _init()
		{
			$this->output->set_template('admin/loggedin');

			$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
			$this->load->js('assets/themes/admin/js/bootstrap.min.js');
			$this->load->js('assets/themes/admin/js/common.js');

			$this->load->css('assets/themes/admin/css/bootstrap.min.css');
			$this->load->css('assets/themes/admin/css/sb-admin.css');
			$this->load->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
			$this->load->css('assets/themes/admin/css/plugins/morris.css'); 
		}
		
		public function index()
		{	
			$this->checkLogin();
			$this->load->js('assets/themes/admin/js/plugins/morris/raphael.min.js');
			$this->load->js('assets/themes/admin/js/plugins/morris/morris.min.js');
			$this->load->js('assets/themes/admin/js/plugins/morris/morris-data.js');
			
			$this->load->model('admin/home');
			$data['sums'] = $this->home->user_sums();
			$data['active'] = $this->home->active_users($data['sums']);
		
			$data['payments'] = $this->home->recent_transactions();
		
			$this->load->view('admin/home', $data);
		}

		public function view_group()
		{
			$this->checkLogin();
			$this->load->js('assets/themes/admin/js/users.js');
			$this->load->model('admin/users');
			$offset = (int)$this->uri->segment(4);
			$group = $this->uri->segment(3);
			$data = $this->users->build_group_data($group, $offset);
			$this->load->view('admin/view-group-details', $data);
		}

		public function edit_user()
		{
			$this->checkLogin();
			$ref = $_SERVER['HTTP_REFERRER'];
			if(empty($ref)) {
				$ref = base_url('n4radmin');
			}
			$type = $this->uri->segment(3);
			$id = (int)$this->uri->segment(4);
			$this->load->model('admin/users');
			$data['user'] = $this->users->get_user_details($type, $id);
			if($data['user'] === false) {
				redirect($ref);
				exit;
			}
			
			if($type == 'renters') {
				$data['transactions'] = $this->users->tenant_transactions($id);
				$data['history'] = $this->users->tenant_rental_history($id);
				$data['requests'] = $this->users->service_requests($id, 'tenant_id');
				$this->load->view('admin/edit-renter', $data);
			} else {
				redirect($ref);
				exit;
			}
		
		}
		
		public function add_new_admin() 
		{
			$this->checkLogin();
			if($this->session->userdata('superadmin')) {
				$this->load->model('admin/user_account_handler');
				$this->user_account_handler->add_user();
				redirect('n4radmin');
				exit;
			} else {
				$this->session->set_flashdata('error', 'You don\'t have the correct permissions to add new admin');
				redirect('n4radmin');
				exit;
			}
		}
		
		public function user_settings()
		{
			$this->checkLogin();
			$this->load->model('admin/user_account_handler');
			$data['user'] = $this->user_account_handler->get_user_details();
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$this->user_account_handler->update_user_details();
				redirect('n4radmin/user_settings');
				exit;
			}
			
			$this->load->view('admin/user-settings', $data);
		}
		
		public function view_user_details()
		{
			$this->checkLogin();
			$type = $this->uri->segment(3);
			$user_id = $this->uri->segment(4);
			
			$this->load->model('admin/user_details');
			$data = $this->user_details->get_user_info($user_id, $type);
		
			if($type=='renter') {
				$this->load->view('admin/view-renter-details', $data);
			} elseif($type=='landlord') {
				$this->load->view('admin/view-landlord-details', $data);
			} elseif($type=='contractor') {
			
			} elseif($type=='advertiser') {
				
			} else {
				$this->session->set_flashdata('error', 'Invalid selection, try again');
				redirect('n4radmin');
				exit;
			}
			
		}
		
		public function search_user()
		{
			$this->checkLogin();
		
			$this->load->model('admin/search_users');
			$data['results'] = $this->search_users->search_for_users();
		
			$this->load->view('admin/search-groups', $data);
		}
		
		public function logout()
		{
			$this->session->sess_destroy();
			redirect('n4radmin/login');
			exit;
		}
	
		public function sort_user_group() 
		{
            $this->checkLogin();
			$this->session->set_flashdata('success', 'Users now sorted by '.$this->uri->segment(3).' '.$this->uri->segment(4));
			
			$this->session->set_userdata('user_sort_by', $this->uri->segment(3));
			$this->session->set_userdata('user_sort_dir', $this->uri->segment(4));
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
		
		public function remove_user_sorting()
		{
            $this->checkLogin();
			$this->session->set_flashdata('success', 'Users are now sorted by Id desc only');
			$sorting_array = array('user_sort_by'=>'', 'user_sort_dir'=>'');
			$this->session->unset_userdata($sorting_array);
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
		
		public function delete_user()
		{
			$this->checkLogin();
			if($this->session->userdata('superadmin')) {
				$this->load->model('admin/user_account_handler');
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					$this->user_account_handler->delete_user();
				}
			}

			redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
		
		public function invalid_public_pages() 
		{
			$this->checkLogin();
			
			$this->load->model('admin/public_page_check');
			$data['pages'] = $this->public_page_check->check_details();
			$this->load->view('admin/invalid-public-pages', $data);
		}
		
		public function supply_houses() 
		{
			$this->checkLogin();
			$this->load->model('admin/supply_houses');
			$data = $this->supply_houses->getSupplyHouses();
			
			$this->load->view('admin/our-supply-houses', $data);
		}
	
		public function add_supply_house() 
		{
			$this->checkLogin();
			$this->load->model('admin/common_selections');
			$data['service_types'] = $this->common_selections->service_types();
			$data['affiliates'] = $this->common_selections->getAffiliates();
			
			$this->load->css('assets/themes/default/css/alertify.core.css');
			$this->load->js('assets/themes/default/js/alertify.min.js');
			$this->load->js('assets/themes/default/js/textarea-tags/jquery.tagsinput.js');
			$this->load->js('assets/themes/admin/js/bootstrap-multiselect.js');
			$this->load->css('assets/themes/default/css/textarea-tags/jquery.tagsinput.css');
			$this->load->css('assets/themes/admin/css/bootstrap-multiselect.css');
			
			$this->load->view('admin/add-supply-house', $data);
		}
		
		public function delete_supply_house()
		{
			$this->checkLogin();
			if($this->session->userdata('superadmin')==1) {
				$this->load->model('admin/supply_houses');
				$this->supply_houses->delete_supply_house($this->uri->segment(3));
			} else {
				$this->session->set_flashdata('error', '<div class="alert alert-danger">Error: You don\'t have the privliages to access this feature</div>');
			}
			redirect('n4radmin/supply-houses');
			exit;
		}
		
		public function supply_house_sample()
		{
			$this->checkLogin();
			$id = (int)$this->uri->segment(3);
			if(empty($id)) {
				$this->session->set_flashdata('error', '<div class="alert alert-danger"><b>Error:</b> No supply house found, try again</div>');
				redirect('n4radmin/supply-houses');
				exit;
			}
			
			$this->load->model('admin/supply_houses');
			$data['house'] = $this->supply_houses->getSupplyHouseById($id);
			
			if(empty($data)) {
				$this->session->set_flashdata('error', '<div class="alert alert-danger"><b>Error:</b>: No supply house found, try again</div>');
				redirect('n4radmin/supply-houses');
				exit;
			}
			
			$this->load->view('admin/view-supply-house-sample', $data);
		}		
		
		public function edit_supply_house()
		{
		
			
			$this->checkLogin();
			$id = (int)$this->uri->segment(3);
			if(empty($id)) {
				$this->session->set_flashdata('error', '<div class="alert alert-danger"><b>Error:</b> No supply house found, try again</div>');
				redirect('n4radmin/supply-houses');
				exit;
			}
			$this->load->model('admin/common_selections');
			$data['affiliates'] = $this->common_selections->getAffiliates();
			$data['service_types'] = $this->common_selections->service_types();
			
			$this->load->css('assets/themes/default/css/alertify.core.css');
			$this->load->js('assets/themes/default/js/alertify.min.js');
			$this->load->js('assets/themes/default/js/textarea-tags/jquery.tagsinput.js');
			$this->load->js('assets/themes/admin/js/bootstrap-multiselect.js');
			$this->load->css('assets/themes/default/css/textarea-tags/jquery.tagsinput.css');
			$this->load->css('assets/themes/admin/css/bootstrap-multiselect.css');
			$this->load->model('admin/supply_houses');
			$data['house'] = $this->supply_houses->getSupplyHouseById($id);
			
			if(empty($data)) {
				$this->session->set_flashdata('error', '<div class="alert alert-danger"><b>Error:</b>: No supply house found, try again</div>');
				redirect('n4radmin/supply-houses');
				exit;
			}
			
			$data['public'] = $this->supply_houses->getSupplyHousePublicPage($id);
	
			$this->load->view('admin/edit-supply-house', $data);
		}
		
		public function affiliates() 
		{
			$this->checkLogin();
			$this->load->model('modules/user_affiliates');
			$this->load->model('modules/user_common');
			
			$offset = (int)$this->uri->segment(3);
			$perPage = 30;
			
			$userData = $this->user_affiliates->getAllAffiliates($offset, $perPage);
			
			$data['links'] = $this->user_common->pagination(3, $this->user_affiliates->countAllAffiliates(), 'https://network4rentals.com/network/n4radmin/affiliates', $perPage);
	
			$headings = array('Id', 'Name', 'Email', 'Signed Up', 'Last Login', 'Contractors', 'Option');
			$classes = array('table', 'table-striped', 'table-bordered');
			$userData = $this->user_affiliates->formatAllAffiliates($userData);

			$data['affiliates'] = $this->user_common->createTableData($headings, $userData, $classes);
		
			$this->load->view('admin/all-affiliates', $data);
		}
		
		public function add_affiliate() 
		{
            $this->checkLogin();
			$this->form_validation->set_rules('first_name', 'First Name', 'xss_clean|min_length[2]|max_length[35]|alpha');
			$this->form_validation->set_rules('last_name', 'Last Name', 'xss_clean|min_length[2]|max_length[35]');
			$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|min_length[5]|max_length[60]|valid_email|is_unique[affiliate_users.email]');
			$this->form_validation->set_rules('phone', 'Phone', 'xss_clean|min_length[9]|max_length[16]');
			$this->form_validation->set_rules('signup_commission', 'Sign Up Commission', 'xss_clean|min_length[1]|max_length[2]|integer');
			$this->form_validation->set_rules('renewal_commission', 'Renewal Commission', 'xss_clean|min_length[1]|max_length[2]|integer');
			$this->form_validation->set_rules('monthly_bonus', 'Monthly Bonus', 'xss_clean|min_length[1]|max_length[2]|integer');
			$this->form_validation->set_rules('yearly_bonus', 'Yearly Bonus', 'xss_clean|min_length[1]|max_length[2]|integer');
			$this->form_validation->set_rules('monthly_quota', 'Monthly Quota', 'xss_clean|min_length[1]|max_length[3]|integer');
			$this->form_validation->set_rules('yearly_quota', 'Yearly Quota', 'xss_clean|min_length[1]|max_length[3]|integer');



			if ($this->form_validation->run() == FALSE) {
				$data['error'] = validation_errors();
			} else {
				extract($_POST);

				$formData = array(
					'first_name' => $first_name,
					'last_name' => $last_name,
					'email' => $email,
					'phone' => $phone,
					'send' => $send,
					'signup_commission' => $signup_commission,
					'renewal_commission' => $renewal_commission,
					'monthly_bonus' => $monthly_bonus,
					'yearly_bonus' => $yearly_bonus,
					'monthly_quota' => $monthly_quota,
					'yearly_quota' => $yearly_quota,
				);

				$this->load->model('modules/user_affiliates');
				$feedback = $this->user_affiliates->addAffiliate($formData);
                if(isset($feedback['success'])) {
                    $this->session->set_flashdata('success', $feedback['success']);
                    redirect('n4radmin/affiliates');
                    exit;
                } else {
                    $data['error'] = $feedback['error'];
                }
			}

			$this->load->view('admin/add-affiliate-account', $data);
		}

        public function view_affiliate()
        {
            $this->checkLogin();
            $affiliate_id = (int)$this->uri->segment(3);

            $this->load->model('modules/user_affiliates');
            $this->load->model('affiliates/affiliate_payments');

            $this->load->js('assets/themes/admin/js/affiliate-payment.js');

            $data['user'] = $this->user_affiliates->getAffiliateById($affiliate_id);
            $data['pending_payments'] = $this->affiliate_payments->pendingPayments($data['user']->unique_id);
            $data['recent_payments'] = $this->affiliate_payments->recentPayments($data['user']->unique_id, 30);

            $this->load->view('admin/view-affiliate', $data);
        }

        public function affiliates_paid()
        {
            $paymentIds = array();
            $invalid = false;
            foreach($_POST['paymentId'] as $val) {
                $id = (int)$val;
                if(!empty($id)) {
                    $paymentIds[] = $id;
                } else {
                    $invalid = true;
                }
            }

            if($invalid) {
                $this->session->set_flashdata('error', 'Invalid payment selection, try again');
            } else {
                $this->load->model('affiliates/affiliate_payments');
                $this->affiliate_payments->markPaymentPaid($paymentIds);
                $this->session->set_flashdata('success', 'The payments you selected have been marked as paid');
            }

            redirect('n4radmin/view-affiliate/'.$_POST['user']);
            exit;
        }


        public function viewing_affiliate()
        {
            $affiliate_id = (int)$this->uri->segment(3);
            $year = (int)$this->uri->segment(4);
            $month = (int)$this->uri->segment(5);

            if(empty($month) || empty($year)) {
                $year = date('Y');
                $month = date('m');
            }
            $this->load->js('assets/themes/admin/js/affiliate-payment.js');
            
            $this->load->model('affiliates/affiliate_payments');
            $this->load->model('modules/user_affiliates');

            $user = $this->user_affiliates->getAffiliateById($affiliate_id);

            $data = $this->affiliate_payments->eligibleNewReferralsLastMonth(
                $user->unique_id,
                $month,
                $year
            );
            $data['user'] = $user;
            $data['pending_payments'] = $this->affiliate_payments->pendingPayments($data['user']->unique_id);
            $data['recent_payments'] = $this->affiliate_payments->recentPayments($data['user']->unique_id, 30);

            $this->load->view('admin/testfile', $data);
        }


	}