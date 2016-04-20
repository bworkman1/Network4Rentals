<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_user_model extends CI_Model {
	
	var $table = 'landlords';
	
	function Create_user_model()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function create_user_account($data) 
	{
		$sql = "SELECT id, user FROM landlords WHERE email = ?";
		$query = $this->db->query($sql, array($data['email']));
		if ($query->num_rows() > 0) {
			$row = $query->row(); 
			if(empty($row->user)) {
				// email found with no user data / non-active user
				$this->db->where("id",$row->id);
				$this->db->update($this->table,$data);
				return 1;
			} else {
				return 3; // Email Is Already In Use By Active User
			}
		} else {
			// No Email Found In Database create one
			$this->db->insert($this->table,$data);			
			return 2;
		}
	} // end user account

	
	private function checkForAssociationInvites($email, $user_id) 
	{
		$this->db->select('id, assoc_id');
		$results = $this->db->get_where('landlord_assoc_members', array('email'=>$email));
		if($results->num_rows()>0) {
			$invites = $results->result();
			$action = 'You Have Been Invited To Join A Landlord Association';
			$type = 'landlords';
			$this->load->model('special/add_activity');
			foreach($invites as $key => $val) {
				$this->db->where('id', $val->id);
				$this->db->update('landlord_assoc_members', array('registered_landlord_id' =>$user_id));
				$this->add_activity->add_new_activity($action, $user_id, $type, $val->assoc_id);
			}
		}
	}
	
	function verify_account($hash) {
		if(!empty($hash))
		$query_str = "SELECT email, id, user FROM landlords WHERE loginHash = ? LIMIT 1";
		$result = $this->db->query($query_str, array($hash));
		if($result->num_rows() > 0) 
		{
			$data = $result->row();
			$query_str = "UPDATE landlords SET confirmed = ?, loginHash = ? WHERE loginHash = ? LIMIT 1";
			$result = $this->db->query($query_str, array('y', NULL, $hash));
			$this->checkForAssociationInvites($data->email, $data->id);
			
			$this->session->set_userdata('side_logged_in', '8468086465404');
			$this->session->set_userdata('user_id', $data->id);
			$this->session->set_userdata('logged_in', TRUE);
			$this->session->set_userdata('username', $data->user);
		
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	//Adds text message feature when the user creates the account
	function update_text_message_option($data) {
		$this->db->limit(1);
		$this->db->where('loginHash', $this->session->userdata('hash'));
		$results = $this->db->update('landlords', $data);
		if($this->db->affected_rows()>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function check_text_code($code)
	{
		$this->db->select('id, user');
		$this->db->limit(1);
		$results = $this->db->get_where('landlords', array('text_msg_code'=>$code));
		if($results->num_rows()>0) {
			$row = $results->row();
			
			$this->session->set_userdata('side_logged_in', '8468086465404');
			$this->session->set_userdata('user_id', $row->id);
			$this->session->set_userdata('logged_in', TRUE);
			$this->session->set_userdata('username', $row->user);
			
			$data = array('loginHash'=>'', 'text_msg_code'=>'', 'confirmed'=>'y');
			
			$this->db->limit(1);
			$this->db->where('id', $row->id);
			$results = $this->db->update('landlords', $data);
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function check_unique_email($email) 
	{	
		$this->db->select('id');
		$results = $this->db->get_where('landlords', array('email'=>$email));
		if($results->num_rows()>0) {
			return true;
		} else {
			return false;
		}
	}
	
}