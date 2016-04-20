<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class public_page_handler extends CI_Model {
		
		function Post_page_handler()
		{
			// Call the Model constructor
			parent::__construct();
		}
	
		function get_page_settings()
		{	
			$this->db->limit(1);
			$results = $this->db->get_where('landlord_page_settings', array( 'landlord_id'=>$this->session->userdata('user_id'), 'type'=>'association'));
			if($results->num_rows()>0) {
				return $results->row();
			}
		}
		
		function get_public_pages()
		{
			$this->db->order_by('stack_order');
			$results = $this->db->get_where('public_pages', array( 'user_id'=>$this->session->userdata('user_id'), 'type'=>'association'));
			if($results->num_rows()>0) {
				$data = $results->result();
				return $data;
			}
		}
		
		function reorder_pages($data)
		{ 
			$this->db->where('id', $data['id']);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->update('public_pages', array('stack_order'=>$data['stack_order']));
		}
		
		function get_page_data($id) 
		{
			$this->db->limit(1);
			$this->db->select('id, page, name, ts');
			$this->db->where('id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$results = $this->db->get('public_pages');
			return $results->row();
		}
		
		function edit_page($page, $id, $name) 
		{
			$this->db->where('id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->update('public_pages', array('page'=>$page, 'name'=>$name));
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		}	
		
		function update_public_page_details($data) {

			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id'), 'type'=>'association'));
			if($results->num_rows()>0) {
				//update page
				$this->db->limit(1);
				$this->db->where('landlord_id', $this->session->userdata('user_id'));
				$this->db->where('type', 'association');
				$this->db->update('landlord_page_settings', $data);
				if($this->db->affected_rows()>0) {
					$this->session->set_flashdata('success', 'Your page has been updated');
				} else {
					$this->session->set_flashdata('error', 'Something went wrong updating your page or maybe you saved it without changing anything. try again');
				}
			} else {
				//insert page
				$this->db->insert('landlord_page_settings', $data);
				if($this->db->insert_id()>0) {
					$this->session->set_flashdata('success', 'Your page has been created and is now live');
				} else {
					$this->session->set_flashdata('error', 'Something went wrong updating your page or maybe you saved it without changing anything. try again');
				}
			}
		}
		
		function delete_page($id)
		{
			$this->db->delete('public_pages', array('id'=>$id, 'type'=> 'association', 'user_id'=>$this->session->userdata('user_id')));
			if($this->db->affected_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
	}