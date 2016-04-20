<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Add_zip_codes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->output->set_template('advertisers/user');
        $this->load->section('sidebar', 'advertiser-sidebar');
        $this->load->model('advertisers/security_check');
        if (!$this->security_check->check()) {
            redirect('local-partner/login');
            exit;
        }
    }

    function index()
    {
        $this->load->js('assets/themes/default/js/bootbox.js');
        $this->load->js('assets/themes/default/js/add-steps-bootstrap.js');
        $this->load->js('assets/themes/default/js/masked-input.js');
		
        $this->load->model('advertisers/account_handler');
        $this->load->model('special/admin_settings');
		
		$data['admin_settings'] = $this->admin_settings->getAdminSettings(array('additional_advertising'));
        $data['user_info'] = $this->account_handler->profile_info();
		
        $this->load->view('advertisers/user/add-zips', $data);
    }

	public function availability()
	{
		$this->output->set_template('json');
		$this->form_validation->set_rules('type', 'Side', 'required|min_length[5]|max_length[11]|xss_clean|alpha');
        $this->form_validation->set_rules('zip', 'Zip Code', 'min_length[5]|max_length[5]|numeric|xss_clean');
		 if ($this->form_validation->run() == FALSE) {
			$feedback = array('error'=>validation_errors('<span>', '</span>'));
        } else {
            extract($_POST);
			$this->load->model('advertisers/ad_handler');
			$feedback = $this->ad_handler->checkAvaliablity($type, $zip);
		}
		
		echo json_encode($feedback);
	}
	
	public function payment() 
	{
		$this->output->set_template('json');
		
		$this->form_validation->set_rules('credit_card', 'Credit Card', 'required|min_length[19]|max_length[19]|xss_clean');
		$this->form_validation->set_rules('expiry_month', 'Expiration Month', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
		$this->form_validation->set_rules('expiry_year', 'Expiration Year', 'required|min_length[2]|max_length[2]|numeric|xss_clean');
		$this->form_validation->set_rules('cvv', 'CCV', 'required|min_length[1]|max_length[5]|numeric|xss_clean');
		$this->form_validation->set_rules('name', 'Name On Credit Card', 'required|min_length[5]|max_length[70]|xss_clean');
		$this->form_validation->set_rules('length', 'Payment Options', 'required|min_length[1]|max_length[1]|xss_clean|integer');
		$this->form_validation->set_rules('amount', 'Amount', 'required|min_length[1]|max_length[6]|xss_clean|');
		
		if ($this->form_validation->run() == FALSE) {
			$feedback = array('error'=>validation_errors('<span>', '</span>'));
        } else {
            extract($_POST);
			
			$this->load->model('advertisers/ad_handler');
			
			if($this->ad_handler->validatePayment($_POST['selections'], $amount, $length)) {			
			
				$nameArray = explode(' ', $name);
				$creditCardDetails = array(
					'x_card_num'			=> $credit_card,
					'x_exp_date'			=> $expiry_month.'/'.$expiry_year,
					'x_card_code'			=> $cvv,
					'x_description'			=> 'Local Partner | Additional Advertising Payment',
					'x_amount'				=> $amount,
					'x_first_name'			=> $nameArray[0],
					'x_last_name'			=> end($nameArray),
					'x_email'				=> $this->session->userdata('email'),
					'x_customer_ip'			=> $this->input->ip_address(),
				);
				$this->load->model('advertisers/process_payment');
				
	
				if($this->process_payment->processInitialPayment($creditCardDetails)) {
					
					$details = array(
						'id' 				=> $this->session->userdata('user_id'),
						'payment_amount'	=> $amount,
						'type'				=> 'advertiser',
						'payment_id'		=> $this->process_payment->trans_id,
						'options'			=> $this->ad_handler->formatOptions($_POST['selections']),
						'frequency'			=> $length,
						'sub_id'			=> $this->process_payment->trans_id,
						'affiliate_id' 		=> $this->session->userdata('affiliate_id'),
						'renewal'			=> 'y'
					);
			
					$this->process_payment->log_payment($details);
					
					if($this->ad_handler->addPurchasedZipCodes($_POST['selections'], $length)) {
						$intro = '<p>Thank you for becoming a local partner with Network4Rentals. This is to confirm that your credit card payment for your account has been authorized and processed. The details of the transaction are below.</p>';
						$this->ad_handler->sendUserEmail($creditCardDetails, $details, $_POST['selections'], $intro);
						
						$this->session->set_flashdata('success', 'Your payment has successfully processed, now you must create the ads in order for them to display on the website');
						$feedback = array('success' => 'success');
					} else {
						$feedback = array('error' => $this->ad_handler->addZipsError);
					}
					
				} else {
					$feedback = array('error' => $this->process_payment->paymentError);
				}
				
				
				
			} else {
				$feedback = array('error'=>'The amount we recieved from you doesn\'t match with what you have, please refresh the page or contact support for further help');
			}
		}
		echo json_encode($feedback);
	}
	
}