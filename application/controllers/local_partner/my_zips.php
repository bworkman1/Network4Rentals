<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class My_zips extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->output->set_template('advertisers/user');
		$this->output->set_title('My Premium Ads');  // SETS TITLE OF THE PAGE		
        $this->load->model('advertisers/security_check');
        if (!$this->security_check->check()) {
            redirect('local-partner/login');
            exit;
        }

    }

    function index()
    {
        $this->load->model('advertisers/ad_handler');

        $data['page_setting'] = $this->ad_handler->check_public_page();
        $data['my_zips'] = $this->ad_handler->get_my_zips();

        $this->load->view('advertisers/user/my-zips', $data);
    }
	
	function edit()
	{
		$this->output->set_title('Edit Premium Ads');  // SETS TITLE OF THE PAGE
		$this->load->model('advertisers/public_page_handler');
		$this->load->model('advertisers/post_handler');
		$id = $this->uri->segment(4);
		
		$this->form_validation->set_rules('apply_post', 'Apply To', 'trim|max_length[1]|xss_clean|required|numeric');
		$this->form_validation->set_rules('title', 'Apply To', 'trim|max_length[30]|xss_clean');
		$this->form_validation->set_rules('desc', 'Post Description', 'trim|max_length[145]|xss_clean');
		if($this->form_validation->run() == true) {
			extract($_POST);
			
			$fileError = '';
			if(isset($_FILES)) {
				if(!empty($_FILES['file']['name'])) {
					$this->load->model('special/user_uploads');
					$upload = $this->user_uploads->upload_image($_FILES['file'], 'file');
					if(isset($upload['success'])) {
						$file = $upload['success']['system_path'];
					} else {
						$this->session->set_flashdata('upload_error', $file['error']);
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
					$this->session->set_flashdata('error', 'Something Went Wrong While Creating/Editing Your Post. Perhaps You Didn\'t Make Any Changes To The Premium Add And That Is Why You See This Error, Please Try Again');
					break;
				case 4:
					$this->session->set_flashdata('success', 'Your premium add has been saved and is now live on the website');
					break;
				case 5:
					$this->session->set_flashdata('error', 'No active premium add found, try again');
					break;
				case 6:
					$this->session->set_flashdata('error', 'Premium add not found, please click the edit on one of t0he premium adds below');
					break;
			}
			redirect('local-partner/my-zips');
			exit;
		}
		
		$data['ad_details'] = $this->post_handler->get_ad_info($id);
		$data['page'] = $this->public_page_handler->get_public_image();
		

		$this->load->view('advertisers/user/edit-ad', $data);
		
	}
	
	public function extend() 
	{
		$this->load->js('assets/themes/default/js/masked-input.js');
		$this->load->model('advertisers/ad_handler');
		$this->load->model('special/admin_settings');
		$data['my_zips'] = $this->ad_handler->get_my_zips();
		$data['admin_settings'] = $this->admin_settings->getAdminSettings(array('additional_advertising'));

		$this->load->view('advertisers/user/extend-ads', $data);
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
			
			$amount = "95.88";
			$credit_card = "4949-9494-9494-9449";
			$cvv = "544";
			$expiry_month = "02";
			$expiry_year = "17";
			$extend = array('25', '24');
			$length = "6";
			$name = "Brian Workman";
			
			
			$this->load->model('advertisers/ad_handler');
			if($this->ad_handler->validateExtendPayment($extend, $amount, $length)) {
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
						'options'			=> $this->ad_handler->formatOptions($extend),
						'frequency'			=> $length,
						'sub_id'			=> $this->process_payment->trans_id,
						'affiliate_id' 		=> $this->session->userdata('affiliate_id')
					);
			
					$this->process_payment->log_payment($details);	
					$this->ad_handler->updateExperationDate($extend, $length);
					$intro = '<p>Thank you for exending your premium ads on Network4Rentals. This is to confirm that your credit card payment for your account has been authorized and processed. The details of the transaction are below.</p>';
					$this->ad_handler->sendUserEmail($creditCardDetails, $details, $extend, $intro);
						
					$feedback = array('success' => 'succcess');
				} else {
					$feedback = array('error' => $this->process_payment->paymentError);
				}
				
			} else {
				$feedback = array('error' => 'Something is not matching up, try refreshing your screen and starting again');
			}
			
		}
		echo json_encode($feedback);		
	}

}