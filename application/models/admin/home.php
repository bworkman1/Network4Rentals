<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Home extends CI_Model {
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		    /* MOVE THESE TO MODEL */
		function user_sums() {
			$data = array();
			$data['landlords'] = $this->get_landlords_sum();
			$data['renters'] = $this->get_renters_sum();
			$data['contractors'] = $this->get_contractors_sum();
			$data['advertisers'] = $this->get_advertisers_sum();
			$data['total_messages'] = $this->get_messages_sum();
			$data['total_service_reqeuests'] = $this->get_service_request_sum();
			$data['user_sum'] = $data['landlords']+$data['renters']+$data['contractors']+$data['advertisers'];
			return $data;
		}
		
		function recent_transactions() {
			$this->db->limit(10);
			$this->db->order_by('ts', 'desc');
			$results = $this->db->get('contractor_purchases');
			return $results->result();
		}

		private function get_messages_sum() 
		{
			$this->db->select('id');
			$results = $this->db->get('messaging');
			return $results->num_rows();	
		}
		
		private function get_service_request_sum() 
		{
			$this->db->select('id');
			$results = $this->db->get('all_service_request');
			return $results->num_rows();	
		}
		
		private function get_landlords_sum(){
			$this->db->select('id');
			$results = $this->db->get('landlords');
			return $results->num_rows();
		}

		private function get_renters_sum(){
			$this->db->select('id');
			$results = $this->db->get('renters');
			return $results->num_rows();
		}

		private function get_contractors_sum(){
			$this->db->select('id');
			$results = $this->db->get('contractors');
			return $results->num_rows();
		}

		private function get_advertisers_sum(){
			$this->db->select('id');
			$results = $this->db->get('advertisers');
			return $results->num_rows();
		}

		function build_group_data($group, $offset) {
			$data['links'] = $this->pagination_group($group);
			$data['table'] = $this->gather_group_details($group, $offset);
			return $data;
		}

		private function gather_group_details($group, $offset) {
			$group_options = array('landlords', 'renters', 'contractors', 'advertisers');
			if(in_array($group, $group_options))  {
				$this->db->limit(20, $offset);
				if($group=='landlords') {
					$select = array('id', 'user', 'name', 'email', 'city', 'state', 'zip', 'phone');
				}
				$this->db->select($select);
				$results = $this->db->get($group);
				$data = $results->result_array();
				$raw_data = array();
				foreach($data as $row) {
					if($group=='landlords') {
						
						$row['city'] = $row['city'].', '.$row['state'].' '.$row['zip'];
						$row['properties'] = $this->get_total_properties($row['id']);
						$row['tenants'] = $this->get_total_tenants($row['id']);
						$row['requests'] = $this->get_total_requests($row['id']);
						
						unset($row['state']);
						unset($row['zip']);
						$row['actions'] = '<a href="#" class="toolTips btn btn-sm btn-primary" title="Edit User Details"><i class="fa fa-pencil"></i></a> | <a href="#" class="toolTips btn btn-sm btn-primary" title="View User Profile"><i class="fa fa-eye"></i></a>';
						if($this->session->userdata('superadmin')) {
							$row['actions'] .= '| <a href="#" class="toolTips btn btn-sm btn-danger" title="Delete User"><i class="fa fa-times"></i></a>';
						}
					}
					
					$raw_data[] = $row;
				}
				$formatted = $this->group_details_table($group, $raw_data);
				return $formatted;
			} else {
				return false;
			}
		}

		private function get_total_tenants($id) 
		{
			$this->db->select('id');
			$results = $this->db->get_where('renter_history', array('link_id'=>$id));	
			return $results->num_rows();
		}
		
		private function get_total_properties($id) 
		{
			$this->db->select('id');
			$results = $this->db->get_where('listings', array('owner'=>$id));	
			return $results->num_rows();
		}	
		
		private function get_total_requests($id) 
		{
			$this->db->select('id');
			$results = $this->db->get_where('all_service_request', array('landlord_id'=>$id));	
			return $results->num_rows();
		}
		
		private function group_details_table($group, $data) {
			$this->load->library('table');
			if($group=='landlords') {
				$this->table->set_heading('<a href="'.base_url().'">ID</a>', 'Username', 'Name', 'Email', 'Location', 'Phone', 'Renters', 'Properties', 'Requests', 'Actions');
			}
		   // $this->table->set_heading('ID, Username, Name, Email, IP, ');
			$tmpl = array (
				'table_open'          => '<div class="table-responsive"><table class="table table-bordered table-hover" border="0" cellpadding="4" cellspacing="0">',

				'heading_row_start'   => '<tr>',
				'heading_row_end'     => '</tr>',
				'heading_cell_start'  => '<th>',
				'heading_cell_end'    => '</th>',

				'row_start'           => '<tr>',
				'row_end'             => '</tr>',
				'cell_start'          => '<td>',
				'cell_end'            => '</td>',

				'row_alt_start'       => '<tr>',
				'row_alt_end'         => '</tr>',
				'cell_alt_start'      => '<td>',
				'cell_alt_end'        => '</td>',

				'table_close'         => '</table></div>'
			);

			$this->table->set_template($tmpl);

			return $this->table->generate($data);
		}

		function total_users_in_group($group) {
			$group_options = array('landlords', 'renters', 'contractors', 'advertisers');
			if(in_array($group, $group_options)) {
				$this->db->select('id');
				$results = $this->db->get($group);
				return $results->num_rows();
			} else {
				return false;
			}
		}

		function pagination_group($group) {
			$this->load->library('pagination');

			$config['base_url'] = base_url().'n4radmin/view-group/'.$group.'/';
			$config['total_rows'] = $this->total_users_in_group($group);
			$config['per_page'] = 20;
			$config['uri_segment'] = 4;
			$config['full_tag_open'] = '<div><ul class="pagination">';
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

			return $this->pagination->create_links();
		}

		public function active_users($sums)
		{
			unset($sums['user_sum']);
			unset($sums['total_service_reqeuests']);
			unset($sums['total_messages']);
			
			$active_users = array();
			foreach($sums as $key => $val) {
				if($key == 'contractors') {
					$key = 'contractor';
					//$this->db->where('test', 'test');
				}
				$this->db->select('id');
				$this->db->where('type', $key);
				$this->db->where("created >", "'".date('Y-m-d', strtotime("-60 days"))."'",FALSE);
				$this->db->group_by('user_id');
				$query = $this->db->get('activity');
				$count = $query->num_rows();
				if($count>0) {
					$active_users[$key] = $val-$count;
				} else {
					$active_users[$key] = $count;
				}
			}
			
			return $active_users;
			
			
			/*
			$feed = Array
				(
					[landlords] => 153
					[renters] => 331
					[contractors] => 12
					[advertisers] => 2
					[total_messages] => 492
					[total_service_reqeuests] => 292
					[user_sum] => 498
				); */
		}

	}