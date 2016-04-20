<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
    class Process_payment extends CI_Model {
		
		public $amount;
		public $freq;
		public $promoDiscount = 0;
		public $amoutIsValid = false;
		public $subscription_id;
		public $promoCode;
		public $total = 0;
		public $authName = 'Local Partner Account 2nd Year Subscription';
		public $trans_id;
		public $approval_code;
		public $billing_name;
		public $paymentError;
		
		
		/*			
			REQUIRED VALUES PASSED IN THROUGH DATA ASSOC ARRAY
			first_name, last_name, amount, credit_card, zip, ccv, credit_card_month, credit_card_year
			
			OPTIONAL:
			promo
			
			FUNCTION checkPaymentAmount ASSUMES admin_settings table will never change the setting_key or add more. If you need to add more payment options you have to remove the else if statements in taht function.
		*/
		
        public function __construct() 
		{
            parent::__construct();
        }
		
		
		public function setAmount($amount)
		{
			$this->load->model('special/admin_settings');
			$retrieve = array('local_partner_monthly_payment', 'local_partner_quarterly_payment', 'local_partner_yearly_payment');
			$settings =$this->admin_settings->getAdminSettings($retrieve);
			
			$options = array();
			if(!empty($settings)) {
				foreach($settings as $key => $val) {
					if($val->setting_value == $amount) {
						$this->amoutIsValid = true;
						$this->amount = $amount;
						$this->billing_name = $val->label;
						if($key==2) {
							$this->freq = 12;
						} elseif($key==1) {
							$this->freq = 6;
						} else {
							$this->freq = 3;
						}
					}
				}
				return true;
			}
			return false;
		}
		
		public function setPromoCode($code)
		{
			$this->promoCode = $code;
			$this->load->model('special/promo_codes');
			$row = $this->promo_codes->checkPromoCode($code, 'partner');
			if($row) {
				$this->promoDiscount = $row->percent;
			}
		}
		
		public function calculateNewTotal()
		{
			$total = $this->amount;
			if(!empty($this->promoDiscount)) {
				$promoDiscount = ($total / 100) * $this->promoDiscount;
				$total = number_format(($total-$promoDiscount), 2);
			}
			$this->total = number_format($total, 2);
		}
		
		public function processInitialPayment($creditCard)
		{			
			$this->load->library('auth/authorize_net');
			$this->authorize_net->setData($creditCard);
	
			if($this->authorize_net->authorizeAndCapture()) {
				$this->trans_id = $this->authorize_net->getTransactionId();
				$this->approval_code = $this->authorize_net->getApprovalCode();
				
				return true;
			} else {
				$this->paymentError = $this->authorize_net->getError();
				return false;
			}
		}
		
		public function setUpSubscription($paymentDetails) 
		{
			$this->load->library('authorize_arb');
			$this->authorize_arb->startData('create');
			$subscription_data = array(
				'name' => $this->authName,
				'paymentSchedule' => array(
					'interval' => array(
						'length' => $this->freq,
						'unit' => 'months',
						),
					'startDate' => date('Y-m-d', strtotime("+".$this->freq." month")),
					'totalOccurrences' => 9999, // Unlimited
					),
				'amount' => $this->amount,
				'payment' => array(
					'creditCard' => array(
						'cardNumber' => $paymentDetails['x_card_num'],
						'expirationDate' => str_replace('/', '-', $paymentDetails['x_exp_date']),
						'cardCode' => $paymentDetails['x_card_code'],
						),
					),
				'billTo' => array(
					'firstName' => $paymentDetails['x_first_name'],
					'lastName' => $paymentDetails['x_last_name'],
					'zip' => $paymentDetails['x_zip'],
					'country' => 'US',
					),
			);			
		
			$this->authorize_arb->addData('subscription', $subscription_data);
			if($this->authorize_arb->send()) {
				
				/*$logData = array(
					'user_id' 			=> $data['id'],
					'amount'			=> $data['payment_amount'],
					'type'				=> $data['type'],
					'payment_id'		=> $data['payment_id'],
					'options'			=> $data['options'],
					'payment_frequency'	=> $data['frequency'],
					'sub_id'			=> $data['sub_id'],
					'expires'			=> $data['expires'],
					'last_4'			=> $data['last_4']
				);*/
				//$this->log_payment($logData);
				$this->subscription_id = $this->authorize_arb->getId();
				return true;
				
			} else {
				return $this->authorize_arb->getError();
			}
			
		} 
		
		public function log_payment($data)
		{
			$log = array(
				'user_id' 			=> $data['id'],
				'amount'			=> $data['payment_amount'],
				'type'				=> $data['type'],
				'payment_id'		=> $data['payment_id'],
				'options'			=> $data['options'],
				'payment_frequency'	=> $data['frequency'],
				'sub_id'			=> $data['sub_id'],
				'expires'			=> $data['expires'],
				'last_4'			=> $data['last_4'],
				'affiliate_id' 		=> $data['affiliate_id']
			);
			$query = $this->db->insert('payments', $log);

			if($this->db->insert_id()>0) {
				return true;
			} else {
				return false;
			}
		}
	
		
		
		
		
    }


