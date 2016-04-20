<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Service_request extends CI_Model {

        private $sortBy;

		public function __construct() {
            parent::__construct();
        }
		
		public function grab_service_requests($limit, $offset) 
		{
            $this->sortBy = $this->session->userdata('sort_request');
			$this->db->select('all_service_request.id, all_service_request.name, all_service_request.address, all_service_request.page_submit, renter_history.group_id, renter_history.rental_zip, renter_history.link_id, all_service_request.complete, all_service_request.submitted, all_service_request.service_type, all_service_request.contractor_received');
			$this->db->join('renter_history', 'renter_history.id = all_service_request.rental_id', 'left');
			$this->db->order_by('submitted', 'desc');
			$this->db->where('all_service_request.contractor_id', $this->session->userdata('user_id'));
            if(!empty($this->sortBy))  {
                $this->db->where('complete', $this->sortBy);
            }
			$this->db->from('all_service_request');
			if(!empty($offset)) {
				$this->db->limit($limit, $offset);
			} else {
				$this->db->limit($limit);
			}
			$results = $query = $this->db->get();
			if($results->num_rows()>0) {
				if(!empty($offset)) {
					$count = $offset+1;
				} else {
					$count = 1;
				}
				
				foreach ($query->result() as $row) {
					$row->counter = $count++;
					if($row->page_submit == 'n'){
						$this->db->select('bName, name');
						$data = $this->db->get_where('landlords', array('id'=>$row->link_id));
						$r = $data->row();
						if(!empty($r->bName)) {
							$row->name = $r->bName;
						} else {
							$row->name = $r->name;
						}
					} else {
						$row->rental_zip = substr($row->address, -5);
					}
				}
				
				return $results->result();
			} else {
				return false;
			}
		}
		
		public function add_service_request($data) 
		{
			$query = $this->db->insert('all_service_request', $data);
			$id = $this->db->insert_id();
			if($id>0) {
				return $id;
			}
			return false;
			
		}
		
		public function save_service_request_note($data) 
		{
			$test = $this->db->get_where('all_service_request', array('id'=>$data['id'], 'contractor_id'=>$this->session->userdata('user_id')));
			if($test->num_rows()>0) {
				if(!empty($data['image']['img']['name'])) {
					$config['upload_path'] = './public-images/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size'] = '5000KB';
					$this->load->library('upload', $config);
					
					$file = "img";
					
					if($this->upload->do_upload($file)) {
						
						$upload = $this->upload->data();
						$file = $upload['file_name'];
						$input['image'] = $file;
						$max_height = 400;
						$max_width = 600;
						if ($upload['image_width']>$max_width || $upload['image_height']>$max_height) {
							// Resize The Image
							$config['image_library'] = 'GD2';
							$config['source_image']	= FCPATH.'public-images/'.$file;
							$config['maintain_ratio'] = TRUE;
							$config['width']	 = 400;
							$config['height']	= 600;

							$this->load->library('image_lib', $config);
							$this->image_lib->resize($config);			
						}
					} else {
						$error = array('error' => $this->upload->display_errors());
						$file = '';
					}
				}
				if(empty($error)) {
					$note = array('contractor_id'=>$this->session->userdata('user_id'), 'note'=>$data['note'], 'visibility'=>1, 'ref_id'=>$data['id'], 'contractor_image'=>$file);
					$this->db->insert('service_request_notes', $note);
					if($this->db->insert_id()>0) {
						$this->load->model('contractor/activity_handler');
						$activity = 'Added a note to service request';
						$this->activity_handler->insert_activity(array('action'=>$activity,'action_id'=>$data['id']));
						return array('success'=>'Note saved successfully');
					} else {
						return array('error'=>'Note failed to save, try again');
					}
				} else {
					return array('error'=>$error);
				}
			} else {
				return array('error'=>'Service request not found');
			}
		}
		
		public function service_request_details($id)
		{
			$this->db->select('*');
			$this->db->join('renter_history', 'renter_history.id = all_service_request.rental_id', 'left');
			$this->db->limit(1);
			$results = $this->db->get_where('all_service_request', array('all_service_request.id'=>$id, 'all_service_request.contractor_id'=>$this->session->userdata('user_id')));
			//$this->session->userdata('user_id')));
			if($results->num_rows()>0) {
				$row = $results->row();
				if(!empty($row->group_id)) {
					$row->landlord_id = $this->get_group_landlord_id($row->group_id);
				}
				
				$row->request_id = $id;
				
				// Landlord and Tenant Info
				$row->landlord_info = $this->get_landlord_info($row->landlord_id);
				$row->tenant_info = $this->get_tenant_info($row->tenant_id);
				
				//Format Phone Numbers
				$row->landlord_info->phone = $this->format_phone_number($row->landlord_info->phone);
				$row->landlord_info->alt_phone = $this->format_phone_number($row->landlord_info->alt_phone);
				
				$row->tenant_info->phone = $this->format_phone_number($row->tenant_info->phone);
				$row->tenant_info->alt_phone = $this->format_phone_number($row->tenant_info->alt_phone);
				$row->schedule_phone = $this->format_phone_number($row->schedule_phone);		
				
				if(!empty($row->listing_id)) {
					$row->items = $this->get_home_items($row->listing_id, $row->service_type);
				}
	
				return $row;
			} else {
				return false;
			}
		}
		
		public function total_requests()
		{
            if(!empty($this->sortBy))  {
                $this->db->where('complete', $this->sortBy);
            }
			$results = $this->db->get_where('all_service_request', array('contractor_id'=>$this->session->userdata('user_id')));
			return $results->num_rows();
		}
		
		public function get_request_notes($id)
		{
			$this->db->order_by('id', 'desc');
			$results = $this->db->get_where('service_request_notes', array('ref_id'=>$id, 'visibility'=>1));
			if($results->num_rows()>0) {
			
				$this->db->select('f_name, l_name, bName');
				$r = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
				$row = $r->row();
				$data = array();
				foreach($results->result() as $val) {
					$val->contractor_name = $row->bName;
					$data[] = $val;
				}

				return $data;
			} else {
				return false;
			}
		}
		
		public function add_item($data)
		{		
		
			$test = $this->db->get_where('all_service_request', array('id'=>$data['request_id'], 'contractor_id'=>$this->session->userdata('user_id'), 'listing_id'=>$data['id']));
			if($test->num_rows()>0) {

				$item = array(
					'desc'=>$data['desc'],
					'modal_num' =>$data['modal_num'],
					'brand' =>$data['brand'],
					'serial' =>$data['serial'],
					'service_type' =>$data['service_type'],
					'listing_id' =>$data['id']
				);
				$this->db->insert('home_items', $item);
				if($this->db->insert_id()>0) {
					$this->load->model('contractor/activity_handler');
					$activity = 'Added item to service request';
					$this->activity_handler->insert_activity(array('action'=>$activity,'action_id'=>$data['request_id']));
					return array('success'=>'Item added successfully');
				} else {
					return array('error'=>'Something went wrong, try again');
				}
			} else {
				return array('error'=>'No Request Found');
			}
		}
		
		public function request_complete($id, $cost)
		{
			$test = $this->db->get_where('all_service_request', array('id'=>$id, 'contractor_id'=>$this->session->userdata('user_id')));
			if($test->num_rows()>0) {
				$date = date('Y-m-d h:i');
				$this->db->where('id', $id);
				$this->db->update('all_service_request', array('cost'=>$cost, 'completed'=>$date, 'complete'=>'y'));
				if($this->db->affected_rows()>0) {
					$this->load->model('contractor/activity_handler');
					$activity = 'Marked service request complete';
					$this->activity_handler->insert_activity(array('action'=>$activity,'action_id'=>$id));
					return array('success'=>'Service Request Updated');
				} else {
					return array('error'=>'Something went wrong, try again');
				}
			} else {
				return array('error'=>'No Request Found');
			}
		}
		
		/* PRIVATE FUNCTIONS */
		
		private function get_home_items($id, $type)
		{
			$results = $this->db->get_where('home_items', array('listing_id'=>$id, 'service_type'=>$type));
			if($results->num_rows()>0) {
				return $results->result();
			} else {
				return false;
			}		
		}
		
		private function get_group_landlord_id($id)
		{
			$this->db->select('sub_admins');
			$results = $this->db->get_where('admin_groups', array('id'=>$id));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}
			
		}
		
		private function get_landlord_info($id) 
		{
			$this->db->select('email, name, city, state, zip, phone, bName, alt_phone');
			$results = $this->db->get_where('landlords', array('id'=>$id));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}		
		}		
		
		private function get_tenant_info($id) 
		{
			$this->db->select('name, phone, email, alt_phone');
			$results = $this->db->get_where('renters', array('id'=>$id));
			if($results->num_rows()>0) {
				return $results->row();
			} else {
				return false;
			}		
		}
		
		private function format_phone_number($phone)
		{
			if(!empty($phone)) {
				$new_phone = "(".substr($phone, 0, 3).") ".substr($phone, 3, 3)."-".substr($phone,6);
			} else {
				$new_phone = '';
			}
			return $new_phone;
		}
		
		
		
	}