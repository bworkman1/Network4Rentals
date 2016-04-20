<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Contact_landlord extends CI_Model {
		
		public function __construct() {        
			parent::__construct();
		}
		
		public function send_landlord_contact_form($data)
		{
			$property = $this->get_property_details($data['id']);
			if(!empty($property)) {
				
				$this->load->model('special/send_email');
				
				$subject = $data['name'].' is Contacting you about your rental property';
				$message = $this->format_message($data, $property);
		
				if($this->send_email->sendEmail($property['email'], $message, $subject)) {
					if(!empty($property['forwarding_email'])) {
						$this->send_email->sendEmail($property['forwarding_email'], $message, $subject);
					}
					$this->session->set_flashdata('success', 'Your message has been sent to the landlord');
					return true;
				} else {
					return false;
				}
			}
			return false;
		}
		
		private function get_property_details($id)
		{	
			$this->db->select('listings.id, listings.address, listings.zipCode, listings.stateAbv, listings.city, listings.owner, listings.contact_id');
			$result = $this->db->get_where('listings', array('id'=>$id));
			if($result->num_rows()>0) {
				$row = $result->row();
				$this->db->select('email, forwarding_email');
				if(!empty($row->contact_id)) {
					$landlord = $this->db->get_where('landlords', array('id'=>$row->contact_id));
				} else {
					$landlord = $this->db->get_where('landlords', array('id'=>$row->owner));
				}
				$landlord_details = $landlord->row();
				if(!empty($landlord)) {
					$data = array();
					foreach($row as $key => $val) {
						$data[$key] = $val;
					}
					$data['email'] = $landlord_details->email;
					$data['forwarding_email'] = $landlord_details->forwarding_email;
					return $data;
				}
			}
			return false;
		}
		
		private function format_message($msg, $listing)
		{
			$message = '<p>Someone has sent you an email through Network4Rentals rental listings page, the details are below.</p><table>';
			$message .= '<thead><tr><td><b>Listing Details</b></td><td><b>Contact Details</b></td></tr></thead>';
			$message .= '<tbody>';
			$message .= '<tr>';
			$message .= '<td><p><b>Address:</b><br> '.$listing['address'].' <br>'.$listing['city'].' '.$listing['stateAbv'].' '.$listing['zipCode'].'</p><a href="'.base_url('listings/view-listing/'.$listing['id']).'">View Listing</a></td>';
			$message .= '<td><p><b>Name:</b> '.$msg['name'].'</p><p><b>Email: </b>'.$msg['email'].'</p><p><b>Phone: </b>'.$msg['phone'].'</p><p><b>Question/Details:</b><br>'.$msg['details'].'</p></td>';
			$message .= '</tr>';
			$message .= '</tbody>';
			$message .= '</table>';
			return $message;
		}
		
	}
	