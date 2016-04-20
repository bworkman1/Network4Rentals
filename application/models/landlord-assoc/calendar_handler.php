<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class calendar_handler extends CI_Model {
		
		var $mMembers; // holds object with name, email, registered_landlord_id, accepted of all members
		var $mEventId;
		var $mEventData; 
		var $mAssociation;
		
		function Calendar_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function add_event($data)
		{
			$this->mAssociation = $this->get_association_details();
			$this->mEventData = $data;
			$this->db->insert('assoc_events', $this->mEventData);
			if($this->db->insert_id()>0) {
				$this->mEventId = $this->db->insert_id();
						
				$this->db->select('name, email, registered_landlord_id, accepted');
				$result = $this->db->get_where('landlord_assoc_members', array('assoc_id'=>$this->session->userdata('user_id')));
				if($result->num_rows()>0) {
					$this->mMembers = $result->result();
				}
				if($data['public'] == 'y') {
					$this->send_member_emails();
					$this->send_activity_notifcations();
				}
				
				return $this->mEventId;
			}
			return false;
		}
		
		private function get_association_details()
		{
			$this->db->select('unique_name, bName, phone, name');
			$result = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'active' => 'y', 'type'=>'association'));
			if($result->num_rows()>0) {
				return $result->row();
			}
		}
		
		private function send_member_emails()
		{		
			$this->load->model('special/send_email');
			$subject = $this->mAssociation->bName.' posted a new event ('.htmlspecialchars($this->mEventData['what']).')';
			
			$email_array = array();
			
			foreach($this->mMembers as $key => $val) {
				if($this->registered_landlord_id>0) {
					$email_array[] = $this->get_registered_landlord_email($val->registered_landlord_id);
				} else {
					$email_array[] = $val->email;
				}
			}
		
			$message = '<h2>'.htmlspecialchars($this->mAssociation->bName).' has posted a new event</h2>';
			$message .= '<p><b>What:</b> '.htmlspecialchars($this->mEventData['what']).'<br>';
			$message .= '<b>Where:</b> '.htmlspecialchars($this->mEventData['where']).'<br>';
			$message .= '<b>On:</b> '.date('m-d-Y H:i:s', strtotime($this->mEventData['start'])).'</p>';
			$message .= '<p>To learn more about this event visit <a href="http://n4r.rentals/events/members/'.$this->mAssociation->unique_name.'/'.$this->mEventId.'">http://n4r.rentals/events/members/'.$this->mAssociation->unique_name.'/'.$this->mEventId.'/</a>';
			
			$this->send_email->sendEmail('', $message, $subject, '', $email_array);
		}
		
		private function get_registered_landlord_email()
		{
			$this->db->select('email');
			$result = $this->db->get_where('landlords', array('id'=>$id));
			if($result->num_rows()>0) {
				$data = $result->result();
				return $data->email;
			}
			return false;
		}
		
		private function send_activity_notifcations()
		{
			$this->load->model('special/add_activity');
			$action = 'New event added by Landlord Association';
			$type = 'landlords';
			$eLink = 'http://n4r.rentals/events/members/'.$this->mAssociation->unique_name.'/'.$this->mEventId;
			//external_link 
			foreach($this->mMembers as $key => $val) {
				if($val->registered_landlord_id>0) {
					$this->add_activity->add_new_activity($action, $val->registered_landlord_id, $type, $this->session->userdata('user_id'), NULL, $eLink);
				}
			}
		}
		
		function load_calendar_events($year, $month, $id)
		{			
			$sql = "SELECT * FROM (`assoc_events`) WHERE  (year(start) = ? AND month(start) = ? AND `user_id` = ?) or (year(end) = ? AND month(end) = ? AND `user_id` = ?)";
			$results = $this->db->query($sql, array($year, $month, $id, $year, $month, $id));
			$dates = $results->result();
			
			$events = array();
			
			foreach($dates as $key => $val) {
			
				$start_date = new DateTime($val->start);
				$end_date = new DateTime($val->end);
				
				$start_date_month = $start_date->format('m');
				$end_date_month = $end_date->format('m');
				
				$diff = $end_date->diff($start_date)->format("%a");
				
				if($diff>0) {
					$diff++;
					for($i=0;$i<$diff;$i++) {						
						if($month == date('m', strtotime($val->start.'+'.$i.' days'))) {
							$events[(int)date('d', strtotime($val->start.'+'.$i.' days'))][$val->id] = $val->what;
						}
					}
				} else {
					//SINGLE DAY EVENT
					$events[(int)date('d', strtotime($val->start))][$val->id] = $val->what;
				}
			}			
			return $events;
		}
		
		function event_details($id) 
		{
			$results =$this->db->get_where('assoc_events', array('id'=>$id, 'user_id'=>$this->session->userdata('user_id')));
			$data = $results->row();

			$data->start = date('m/d/Y h:i a', strtotime($data->start));
			$data->end = date('m/d/Y h:i a', strtotime($data->end));
			
			
			return $data;
		}
		
		function delete_event_details($id) {
			$this->db->where('id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->delete('assoc_events');
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function edit_calendar_event($data)
		{
			$this->db->where('id', $data['id']);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->limit(1);
			$this->db->update('assoc_events', $data);
			
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function edit_event($data)
		{
			$this->db->where('id', $data['event_id']);
			
			unset($data['event_id']);
			
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->limit(1);
			$this->db->update('assoc_events', $data);
			
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		
	}