<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Ad_handler extends CI_Model {
		
		public $addZipsError;
		public $deactive;
		private $adIds;
		
        public function __construct() {

            parent::__construct();

        }
	
		function get_my_zips()
		{
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'advertiser'));
			if($results->num_rows()>0) {
				$row = $results->row();
				$visits = $row->visits;
			} else {
				$visits = 0;
			}
				
			$results = $this->db->get_where('advertiser_zips', array('advertiser_id'=>$this->session->userdata('user_id'), 'active'=>'y'));
			if($results->num_rows()>0) {
				$results = $results->result();
				for($i=0;$i<count($results);$i++) {
					$query = $this->db->get_where('zips', array('zipCode'=>$results[$i]->zip_purchased));
					$r = $query->row();
					$results[$i]->city = $r->city;
					$results[$i]->state = $r->stateAbv;
					$results[$i]->price = $r->contractor_price;
					$results[$i]->visits = $visits;
					$this->db->select('id');
					$r = $this->db->get_where('advertiser_ads', array('ref_id'=>$results[$i]->id));
					if($r->num_rows()>0) {
						$results[$i]->created = 'y'; 
					} else {
						$results[$i]->created = 'n';
					}
				}
				return $results;
			} else {
				return false;
			}
			
		}
		
		public function check_public_page()
		{
			$this->db->select('id');
			$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'advertiser'));
			if($query->num_rows()>0) {
				return '1';
			} else {
				return '2';
			}
		}
		
		public function public_link($id, $ad_id) 
		{
			$this->db->select('unique_name');
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$id, 'type'=>'advertiser', 'active'=>'y'));
			if($results->num_rows()>0) {
				$row = $results->row();
				$link = 'http://n4r.rentals/'.$row->unique_name;
				$results = $this->db->get_where('advertiser_zips', array('id'=>$ad_id, 'active'=>'y'));
				$row = $results->row();
				$clicks = $row->clicks+1;
				$this->db->where('id', $ad_id);
				$this->db->update('advertiser_zips', array('clicks'=>$clicks));
				
				return $link;
			} else {
				return false;
			}
		}
		
		public function checkAvaliablity($type, $zip) 
		{
			if($type=='Landlords') {
				$service = '1';
			} elseif($type=='Renters') {
				$service = '2';
			} elseif($type=='Contractors') {
				$service = '3';
			} elseif($type=='Advertisers') {
				$service = '4';
			} else {
				return array('error'=>'Invalid user type');
			}
			$query = $this->db->get_where('advertiser_zips', array('zip_purchased'=>$zip, 'service_purchased'=>$service));
			if($query->num_rows()>2) {
				return array('error' => 'There are already 3 advertisers in this zip');
			}
			
			if($query->num_rows()>0) {
				foreach($query->result() as $row) {
					if($row->advertiser_id == $this->session->userdata('user_id')) {
						return array('error' => 'You already advertise in this zip code');
						exit;
					}
				}
			}
			
			return array('success' => 'Add');
		}
		
		public function validatePayment($values, $userAmount, $length) //returns true or false ?? is the payment info valid values
		{
			/* values will contain an array of values that look like this 43055|Renters */
			$items = array();
			$isValid = true;
			$count = 0;
			foreach($values as $val) {
				$count++;
				$valArray = explode('|', $val);
				$data = $this->checkAvaliablity($valArray[1], $valArray[0]);
				if(isset($data['error'])) {
					$isValid = false;
					return false;
				}
			}
			
			if($isValid) {
				$adminSettings = $this->getMonthlyCost();
				$total = number_format(($adminSettings->setting_value*count($values))*$length, 2);
				if($total != $userAmount) {
					return false;
				}
				return true;
			}
			return false;
		}
		
		public function getMonthlyCost() 
		{
			$query = $this->db->get_where('admin_settings', array('setting_key' => 'additional_advertising'));
			return $query->row();
		}
		
		public function addPurchasedZipCodes($selections, $length)
		{			
			$inserts = count($selections);
			$today = date('Y-m-d');
			$this->deactive = date('m-d-Y', strtotime('+'.$length.' months', strtotime($today)));
			$count = 0;
			$inserted = true;
			$insertIds = array();
			
			foreach($selections as $val) {
				$count++;
				$selectedArray = explode('|', $val);
				$zip = $selectedArray[0];
				$side = $this->sideToNumber($selectedArray[1]);
				
				$data = array(
					'advertiser_id' => $this->session->userdata('user_id'),
					'zip_purchased' => $zip,
					'service_purchased' => $side,
					'active_date' => $today,
					'deactivation_date' => $this->deactive
				);
				$this->db->insert('advertiser_zips', $data);
				
				if($this->db->affected_rows()==0) {
					$this->addZipsError = 'One or more of your ads was not created, contact support for further help';
					$inserted = false;
				} else {
					$insertIds[] = $this->db->insert_id();
				}
				
				if($count>50) {
					return false;
				}
			}
			
			$this->adIds = $insertIds;
			return $inserted;
		
		}
		
		private function sideToNumber($side) 
		{
			$side = strtolower($side);
			switch ($side) {
				case 'renters':
					return '2';
					break;
				case 'contractors':
					return '3';
					break;
				case 'landlords':
					return '1';
					break;
				case 'advertisers': 
					return '4';
					break;
				default:
					return '1';
					break;
			}
			
		}
	
		public function formatOptions($options)
		{
			$string = '';
			foreach($options as $val) {
				$optionArray = explode('|', $val);
				$zip = $optionArray[0];
				$side = $this->sideToNumber($optionArray[1]);
				$string .= $zip.'-'.$side.'|';
			}
			$string = rtrim($string, '|');
			return $string;
		}
		
		public function validateExtendPayment($extend, $amount, $length)
		{
			foreach($extend as $val) {
				$this->db->or_where('id', $val);
			}
			$this->db->where('advertiser_id', $this->session->userdata('user_id'));
			$this->db->from('advertiser_zips');
			$count = $this->db->count_all_results();
			
			if($count != count($extend)) {	
				return false;
			}
			
			$cost = $this->getMonthlyCost();			
			$ourTotal = number_format(($cost->setting_value*$count)*$length, 2);

			if($amount != $ourTotal) {
				return false;
			}
			return true;				
		}
		
		public function updateExperationDate($ad_ids, $length) 
		{
			$this->db->select('id, deactivation_date');
			foreach($ad_ids as $val) {
				$this->db->or_where('id', $val);
			}
			$this->db->where('advertiser_id', $this->session->userdata('user_id'));
			$query = $this->db->get('advertiser_zips');
			
			foreach ($query->result() as $row) {				
				$this->deactive =  date('Y-m-d', strtotime('+'.$length.' months', strtotime($row->deactivation_date)));
				$this->db->where('id', $row->id);
				$this->db->where('advertiser_id', $this->session->userdata('user_id'));
				$this->db->update('advertiser_zips', array('deactivation_date'=>$this->deactive));
			}
		}
		
		public function sendUserEmail($creditCardDetails, $details, $options, $intro) {
			
			$message = '<h3>'.$creditCardDetails['x_first_name'].' '.$creditCardDetails['x_last_name'].'</h3>
							'.$intro.'
							<h4>Premium Purchase Details</h4>
							<table cellpadding="4" width="60%" align="left">
								<tr>
									<td><b>Name:</b></td>
									<td>'.$creditCardDetails['x_first_name'].' '.$creditCardDetails['x_last_name'].'</td>
								</tr>
								<tr>
									<td><b>Date:</b></td>
									<td>'.date('m-d-Y').'</td>
								</tr>
							</table>
							<table cellpadding="4" width="39%" align="right">
								<tr>
									<td align="right"><b>Premium Ads Purchased:</b></td>
									<td>'.count($options).'</td>
								</tr>
								<tr>
									<td align="right"><b>Duration:</b></td>
									<td>'.$details['frequency'].' months</td>
								</tr>
								<tr>
									<td align="right"><b>Premium Ad Expires:</b></td>
									<td>'.$this->deactive.'</td>
								</tr>
								
								<tr>
									<td align="right"><b>Total:</b></td>
									<td>$'.number_format($details['payment_amount'], 2).'</td>
								</tr>
							</table>
							<table width="100%">
							<tr>
								<td><h3>Build Your Premium Ad</h3>
							<p>You may have already followed the websites request and created your premium ads. If not please do so as soon as possible so that you are taking full advantage of your premium ad space. To edit your premium ads go to <a href="https://network4rentals.com/network/local-partner/my-zips">https://network4rentals.com/network/local-partner/my-zips</a>.</p>
								</td>
							</tr>
							</table>';
							$mssage .= '<br><br><small>"Network4Rentals.com premium ad operates on a '.$details['frequency'].' months duration. And your premium add is schudeled to run until '.$this->deactive.'."</small>';
			
			$this->load->model('special/send_email');
			$subject = 'N4R Premium Ad Purchase';
			$this->send_email->sendEmail($this->session->userdata('email'), $message, $subject);
		}
		
		
    }
