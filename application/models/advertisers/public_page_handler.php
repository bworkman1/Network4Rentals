<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Public_page_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
	
		function get_public_page_info()
		{
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'advertiser'));
			if($results->num_rows()>0) {
				$row = $results->row();
				return $row;
			} else {
				return false;
			}
			
		}
		
		function contractor_details()
		{
			$results = $this->db->get_where('advertisers', array('id'=>$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$row = $results->row();
				$row->name = $row->f_name.' '.$row->l_name;
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
				$query = $this->db->get_where('landlord_page_settings', array('landlord_id' => $this->session->userdata('user_id'), 'type' => 'advertiser'));
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
		
		public function get_public_image() 
		{
			$this->db->select('id, image, bName, phone');
			$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id')));
			if ($query->num_rows() > 0) {
				$results = $query->row();
				return $results;
			} else {
				return false;
			}
			
		}
		
		public function get_category_options()
        {
            $result = $this->db->get("advertiser_categories");
            return $result->result();
        }

        public function getUserCategory($id = null)
        {
            if(empty($id)) {
                $id = $this->session->userdata('user_id');
            }
            $this->db->select('category');
            $result = $this->db->get_where('advertisers', array('id'=>$id));

            return $result->row();
        }

        public function updateUserCategory($userId, $categoryId)
        {
            $this->db->where('id', $userId);
            $this->db->update('advertisers', array('category'=>$categoryId));
            return $this->db->affected_rows();
        }

    }


