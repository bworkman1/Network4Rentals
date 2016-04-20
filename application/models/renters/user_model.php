<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class User_model extends CI_Model {
	
		function User_model()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function check_login($username, $password)
		{
			$md5_password = md5($password);
			if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
				$column = 'user';
			} else {
				$column = 'email';
			}
			
			$query = $this->db->get_where('renters', array(
				$column => $username,
				'pwd' => $md5_password
			));
			
			if($query->num_rows()>0) {
				$userData = $query->row();
			} else {
				return false;
			}
			return $userData;
		}
		
		function get_users_details($id = NULL) 
		{
			if(empty($id)) {
				$id = $this->session->userdata('user_id');
			}
			$query = $this->db->get_where('renters', array('id' => $id));
			foreach ($query->result_array() as $row) {
				$data[] = $row;
			}
			$query = $this->db->get_where('renter_history', array('tenant_id' => $id, 'current_residence' => 'y'));
			foreach ($query->result_array() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		
		public function getUsersFullName($id) 
		{
			if($id>0) {
				$query = $this->db->get_where('renters', array('id'=>$id));
				$data = $query->row();
				return $data->name;
			}
		}

        public function getCords($zip)
        {
            $this->db->where('latitude !=', '');
            $query = $this->db->get_where('zips', array('zipCode'=>$zip));
            $row = $query->row();
            if(!empty($row->latitude) && !empty($row->longitude)) {
                $lat = $row->latitude;
                $lng = $row->longitude;
            } else {
                $lat = '40.079117';
                $lng = '-82.400543';
            }
            return array(
                'lat' => $lat,
                'lng' => $lng,
            );
        }

		function get_current_landlord_info() 
		{
			$sql = "SELECT id, checklist_id, payments, min_payment, address_locked, link_id, group_id, payments_allowed, partial_payments FROM renter_history WHERE current_residence = 'y' AND tenant_id = ?  LIMIT 1";
			$result = $this->db->query($sql, array($this->session->userdata('user_id')));
			if ($result->num_rows() > 0) {
				$row = $result->row_array();

				$ref_id = $row['id'];
				$info = $this->get_landlords_info($row['link_id']);
				$groupId = $row['group_id'];
				$locked = $row['address_locked'];
				$info['ref_id'] = $ref_id;
				$info['checklist_id'] = $row['checklist_id'];
				$info['row_id'] = $row['id'];
				$info['payments_allowed'] = $row['payments_allowed'];
				$info['partial_payments'] = $row['partial_payments'];
				$info['min_payment'] = $row['min_payment'];
				$info['payments'] = $row['payments'];

				if(!empty($groupId)) {
					
					$sql = "SELECT sub_b_name, sub_admins FROM admin_groups WHERE id = ? LIMIT 1";
					$result = $this->db->query($sql, array($groupId));
					$row = $result->row_array();
					$info = $this->get_landlords_info($row['sub_admins']);
					$info['bName'] = $row['sub_b_name'];
					$info['groupId'] = $groupId;
				}
				$info['address_locked'] = $locked;
				return $info;
			} else {
				return false;
			}
		}
		
		function get_landlords_info($id) 
		{
			$this->db->select('id, user, email, bName, name, address, city, state, zip, phone, sign_up,	forwarding_email, alt_phone, cell');
			$result = $this->db->get_where('landlords', array('id'=>$id));
			if ($result->num_rows() > 0) 
			{
				$row = $result->row_array(); 
				return $row;
			} else {
				return false;
			}
		}
			
		function get_landlords_info_unlinked($id) 
		{
			$sql = "SELECT id, landlord_email, bName, landlord_name, landlord_address, landlord_city, state, zip, landlord_phone, cell FROM renter_history WHERE id = ? LIMIT 1";
			$result = $this->db->query($sql, array($id));
			if ($result->num_rows() > 0) 
			{
				$row = $result->row_array(); 
			}
			return $row;
		}
		
		function set_landlord_through_bName($bName) 
		{
			$sql = "SELECT id, email FROM landlords WHERE bName = ? LIMIT 1";
			$result = $this->db->query($sql, array($bName));
			if($result->num_rows() == 1) { 
				$row = $result->row_array();
				$id = $row['id'];
				$email = $row['email'];
				$new_landlord_ids = $this->add_to_all_landlords($id);
				if($new_landlord_ids != false)
				{
					$sql = "UPDATE renters SET all_landlords = ?, landlordEmail = ? WHERE id = ? LIMIT 1";
					$result = $this->db->query($sql, array($new_landlord_ids, $email, $this->session->userdata('user_id')));
					if($result) {
						return true;
					} else {
						return false;
					}
				}
				else 
				{
					return false;
				}
			} 
			else 
			{
				return FALSE;
			}
		}
		
		function get_sub_group_bname($group_id)
		{
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) { 
				$row = $query->row_array(); 
				return $row['sub_b_name'];
			} else {
				return false;
			}
		}
		
		function set_landlord_email($email) 
		{
			$sql = "SELECT id FROM landlords WHERE email = ? LIMIT 1";
			$result = $this->db->query($sql, array($email));
			if($result->num_rows() == 1) {
				$row = $result->row_array();
				$id = $row['id'];
				$new_landlord_ids = $this->add_to_all_landlords($id);
			}
			$sql = "UPDATE renters SET landlordEmail = ? WHERE id = ? LIMIT 1";
			$result = $this->db->query($sql, array($email, $this->session->userdata('user_id')));
			if($result) {
				return true;
			} else {
				return false;
			}
		}
		
		function add_activity($data)
		{
			$data['created'] = date('Y-m-d H:i:s');
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$this->db->insert('activity', $data); 
		}
		
		function add_activity_feed($action, $action_id, $message_from, $user = NULL, $type) 
		{
			$created = date('Y-m-d H:i:s');
			$ip = $_SERVER['REMOTE_ADDR'];
			
			if(empty($user)) {
				$user = $this->session->userdata('username');
			}
			$sql = "INSERT INTO activity (action, created, user, type ,whereAt, action_id,	message_from) VALUES (?, ?, ?, ?, ?, ?, ?)";
			$result = $this->db->query($sql, array($action, $created, $user, $type, $ip, $action_id, $message_from));
		}
		
		function get_username_from_email($email)
		{
			$query_str = "SELECT user FROM renters WHERE email = ? LIMIT 1";
			$result = $this->db->query($query_str, array($email));
			if($result->num_rows() > 0)
			{
				$row = $result->row_array();
			}
			return $row['user'];
		}
		
		function show_my_rental_history() 
		{
			$sql = "SELECT * FROM renter_history WHERE tenant_id = ? ORDER BY current_residence DESC, move_in DESC";
			$query = $this->db->query($sql, $this->session->userdata('user_id'));
			if($query->num_rows() > 0)
			{
				
				$row = $query->result_array();				
				$total = 0;
				for($i=0;$i<sizeof($row);$i++) {
					if($row[$i]['current_residence'] == 'n' AND $row[$i]['move_out'] == '0000-00-00') {
						$row[$i]['warning'] = true;
					}
					if($row[$i]['group_id']>0) {
						$q = $this->db->get_where('admin_groups', array('id' => $row[$i]['group_id']), 1);
						foreach ($q->result() as $r) {
							if(!empty($r->sub_b_name)) {
								$group_name = $r->sub_b_name;
								$id = $r->sub_admins;
							}
						}
						$sql = "SELECT user, email, name, address, city, state, zip, phone, bName FROM landlords WHERE id = ? LIMIT 1";
						$query = $this->db->query($sql, $id);

						$results = $query->row_array();
						$row[$i]['landlord_name'] = $results['name'];
						$row[$i]['landlord_email'] = $results['email'];
						$row[$i]['landlord_address'] = $results['address'];
						$row[$i]['landlord_city'] = $results['city'];
						$row[$i]['state'] = $results['state'];
						$row[$i]['zip'] = $results['zip'];
						$row[$i]['landlord_phone'] = $results['phone'];
						$row[$i]['bName'] = $results['bName'];
						if(!empty($group_name)) {
							$row[$i]['bName'] = $group_name;
						} else {
							$row[$i]['bName'] = $results['bName'];
						}
						
						$row[$i]['user'] = $results['user'];
						
						
						
					} else {
						if($row[$i]['link_id'] != '0') {
							$sql = "SELECT user, email, name, address, city, state, zip, phone, bName FROM landlords WHERE id = ? LIMIT 1";
							$query = $this->db->query($sql, $row[$i]['link_id']);
							$results = $query->row_array();
							$row[$i]['landlord_name'] = $results['name'];
							$row[$i]['landlord_email'] = $results['email'];
							$row[$i]['landlord_address'] = $results['address'];
							$row[$i]['landlord_city'] = $results['city'];
							$row[$i]['state'] = $results['state'];
							$row[$i]['zip'] = $results['zip'];
							$row[$i]['landlord_phone'] = $results['phone'];
							$row[$i]['bName'] = $results['bName'];
							$row[$i]['user'] = $results['user'];
						}
					}
					
					$sql = "SELECT SUM(amount) AS amount FROM payment_history WHERE ref_id = ? AND status = 'complete'";
					$query = $this->db->query($sql, $row[$i]['id']);
					if ($query->num_rows() > 0)
					{
						$amount = $query->result_array(); 
					}
					if(!empty($amount[0]['amount'])) {
						$total = $amount[0]['amount'];
					} else {
						$total = '0';
					}
					$row[$i]['amount'] = $total;
					
					
					$sql = "SELECT count(id) AS count FROM payment_history WHERE ref_id = ? AND status = 'disputed'";
					$query = $this->db->query($sql, $row[$i]['id']);
					if ($query->num_rows() > 0)
					{
						$disputes = $query->result_array(); 
					}
					
					$count = $disputes[0]['count'];
				
					$row[$i]['count'] = $count;
					
				}
				return $row;
			} else {
				return false;
			}
			
		}
		
		function request_registration_landlord($id) // Retrieves the email from the renter_history to send an email to unregistered user
		{ 
			$sql = "SELECT tenant_id, landlord_email FROM renter_history WHERE id = ? LIMIT 1"; 
			$query = $this->db->query($sql, array($id));
			if ($query->num_rows() > 0)
			{
				$row = $query->row(); 
				$tenant_id = $row->tenant_id;
				$email = $row->landlord_email;
				$sql = "SELECT name FROM renters WHERE id = ? LIMIT 1"; 
				$query = $this->db->query($sql, array($tenant_id));
				if ($query->num_rows() > 0)
				{
					$row = $query->row();
					$name = $row->name;
					$data = array('email' => $email, 'tenant_name' => $name);
					return $data;
				} else {
					return false;
				}
			} 
			else 
			{
				return false;
			}
			
		}
		
		function show_rental_address($id) 
		{
			$sql = "SELECT link_id, group_id, rental_address, rental_city, rental_state, rental_zip, move_in, current_residence, move_out FROM renter_history WHERE tenant_id = ?  AND id = ? LIMIT 1";
			$query = $this->db->query($sql, array($this->session->userdata('user_id'), $id));
			if($query->num_rows() > 0)
			{
				return $query->row_array();
			} else {
				return false;
			}
			
		}
		
		function view_rental_payments($id) 
		{
			$sql = "SELECT id, recurring_payment, amount, paid_on, payment_type, auto_paid, status FROM payment_history WHERE ref_id = ? AND tenant_id = ? ORDER BY id DESC"; 
			$query = $this->db->query($sql, array($id, $this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return false;
			}
		}
		
		function add_payment($payment) 
		{		
			$payment['payment_type'] = 'Offline - '.$payment['payment_type'];
			$payment['created'] = date('Y-m-d');
			$payment['paid_on'] = date('Y-m-d', strtotime($payment['paid_on']));
			$payment['tenant_id'] = $this->session->userdata('user_id');
			$payment['status'] = 'Complete';
			$payment['entered_by'] = '0';
			
			$this->db->select('rental_address, rental_city, rental_state, link_id, group_id, move_in, move_out');
			$query = $this->db->get_where('renter_history', array('tenant_id'=> $this->session->userdata('user_id'),'id'=> $this->session->userdata('ref_id')));
			if($query->num_rows() > 0) {
				$row = $query->row();
				
				$address = $row->rental_address.' '.$row->rental_city.', '.$row->rental_state;
				$group_id = $row->group_id;
				$landlord_id = $row->link_id;
				
				$this->db->insert('payment_history', $payment);
				$paymentId = $this->db->insert_id();
				if($paymentId>0) {
				
					if(!empty($payment['reason'])) {
						$this->db->insert('payment_notes', array(
							'landlord_id' => $landlord_id,
							'tenant_id' => $this->session->userdata('user_id'),
							'group_id' => $group_id,
							'payment_id' => $paymentId,
							'note' => $payment['reason'],
							'sent_by' => 'renter',
						));
					}
					
					$group_landlord_id = $this->get_sub_admin_id($group_id);
					
					$data = array('insert_id'=>$this->db->insert_id(), 'landlord_id'=>$landlord_id, 'group_landlord_id'=> $group_landlord_id, 'ref_id'=>$this->session->userdata('ref_id'), 'address'=>$address);
				
					
					$this->db->select('email');
					if($group_id>0) {
						$this->db->where('id', $group_landlord_id);
						$data['group_id'] = $group_id;
					} else {
						$this->db->where('id', $landlord_id);
						$data['group_id'] = NULL;
					}
					$results = $this->db->get('landlords');
					$row = $results->row();
					$data['landlord_email'] = $row->email;
					
					return $data;
				} else {
					return false;
				}
			} else {
				return false;
			}
			
		}
		
		function get_rental_history_details($id) 
		{
			$sql = "SELECT * FROM `renter_history` WHERE id = ? AND tenant_id = ? LIMIT 1";
			$query = $this->db->query($sql, array($id, $this->session->userdata('user_id')));
			if ($query->num_rows() > 0)
			{
				$row = $query->row(); 
				return $row;
			} else {
				return false;
			}
		}
		
		function get_landlord_group_id()
		{
			$info = $this->get_current_landlord_info();
			//var_dump($info);
		}
		
		function get_payment_totals($id) 
		{
			$sql = "SELECT SUM(amount) AS amount FROM payment_history WHERE ref_id = ? AND status = 'complete'";
			$query = $this->db->query($sql, $id);
			if ($query->num_rows() > 0)
			{
				$amount = $query->result_array(); 
			}
			if(!empty($amount[0]['amount'])) {
				$total = $amount[0]['amount'];
			} else {
				$total = '0';
			}
			return $total;
		}
		
		function disputed_payments($id) 
		{
			$sql = "SELECT count(id) AS count FROM payment_history WHERE ref_id = ? AND status = 'disputed'";
			$query = $this->db->query($sql, $id);
			if ($query->num_rows() > 0)
			{
				$disputes = $query->result_array(); 
			}
			
			$count = $disputes[0]['count'];
		
			return $count;
		}
		
		function get_sub_admin_id($group_id) 
		{	
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				return $row->sub_admins;
			}
		}
		
		function get_admin_id($group_id) 
		{	
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				return $row->main_admin_id;
			}
		}
		
		function get_user_emails() 
		{
			$this->db->select('email, forwarding_email, name');
			$query = $this->db->get_where('renters', array('id'=>$this->session->userdata('user_id')));
			return $query->row_array();
		}
		
		function subscription_details($landlord_id, $rental_id)
		{
			$this->db->limit(1);
			$this->db->select('created, amount, start_date');
			$results = $this->db->get_where('payment_history', array('landlord_id'=>$landlord_id, 'ref_id'=>$rental_id, 'tenant_id'=>$this->session->userdata('user_id'), 'recurring_payment'=>'y'));
			if($results->num_rows()>0) {
				$row = $results->row();
				$row->next_date = $this->set_start_date($row->start_date);
				return $row;
			} else {
				return false;
			}
		}
		
		function set_start_date($date)
		{			
			$thisMonthDate = strtotime($date);
			$nextMonthDate = strtotime($date . ' +1 month');
			
			if (date('j', $thisMonthDate) !== date('j', $nextMonthDate)) {
				$nextMonthDate = strtotime(date('Y-m-d', $nextMonthDate) . ' last day of previous month');
			}
			$day = date('d', $nextMonthDate);
			if($day<date('d')) {
				$month = date('m')+1;
			} else {
				$month = date('m');
			}
			$nextDate = $month.'-'.$day.'-'.date('Y');
			
			return $nextDate;
		}
		
	}
?>