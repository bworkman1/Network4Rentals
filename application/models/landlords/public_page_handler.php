<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_page_handler extends CI_Model {

	function public_page_handler()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_public_page_info() 
	{
		$query = $this->db->get_where('landlord_page_settings', array('landlord_id' => $this->session->userdata('user_id')));
		if ($query->num_rows() > 0) {
			$row = $query->row(); 
			return $row;
		} else {
			return false;
		}
	}
	
	public function landlord_details() 
	{
		$check_permissions = $this->session->userdata('permissions');
		if(!empty($check_permissions)) {
			return false;
		} else {
			$this->db->select('email, name, address, city, state, zip, phone, bName, alt_phone, forwarding_email');
			$this->db->where('id', $this->session->userdata('user_id'));
			$query = $this->db->get('landlords');
			if ($query->num_rows() > 0) {
				$row = $query->row(); 
				return $row;
			} else {
				return false;
			}
		}
	}
	
	public function update_settings($data) 
	{
		$check_permissions = $this->session->userdata('permissions');
		if(!empty($check_permissions)) {
			return false;
		} else {
			$query = $this->db->get_where('landlord_page_settings', array('landlord_id' => $this->session->userdata('user_id')));
			$data['landlord_id'] = $this->session->userdata('user_id');
			if ($query->num_rows() > 0) {
				$query = $this->db->get_where('landlord_page_settings', 
					array(
						'unique_name' => $data['unique_name'], 
						'landlord_id !=' => $this->session->userdata('user_id')
					)
				);
				if($query->num_rows()==0) {
					$this->db->where('landlord_id', $this->session->userdata('user_id'));
					$this->db->update('landlord_page_settings', $data);
					if ($this->db->trans_status() === FALSE) {
						return false;
					} else {
						return true;
					}
				} else {
					$this->session->set_flashdata('error', 'Unique Name Is Already Taken, Try A Different One');
					redirect('landlords/public-page-settings');
					exit();
				}
			} else {
				$this->db->insert('landlord_page_settings', $data); 
				if ($this->db->trans_status() === FALSE) {
					return false;
				} else {
					return true;
				}
			}
				
		
		}
	}
	
	public function check_for_unique_ajax($input)
	{	
		$query = $this->db->get_where('landlord_page_settings', 
			array(
				'unique_name' => $input, 
				'landlord_id !=' => $this->session->userdata('user_id')
			)
		);
		if($query->num_rows()>0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function delete_public_image($id)
	{		
		if($id>0) {
			$query = $this->db->get_where('landlord_page_settings', array('id'=>$id, 'landlord_id'=>$this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {
				$this->db->where('id', $id);
				$this->db->update('landlord_page_settings', array('background'=>''));
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
}