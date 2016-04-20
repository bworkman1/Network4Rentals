<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Local_payments extends CI_Model {
	
		// Call the Model constructor
		function Local_payments()
		{		
			parent::__construct();
		}
		
		/*
			Pass in a type and user user_id and recieve payments
		*/
		public function getPayments($data) 
		{	
			$this->db->order_by('id', 'desc');
			$this->db->select('local_payments.firstname, local_payments.lastname, local_payments.trans_id, local_payments.approval_id, invoices.invoice_num, local_payments.ts, local_payments.amount, local_payments.id');
			$this->db->join('invoices', 'invoices.id = local_payments.invoice_id');
			$result = $this->db->get_where('local_payments', array('local_payments.type'=>$data['type'], 'local_payments.user_id'=>$data['id']));
			return $result->result();
		}
		
		public function getPaymentsSum($data)
		{
			$this->db->select_sum('amount');
			$this->db->where('type', $data['type']);
			$this->db->where('user_id', $data['id']);
			
			$result = $this->db->get('local_payments');
			
			return $result->row();
		}
		
		public function searchPayments($search, $format = false)
		{
			$seachArray = explode(' ', $search);
			
			$params = array($this->session->userdata('user_id'), strtolower($this->session->userdata('side')), '%'.$search.'%', '%'.$search.'%');
		
			$sql = "SELECT local_payments.firstname, local_payments.lastname, local_payments.trans_id, local_payments.approval_id, invoices.invoice_num, local_payments.ts, local_payments.amount, local_payments.id FROM local_payments LEFT JOIN invoices ON local_payments.invoice_id = invoices.id WHERE local_payments.user_id = ? AND local_payments.type = ? AND (local_payments.trans_id LIKE ? OR invoice_num LIKE ?";
		
			foreach($seachArray as $val) {
				$sql .= " OR local_payments.firstname LIKE ?";
				$sql .= " OR local_payments.lastname LIKE ?";
				$params[] = '%'.$val.'%';
				$params[] = '%'.$val.'%';
			}
			
			$sql .= ")";
			
			$result = $this->db->query($sql, $params);			
			if($format) {
				return $this->formatSearchData($result->result());
			} else {
				return $result->result();
			}
			
		}
		
		public function searchInvoices($search, $format = false)
		{
			$seachArray = explode(' ', $search);
			
			$params = array($this->session->userdata('user_id'), strtolower($this->session->userdata('side')), '%'.$search.'%', '%'.$search.'%');
		
			$sql = "SELECT * FROM invoices WHERE user_id = ? AND type = ? AND (email LIKE ? OR invoice_num LIKE ?";
		
			foreach($seachArray as $val) {
				$sql .= " OR name LIKE ?";
				$params[] = '%'.$val.'%';
				$params[] = '%'.$val.'%';
			}
			
			$sql .= ")";
			
			$result = $this->db->query($sql, $params);		

			$returnData = array();
			foreach($result->result() as $row) {
				$row->payments_sum = number_format($this->getPaymentsSumPerInvoice($row->id)->amount,2);
				$returnData[] = $row;
			}

			
			if($format) {
				return $this->formatInvoiceData($returnData);
			} else {
				return $returnData;
			}
			
		}
		
		public function formatInvoiceData($data) 
		{
			$table = '';
			foreach($data as $row) {
			
				if($row->paid == 'n') {
					$status = 'Open';
				} else {
					if($row->payments_sum < $row->amount) {
						$status = 'Partial';
					} elseif($row->payments_sum >= $row->amount) {
						$status = 'Paid';
					}
				}
				
				$table .= "<tr class='searchdata' style='border-bottom: 1px solid #ddd;'>";
					$table .= "<td>".$row->name."</td>";
					$table .= "<td>".$row->invoice_num."</td>";
					$table .=  "<td>".date("m-d-Y", strtotime($row->created))."</td>";
					$table .=  "<td>".$status."</td>";
					
					$table .=  "<td>$".number_format($row->amount, 2)."</td>";
					$table .= "<td>$".number_format($row->payments_sum, 2)."</td>";
					$table .= "<td class='text-right'><a href='".base_url('contractor/view-invoice/'.$row->id)."'>View</a></td>"; 
				$table .=  "<tr>";
			
			}
			return $table;
		}
		
		public function formatSearchData($data) 
		{
			$table = '';
			foreach($data as $row) {
	
				$table .= "<tr class='searchdata' style='border-bottom: 1px solid #ddd;'>";
					$table .= "<td>".$row->firstname."</td>";
					$table .= "<td>".$row->lastname."</td>";
					$table .= "<td>".$row->trans_id."</td>";
					if($row->approval_id) {
						$table .=  "<td>Credit Card</td>";
					} else {
						$table .=  "<td>E-Check</td>";
					}
					$table .= "<td>".$row->invoice_num."</td>";
					$table .=  "<td>".date("m-d-Y", strtotime($row->ts))."</td>";
					$table .=  "<td>$".number_format($row->amount, 2)."</td>";
					$table .= "<td class='text-right'><a href='".base_url('contractor/view-payment-info/'.$row->id)."'>View</a></td>"; 
				$table .=  "<tr>";
			
			}
			return $table;
		}
		
		public function getSinglePayment($id)
		{			
			$data = (object) array();
			$data->payment = $this->getLocalPayment($id);
			if($data->payment->invoice_id>0) {
				$data->invoice = $this->getInvoiceDetails($data->payment->invoice_id);
				$data->invoicePayments = $this->getInvoicePayments($data->payment->invoice_id);
			}
			return $data;
		}
		
		public function getSingleInvoice($id)
		{			
			$data = (object) array();
			$data->invoice = $this->getInvoiceDetails($id);
			$data->invoicePayments = $this->getInvoicePayments($id);
			
			return $data;
		}
		
		public function getPublicPageSettings($id, $type)
		{
			$result = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$id, 'type'=>$type));
			return $result->row();
		}
		
		private function getLocalPayment($id) 
		{
			$result = $this->db->get_where('local_payments', array('id'=>$id, 'type'=>strtolower($this->session->userdata('side')), 'user_id'=>$this->session->userdata('user_id') ));
			return $result->row();
		}
		
		private function getInvoicePayments($invoice_id)
		{
			$result = $this->db->get_where('local_payments', array('invoice_id'=>$invoice_id, 'type'=>strtolower($this->session->userdata('side')), 'user_id'=>$this->session->userdata('user_id')));
			return $result->result();
		}
		
		private function getInvoiceDetails($id)
		{
			$result = $this->db->get_where('invoices', array('id'=>$id, 'type'=>strtolower($this->session->userdata('side')), 'user_id'=>$this->session->userdata('user_id')));
			return $result->row();
		}
		
		public function printPaymentDetails($id, $user_id, $type, $hook) 
		{ 
			$this->output->set_template('blank');
			$this->load->helper(array('dompdf', 'file'));
			$data['user'] = $this->getPublicPageSettings($user_id, $type);
		
			if($hook == 'view-invoice') {
				$data['invoice'] = $this->getInvoiceDetails($id);
			} else {
				$data['payment'] = $this->getSinglePayment($id);
			}
					
			$html = $this->load->view('common/print-payment-details', $data, true);
			
			pdf_create($html, 'Payment Details'.$data['payment']->payment->trans_id);  
		}
		
		/*
		User must be logged in and this checks to see if the user can/does accept online payments
		*/
		private function checkForOnlinePayments() 
		{
			$result = $this->db->get_where('local_payment_settings', array(
				'user_id'=> $this->session->userdata('user_id'), 
				'type'=> strtolower($this->session->userdata('side')),
			));
			$payments = $result->row();
			
			if($payments->allow_payments == 'y' && $payments->net_api && $payments->net_key && ($payments->accept_cc == 'y' || $payments->accept_echeck == 'y')) {
				return true;
			}
			return false;
		}
		
		public function getInvoices($data) 
		{
			$this->db->order_by('id', 'desc');
			$result = $this->db->get_where('invoices', array('type'=>$data['type'], 'user_id'=>$data['id']));
			$returnData = array();
			foreach($result->result() as $row) {
				$row->payments_sum = number_format($this->getPaymentsSumPerInvoice($row->id)->amount,2);
				$returnData[] = $row;
			}
			return $returnData;
		}
		
		public function getPaymentsSumPerInvoice($invoice_id)
		{
			if($invoice_id) {
				$this->db->select_sum('amount');
				$this->db->where('type', strtolower($this->session->userdata('side')));
				$this->db->where('user_id', $this->session->userdata('user_id'));
				$this->db->where('invoice_id', $invoice_id);
				
				$result = $this->db->get('local_payments');
				return $result->row();
			}
			return false;
		}
		
		
		public function getInvoiceSum($data)
		{
			$this->db->select_sum('amount');
			$this->db->where('type', $data['type']);
			$this->db->where('user_id', $data['id']);
			
			$result = $this->db->get('invoices');
			
			return $result->row();
		}
		
		public function create_invoice()
		{
			$this->form_validation->set_rules('amount', 'Amount', 'min_length[1]|max_length[15]|xss_clean|required');
			$this->form_validation->set_rules('invoice', 'Invoice Number', 'min_length[1]|max_length[20]|xss_clean|required');
			$this->form_validation->set_rules('name', 'Name', 'min_length[1]|max_length[60]|xss_clean|required');
			$this->form_validation->set_rules('email', 'Email', 'min_length[1]|max_length[60]|xss_clean|valid_email');
			$this->form_validation->set_rules('note', 'Note', 'min_length[1]|max_length[800]|xss_clean');
			$this->form_validation->set_rules('send_email', 'Send Email', 'min_length[1]|max_length[1]|xss_clean|alpha');
			
			if ($this->form_validation->run() == FALSE) {
				return array('error' => validation_errors());
			} else {
				extract($_POST);
				
				$this->load->model('special/user_uploads');
				if(!empty($_FILES['file']['name'])) {
					$fileData = $this->user_uploads->uploadFileCallback($_FILES['file']);

					if(isset($fileData['error'])) {
						return array('error' => $fileData['error']);
						exit;
					}
				}
				
				if($this->isDuplicateInvoice($invoice)) {
					return array('error' => 'Invoice number has already been used');
					exit;
				}
				
				$invoice = array(
					'amount'=>$amount, 
					'user_id'=>$this->session->userdata('user_id'),
					'email' => $email,
					'created' => date('Y-m-d H:i:s'),
					'invoice_num' => $invoice,
					'name' => $name,
					'note' => $note,
					'type' => strtolower($this->session->userdata('side')),
					'file' => $fileData['success']['system_path'],
					'ref_id' => $ref_id,
				);
				
				if($send_email == 'y' || $email) {
					$this->sendEmail($email, $fileData['success']['file_path'], $invoice);
				}
				
				$this->db->insert('invoices', $invoice);
				
				if($send_email == 'y') {
					$this->session->set_flashdata('success', 'Invoice created, and an email was sent to '.$email.' with the invoice details.');
				} else {
					$this->session->set_flashdata('success', 'Invoice created!');
				}
				
				return array('success'=>'Invoice created successfully'); 
			}
		}
		
		public function isDuplicateInvoice($invoice_num)
		{
			$result = $this->db->get_where('invoices', array('user_id'=>$this->session->userdata('user_id'), 'type'=>strtolower($this->session->userdata('side')), 'invoice_num'=>$invoice_num));
			if($result->num_rows()>0) {
				return true;
			}
			return false;
		}
		
		private function sendEmail($email, $file, $invoice)
		{
			$this->load->model('special/send_email');
			$data['page'] = $this->getPublicPageSettings($this->session->userdata('user_id'), strtolower($this->session->userdata('side')));
			$data['invoice'] = $invoice;
			$data['accept_payments'] = $this->checkForOnlinePayments();
			$subject = $data['page']->bName.' - Invoice';
			$message = $this->load->view('email-templates/invoice-email', $data, true);
			$this->send_email->sendEmail($email, $message, $subject, $alt_message = null, $emailArray = null, $file);
		}
		
		public function getUserSettings($id)
		{
			$this->load->library('encrypt');
			$result = $this->db->get_where('local_payment_settings', array('user_id'=>$id, 'type'=>$this->session->userdata('side')));
			
			$data = $result->row();
			if($data) {
				$data->net_api = $this->encrypt->decode($data->net_api);
				$data->net_key = $this->encrypt->decode($data->net_key);
			}
			return $data;
		}
		
		public function saveUserSettings($data) 
		{
			//credit_card_discount	e_check_discount
			$this->form_validation->set_rules('net_api', 'API Login Id', 'min_length[2]|max_length[100]|xss_clean|required');
			$this->form_validation->set_rules('net_key', 'API Key', 'min_length[2]|max_length[100]|xss_clean|required');
			$this->form_validation->set_rules('allow_payments', 'Allow Payments', 'min_length[1]|max_length[1]|xss_clean|required');
			
			$this->form_validation->set_rules('accept_cc', 'Accept Credit Card', 'min_length[1]|max_length[1]|xss_clean');
			$this->form_validation->set_rules('accept_echeck', 'Accept E-Check', 'min_length[1]|max_length[1]|xss_clean');
			$this->form_validation->set_rules('min_payment', 'Min Payment', 'min_length[1]|max_length[10]|xss_clean');
			
			$this->form_validation->set_rules('credit_card_discount', 'Credit Card Discount', 'min_length[1]|max_length[10]|xss_clean');
			$this->form_validation->set_rules('e_check_discount', 'E Check Discount', 'min_length[1]|max_length[10]|xss_clean');
			
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				extract($_POST);
				$this->load->library('encrypt');
				$settings = array(
					'net_api'				=> $this->encrypt->encode($net_api),
					'net_key'				=> $this->encrypt->encode($net_key),
					'allow_payments'		=> $allow_payments == 'y'?'y':'n',
					'accept_cc'				=> $accept_cc == 'y'?'y':'n',
					'accept_echeck'			=> $accept_echeck == 'y'?'y':'n',
					'user_id'				=> $this->session->userdata('user_id'),
					'type'					=> $this->session->userdata('side'),
					'min_payment'			=> $min_payment == ''?'0':$min_payment,
					'credit_card_discount'	=> $credit_card_discount == ''?'0':$credit_card_discount,
					'e_check_discount'		=> $e_check_discount == ''?'0':$e_check_discount,
				);
		
				$result = $this->db->get_where('local_payment_settings', array('type'=>$this->session->userdata('side'), 'user_id'=>$this->session->userdata('user_id')));
				if($result->num_rows()>0) {
					$this->db->where('user_id', $this->session->userdata('user_id'));
					$this->db->where('type', $this->session->userdata('side'));
					$this->db->update('local_payment_settings', $settings);
				} else {
					$this->db->insert('local_payment_settings', $settings);
				}
				if($this->db->affected_rows()>0) {
					$this->session->set_flashdata('success', 'Settings saved successfully');
					return true;
				}
				$this->session->set_flashdata('error', 'Settings failed to save');
				return false;
			}
		}
		
		public function addOfflinePayment($data)
		{
			$this->form_validation->set_rules('firstname', 'First Name', 'max_length[40]|xss_clean|required');
			$this->form_validation->set_rules('lastname', 'Accept E-Check', 'max_length[40]|xss_clean|required');
			$this->form_validation->set_rules('amount', 'Amount', 'max_length[10]|xss_clean|required');
			$this->form_validation->set_rules('invoice_id', 'Invoice Id', 'max_length[40]|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'max_length[40]|xss_clean|valid_email');
			$this->form_validation->set_rules('note', 'Note', 'max_length[500]|xss_clean');
				
			if ($this->form_validation->run() == FALSE) {
				return array('error' => validation_errors('<span>','</span>'));
			} else {
				$invoice = array();
				if($data['invoice_id']) {
					$invoice = $this->getInvoiceByNum($data['invoice_id']);
					if(empty($invoice)) {
						return array('error' => 'Invalid Invoice Number');
						exit;
					}
				}
				
				if($this->logPayment($data, $invoice)) {
					$this->session->set_flashdata('success', 'Payment added successfully');
					return array('success'=>'Payment saved');
				} else {
					return array('error' => 'Problem saving payment, try again');
				}
			}
		}
		
		private function logPayment($data, $invoice)
		{		
			$this->db->insert('local_payments', 
				array(
					'firstname' => $data['firstname'],
					'lastname' => $data['lastname'],
					'email' => $data['email'],
					'user_id' => $this->session->userdata('user_id'),
					'trans_id' => '',
					'approval_id' => '',
					'note' => $data['note'],
					'type' => $this->session->userdata('side'),
					'invoice_id' => $invoice->id,
					'amount' => $data['amount'],
					'fee' => '',
					'discount' => '', 
				)
			);
			if($this->db->insert_id()>0) {
				return true;
			}
			return false;
		}
	
		public function getInvoiceByNum($invoice_num) 
		{
			$result = $this->db->get_where('invoices', array('user_id'=>$this->session->userdata('user_id'), 'type'=>strtolower($this->session->userdata('side')), 'invoice_num'=>$invoice_num));
			return $result->row();
		}
		
		
	}