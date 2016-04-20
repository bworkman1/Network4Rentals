<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Landlord_details extends CI_Model {
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		public function get_landlord_properties($id) 
		{
			$this->db->select('id, title, details, bedrooms, bathrooms, sqFeet, price, deposit, active, address, zipCode, stateAbv, city, lastmodified');
			//$this->db->join('listing_images', 'listings.id = listing_images.listingId', 'left outer');
			$results = $this->db->get_where('listings', array('owner'=>$id));
			$data = array();
			foreach($results->result_array() as $key => $val) {
			
				$address = ucwords(strtolower(htmlspecialchars($val['address']))).' '.ucwords(strtolower(htmlspecialchars($val['city']))).', '.$val['stateAbv'].' '.$val['zipCode'];
				$val['title'] = ucwords(strtolower(htmlspecialchars($val['title'])));
				$val['details'] = htmlspecialchars($val['details']);
				$val['price'] = '$'.number_format($val['price']);
				$val['deposit'] = '$'.number_format($val['deposit']);
				$val['lastmodified'] = date('m-d-Y H:i a', strtotime($val['lastmodified']));
				
				
				
				if($val['active']=='y') {
					$val['active'] = '<span class="label label-success">Yes</label>';
				} else {
					$val['active'] = '<span class="label label-danger">No</label>';
				}
				
				
				unset($val['address']);
				unset($val['city']);
				unset($val['stateAbv']);
				unset($val['zipCode']);
				
				$val['address'] = $address;
		
				$data[] = $val;
			}
		
			return $this->create_table('properties', $data);
		}
		
		public function get_landlords_tenants($id) 
		{	
			$this->db->select('id, rental_address, rental_city, rental_state, rental_zip, move_in, move_out, tenant_id, current_residence, day_rent_due, request_online_payments');
			$results = $this->db->get_where('renter_history', array('link_id' => $id));
			$data = array();
			foreach($results->result_array() as $key => $val) {
				$address = $val['rental_address'].' '.$val['rental_city'].', '.$val['rental_state'].' '.$val['rental_zip'];
				$renter = $this->get_renters_name($val['id']);
				$val['rental_address'] = $address;
				
				if($val['move_in'] != '0000:00:00') {
					$val['move_in'] = date('m-d-Y', strtotime($val['move_in']));
				} else {
					$val['move_in'] = 'NA';
				}
			
				if($val['move_out'] != '0000-00-00') {
					$val['move_out'] = date('m-d-Y', strtotime($val['move_out']));
				} else {
					$val['move_out'] = 'NA';
				}
				
				if($val['current_residence'] != 'y') {
					$val['current_residence'] = '<span class="label label-danger">No</span>';
				} else {
					$val['current_residence'] = '<span class="label label-success">Yes</span>';
				}
				
				if($val['request_online_payments'] != 'y') {
					$val['request_online_payments'] = '<span class="label label-danger">No</span>';
				} else {
					$val['request_online_payments'] = '<span class="label label-success">Yes</span>';
				}
				
				unset($val['rental_city']);
				unset($val['rental_state']);
				unset($val['rental_zip']);
				
				$val['tenant_id'] = ucwords(strtolower(htmlspecialchars($renter->name)));;
				

				$data[] = $val;
			}
			if(!empty($data)) {
				return $this->create_table('tenants', $data);
			} else {
				return false;
			}
		}
		
		private function create_table($heading, $data) 
		{
			$this->load->library('table');
			if($heading == 'properties') {
				$this->table->set_heading('ID', 'Title', 'Description', 'Beds', 'Baths', 'Sq-Feet', 'Price', 'Deposit', 'Active', 'Modified', 'Address');
			} elseif($heading == 'tenants') {
				$this->table->set_heading('ID', 'Address', 'Move In', 'Move Out', 'Tenant', 'Current', 'Rent Due', 'Request OP');
			}
			
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
			if(!empty($data)) {
				return $this->table->generate($data);
			} else {
				return false;
			}
		}
		
		private function get_renters_name($id) 
		{
			$result = $this->db->get_where('renters', array('id'=>$id));
			return $result->row();
		}
		

		
	}
	
	
	
		