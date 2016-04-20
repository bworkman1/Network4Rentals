<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rental_listing_model extends CI_Model {
	
	
	/* listing images settings	*/
	var $image1;
	var $image2;
	var $image3;
	var $image4;
	var $image5;
	var $listingId;
	var $featured_image;
	var $lat;
	var $long;
	
	function rental_listing_model()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	public function add_new_listing($data)
	{
		$this->get_property_cords($data['zipCode']);
		$data['owner'] = $this->session->userdata('user_id');
		$data['active'] = 'n';
		$data['latitude'] = $this->lat;
		$data['longitude'] = $this->long;
		
		$this->image1 = $data['image1'];
		$this->image2 = $data['image2'];
		$this->image3 = $data['image3'];
		$this->image4 = $data['image4'];
		$this->image5 = $data['image5'];
		$this->featured_image = $data['featured_image'];
		if(empty($this->featured_image)) {
			$this->featured_image = 1;
		}
		unset($data['image1']);
		unset($data['image2']);
		unset($data['image3']);
		unset($data['image4']);
		unset($data['image5']);
		unset($data['featured_image']);
		
		$result = $this->db->insert('listings', $data);
		if($this->db->insert_id()>0) {
			$this->listingId = $this->db->insert_id();
			if($this->create_image_row()) {
				return true;
			}
			return false;
		}
		return false;
	}
	
	private function get_property_cords($zip) 
	{
		$result = $this->db->get_where('zips', array('zipCode'=>$zip));
		if($result->num_rows()>0) {
			$row = $result->row();
			$this->lat = $row->latitude;
			$this->long = $row->longitude;
		}
	}
	
	/* 
		listings table 
		
		title	details	bedrooms	price	active	owner	deposit	address	link	zipCode	stateAbv	latitude	longitude	city	lastmodified	sqFeet	bathrooms	map_correct	contact_id	deleted	central_air	laundry_hook_ups	off_site_laundry	on_site_laundry	basement	single_lvl	shed	park	inside_city	outside_city	deck_porch	large_yard	fenced_yard	partial_utilites	all_utilities	appliances	furnished	pool	shopping	garage	parking	pets
		
		
		listing_images 
		
		image1	image2	image3	image4	image5	listingId	featured_image	desc_1	desc_2	desc_3	desc_4	desc_5
	*/
	
	
	private function create_image_row() //returns boolean 
	{
		$data = array('image1'=>$this->image1,	'image2'=>$this->image2, 'image3'=>$this->image3,	'image4'=>$this->image4,	'image5'=>$this->image5, 'listingId'=>$this->listingId, 'featured_image'=>$this->featured_image);
		$this->db->insert('listing_images', $data);
		if($this->db->insert_id()>0) {
			return true;
		}
		return false;
	}
	
	public function retrieve_listing_details($id)
	{
		$this->listingId = $id;
		$result = $this->db->get_where('listings', array('id'=>$this->listingId, 'owner'=>$this->session->userdata('user_id')));
		if($result->num_rows()>0) {
			$data = $this->get_rental_images();
			$listing = $result->row_array();
			foreach($data as $key => $val) {
				$listing[$key] = $val;
			}
			return $listing;
		}
		return false;
	}
	
	private function get_rental_images()
	{
		$result = $this->db->get_where('listing_images', array('listingId'=>$this->listingId));
		if($result->num_rows()>0) {
			return $result->row();
		}
	}
	
	public function edit_rental_listing($data, $id) 
	{

		
		if(!empty($data['image1'])) {
			$this->image1 = $data['image1']; 
		}
		if(!empty($data['image2'])) {
			$this->image2 = $data['image2'];
		}
		if(!empty($data['image3'])) {
			$this->image3 = $data['image3'];
		}
		if(!empty($data['image4'])) {
			$this->image4 = $data['image4'];
		}
		if(!empty($data['image5'])) {
			$this->image5 = $data['image5'];
		}
		
		$this->featured_image = $data['featured_image'];
		if(empty($this->featured_image)) {
			$this->featured_image = 1;
		}
		
		unset($data['image1']);
		unset($data['image2']);
		unset($data['image3']);
		unset($data['image4']);
		unset($data['image5']);
		unset($data['featured_image']);
		
		$this->db->where('id', $id);
		$result = $this->db->update('listings', $data);
		
		$this->db->where('listingId', $id);
		$this->db->update('listing_images', array('image1'=>$this->image1, 'image2'=>$this->image2, 'image3'=>$this->image3, 'image4'=>$this->image4, 'image5'=>$this->image5, 'featured_image'=>$this->featured_image));
		
		return true;
		
	}
	
	
	
	
}