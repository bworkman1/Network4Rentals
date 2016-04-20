<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Create_account extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
		
		$this->_init();
        $this->output->set_template('advertisers/non-user');
    }
	
	public function _init() 
	{
		if($this->session->userdata('user_id')>0) {
			redirect('local-partner/home');
			exit;
		}
	}
	
    public function index()
    {	
	
		$this->load->js('assets/themes/default/js/masked-input.js'); 
		$this->load->js('assets/themes/default/js/jquery.creditCardValidator.js'); 
		$this->load->css('assets/themes/default/css/alertify.core.css'); 
		$this->load->js('assets/themes/default/js/alertify.min.js'); 
		$this->load->js('assets/themes/default/js/local-partner/partner.js'); 
		
		$this->load->model('special/admin_settings');
		
		$retrieve = array('local_partner_monthly_payment', 'local_partner_quarterly_payment', 'local_partner_yearly_payment');
		$data['payment_settings'] =$this->admin_settings->getAdminSettings($retrieve);
		$data['categories'] = $this->admin_settings->getLocalPartnerCategories();
		$this->load->view('advertisers/non-user/create-new-account', $data);
    }
	
	public function submit()
	{
		$this->output->set_template('json');
		
		$feedback = array();
		
		//Personal Details
		$this->form_validation->set_rules('company_name', 'Company Name', 'min_length[3]|max_length[70]|xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[50]|alpha|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[50]|alpha|xss_clean');
		
		$this->form_validation->set_rules('cc_fname', 'Credit Card First Name', 'required|min_length[2]|max_length[50]|alpha|xss_clean');
		$this->form_validation->set_rules('cc_lname', 'Credit Card Last Name', 'required|min_length[2]|max_length[50]|alpha|xss_clean');
		
		$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[19]|max_length[19]|xss_clean');
		$this->form_validation->set_rules('ccv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean');
		$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[3]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('credit_card_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
		$this->form_validation->set_rules('zip', 'Zip', 'required|min_length[5]|max_length[5]|numeric|xss_clean');
		
		$this->form_validation->set_rules('email', 'Email', 'required|min_length[3]|max_length[60]|valid_email|xss_clean|is_unique[contractors.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('amount', 'Amount', 'required|min_length[4]|max_length[6]|xss_clean');
		$this->form_validation->set_rules('promo', 'Promo Code', 'min_length[2]|max_length[15]|xss_clean');
		$this->form_validation->set_rules('category', 'Category', 'min_length[1]|max_length[100]|xss_clean');
		
		$this->form_validation->set_message('is_unique', 'The email address you entered is already in use');

		if ($this->form_validation->run() == FALSE) {
			$feedback = array('error' => validation_errors());
		} else { 
			extract($_POST);
		
			/*
			$company_name = 'EMF Web Solutions';
			$first_name = 'Brianss';
			$last_name = 'Workmanss';
			
			$credit_card = '4111111111111111';
			$credit_card_month = '01';
			$credit_card_year = '2019';
			$ccv = '322';
			$zip = '43055';
			$email = 'brian@semf-websolutions.com';
			$password = 'password12';
			$amount = '155.00';
			$promo = 'n4r';
			*/
			
			$this->load->model('advertisers/process_payment');
			
			$this->process_payment->setAmount($amount);
			if(!empty($promo)) {
				$this->process_payment->setPromoCode($promo);
			}
			$this->process_payment->calculateNewTotal();
			$this->process_payment->authName = 'N4R Local Partner Account '.ucwords($this->process_payment->billing_name).' Payment';
			
			$credit_card = preg_replace("/[^0-9,.]/", "", $credit_card);
			
			$creditCardDetails = array(
				'x_card_num'			=> $credit_card, // Visa
				'x_exp_date'			=> $credit_card_month.'/'.ltrim($credit_card_year,'20'),
				'x_card_code'			=> $ccv,
				'x_description'			=> 'N4R Local Partner Account '.ucwords($this->process_payment->billing_name).' Payment',
				'x_amount'				=> $this->process_payment->total,
				'x_first_name'			=> $cc_fname,
				'x_last_name'			=> $cc_lname,
				'x_zip'					=> $zip,
				'x_country'				=> 'US',
				'x_email'				=> $email,
				'x_customer_ip'			=> $this->input->ip_address(),
			);
			
			if($this->process_payment->total > 0) {
				if(!$this->process_payment->processInitialPayment($creditCardDetails)) {
					$feedback = array('error' => $this->process_payment->paymentError);
				} else {
					$subscription = true;
					$arb_setup = $this->process_payment->setUpSubscription($creditCardDetails);
					if($arb_setup !== true) {
						$subscription = false;
					}
					
					$user = array(
						'user' => $email,
						'password' => md5($password),
						'email' => $email,
						'zip' => $zip,
						'f_name' => $first_name,
						'l_name' => $last_name,
						'sub_id' => $this->process_payment->subscription_id,
						'bName' => $company_name,
						'active' => 'y',
						'category' => $category,
						'affiliate_id' => $this->session->userdata('affiliate_id')
					);
					
					$this->load->model('advertisers/create_user_handler');
					$user_id = $this->create_user_handler->createUserAccount($user);
					
					$user['amount'] = $this->process_payment->amount;
					$user['freq'] = $this->process_payment->freq;
					$user['subscription_id'] = $this->process_payment->subscription_id;
					$user['total'] = $this->process_payment->total;
					
					$details = array(
						'id' 				=> $user_id,
						'payment_amount'	=> $user['amount'],
						'type'				=> 'advertiser',
						'payment_id'		=> $this->process_payment->trans_id,
						'options'			=> '',
						'frequency'			=> $user['freq'],
						'sub_id'			=> $this->process_payment->subscription_id,
						'affiliate_id' 		=> $this->session->userdata('affiliate_id'),
						'renewal'			=> 'n',
						'expires' => date('Y-m-d', strtotime($credit_card_month.'/01/'.$credit_card_year)),
						'payment_type' => 'auto'
					);
			
					$this->process_payment->log_payment($details);
					
					$this->create_user_handler->sendWelcomeEmail($user);
					
					$this->session->set_userdata('logged_in', true);
					$this->session->set_userdata('user_id', $user_id);
					$this->session->set_userdata('email', $email);
					$this->session->set_userdata('side_logged_in', 'local-partner');
					$feedback = array('success' => 'Your account has been created successfully');
					
					if($subscription) {						
						$this->session->set_flashdata('success', 'Your account has been created successfully');
					} else {
						$this->session->set_flashdata('success', 'Your first year payment successfully processed but your yearly subscription didn\'t process due to an error. Please contact support to sort this out.<br><b>Reason: </b>'.$arb_setup);
					}					
				}
			} else {
				$feedback = array('error' => 'Invalid payment plan selected');
			}
						
		}
		
		echo json_encode($feedback);
	}
	
	public function submit_old()
	{
		$this->output->set_template('json');
		
		$feedback = array();
		
		//Personal Details
		$this->form_validation->set_rules('company_name', 'Company Name', 'min_length[3]|max_length[70]|xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[3]|max_length[50]|alpha|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[3]|max_length[50]|alpha|xss_clean');
		
		$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[19]|max_length[19]|xss_clean');
		$this->form_validation->set_rules('ccv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean');
		$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[3]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('credit_card_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
		$this->form_validation->set_rules('zip', 'Zip', 'required|min_length[5]|max_length[5]|numeric|xss_clean');
		
		$this->form_validation->set_rules('email', 'Email', 'required|min_length[3]|max_length[60]|valid_email|xss_clean|is_unique[advertisers.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('amount', 'Amount', 'required|min_length[4]|max_length[6]|xss_clean');
		$this->form_validation->set_rules('promo', 'Promo Code', 'min_length[2]|max_length[15]|xss_clean');
		$this->form_validation->set_rules('category', 'Category', 'min_length[1]|max_length[2]|xss_clean
		required');
		
		$this->form_validation->set_message('is_unique', 'The email address you entered is already in use');
		
		if ($this->form_validation->run() == FALSE) {	
			$feedback = array('error' => validation_errors());
		} else {
			extract($_POST);
			
			/*
			$company_name = 'EMF Web Solutions';
			$first_name = 'Brian';
			$last_name = 'Workman';
			
			$credit_card = '4111111111111111';
			$credit_card_month = '01';
			$credit_card_year = '2016';
			$ccv = '494';
			$zip = '43055';
			$email = 'brian@semf-websolutions.com';
			$password = 'password12';
			$amount = '79.00';
			$promo = 'n4r';
			*/
			$this->load->model('advertisers/process_payment');
		
			$this->process_payment->setAmount($amount);
			if(!empty($promo)) {
				$this->process_payment->setPromoCode($promo);
			}
			$this->process_payment->calculateNewTotal();
			
			$creditCardDetails = array(
				'x_card_num'			=> $credit_card, // Visa
				'x_exp_date'			=> $credit_card_month.'/'.ltrim($credit_card_year,'20'),
				'x_card_code'			=> $ccv,
				'x_description'			=> 'Local Partner Account 1st Year Payment',
				'x_amount'				=> $this->process_payment->total,
				'x_first_name'			=> $first_name,
				'x_last_name'			=> $last_name,
				'x_zip'					=> $zip,
				'x_country'				=> 'US',
				'x_email'				=> $email,
				'x_customer_ip'			=> $this->input->ip_address(),
			);
				
			if($this->process_payment->total > 0) {
				
				if(!$this->process_payment->processInitialPayment($creditCardDetails)) {
					$feedback = array('error' => $this->process_payment->paymentError);
				} else {
					$arb_setup = $this->process_payment->setUpSubscription($creditCardDetails);
					if($arb_setup !== true) {
						$feedback = array('error' => 'Your first year payment successfully processed but your yearly subscription didn\'t process due to an error.<br> '.$arb_setup.' Please contact us to fix this issues');
					} else {
						$user = array(
							'user' => $email,
							'password' => md5($password),
							'email' => $email,
							'zip' => $zip,
							'f_name' => $first_name,
							'l_name' => $last_name,
							'sub_id' => $this->process_payment->subscription_id,
							'bName' => $company_name,
							'active' => 'y',
							'category' => $category,
						);
						$this->load->model('advertisers/create_user_handler');
						$user_id = $this->create_user_handler->createUserAccount($user);
						
						$user['amount'] = $this->process_payment->amount;
						$user['freq'] = $this->process_payment->freq;
						$user['subscription_id'] = $this->process_payment->subscription_id;
						$user['total'] = $this->process_payment->total;
						
						$details = array(
							'id' 				=> $user_id,
							'payment_amount'	=> $user['amount'],
							'type'				=> 'advertiser',
							'payment_id'		=> $this->process_payment->trans_id,
							'options'			=> '',
							'frequency'			=> $user['freq'],
							'sub_id'			=> $this->process_payment->subscription_id,
							'affiliate_id' 		=> $this->session->userdata('affiliate_id'),
							'renewal'			=> 'n',
							'expires' => date('Y-m-d', strtotime($credit_card_month.'/01/'.$credit_card_year)),
							'payment_type' => 'auto'
						);
				
						$this->process_payment->log_payment($details);
						
						$this->create_user_handler->sendWelcomeEmail($user);
						
						$this->session->set_userdata('logged_in', true);
						$this->session->set_userdata('user_id', $user_id);
						$this->session->set_userdata('email', $email);
						$this->session->set_userdata('side_logged_in', 'local-partner');
						$feedback = array('success' => 'Your account has been created successfully');
						
						$this->session->set_flashdata('success', 'Your account has been created successfully');
						redirect('local-partner/home');
						exit;
					}
				}
			} else {
				$feedback = array('error' => 'Invalid payment plan selected');
			}
						
		}
		
		echo json_encode($feedback);
	}
	
	public function checkpromo() 
	{		
		$this->output->set_template('json');
		$this->form_validation->set_rules('promo', 'Promo Code', 'required|min_length[2]|max_length[15]|xss_clean');		
		if ($this->form_validation->run() == FALSE) {	
			$feedback = array('error' => validation_errors('<span>', '</span>'));
		} else {
			extract($_POST);
			$this->load->model('advertisers/create_user_handler');
			$data = $this->create_user_handler->checkPromoCode($promo);
			$feedback = array('success' => $data->percent);
		}
		echo json_encode($feedback);
	}
	
}