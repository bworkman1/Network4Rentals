<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Create_user_handler extends CI_Model {
		
		public $userId;
		
        public function __construct() {

            parent::__construct();

        }
		
		public function createUserAccount($data)
		{
			$data['active'] = 'y';
			$this->db->insert('advertisers', $data);
			$this->userId = $this->db->insert_id();
			if($this->userId>0) {
				return $this->userId;
			}
			return false;
		}
		
		public function check_username_avaliable($user)
		{
			$query = $this->db->get_where('advertisers', array('user'=>$user));
			if ($query->num_rows() > 0) {
				echo '0';
			} else {
				echo '1';
			}
		}

		public function check_email_avaliable($email)
		{
			$query = $this->db->get_where('advertisers', array('email'=>$email));
			if ($query->num_rows() > 0) {
				echo '0';
			} else {
				echo '1';
			}
		}
		
		public function sendWelcomeEmail($data) {
			$subject = 'Thanks for joining N4R | Account Details';
			$message = '<h3>'.$data['f_name'].'</h3>
					<p>Thank you for becoming a sponsored Network4Rentals local partner. This is to confirm that your credit card payment for your account has been authorized and processed. The details of the transaction are below.</p>
					<h4>Business Info</h4>
					<table cellpadding="4" width="60%" align="left">
						<tr>
							<td><b>Company Name:</b></td>
							<td>'.$data['bName'].'</td>
						</tr>
						<tr>
							<td><b>Contact Name:</b></td>
							<td>'.$data['f_name'].' '.$data['l_name'].'</td>
						</tr>
						<tr>
							<td><b>Email/Username:</b></td>
							<td>'.$data['email'].'</td>
						</tr>
						<tr>
							<td><b>Date:</b></td>
							<td>'.date('m-d-Y').'</td>
						</tr>
					</table>	
					<table cellpadding="4" width="39%" align="right">
						<tr>
							<td align="right"><b>Billing Cycle:</b></td>
							<td>'.$data['freq'].'</td>
						</tr>	
						<tr>
							<td align="right"><b>Cost Per Billing Cycle:</b></td>
							<td>$'.$data['amount'].'</td>
						</tr>
						<tr>
							<td align="right"><b>Subscription Term:</b></td>
							<td>1 Year</td>
						</tr>
					</table>		
					<table width="100%">
					<tr>
						<td><h3>Build Your Post</h3>
					<p>You may have already followed the websites request and created your web site so that your ads start displaying on the sidebar of the website. If not please do so as soon as possible so that you are taking full advantage of your sponsored space. To edit your public web page go to <a href="https://network4rentals.com/network/local-partner/public-page-settings">https://network4rentals.com/network/local-partner/public-page-settings</a></p>
						</td>
					</tr>
					</table>';
					
					$message .= '<br><br><small>"Network4Rentals.com operates on a 1year subscription basis. All accounts not cancelled in writing at least 30 days prior to the subscription renewal date will have their subscription automatically renewed and be billed as per their previously selected billing terms."</small>';
			
			$this->load->model('special/send_email');
			$this->send_email->sendEmail($email, $message, $subject);
		}
		
		public function checkPromoCode($promo) 
		{
			$query = $this->db->get_where('promo_codes', array('code' => $promo, 'user_type' => 'partner'));
			return $query->row();
		}
		
    }


