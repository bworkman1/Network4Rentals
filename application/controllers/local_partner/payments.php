<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Payments extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
		$this->output->set_template('advertisers/user');
		$this->output->set_title('Payments');  // SETS TITLE OF THE PAGE
        $this->load->model('advertisers/security_check');
        if (!$this->security_check->check()) {
            redirect('local-partner/login');
            exit;
        }
	}
	
	public function index()
	{		
		$this->load->model('special/local_payments');
		if(!empty($_POST)) {
			$this->local_payments->saveUserSettings($_POST);
			redirect('local-partner/payments?settings=true');
			exit;
		}
		
		$data['settings'] = $this->local_payments->getUserSettings($this->session->userdata('user_id'));
		
		if($data['settings'] || $_GET['settings']) {
			$this->load->view('common/payment-settings', $data);
		} else {
			$this->load->view('common/payment-intro');
		}
	}
	
	public function view_payments() 
	{
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
		$this->local_payments->printPaymentDetails($this->uri->segment(3), $this->session->userdata('user_id'), 'contractor', $_GET['type']);
	}
	
	public function create_invoice()
	{
		$this->load->model('special/local_payments');
		$this->load->js('assets/themes/default/js/payment-search.js');
		
		
		$this->load->view('common/create-invoice');
		
	}
	
	public function view_invoices() 
	{
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
