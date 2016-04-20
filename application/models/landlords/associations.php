<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Associations extends CI_Model {
		
		public function __construct() {        
			parent::__construct();
		}
		
		public function get_association_details($id) 
		{
			$this->db->select('assoc_id, accepted');
			$results = $this->db->get_where('landlord_assoc_members', array('assoc_id'=>$id, 'registered_landlord_id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$memberData = $results->row();
				$this->db->select('title, name, phone, address, city, state, zip');
				$results = $this->db->get_where('landlord_assoc', array('id'=>$id, 'active'=>'y'));
				if($results->num_rows()>0) {
					
					$row = $results->row();
					$otherDetails = $this->getAssocLogo($id);
					if(!empty($otherDetails)) {
						$row->logo = $otherDetails->image;
						$row->link = $otherDetails->unique_name;
					} else {
						$row->logo = '';
						$row->link = '';
					}
					$row->accepted = $memberData->accepted;
					return $row;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}

		private function getAssocLogo($id) 
		{
			$this->db->select('unique_name, image');
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$id, 'type' => 'association' ));
			if($results->num_rows()>0) {
				return $results->row();
			}
			return false;
		}
		
		public function acceptInvite($id, $accepted) 
		{
			if($accepted == 2) {
				$this->db->where('assoc_id', $id);
				$this->db->where('registered_landlord_id', $this->session->userdata('user_id'));
				$this->db->update('landlord_assoc_members', array('accepted'=>'y'));
				if($this->db->affected_rows()>0) {
					return true;
				}
			} else if($accepted==1) {
				$this->db->where('assoc_id', $id);
				$this->db->where('registered_landlord_id', $this->session->userdata('user_id'));
				$this->db->delete('landlord_assoc_members');
				if($this->db->affected_rows()>0) {
					return true;
				}
			}
			return false;
		}
		
	}