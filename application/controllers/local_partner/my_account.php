<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class My_account extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->section('sidebar', 'advertiser-sidebar');
		$this->output->set_template('advertisers/user');
		$this->output->set_title('My Account');  // SETS TITLE OF THE PAGE		
        $this->load->model('advertisers/security_check');
        if(!$this->security_check->check()) {
            redirect('local-partner/login');
            exit;
        }

    }

    function index()
    {
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

        $this->load->view('advertisers/user/my-account', $data);
    }
	
	public function update() 
	{
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
		redirect('local-partner/my-account');
		exit;
			
	}
	
	function password()
	{
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
		redirect('local-partner/my-account');
		exit;
	}

}