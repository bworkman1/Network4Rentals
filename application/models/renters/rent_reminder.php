<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Rent_reminder extends CI_Model {
		
		function Rent_reminder()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		public function reset_rent_reminders()
		{
			$this->db->update('renters', array('received_rent_reminder'=>'n'));
		}
		
		public function send_rent_emails()
		{
			$curdate = date('Y-m-d');
			$mydate=getdate(strtotime($curdate));
			switch($mydate['wday']){
				case 0: // sun
					$days = 6;
					break;
				case 1: // mon
					$days = 7;
					break;
				case 2: // tues  
					$days = 7;
					break;
				case 3: // wed
					$days = 7;
					break;
				case 4: // thur
					$days = 7;
					break;
				case 5: // fri
					$days = 7;
					break;
				case 6: // sat
					$days = 6;
					break;
			}
			$rentDate = date('d', strtotime("$curdate +$days days"));
		
			$this->db->limit(20);
			$this->db->select('renter_history.id, renter_history.tenant_id, renter_history.day_rent_due, renters.email, renters.name, renters.cell_phone, renters.sms_msgs');
			$this->db->join('renters', 'renter_history.tenant_id = renters.id');
			$this->db->where('renter_history.day_rent_due', $rentDate);
			$this->db->where('renter_history.current_residence', 'y');
			$this->db->where('renters.received_rent_reminder', 'n');
			$results = $this->db->get('renter_history');
			if($results->num_rows()>0) {
				$count = 0;
				foreach($results->result() as $row) {
					$count++;
					if(!empty($row->email)) {
						$this->db->limit(1);
						$this->db->where('id', $row->tenant_id);
						$row->update = 'yes';
						$this->db->update('renters', array('received_rent_reminder'=>'y'));
						
						$email = $row->email;
						$name_array = explode(' ', $row->name);
						
						
						$num = $row->day_rent_due % 100; // protect against large numbers
						if($num < 11 || $num > 13) {
							switch($num % 10) {
								case 1: $suffix = 'st';
								case 2: $suffix = 'nd';
								case 3: $suffix = 'rd';
							}
						} else {
							$suffix = 'th';
						}
						
						$message = '<h2>Hello '.$name_array[0].',</h2><p>Network4Rentals would like to remind you that your rent is coming due in 5 business days. Below are two links for your convenience. If you are or have paid your rent with cash, check, or money order you can click on the "cash/check" link and record that payment for your own records (This will help protect you from any payment disputes). If your landlord has authorized online payment functions you can click on "Pay Online" link and make your rent payment electronically.</p><p><b>To pay your rent online:</b> <a href="'.base_url().'renters/pay-rent">'.base_url().'renters/pay-rent</a></p><p><b>To record your cash/check rent payments:</b> <a href="'.base_url().'renters/rent-receipt">'.base_url().'renters/rent-receipt</a></p>';
						$subject = 'Rent Reminder';
						$this->load->model('special/send_email');
						if($this->send_email->sendEmail($email, $message, $subject)) {
							//SEND REMINDER TO ACTIVITY PAGE
							$this->load->model('special/add_activity');
							$action = 'Reminder, Your rent is due soon';
							$user_id = $row->tenant_id;
							$type = 'renters';
							$this->add_activity->add_new_activity($action, $user_id, $type);
						
							$this->db->limit(1);
							$this->db->where('id', $row->tenant_id);
							$row->update = 'yes';
							$this->db->update('renters', array('received_rent_reminder'=>'y'));
							
							if(!empty($row->cell_phone) && !empty($row->sms_msgs)) {
								$msg = 'This is a reminder from Network4Rentals that your rent is coming in 5 business days '.base_url('renters/rent-receipt');
								$this->send_data_message($row->cell_phone, $msg);
							}
							echo 'Email Sent To: '.$email.'<br>';
						}
						
					}
				}
			} else {
				$num = $rentDate%100; // protect against large numbers
				if($num < 11 || $num > 13) {
					switch($num % 10) {
						case 1: $suffix = 'st';
						case 2: $suffix = 'nd';
						case 3: $suffix = 'rd';
					}
				} else {
					$suffix = 'th';
				}
				echo $suffix;
				echo 'No Rent Payments Due On The '.$rentDate.''.$suffix;
			}
		}
		
		function send_data_message($num, $msg)
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