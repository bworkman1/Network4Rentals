<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Public_pages extends CI_Model {
		
		var $user_id;
		var $page_id;
		
        public function __construct() {

            parent::__construct();

        }
		
		public function add_page($page_name)
		{
			$slug = $this->generate_slug($page_name);
			if($slug != false) {
				$this->user_id = $this->session->userdata('user_id');
				$result = $this->db->get_where('public_pages', array('user_id'=>$this->user_id, 'type'=>'contractor'));
				if($result->num_rows()<4) {
					$this->db->insert('public_pages', array('user_id'=>$this->user_id, 'type'=>'contractor', 'name'=>$page_name, 'url_slug'=>$slug));
					$this->session->set_flashdata('success', 'Your page has successfully been created');
				} else {
					$this->session->set_flashdata('error', 'You are only allowed 4 pages total');
				}
			} else {
				$this->session->set_flashdata('error', 'You already have a page named '.$page_name);
			}
		}
		
		private function generate_slug($name)
		{
			$slug = url_title($name, 'dash', TRUE);
			$result = $this->db->get_where('public_pages', array('user_id'=>$this->session->userdata('user_id'), 'url_slug'=>$slug));
			if($result->num_rows()>0) {
				return false;
			} 
			return $slug;
		}
		
	}