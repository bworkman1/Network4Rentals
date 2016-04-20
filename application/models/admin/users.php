<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Users extends CI_Model {
		
		function index()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		function build_group_data($group, $offset) 
		{
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$form_data = $this->validate_search_data();
				if(empty($form_data)) {
					redirect('n4radmin/view-group/'.$group);
					exit;
				}
				$group = strtolower($form_data['user_type']);
			}
		
			$data['links'] = $this->pagination_group($group);
			$data['table'] = $this->gather_group_details($group, $offset, $form_data);
			return $data;
		}

		private function gather_group_details($group, $offset, $form_data = NULL) 
		{
			$group_options = array('landlords', 'renters', 'contractors', 'advertisers');
			if(in_array($group, $group_options))  {
				$this->db->limit(50, $offset);
				
				if($group=='landlords') {
					$select = array('id', 'bName', 'user', 'name', 'email', 'address', 'city', 'state', 'zip', 'phone', 'sign_up', 'hear', 'rental_units', 'applied_auth');
				} elseif($group=='renters') {
					$select = array('renters.id', 'renters.user', 'renters.name', 'renters.email', 'renters.phone', 'renters.sign_up', 'renters.hear', 'renters.sms_msgs', 'renters.zip', 'renter_history.rental_address', 'renter_history.rental_city', 'renter_history.rental_state', 'renter_history.rental_zip', 'renter_history.link_id');
					$this->db->join('renter_history', 'renter_history.tenant_id = renters.id AND renter_history.current_residence = "y"', 'left outer');
				} 
				
				$this->db->select($select);
				
				if(!empty($form_data)) {
			
				}
				
				$order_by = $this->session->userdata('user_sort_by');
				$direction = $this->session->userdata('user_sort_dir');
				
				if($group=='renters') {
					if($order_by == 'zip') {
						$order_by = 'renter_history.rental_zip';
					} 
				} 
			
				if(empty($order_by)) {
					$order_by = 'id';
				}
				if(empty($direction)) {
					$direction = 'desc';
				}
				
				$this->db->order_by($order_by, $direction);
			
				$results = $this->db->get($group);
				$data = $results->result_array();
				$raw_data = array();
				$count = 0;
				foreach($data as $row) {
					
					if(!empty($row['phone'])) {
						$row['phone'] = "(".substr($row['phone'], 0, 3).") ".substr($row['phone'], 3, 3)."-".substr($row['phone'],6);
					} else {
						$row['phone'] = 'NA';
					}
					if(empty($row['hear'])) {
						$row['hear'] = 'NA';
					}
					
					
					if($group=='landlords') {  // SORT THE DATA AND RETURN A TABLE OF DATA
						$row = $this->sort_landlord_data($row, $group);
					
					} elseif($group=='renters') {
						$row = $this->sort_renters_data($row, $group);
					}
					
					$count++;
					$raw_data[] = $row;
				}
				
				$formatted = $this->group_details_table($group, $raw_data);
			
				return $formatted;
			} else {
				return false;
			}
		}

		private function count_total_messages_sent($id, $type)
		{
			$sent_by = '0';
			if($type=='landlord') {
				$sent_by = '1';
				$user = 'landlord_id';
			} else {
				$user = 'tenant_id';
			}
			
			$results = $this->db->get_where('messaging', array('sent_by'=>$sent_by, $user=>$id));
			return $results->num_rows();
		}
		
		private function get_landlord_details($id)
		{
			$this->db->limit(1);
			$this->db->select('bName, name, rental_units');
			$results = $this->db->get_where('landlords', array('id'=>$id));
			if($results->num_rows()>0) {
				return $results->row();
			}
			return false;
		}
		
		private function get_total_past_rentals($id) 
		{
			$this->db->select('id');
			$results = $this->db->get_where('renter_history', array('tenant_id'=>$id, 'current_residence'=>'n'));	
			return $results->num_rows();
		}	
		
		private function get_total_tenants($id) 
		{
			$this->db->select('id');
			$results = $this->db->get_where('renter_history', array('link_id'=>$id, 'current_residence'=>'y'));	
			return $results->num_rows();
		}
		
		private function get_total_properties($id) 
		{
			$this->db->select('id');
			$results = $this->db->get_where('listings', array('owner'=>$id));	
			return $results->num_rows();
		}	
		
		private function get_total_requests($id, $group) 
		{
			$this->db->select('id');

			if($group=='landlords') {
				$results = $this->db->get_where('all_service_request', array('landlord_id'=>$id));	
			} elseif($group=='renters') {
				$results = $this->db->get_where('all_service_request', array('tenant_id'=>$id));	
			}
			return $results->num_rows();
		}
		
		private function sort_renters_data($row, $group)
		{
			if(!empty($row['rental_address'])) {
				$row['address'] = ucwords($row['rental_address'].', '.$row['rental_city'].' '.$row['rental_state'].' '.$row['rental_zip']);
			} else {
				$row['address'] = '<span class="label label-danger">No Current Residence</span>';
			}
			
			$landlord_data = $this->get_landlord_details($row['link_id']);	
			
			if(!empty($landlord_data)) {
				if(empty($landlord_data->bName)) {
					$row['landlord'] = ucwords($landlord_data->name);
				} else {
					$row['landlord'] = ucwords($landlord_data->bName);
				}
			} else {
				$row['landlord'] = 'Not Registered';
			}
			
			if($row['sms_msgs'] == 'y') {
				$row['sms'] = '<span class="label label-success">Yes</span>';
			} else {
				$row['sms'] = '<span class="label label-danger">No</span>';
			}
			
			if(!empty($row['zip'])) {
				$row['rental_zip'] = $row['zip'];
			}
			
			unset($row['rental_address']);
			unset($row['rental_city']);
			unset($row['rental_state']);
			unset($row['link_id']);
			unset($row['sms_msgs']);
			unset($row['zip']);
		
			$row['sign_up'] = date('m-d-Y', strtotime($row['sign_up']));
			$row['past_rentals'] = $this->get_total_past_rentals($row['id']);
			$row['requests'] = $this->get_total_requests($row['id'], $group);
			$row['messages_sent'] = $this->count_total_messages_sent($row['id'], 'renter');
			
			
			$options = '<li><a href="'.base_url('n4radmin/view-user-details/renter/'.$row['id']).'"><i class="fa fa-eye"></i> View User Details</a></li>';
			if($this->session->userdata('superadmin')) {
				$options .= '<li><a href="'.base_url('n4radmin/edit-user/'.$group.'/'.$row['id'].'/').'"><i class="fa fa-pencil"></i> Edit User</a></li>';
				$options .= '<li><a href="#" class="deleteUserBtn" data-toggle="modal" data-target="#deleteUser" data-type="renter" data-id="'.$row['id'].'" data-name="'.$row['name'].'"><i class="fa fa-times"></i> Delete User</a></li>';
			}
			
			$btn = '<div class="dropdown">
					<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu'.$count.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Options
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu'.$count.'">
						'.$options.'
					</ul>
				</div>';
			
			$row['actions'] = $btn;
			
			return $row;
		}
		
		private function sort_landlord_data($row, $group) 
		{
			$row['sign_up'] = date('m-d-Y', strtotime($row['sign_up']));
			$row['city'] = $row['address'].' '.$row['city'].', '.$row['state'].' '.$row['zip'];
			$row['zip'] = $row['zip'];
			$row['properties'] = $this->get_total_properties($row['id']);
			$row['tenants'] = $this->get_total_tenants($row['id']);
			$row['requests'] = $this->get_total_requests($row['id'], $group);
			$row['messages_sent'] = $this->count_total_messages_sent($row['id'], 'landlord');
			$row['public_page'] = $this->public_page_setup('landlord', $row['id']);
			$val['multi_level'] = $this->check_landlord_has_multilevel($val['id']);
			
			unset($row['address']);
			unset($row['state']);
			
			
			
			if($row['multi_level']) {
				$row['multi_level'] = '<span class="label label-success">Yes</span>';
			} else {
				$row['multi_level'] = '<span class="label label-danger">No</span>';
			}
			
			if($row['sms_msgs'] == 'y') {
				$row['sms_msgs'] = '<span class="label label-success">Yes</span>';
			} else {
				$row['sms_msgs'] = '<span class="label label-danger">No</span>';
			}
			
			if($row['public_page']) {
				$row['public_page'] = '<span class="label label-success">Yes</span>';
			} else {
				$row['public_page'] = '<span class="label label-danger">No</span>';
			}
			
			if($row['applied_auth'] == 'y') {
				$row['applied_auth'] = '<span class="label label-success">Yes</span>';
			} else {
				$row['applied_auth'] = '<span class="label label-danger">No</span>';
			}
			
			$options = '<li><a href="'.base_url('n4radmin/view-user-details/landlord/'.$row['id']).'"><i class="fa fa-eye"></i> View User Details</a></li>';
			
			if($this->session->userdata('superadmin')) {
				$options .= '<li><a href="'.base_url('n4radmin/edit-user/'.$group.'/'.$row['id'].'/').'"><i class="fa fa-pencil"></i> Edit User</a></li>';
				$options .= '<li><a href="#" class="deleteUserBtn" data-toggle="modal" data-target="#deleteUser" data-id="'.$row['id'].'" data-name="'.$row['name'].'" data-type="landlord"><i class="fa fa-times"></i> Delete User</a></li>';
			}
			$btn = '<div class="dropdown">
					<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu'.$count.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Options
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu'.$count.'">
						'.$options.'
					</ul>
				</div>';
			$row['actions'] = $btn;
			
			return $row;
		}
		
		private function group_details_table($group, $data) 
		{
			$this->load->library('table');
				
			$sort_by = $this->session->userdata('user_sort_by');
			$sort_dir = $this->session->userdata('user_sort_dir');
			if(!empty($sort_by) && !empty($sort_dir)) {
				if($sort_dir == 'desc') {
					$zip = '<a href="'.base_url('n4radmin/sort-user-group/zip/asc').'">Zip <i class="fa fa-caret-down"></i></a>';
				} else {
					$zip = '<a href="'.base_url('n4radmin/sort-user-group/zip/desc').'">Zip <i class="fa fa-caret-up"></i></a>';
				}
			} else {
				$zip = '<a href="'.base_url('n4radmin/sort-user-group/zip/desc').'">Zip</a>';
			}
			
			if($group=='landlords') {
				$this->table->set_heading('ID', 'Business Name', 'Username', 'Name', 'Email', 'Location', $zip, 'Phone', 'Signed Up', 'Referral', 'Units Owned', 'Online Payments', 'Renters', 'Properties in System', 'Requests', 'Messages', 'SMS', 'Public Page', 'Multi-lvl', 'Actions');
			} elseif($group=='renters') {			
				$this->table->set_heading('ID', 'Username', 'Name', 'Email', 'Phone', 'Signed Up', 'Source', $zip, 'Current Address', 'Landlord', 'SMS', 'Rentals', 'Requests', 'M Sent', 'Actions');
			}
	
			$tmpl = array (
				'table_open'          => '<div class="table-responsive"><table class="table table-bordered table-hover" border="0" cellpadding="4" cellspacing="0" style="white-space: nowrap">',

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

		function total_users_in_group($group) 
		{
			$group_options = array('landlords', 'renters', 'contractors', 'advertisers');
			if(in_array($group, $group_options)) {
				return $this->db->count_all($group);
			} else {
				return false;
			}
		}
		
		function pagination_group($group) 
		{
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
		
		function get_user_details($type, $id)
		{
			$group_options = array('landlords', 'renters', 'contractors', 'advertisers');
			if(in_array($type, $group_options))  {
				$results = $this->db->get_where($type, array('id'=>$id));
				if($results->num_rows()>0) {
							
					return $results->row();
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		function tenant_transactions($id) 
		{
			$this->db->order_by('id', 'desc');
			$this->db->select('id, amount, created, paid_on, status, ref_id, landlord_id, trans_id, payment_type, recurring_payment, disputed, sub_id, start_date, auto_paid');
			$results = $this->db->get_where('payment_history', array('tenant_id' => $id));
			if($results->num_rows()>0) {
				
				$this->load->library('table');
				
				$data = array();
				foreach($results->result_array() as $key => $val) {
				
					if($val['landlord_id']>0) {
						$landlord_data = $this->get_landlord_details($val['landlord_id']);
						if(empty($landlord_data->bName)) {
							$val['landlord_id'] = $landlord_data->name;
						} else {
							$val['landlord_id'] = $landlord_data->bName;
						}
					}
					
					$val['amount'] = '$'.number_format($val['amount']);
					if($val['ref_id']>0) {
						$rental = $this->get_rental_address_by_id($val['ref_id']);
						if(!empty($rental)) {
							$val['address'] = $rental->rental_address.' '.$rental->rental_city.', '.$rental->rental_state.' '.$rental->rental_zip;
						} else {
							$val['address'] = 'NA';
						}
					}
					
					$val['created'] = date('m-d-Y', strtotime($val['created']));
					$val['paid_on'] = date('m-d-Y', strtotime($val['paid_on']));
					
					if(!empty($val['start_date'])) {
						$val['start_date'] = date('m-d-Y', strtotime($val['start_date']));
					} else {
						$val['start_date'] = 'NA';
					}
					
					if(empty($val['trans_id'])) {
						$val['trans_id'] = 'NA';
					}
					
					if($val['recurring_payment'] == 'y') {
						$val['recurring_payment'] = '<span class="label label-success">Yes</span>';
					} else {
						$val['recurring_payment'] = '<span class="label label-danger">No</span>';
					}
					
					if($val['auto_paid'] == 'y') {
						$val['auto_paid'] = '<span class="label label-success">Yes</span>';
					} else {
						$val['auto_paid'] = '<span class="label label-danger">No</span>';
					}
					
					if($val['disputed'] == 'y') {
						$val['disputed'] = '<span class="label label-success">Yes</span>';
					} else {
						$val['disputed'] = '<span class="label label-danger">No</span>';
					}
					if(empty($val['sub_id'])) {
						$val['sub_id'] = 'NA';
					} 
					
					unset($val['ref_id']);
					
					$data[] = $val;
				}
				
				//amount	created	paid_on	status	reason
				$this->table->set_heading('ID', 'Amount', 'Created', 'Paid On', 'Status', 'Landlord', 'Transaction ID', 'Payment Type', 'Auto Pay', 'Disputed', 'Sub ID', 'Start Date', 'Auto Paid', 'Rental Address');
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
				
			} else {
				return false;
			}
		}
		
		function tenant_rental_history($id)
		{
			$this->db->select('rental_address, rental_city,	rental_state, rental_zip, move_in, move_out, link_id, current_residence, lease, payments, deposit, checklist_id, created, request_online_payments, day_rent_due');
			$results = $this->db->get_where('renter_history', array('tenant_id'=>$id));
			if($results->num_rows()>0) {
				$this->load->library('table');
				$requests = $results->result_array();
			
				$data = array();
				
				$count=0;
				foreach($requests as $key => $val) {
					$address = '';
					$values = array();
					foreach($val as $k => $v) {
						if($k == 'created' || $k == 'move_in' || $k == 'move_out') {
							if(!empty($v)) {
								if($v != '0000-00-00 00:00:00') {
									$v = date('m-d-Y', strtotime($v));
								} else {
									$v = 'NA';
								}
							} else {
								$v = 'NA';
							}
						}
						
						if($k == 'checklist_id') {
							if(empty($v)) {
								$v = '<span class="label label-danger">No</span>';
							} else {
								$v = '<span class="label label-success">Yes</span>';
							}
						}
						
						if($k == 'payments' || $k == 'deposit') {
							$v = '$'.number_format($v, 2);
						}
						
						if($k == 'day_rent_due') {
							$v = $v.$this->ordinal_suffix($v);
						}
						
						if($k == 'request_online_payments') {
							if($v == 'n') {
								$v = 'No';
							} else {
								$v = 'Yes';
							}
						}
						
						if($k == 'current_residence') {
							if($v == 'y') {
								$v = '<span class="label label-success">Yes</span>';
							} else {
								$v = '<span class="label label-danger">No</span>';
							}
						}
						
						if($k == 'rental_address' || $k == 'rental_city' || $k == 'rental_state' || $k == 'rental_zip') {
							$address .= $v.' ';
							$v = '';
							unset($k);
						}
						
						if($k == 'link_id') {
							$landlord = $this->get_landlord_details($v);
							if(!empty($landlord)) {
								if(empty($landlord->bName)) {
									$v = ucwords($landlord->name);
								} else {
									$v = ucwords($landlord->bName);
								}
							} else {
								$v = 'Un-Registered';
							}
						}
						
						if($v != '') {
							$values[$k] = $v;	
						}
						
					}
					$renterAddress['address'] = $address;
					
					$data[$count] = $renterAddress + $values;
					$count++;
				}
			
				$this->table->set_heading('Address', 'Moved In', 'Moved Out', 'Landlord', 'Current', 'Lease', 'Rent', 'Deposit', 'Checklist', 'Created', 'Requested OP', 	'Rent Due');	
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
			} else {
				return false;
			}
		}

		function service_requests($id, $type)
		{
			$this->db->select('service_type, enter_permission, description, schedule_phone, submitted, viewed, completed, landlord_id, rental_id');
			$results = $this->db->get_where('all_service_request', array($type=>$id));
			if($results->num_rows()>0) {
				$requests = $results->result_array();

				$data = array();
				$count=0;
				foreach($requests as $key => $val) {
					foreach($val as $k => $v) {
						if($k == 'submitted' || $k == 'completed' || $k == 'viewed') {
							if($v != '0000-00-00 00:00:00') {
								$v = date('m-d-Y h:i a', strtotime($v));
							} else {
								$v = 'NA';
							}
						}
						
						if($k == 'schedule_phone') {
							$v = "(".substr($v, 0, 3).") ".substr($v, 3, 3)."-".substr($v,6);
						}
						
						if($k == 'service_type') {
							$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
							$v = $services_array[$v];
						}
						
						if($k == 'landlord_id') {
							$landlord = $this->get_landlord_details($v);
							if(empty($landlord->bName)) {
								$v = ucwords($landlord->name);
							} else {
								$v = ucwords($landlord->bName);
							}
						}
						
						if($k == 'rental_id') {
							$rental = $this->get_rental_address_by_id($v);
							if(empty($rental)) {
								$v = 'NA';
							} else {
								$v = ucwords($rental->rental_address.', '.$rental->rental_city.' '.$rental->rental_state.'. '.$rental->rental_zip);
							}
						}
						
						if($k == 'enter_permission') {
							$v = ucwords($v);
						}
						$data[$count][$k] = $v;	
					}
					$count++;
				}
				
				$this->load->library('table');
				
				$this->table->set_heading('Type', 'Permission', 'Description', 'Phone #', 'Submitted', 'Viewed', 'Completed', 'Landlord', 'Address');
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
			} else {
				return false;
			}
		}
		
		private function get_rental_address_by_id($rent_id) 
		{
			$this->db->select('rental_address, rental_city, rental_state, rental_zip');
			$results= $this->db->get_where('renter_history', array('id'=>$rent_id));
			if($results->num_rows()>0) {
				return $results->row();
			}
			return false;
			
		}
		
		private function ordinal_suffix($num)
		{
			$num = $num % 100;
			if($num < 11 || $num > 13){
				 switch($num % 10){
					case 1: return 'st';
					case 2: return 'nd';
					case 3: return 'rd';
				}
			}
			return 'th';
		}
		
		private function public_page_setup($type, $id) 
		{
			$results = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$id, 'type'=>$type));
			if($results->num_rows()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		private function check_landlord_has_multilevel($id) 
		{
			$results = $this->db->get_where('admin_groups', array('main_admin_id'=>$id));
			if($results->num_rows()>0) {
				return true;
			}
			return false;
		}
		
		private function validate_search_data()
		{
		
			$this->form_validation->set_rules('searchFor', 'Search For', 'xss_clean|min_length[3]|max_length[30]');
			$this->form_validation->set_rules('searchBy', 'Search By', 'xss_clean|min_length[4]|max_length[30]|alpha');
			$this->form_validation->set_rules('source', 'Source/Referral', 'xss_clean|min_length[4]|max_length[40]');	
			$this->form_validation->set_rules('user_type', 'User Type', 'required|xss_clean|min_length[1]|max_length[20]|alpha');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				return false; 
			} else {
				extract($_POST);
				
				$isValidSelection = true;
			
				$allowed_types = array('renters', 'landlords', 'advertisers', 'renters');
				if(!in_array($user_type, $allowed_types)) {
					$this->session->set_flashdata('error', 'Invalid user type selection');
					$isValidSelection = false;
				}
				
				if(empty($searchFor) || empty($searchBy) || empty($source)) {
					$this->session->set_flashdata('error', 'You have to search for something');
					return false;
				} else {
					$allowed_types = array('user', 'name', 'email', 'phone', 'city', 'state', 'zip');
					if(!in_array($searchFor, $allowed_types)) {
						$this->session->set_flashdata('error', 'Invalid Search By Selection');
						$isValidSelection = false;
					}
				}
				
				if($isValidSelection) {
					$data = array('searchFor'=>$searchFor, 'searchBy'=>$searchBy, 'source'=>$source, 'user_type'=>$user_type);
					return $data;
				} else {
					return false;
				}
			}
			
		}
		
	}