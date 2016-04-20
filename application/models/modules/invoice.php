<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Model
{
	
	public $amount;
	public $file;
	public $contractor_id;
	public $landlord_id;
	public $request_id;
	private $created;
	private $paid;
	private $paid_on;
	private $trans_id;
	private $note;
	private $invoice_num;
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->created = date('Y-m-d H:i');
		
    }
	
	
	function createInvoice($request_id, $landlord_id, $contractor_id)
	{
		$this->form_validation->set_rules('amount', 'Amount', 'trim|max_length[10]|xss_clean|required|greater_than[0]');
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[70]|xss_clean|valid_email');
		$this->form_validation->set_rules('note', 'Note', 'trim|max_length[500]|xss_clean');
		$this->form_validation->set_rules('invoice', 'Invoice Number', 'trim|min_length[1]|max_length[25]|xss_clean|required');
		
		extract($_POST);
		
		if($this->form_validation->run() == true) {
			
			$this->amount = $amount;
			$this->email = $email;
			$this->note = $note;
			$this->invoice_num = $invoice;
			$this->request_id = $request_id;
			$this->landlord_id = $landlord_id;
			$this->contractor_id = $contractor_id;
			
			if($this->checkDuplicateInvoice()) {
				return 'There is already an invoice with that number, change the number and try again';
				exit;
			}

			if(!empty($_FILES['attachment']['name'])) {
				$this->load->model('special/user_uploads');
				$doc = $this->user_uploads->uploadPDF($_FILES['attachment'], 'attachment');
				
				if(!empty($doc['error'])) {
					return $doc['error'];
					exit;
				} else {
					$this->file = $doc['success']['system_path'];
				}
			}
			
			return $this->insertInvoice();
			exit;
		} else {
			return  validation_errors('<span>', '</span>');
			exit;
		}
		
	}
	
	private function checkDuplicateInvoice()
	{
		$query = $this->db->get_where('service_request_invoices', array('invoice_num' => $this->invoice_num, 'contractor_id' => $this->contractor_id));
		if($query->num_rows()>0) {
			return true;
		}
		return false;
	}
	
	private function addInvoiceNote() 
	{
		$note = '<p>Invoice/Notes: '.$this->note.'<br><b>Amount</b></p>';
		$this->db->insert('service_request_notes', array(
			'note' => $note, 
			'visibility' => '1',
			'ref_id' => $this->request_id,
			'landlord_id' => $this->landlord_id,
			'contractor_id' => $this->contractor_id
		));
	}
	
	public function markInvoiceAsPaid($invoice_id)
	{
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('id', $invoice_id);
		$this->db->update('invoices',  array('paid'=>'y', 'paid_on' => date('Y-m-d')));	
		if($this->db->affected_rows()>0) {
			return true;
		}
		return false;
	}
	
	private function insertInvoice()
	{
		$data = array(
			'amount' => $this->amount,
			'file' => $this->file,
			'contractor_id' => $this->contractor_id,
			'landlord_id' => $this->landlord_id,
			'request_id' => $this->request_id,
			'created' => $this->created,
			'email' => $this->email,
			'invoice_num' => $this->invoice_num
		);
		
		$this->db->insert('service_request_invoices', $data);
		if($this->db->insert_id()>0) {
			$this->addInvoiceNote();
			$this->sendInvoiceEmail();
		
			return true;
		}
		
		return 'Failed to add service request, try again';
	}
	
	private function sendInvoiceEmail()
	{
		$contractor = $this->getContractorDetails();
		$request = $this->getServiceRequestDetails();
		
		if(empty($requst->address)) {
			$address = $this->getRepairAddress($request->rental_id, $request->listing_id);
		} else {
			$address = $request->address;
		}
		
		$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
		
		$subject = 'New Invoice From '.htmlspecialchars ($contractor->bName); 
		$message = '<h2>New Invoice: '.$this->invoice_num.'</h2><h3>Amount: '.$this->amount.'</h3><p>'.htmlspecialchars(ucwords($contractor->name)).' from '.ucwords(htmlspecialchars($contractor->bName)).' has sent you an invoice for for the '.$services_array[$request->service_type].' they completed on '.$address.'. You can now make easy one time payments online through my secure website by clicking the link below. Once there enter the invoice number <b>'.$this->invoice_num.'</b> to see the details about your invoice.</p><p>If you have any questions contact me at ('.substr($contractor->phone, 0, 3).') '.substr($contractor->phone, 3, 3).'-'.substr($contractor->phone,6).'</p><br><a href="https://n4r.rentals/'.$request->unique_name.'/pay-online/?invoice_num='.$this->invoice_num.'">Pay My Invoice Online</a>';
		
		if(!empty($this->note)) {
			$message .= '<h4>Invoice Note: </h4><p>'.htmlspecialchars($this->note).'</p>';
		}
	
		$this->load->model('special/send_email');
		$this->send_email->sendEmail($this->email, $message, $subject, $this->file);
	}
	
	private function getRepairAddress($rental_id, $listing_id)
	{
		$query = $this->db->get_where('renter_history', array('id' => $rental_id));
		if($query->num_rows()>0) {
			$data = $query->row();
			return $data->rental_address.' '.$data->rental_city.', '.$data->rental_state;
		} else {
			$query = $this->db->get_where('listings', array('id' => $listing_id));
			$data = $query->row();
			return $data->address.' '.$data->city.', '.$data->state;
		}
	}
	
	private function getServiceRequestDetails() 
	{
		$query = $this->db->get_where('all_service_request', array('id' => $this->request_id));
		return $query->row();
	}
	
	private function getContractorDetails() 
	{
		$query = $this->db->get_where('landlord_page_settings', array('landlord_id' => $this->contractor_id, 'type' => 'contractor'));
		return $query->row();
	}
	
	public function getRequestInvoices($ref_id) 
	{
		$query = $this->db->get_where('invoices', array('ref_id' => $ref_id, 'user_id'=>$this->session->userdata('user_id')));
		return $query->result();
	}
	
}	