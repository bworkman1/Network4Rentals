<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
				
	class Admin_settings extends CI_Model {
		
		// Call the Model constructor
		function __construct()
		{		
			parent::__construct();
		}
	
		function getAdminSettings($keys) //Pass in an array of "setting_key
		{
			foreach($keys as $val) {
				$this->db->or_where('setting_key', $val);
			}
			$results = $this->db->get('admin_settings');
			return $results->result();
		}
		
		public function getLocalPartnerCategories() 
		{
			$query = $this->db->get('advertiser_categories');
			return $query->result();
		}
		
	}