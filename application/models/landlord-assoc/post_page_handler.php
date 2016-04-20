<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class post_page_handler extends CI_Model {
		function Post_page_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function add_new_post($data)
		{
			$this->db->insert('assoc_posts', $data);
			$inserted = $this->db->affected_rows();
			if($inserted>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function edit_post($data)
		{
			$this->db->where('id', $data['id']);
			$this->db->update('assoc_posts', $data);
			$inserted = $this->db->affected_rows();
			if($inserted>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function show_all_posts() 
		{
			$results = $this->db->get_where('assoc_posts', array('user_id'=>$this->session->userdata('user_id')));
			$data = $results->result();
			return $data;
		}
		
		function delete_post($id) 
		{
			$this->db->where('id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->delete('assoc_posts'); 
			if ($this->db->affected_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		function get_single_posts($id) 
		{
			$results = $this->db->get_where('assoc_posts', array('id'=>$id, 'user_id'=>$this->session->userdata('user_id')));
			$data = $results->row();
			return $data;
		}
		
		function add_new_page($name) 
		{	
			$name = strtolower($name);
			$results = $this->db->get_where('public_pages', array('user_id'=>$this->session->userdata('user_id'), 'type'=>'association'));
			if($results->num_rows()>4) {
				return '4';
			} else {
				$results = $this->db->get_where('public_pages', array('user_id'=>$this->session->userdata('user_id'), 'type'=>'association', 'name'=>$name));
				if($results->num_rows()>0) {
					return '0';
				} else {
					$this->db->insert('public_pages', array('name'=>$name, 'user_id'=>$this->session->userdata('user_id'), 'type'=>'association'));
					if($this->db->insert_id()>0) {
						return $this->db->insert_id();
					} else {
						return '2';
					}
				}
			}
		}
	
		
	}