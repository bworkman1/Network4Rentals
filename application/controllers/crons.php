<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//if (php_sapi_name() !='cli') exit; //Make sure only cron jobs can run this

class Crons extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('cookie');
	
	} 
	
	// Sends out a rent reminder 5 business days prior to renters according to their due date they set in their account
	/*
		RUN THIS ONE EVERYDAY AROUND 9am EVERY 15 MINS - SERVER TIME IS AN HOUR BEHIND US 
		THIS WILL SEND AN EMAIL EVERY 15 MINS UNTIL IT CANNOT FIND ANYONE ELSE.
	*/
	function send_rent_reminders() {
		//if($this->input->is_cli_request()) {
			$this->load->model('renters/rent_reminder');
			$sent = $this->rent_reminder->send_rent_emails();
		//} else {
		//	echo 'Restricted Area';
		//}
	}
	
	function send_sms_rent_reminders() {
		if($this->input->is_cli_request()) {
			$this->load->model('renters/sms_handler');
			$this->sms_handler->send_sms_reminders();
		} else {
			echo 'Restricted Area';
		}
	}
	
	function reset_rent_reminders() //Set this to go off at midnight on server time 
	{
		//if($this->input->is_cli_request()) {
			$this->load->model('renters/rent_reminder');
			$this->rent_reminder->reset_rent_reminders();
		//}
	}
	
	//THIS WILL SEND AN EMAIL TO THE LANDLORD EVERYDAY THAT NOTIFIES THEM OF ALL THE PAYMENTS THEY HAVE COMING IN THAT DAY
	/*
		RUN THIS ONE EVERYDAY AROUND 6pm - SERVER TIME IS AN HOUR BEHIND US
	*/
	function batch_payment_results_landlord()
	{	
	
		//select and count how many landlords have payments coming to them today
		$sql = "SELECT landlord_id, Count(*) as payments FROM payment_history WHERE DATE(`last_updated`) = CURDATE() GROUP BY landlord_id"; 
		$results = $this->db->query($sql);
		if ($results->num_rows() > 0) {	
			$this->load->model('special/add_activity');
	
			foreach ($results->result() as $row) {
			
				$this->db->select('email, name');
				$info = $this->db->get_where('landlords', array('id'=>$row->landlord_id));
				$r = $info->row();
				$email = $r->email;
				$subject = 'You have '.$row->payments.' rental payments that processed today';
				$message = '<h2>'.$r->name.',</h2><p>You have '.$row->payments.' rental payments that have been updated or submitted through Network4Rentals.com today.</p><p>To view more details about these payments go to <a href="'.base_url().'landlords/payment-data/'.date('Y').'/'.date('m').'">'.base_url().'landlords/payment-data/</a>';
				
				$this->sendEmail($email, $message, $subject, $alt_message = null);
				$this->add_activity->add_new_activity('Rental Payments Processed Today', $row->landlord_id, 'landlords');
				
			}
		}
	}
	
	function email_format($message)
	{
		$email_body = '
		<html>
		<head>
		</head>
		<body>
		<center>
			<table width="100%" bgcolor="#428BCA" cellpadding="10">
				<tr>
					<td width="350px">
						<center>
							<a href="https://network4rentals.com"><img src="https://network4rentals.com/wp-content/uploads/2013/07/network_logo.png" width="300" alt="Network 4 Rentals"></a>
						</center>
					</td>
					<td width="400px" align="center">
						<FONT COLOR="#fff"><p><b>Improving Landlord &amp; Tenant Relations Nationwide</b></p></FONT>
					</td>
				</tr>
			</table>
			<table cellpadding="10" bgcolor="#ffffff">
				<tr>
					<td valign="top" align="left">
						'.$message.'<br><br><h3>Unsure Of What To Do Next?</h3><p>If at any point you find yourself lost or unsure of what to do next please check out the many resources available on the <a href="https://network4rentals.com/fas/">faqs</a> or blog <a href="https://network4rentals.com/blog/">blog</a> page or contact us <a href="http://network4rentals.com/help-support/">here</a>.</p>
					</td>
				</tr>
			</table>
		</center>
		</body>
		</html>';
		return $email_body;
	}	
	
	function sendEmail($email, $message, $subject, $alt_message = null)
	{
		$this->load->library('email');
		$config['mailtype'] = 'html';	
		$this->email->initialize($config);
		$this->email->from('no-reply@network4rentals.com', 'Network4Rentals');
		$this->email->to($email);   
	
		$this->email->subject($subject);
		$message = $this->email_format($message);
		$this->email->message($message);	
		
		

		if($this->email->send()) {
			return true;
		} else {
			return false;
		}
		
	}	
	
	function sendEmailBBC($emails, $message, $subject)
	{
		$this->load->library('email');
		$config['mailtype'] = 'html';	
		$this->email->initialize($config);
		$this->email->from('no-reply@network4rentals.com', 'Network4Rentals');
		$this->email->to($email);   
	
		$this->email->subject($subject);
		$message = $this->email_format($message);
		$this->email->message($message);	
		
		if($this->email->send()) {
			return true;
		} else {
			return false;
		}
	}	
	
	function check_listings_for_match_iso()
	{
		//if($this->input->is_cli_request()) {
			$this->load->model('renters/iso');
			$this->iso->check_matches();
		//}
	}
	
	function reset_sent_iso_emails()
	{
		//if($this->input->is_cli_request()) {
			$this->load->model('renters/iso');
			$this->iso->reset_iso();
		//}
	} 
	
	function send_pm_reminders_cron()
	{
		$this->load->model('landlords/service_request_handler');
		$this->service_request_handler->send_pm_reminders();
	}
	
	function reset_pm_reminders_cron()
	{
		$this->load->model('landlords/service_request_handler');
		$this->service_request_handler->reset_pm_reminders();
	}	
	
	public function check_old_listings()
	{
		$this->load->model('landlords/listings_handler');
		$this->listings_handler->remove_old_listings();
	}
	
}