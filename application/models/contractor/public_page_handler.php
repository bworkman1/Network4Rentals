<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Public_page_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
	
		function contractor_details()
		{
			$results = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$row = $results->row();
				$row->name = $row->f_name.' '.$row->l_name;
				return $row;
			} else {
				return false;
			}
		}
			
		function get_pages() 
		{
			$this->db->select('name, id');
			$this->db->order_by('stack_order', 'desc');
			$result = $this->db->get_where('public_pages', array('user_id'=>$this->session->userdata('user_id'), 'type'=>'contractor'));
			return $result->result();
		}	
			
		function get_public_page_info()
		{
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'contractor'));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row;
			} else {
				return false;
			}
			
		}
		
		public function update_settings($data) 
		{
			$check_permissions = $this->session->userdata('permissions');
			
			if(!empty($check_permissions)) {
				return false;
			} else {
				$query = $this->db->get_where('landlord_page_settings', array('landlord_id' => $this->session->userdata('user_id'), 'type' => 'contractor'));
				
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
						$this->db->where('type', 'contractor');

						$this->db->update('landlord_page_settings', $data);
						if ($this->db->trans_status() === FALSE) {
							return false;
						} else {
							$this->load->model('contractor/activity_handler');
							$activity = 'Update N4R Public Profile Page';
							$this->activity_handler->insert_activity(array('action'=>$activity,'action_id'=>''));
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
		
		function delete_page($id)
		{
			$this->db->delete('public_pages', array('id'=>$id, 'type'=>'contractor', 'user_id'=>$this->session->userdata('user_id')));
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
	}