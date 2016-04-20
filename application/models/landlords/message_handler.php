<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message_handler extends CI_Model {
	
	function message_handler()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function add_group_message($data) 
	{
		$sent_count = 0;
		$today = date('Y-m-d h:i:s');
		$query = $this->db->get_where('renter_history', array('current_residence'=>'y', 'link_id'=>$data['link_id']));
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$hashMail = md5($this->session->userdata('user_id').rand(100, 9999999).date('Y-m-d'));
				$msg = array(
					'message' => $data['message'],
					'tenant_id' => $row->tenant_id,
					'rental_id' => $val->id,
					'landlord_viewed' => $today,
					'hash_mail' => $hashMail,
					'attachment' => $data['file'],
					'subject' => $data['subject'],
					'landlord_id' => $data['link_id'],
					'sent_by' => $data['sent_by']
				);
				if($this->db->insert('messagings', $msg)) {
					$sent_count++;
				}
			}
			return $sent_count;
		} else {
			return false;
		}
	}
	
	
}

	