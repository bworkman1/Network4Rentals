<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Common_selections extends CI_Model {
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		public function service_types()
		{
			$query = $this->db->get('service_types');
			return $query->result();
		}

		public function getAffiliates()
		{
			$this->db->select('first_name, last_name, unique_id');
			$query = $this->db->get('affiliate_users');
			return $query->result();
		}

	}