<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class email_handler extends CI_Model {
		function Email_handler()
		{
			// Call the Model constructor
			parent::__construct();
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
						<td width="350px" align="left">
							<center>
								<a href="https://network4rentals.com"><img src="https://network4rentals.com/wp-content/uploads/2013/07/network_logo.png" border="0" width="300" alt="Network 4 Rentals"></a>
							</center>
						</td>
						<td width="400px" align="left">
							<FONT COLOR="#ffffff"><p><b>Improving Landlord &amp; Tenant Relations Nationwide</b></p></FONT>
						</td>
					</tr>
				</table>
				<table width="500px" cellpadding="10" bgcolor="#ffffff" align="left">
					<tr>
						<tdvalign="top" align="left">
							'.$message.'<br><br><h3>Unsure Of What To Do Next?</h3><p>If at any point you find yourself lost or unsure of what to do next there are plenty of resources available at your disposal our on <a href="https://network4rentals.com/fas/">faqs</a> page or our <a href="https://network4rentals.com/blog/">blog</a> page.</p>
						</td>
					</tr>
				</table>
			</center>
			</body>
			</html>';
			return $email_body;
		}		

		function sendEmail($email, $message, $subject)
		{
			
			$this->load->library('email');
			
			$config['mailtype'] = 'html';
			
			$this->email->initialize($config);
			$this->email->from('no-reply@network4rentals.com', 'No Reply');
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
}		