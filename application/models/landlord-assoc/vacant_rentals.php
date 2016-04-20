<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Vacant_rentals extends CI_Model {
		
		var $mMemberIds;
		var $mVacantTotals;
		var $mVacantRentals;
		var $mPerPage = 25;
		var $mRentalOffset = 0;
		
		public function buildVacantRentals()
		{
			$offset = (int)$this->uri->segment(3);
			if($offset) {
				$this->mRentalOffset = (int)$this->uri->segment(3);
			}
			$this->mMemberDetails = $this->getMemberLandlords();
			$this->mVacantRentals = $this->getVacantRentals();
			$this->getTotalRentals();
			
			$data['rentals'] = $this->mVacantRentals;
			$data['pagination'] = $this->pagination();
			$data['total'] = $this->mVacantTotals;

			return $data;
		}
		
		private function getMemberLandlords()
		{
			$this->db->select('registered_landlord_id, name');
			$results = $this->db->get_where('landlord_assoc_members', array('assoc_id'=>$this->session->userdata('user_id'), 'registered_landlord_id >' => 0, 'accepted' => 'y'));
			if($results->num_rows()>0) {
				return $results->result();
			}
			return false;
		}
		
		private function getVacantRentals() 
		{	
			$count = 0;
			$this->db->distinct();
			$this->db->select('listings.id, listings.owner, listings.title, listings.bedrooms, listings.bathrooms, listings.city, listings.zipCode, listing_images.featured_image, listing_images.image1, listing_images.image2, listing_images.image3, listing_images.image4, listing_images.image5');
			$this->db->from('listings');
			 
			$this->db->where('listings.active', 'y');
			 
			$this->db->limit($this->mPerPage, $this->mRentalOffset);
			foreach($this->mMemberDetails as $key => $val) {
				if($count==0) {
					$query = "(listings.owner = ".$val->registered_landlord_id." ";
				} else {
					$query .= "OR listings.owner = ".$val->registered_landlord_id." ";
				}
				$count++;
			}
			$this->db->where($query.')');
			$this->db->join('listing_images', 'listings.id = listing_images.listingId');
			
			$results = $this->db->get();
			
			if($results->num_rows()>0) {
				$data = array();
				foreach($results->result() as $key => $val) {
					foreach($this->mMemberDetails as $k => $v) {
						if($val->owner == $v->registered_landlord_id) {
							$val->owner_name = $v->name;
						}
					}
					$data[] = $val;
				}
				
				return $data;
			}
			return false;
		}
		
		
		
		
		private function getTotalRentals() 
		{
			$count=0;
			$this->db->from('listings');
			$this->db->select('id');
			$this->db->where('active', 'y');
			foreach($this->mMemberDetails as $key => $val) {
				if($count==0) {
					$query = "(owner = ".$val->registered_landlord_id." ";
				} else {
					$query .= "OR owner = ".$val->registered_landlord_id." ";
				}
				$count++;
			}
			$this->db->where($query.')');
			$results = $this->db->get();
			$this->mVacantTotals = $results->num_rows();
		}
		
		private function pagination() 
		{			
			$this->load->library('pagination');
		
			$config['base_url'] = base_url().'landlord-associations/vacant-member-listings';

			$config['total_rows'] = $this->mVacantTotals;
			$config['per_page'] = $this->mPerPage; 
			$config['full_tag_open'] = '<div class="text-center"><ul class="pagination">';
			$config['full_tag_close'] = '</ul></div><!--pagination-->';

			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';

			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';

			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';

			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';

			$config['cur_tag_open'] = '<li class="active text-warning"><a href="">';
			$config['cur_tag_close'] = '</a></li>';

			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			
			$this->pagination->initialize($config); 
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			return $this->pagination->create_links();
		}
		
		
		  
	} //EOC