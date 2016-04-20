<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
				
	class Send_email extends CI_Model {
		
		// Call the Model constructor
		function send_email()
		{		
			parent::__construct();
		}
		
		public function sendEmail($email = null, $message, $subject, $alt_message = null, $emailArray = null, $file = null)
		{
			$this->load->library('email');
			$config['mailtype'] = 'html';	
			$config['smtp_host'] = 'smtp.gmail.com';
			$config['smtp_user'] = 'No-reply@network4rentals.com';
			$config['smtp_pass'] = 'Nv#fg83PLk3';
			$config['smtp_port'] = '587';
			
			$this->email->initialize($config);
			$this->email->from('no-reply@network4rentals.com', 'N4RLocal');
			if(!empty($email)) {
				$this->email->to($email);  
			} elseif(!empty($emailArray)) {
				$this->email->to('no-reply@network4rentals.com'); 
				$this->email->bcc($emailArray);
				$config['bcc_batch_mode'] = true;
			} else { 
				return false;
			}
		
			
			
			$this->email->subject($subject);
			$message = $this->email_format($message);
			$this->email->message($message);			
			if(!empty($file)) {	
				//$this->email->attach($file);
			}
			
			
			
			if($this->email->send()) {
				
				return true;
			} else {
				return false;
			}
			
		}	
		
		private function email_format($message)
		{
			$email_body = '
			<html>
			<head>
			</head>
			<body>
			<center>
				
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
		
		
		public function send_data_message($num, $msg)
		{			
			$this->load->library('plivo');
			$sms_data = array(
				'src' => '13525593099',
				'dst' => '1'.$num,
				'text' => $msg,
				'type' => 'sms', 
				'url' => '',
				'method' => 'POST',
			);

			$response_array = $this->plivo->send_sms($sms_data);
			if ($response_array[0] == '200' || $response_array[0] == '202') {
				return true;
			} else {
				return false;
			}
		}
	}