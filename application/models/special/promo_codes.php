<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
    class Promo_codes extends CI_Model {
		
        public function __construct() 
		{
            parent::__construct();
        }
		
		public function checkPromoCode($code, $type)
		{
			$query = $this->db->get_where('promo_codes', array('code' => strtolower($code), 'user_type' => $type, 'active' => 'y'));
			if($query->num_rows()>0) {
				return $query->row();
			}
			return false;
		}
		
	}