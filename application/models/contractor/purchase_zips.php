<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Purchase_zips extends CI_Model {	
			
        public function __construct()
		{
            parent::__construct();
        }
		
		public function purchase($data)
		{
			//Check to make sure that zips are selected and prices match what the user was shown
			$feed = $this->check_amount($data['total'], $data['frequency']);
			if($feed !== true) {
				return $feed;
				exit;
			} else {
				$userData = $this->get_user_email();
				$data['email'] = $userData->email;
				$data['sub_id'] = $userData->sub_id;
				if(!empty($data['email'])) {
					//Process the payment
					$feed = $this->process_payment($data);
					if(isset($feed['error'])) {
						return $feed;
					} else {
						$data['trans_id'] = $feed[0];
						//Payment Successful Log Payment
						$this->log_payment($data);
						
						//Add zip codes to users account
						$this->add_zips_to_account($data);
						$this->load->model('contractor/activity_handler');
						$activity = 'Purchased ads for a total of '.$data['total'];
						$this->activity_handler->insert_activity(array('action'=>$activity,'action_id'=>''));
						
						$this->session->set_flashdata('success', 'Your payment has been processed and the zips have been added to your account. You can now build out your ads by clicking the gear icons below.');
						return array('success'=>'payment successful');
					}
				} else {
					$feed = array('error'=>'No email could be found on your record, contact support to complete your purchase. No charges were made to your account');
				}
				
			}
			return $feed;
			
		}
		
		private function add_zips_to_account($data) 
		{
			$zips = $this->session->userdata('ad_zips');
			$service = $this->session->userdata('ad_service');
			if($data['frequency'] == 1) {
				$freq = 3;
			} else if($data['frequency'] == 2) {
				$freq = 6;
			} else if($data['frequency'] == 4){
				$freq = 12;
			}
			$q = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
			$row = $q->row();
			
			$deactiveDate = date('Y-m-d', strtotime('+'.$freq.' months'));
			for($i=0;$i<count($zips);$i++) {
				$d = array(
					'zip'=>$zips[$i],
					'contractor_id' =>$this->session->userdata('user_id'),
					'service_type' =>$service[$i],
					'sub_id' => $data['sub_id'],
					'trans_id' => $data['trans_id'], //ADD THIS 
					'active' =>'y',
					'deactivation_date' => $deactiveDate,
					'purchased'=>'y',
					'bName' => $row->bName,
					'title' => $row->bName,
					'phone' => $row->phone,
				);
				
				$this->db->select('id');
				$current_ads = $this->db->get_where('contractor_zip_codes', array('contractor_id'=>$this->session->userdata('user_id'), 'service_type'=>$service[$i], 'zip'=>$zips[$i]));
				
				if($current_ads->num_rows()>0) {
					$r = $current_ads->row();
					$this->db->where('id', $r->id);
					$this->db->update('contractor_zip_codes', $d);
				} else {
					$this->db->insert('contractor_zip_codes', $d);
				}
				
				
				
			}
			$this->session->unset_userdata('ad_zips');
			$this->session->unset_userdata('ad_state');
			$this->session->unset_userdata('ad_service');
			$this->session->unset_userdata('ad_city');
		}
		
		private function log_payment($data) 
		{
			// Log the details of the contractor payment
			$zips = $this->session->userdata('ad_zips');
			$service = $this->session->userdata('ad_service');
			$zip_codes = '';
			$service_types = '';
			for($i=0;$i<count($zips);$i++) {
				$zip_codes .= $zips[$i].'|';
				$service_types .= $service[$i].'|';
			}
			$zip_codes = rtrim($zip_codes, '|');
			$service_types = rtrim($service_types, '|');
			$info = array(
				'contractor_id' => $this->session->userdata('user_id'),
				'baddress' => $data['baddress'],
				'bcity' => $data['bcity'],
				'bstate' => $data['bstate'],
				'bzip' => $data['bzip'],
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				'total' => $data['total'],
				'zips' => $zip_codes,
				'service_type' => $service_types,
				'frequency' => $data['frequency'],
				'trans_id' => $data['trans_id'],
			);
			$this->db->insert('contractor_purchases', $info);
		}
		
		private function process_payment($data)
		{
			$this->load->library('auth/authorize_net');
			$auth_net = array(
				'x_card_num'			=> $data['credit_card'], // Visa
				'x_exp_date'			=> $data['exp_month'].'/'.$data['exp_year'],
				'x_card_code'			=> $data['ccv'],
				'x_description'			=> 'Contractor Ad Space',
				'x_amount'				=> $data['total'],
				'x_first_name'			=> $data['first_name'],
				'x_last_name'			=> $data['last_name'],
				'x_address'				=> $data['baddress'],
				'x_city'				=> $data['bcity'],
				'x_state'				=> $data['bstate'],
				'x_zip'					=> $data['bzip'],
				'x_country'				=> 'US',
				'x_email'				=> $data['email'],
				'x_customer_ip'			=> $this->input->ip_address()				
			);

			$this->authorize_net->setData($auth_net);
			// Try to AUTH_CAPTURE
			if( $this->authorize_net->authorizeAndCapture() ) {
				$tranId = $this->authorize_net->getTransactionId();
				$appCode = $this->authorize_net->getApprovalCode();
				return array($tranId, $appCode);
			} else {
				return array('error'=>$this->authorize_net->getError());
			}
			
		} // EOF
		
		private function check_amount($price, $frequency)
		{
			$base_price = 19.99;
			$zips = $this->session->userdata('ad_zips');
			$service = $this->session->userdata('ad_service');
			if(count($zips)==0) {
				return array('error'=>'You must select some zip codes before purchasing');
			} else {
				if(count($zips) !== count($service)) {
					return array('error'=>'Zips and service count do not match');
				} else {
					$totalZips = count($zips);
					if($frequency == 4) {
						$discount = .8;
					} elseif($frequency == 2) {
						$discount = .9;
					} else {
						$discount = 0;
					}
					if(!empty($discount)) {
						$checkTotal = (($totalZips*$base_price)*$frequency)*$discount;
						$checkTotal = number_format($checkTotal, 2);
					} else {
						$checkTotal = ($totalZips*$base_price)*3;
						$checkTotal = number_format($checkTotal, 2);
					}
					
					if($checkTotal!=$price) {
						return array('error'=>'The price we have doesn\'t match what is shown. Try removing your zip codes and adding them again to fix the problem. '.$checkTotal.' = '.$price.' | '.$totalZips);
					} else {
						return true;
					}
				}
			}
		} //EOF
		
		private function get_user_email()
		{
			$this->db->select('email, sub_id');
			$result = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
			if($result->num_rows()>0) {
				return $result->row();
			} else {
				return false;
			}
			
		}
		

	}
	