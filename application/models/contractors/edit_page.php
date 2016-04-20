<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Edit_page extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
	
		public function page_details($id) 
		{
			$result =$this->db->get_where('public_pages', array('id'=>$id, 'user_id'=>$this->session->userdata('user_id')));
			return $result->row();
		}
	
		public function update() 
		{
			$this->form_validation->set_rules('post', 'Post Is Required', 'required|trim|min_length[20]|max_length[6000]');
			$this->form_validation->set_rules('id', 'Page Id', 'required|trim|min_length[1]|max_length[11]|integer');
			$this->form_validation->set_rules('name', 'Page Name', 'required|trim|min_length[3]|max_length[50]');
			$this->form_validation->set_rules('seo_description', 'SEO Description', 'trim|max_length[160]|xss_clean');
			$this->form_validation->set_rules('seo_keywords', 'SEO Keywords', 'trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('name', 'Page Name', 'required|trim|min_length[3]|max_length[50]');

			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
			} else {
				extract($_POST);
				$ts = date('Y-m-d H:i:s');
				//$this->load->helper('htmlpurifier');
				$post = str_replace('&nbsp;', ' ', $_POST['post']);
				//$post = html_purify($post);
				$this->db->where('id', $id);
				$this->db->where('user_id', $this->session->userdata('user_id'));
				$this->db->update('public_pages', array('name'=>$name, 'page'=>$post, 'ts'=>$ts, 'seo_description' => $seo_description, 'seo_keywords'=>$seo_keywords, 'url_slug'=>url_title($name, '-', TRUE)));
				if($this->db->affected_rows()>0) {
					$this->session->set_flashdata('success', 'Page updated successfully');
				} else {
					$this->session->set_flashdata('error', 'No changes where made to this page');
				}
			}
		}
		
	}