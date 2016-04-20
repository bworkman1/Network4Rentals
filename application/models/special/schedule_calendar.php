<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Schedule_calendar extends CI_Model {
		
		public function loadEvents($type, $data)
		{
			$this->db->select('schedule_calendar.id, schedule_calendar.link, schedule_calendar.user_id, schedule_calendar.employee_id, schedule_calendar.start, schedule_calendar.end, schedule_calendar.allDay, schedule_calendar.title');
			$this->db->where('schedule_calendar.user_type', $type);
			
			$this->db->where('schedule_calendar.start >', date('Y-m-d', $data['start']));
			$this->db->where('schedule_calendar.end <', date('Y-m-d', $data['end']));
			
			$this->db->where('schedule_calendar.user_id', $this->session->userdata('user_id'));
			$query = $this->db->get('schedule_calendar');
			if($type=='contractor') {
				$data = array();
				$employee_ids = array();
				foreach($query->result() as $row) {
					
					if($row->employee_id == $row->user_id) {
						$row->borderColor = '#28B62C';
						$row->backgroundColor = '#28B62C';
					} else {
					
						if(!array_key_exists($employee_ids, $row->employee_id)) {
							$this->db->select('color');
							$q = $this->db->get_where('contractor_employees', array('id'=>$row->employee_id));
							$r = $q->row();
							$employee_ids[$row->employee_id] = $r->color;
							$row->borderColor = $r->color;
							$row->backgroundColor = $r->color;
						} else {
							$row->borderColor = $employee_ids[$row->employee_id];
							$row->backgroundColor = $employee_ids[$row->employee_id];
						}		
					}
					unset($row->employee_id);
					unset($row->user_id);
					
					if($row->allDay == 'true') {
						$row->allDay = true;
					} else {
						$row->allDay = false;
					}
					
					$data[] = $row;
				}
				
			} else {
				$data = $query->result();
			}

			return $data;
		}
		
		public function eventScheduled($link, $type)
		{
			$query = $this->db->get_where('schedule_calendar', array('user_type'=>'contractor', 'link'=>$link, 'user_id'=>$this->session->userdata('user_id')));
			if($query->num_rows()>0) {
				return $query->row();
			} 
			return false;
		}
		
		public function addEvent($data) 
		{		
			$query = $this->db->insert('schedule_calendar', $data);
			$insertId = $this->db->insert_id();
			if($insertId>0) {
				if($data['employee_id'] != $data['user_id']) {
					
					$this->db->select('color');
					$query = $this->db->get_where('contractor_employees', array('id'=>$data['employee_id']));
					$row = $query->row();

					if(!empty($row->color)) {
						$data['color'] = $row->color;
					}
				}
				
				if(empty($data['color'])) {
					$data['color'] = '#28B62C';
				}
				
				if($data['service_request_id']>0) {
					
					$query = $this->db->get_where('all_service_request', array('id'=>$data['service_request_id']));
					$request = $query->row();
					$side = $this->session->userdata('side');
					if($side == 'Contractor') {
						if($request->tenant_id>0) {
							$this->db->select('email');
							$query = $this->db->get_where('renters', array('id'=>$request->tenant_id));
							$renters = $query->row();
						}
						
						if($request->landlord_id>0) {
							$this->db->select('email');
							$query = $this->db->get_where('landlords', array('id'=>$request->landlord_id));
							$landlords = $query->row();
						}
					
						
						$this->db->select('bName, f_name, l_name');
						$request = $this->db->get_where('contractors', array('id'=>$this->session->userdata('user_id')));
						$contractorDetails = $request->row();
						
						if(!empty($contractorDetails->bName)) {
							$contractorName = $contractorDetails->bName;
						} else {
							$contractorName = $contractorDetails->f_name.' '.$contractorDetails->l_name;
						}
					
						$this->load->model('special/send_email');
						
						$subject = 'Service Request Scheduled';
						
						$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
						
						$note_string = $contractorName.' has scheduled your service request for approximate arrival time between '.date('m-d-Y h:i a', strtotime($data['start'])).' and '.date('m-d-Y h:i a', strtotime($data['end'])).'.';
						$note = array(
							'note' 			=> $note_string,
							'visibility' 	=> '1',
							'ref_id' 		=> $data['service_request_id'],
							'landlord_id'  	=> $request->landlord_id,
							'contractor_id' => $this->session->userdata('user_id'),
						);
						$this->db->insert('service_request_notes', $note);
					
						if(!empty($landlords->email)) {
							$message = '<h3>Service Request Scheduled</h3><p>'.$contractorName.' has scheduled your service request for approximate arrival time between '.date('m-d-Y h:i a', strtotime($data['start'])).' and '.date('m-d-Y h:i a', strtotime($data['end'])).'.</p><p><a href="https://network4rentals.com/network/landlords/view-service-request/'.$data['service_request_id'].'">View Full Service Request</a></p>';
	
							$this->send_email->sendEmail($landlords->email, $message, $subject);
						}
						
						if(!empty($renters->email)) {
							$message = '<h3>Service Request Scheduled</h3><p>'.$contractorName.' has scheduled your service request for approximate arrival time between '.date('m-d-Y h:i a', strtotime($data['start'])).' and '.date('m-d-Y h:i a', strtotime($data['end'])).'.</p><p><a href="https://network4rentals.com/network/renters/view-requests/'.$data['service_request_id'].'">View Full Service Request</a></p>';
							$this->send_email->sendEmail($renters->email, $message, $subject);
						}
					}
					
				}
				
				$data['id'] = $insertId;
				
				return $data;
			}
			return false;
		}
			
		public function updateEvent($data, $id) 
		{
			$this->db->where('id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$query = $this->db->update('schedule_calendar', $data);
			
			return true;
		}
		
		public function deteleEvent($id) 
		{
			$this->db->where('id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->delete('schedule_calendar');
			if($this->db->affected_rows()>0) {
				return true;
			}
			return false;
		}
		
	}