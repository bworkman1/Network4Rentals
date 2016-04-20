<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Payment_details extends CI_Model {
		function Payment_details()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function show_payment_details($id) 
		{
			$query_str = "SELECT * FROM payment_history WHERE tenant_id = ? and id = ?  LIMIT 1";
			$result = $this->db->query($query_str, array($this->session->userdata('user_id'), $id));
			
			if($result->num_rows()>0) {
				$payment_info = $result->row_array();
				$payment_info['next_payment_date'] = $this->set_start_date($payment_info['start_date']);
		
				$sql = "SELECT * FROM renter_history WHERE id = ? LIMIT 1";
				$result = $this->db->query($sql, array($payment_info['ref_id']));
				if ($result->num_rows() > 0) 
				{
					$landlord_info = $result->row_array(); 
				}
				$data = array($landlord_info, $payment_info);
				return $data;
			} else {
				return false;
			}
		}
		
		function get_payment_landlord_info($id) 
		{
			$query = $this->db->get_where('payment_history', array('id'=>$id, 'tenant_id'=>$this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {
				$row = $query->row(); 
				$ref_id = $row->ref_id;
				if($ref_id>0){
					$query = $this->db->get_where('renter_history', array('id'=>$ref_id));
					if ($query->num_rows() > 0) {
						$row = $query->row();
						if($row->group_id>0) {
							$query = $this->db->get_where('admin_groups', array('id'=>$row->group_id));
							if ($query->num_rows() > 0) {
								$row = $query->row();
								$landlord_id = $row->sub_admins;
								$sub_b_name = $row->sub_b_name;
							}
						} else {
							$landlord_id = $row->link_id;
						}
					}
				}
			}
			if(!empty($landlord_id)) {
				$query = $this->db->get_where('landlords', array('id'=>$landlord_id));
				if ($query->num_rows() > 0) {
					$row = $query->row();
					if(!empty($sub_b_name)) {
						$row->bName = $sub_b_name;
					}
					return $row;
				}
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