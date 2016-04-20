<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supply_houses extends CI_Model { 
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('upload');
		
		$id = $this->session->userdata('user_id');
		if(empty($id)) {
			echo 'Get OUT!';
			exit;
		}
	}

	function addSupplyHouse($input)
	{		
		if($this->validateServices($input['resource_types'], ',') && $this->validateServices($input['ad_services'], ',')) {
			if($this->validateZips($input['ad_areas'], '|')) {
				$cords = $this->getCoordinates($input['address'].' '.$input['city'].' '.$input['state']);	
				$input['lat'] = $cords['lat'];
				$input['longitude'] = $cords['long'];
				$input['zip'] = $cords['zip'];
		
				$background = $input['background'];
				$unique = $input['unique'];
				$input['phone'] = preg_replace("/[^0-9,.]/", "", $input['phone']);
				unset($input['background']);
				unset($input['unique']);
				
				$this->db->insert('supply_houses', $input);
				$insertId = $this->db->insert_id();
			
				if($insertId>0) {
					if($input['affiliate'] == 'y') {
						$input['background'] = $background;
						$input['unique'] = $unique;
						$input['insert_id'] = $insertId;
					
						$publicPageId = $this->createPublicPage($input);
						
						$this->db->where('id', $insertId);
						$this->db->update('supply_houses', array('page_settings_id' => $publicPageId));
					}
					return true;
				} else {
					return 'Error inserting supply house, try again';
				}
			} else {
				return 'There is an error with one of your zip codes. Zip codes must be only 5 digits';
			}
		} else {
			return 'There is an error in your service types selections';
		}
		
	}
	
	public function checkUniuqeName($name, $id) 
	{
		$query = $this->db->get_where('landlord_page_settings', array('unique_name'=>url_title(strtolower($name)), 'landlord_id !='=>$id));
		if($query->num_rows()>0) {
			return true;
		}
		return false;
	}
	
	public function editSupplyHouse($data, $id)
	{
		$publicPage = $this->getSupplyHousePublicPage($id);
		$currentSettings = $this->getSupplyHouseById($id);
		
		$cords = $this->getCoordinates($data['address'].' '.$data['city'].' '.$data['state']);
		$data['lat'] = $cords['lat'];
		$data['longitude'] = $cords['long'];
		$data['zip'] = $cords['zip'];
		
		$background = $data['background'];
		$unique = $data['unique'];
		$data['phone'] = preg_replace("/[^0-9,.]/", "", $data['phone']);
		unset($data['background']);
		unset($data['unique']);
		
		$this->db->where('id', $id);
		$this->db->update('supply_houses', $data);
		
		if($data['affiliate'] == 'y') {
			$data['background'] = $background;
			$data['unique'] = $unique;
			$data['id'] = $id;
			if(empty($data['logo'])) {
				$data['logo'] = $currentSettings->logo;
			}
			if(!empty($publicPage)) {
				$this->updatePublicPage($data, $id);
			} else {
				$publicPageId = $this->createPublicPage($data);
				
				$this->db->where('id', $id);
				$this->db->update('supply_houses', array('page_settings_id' => $publicPageId));
			}
		}

		return true;
	
	}
	
	public function getSupplyHouses()
	{
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$per_page = 20;
	
		$this->db->limit($per_page, $page);
		$query = $this->db->get('supply_houses');
		$pageArgs = array('per_page'=>$per_page, 'base_url'=>'n4radmin/supply-houses', 'total_rows'=>$this->db->count_all_results('supply_houses'));
		
		$data = array();
		foreach($query->result() as $row) {
			$uniqueName = '';
			if(!empty($row->page_settings_id)) {
				$publicPage = $this->getSupplyHousePublicPage($row->id);
				$uniqueName = $publicPage->unique_name;
			}
			$row->unique_name = $uniqueName;
			$data[] = $row;
		}
		
		$data['results'] = $data;
		$data['links'] = $this->pagination($pageArgs);
		
		return $data;
	}
	
	private function pagination($args) // 'total_rows', 'base_url', 'per_page'
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url($args['base_url']);
		
		$config['total_rows'] = $args['total_rows'];
		$config['per_page'] = $args['per_page'];
		$config['full_tag_open'] = '<div><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div>';
		 
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
	
	private function createPublicPage($data)
	{
		$this->load->helper('url');
		$inputs = array(
			'bName' => ucwords($data['business']),
			'image' => $data['logo'],
			'address' => ucwords($data['address']), 
			'city' => ucwords($data['city']),
			'state' => strtoupper($data['state']),
			'phone' => $data['phone'],
			'zip' => $data['zip'],
			'landlord_id' => $data['insert_id'],
			'unique_name' => url_title(strtolower($data['unique'])),
			'background' => $data['background'],
			'type' => 'supplier',
			'active' => 'y',
			'website' => $data['url'],
			'email' => $data['email']
		);

		$this->db->insert('landlord_page_settings', $inputs);
		
		return $this->db->insert_id();
	}
	
	private function updatePublicPage($data, $id) 
	{
		
		$this->load->helper('url');
		$inputs = array(
			'bName' => ucwords($data['business']),
			'image' => $data['logo'],
			'address' => ucwords($data['address']), 
			'city' => ucwords($data['city']),
			'state' => strtoupper($data['state']),
			'phone' => $data['phone'],
			'zip' => $data['zip'],
			'landlord_id' => $data['id'],
			'unique_name' => url_title(strtolower($data['unique'])),
			'type' => 'supplier',
			'active' => 'y',
			'website' => $data['url'],
			'email' => $data['email'],
			'background' => $data['background']
		);		
	
		$this->db->where('landlord_id', $id);
		$this->db->where('type', 'supplier');
		$this->db->update('landlord_page_settings', $inputs);
	}
	
	private function getCoordinates($address){
		$address = str_replace(' ', '+', $address); // replace all the white space with "+" sign to match with google search pattern
	 
		$url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address='.$address;
	 
		$response = file_get_contents($url);
	 
		$json = json_decode($response,TRUE); //generate array object from the response from the web
		$cords = array('lat'=>$json['results'][0]['geometry']['location']['lat'], 'long'=>$json['results'][0]['geometry']['location']['lng'], 'zip'=>$json['results'][0]['address_components'][count($json['results'][0]['address_components'])]['short_name']);
		return $cords;
	}	
		
	// Validation Functions
	private function validateServices($services_string, $delimiter)
	{
		$isValid = true;
		$services_array = explode($delimiter, $services_string);
		foreach($zip_array as $val) {
			$service = (int)trim($val);
			if($service>14 || $service<1) {
				$isValid = false;
			}
		}
		return $isValid;
	}
	
	private function validateZips($zip_string, $delimiter)
	{
		$isValid = true;
		$zip_array = explode($delimiter, $zip_string);
		
		foreach($zip_array as $val) {
			$zip = (int)trim($val);
			$zipLen = strlen($zip);
			  
			if($zipLen!= '5') {
			
				$isValid = false;
			}
		}
		return $isValid;
	}
		
	public function delete_supply_house($id) 
	{
		$this->db->limit(1);
		$this->db->where('id', $id);
		$this->db->delete('supply_houses');
		
		$this->db->limit(1);
		$this->db->where('landlord_id', $id);
		$this->db->where('type', 'supplier');
		$this->db->delete('landlord_page_settings');
		
		$this->session->set_flashdata('success', '<div class="alert alert-success">Success: Supply house deleted succesfully</div>');
	}
	
	public function getSupplyHouseById($id) 
	{
		$this->db->where('id', $id);
		$data = $this->db->get('supply_houses');
		return $data->row();
	}
	
	public function getSupplyHousePublicPage($id) 
	{
		$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$id, 'type'=>'supplier'));
		return $query->row();
	}
	
}