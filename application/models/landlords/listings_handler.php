<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listings_handler extends CI_Model {
	
	private $listing_expires;
	
	function listings_handler()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function set_default_payment_settings($renterId) 
	{
		$this->db->select('default_partial_payments, default_min_payment, default_auto_pay_discount, online_payment_discount');
		$query = $this->db->get_where('landlords', array('id' => $this->session->userdata('user_id')));
		$paymentSettings = $query->row();

		$this->db->where('id', $renterId);
		$this->db->limit(1);
		$this->db->update('renter_history', array(
			'discount_payment'=>$paymentSettings->online_payment_discount, 
			'auto_pay_discount'=>$paymentSettings->default_auto_pay_discount,
			'min_payment'=>$paymentSettings->default_min_payment,
			'partial_payments'=>$paymentSettings->default_partial_payments,
		));
	}
	
	function add_listing($data, $file_names, $file_details)
	{
		$group_id = $this->session->userdata('temp_id');
		
		if(!empty($group_id)) {
			$this->db->select('main_admin_id');
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				$data['owner'] = $row->main_admin_id;
			} else {
				return false;
			}
		}	
		
		$query = $this->db->get_where('zips', array('zipCode'=>$data['zipCode']));
		if ($query->num_rows() > 0) {
			$row = $query->row(); 
			$data['latitude'] = $row->latitude;
			$data['longitude'] = $row->longitude;
		}
	
		$this->db->insert('listings', $data);
		
		$checklist_id = $this->db->insert_id();
		$sql = "INSERT INTO listing_images (image1, image2, image3, image4, image5, listingId, featured_image, desc_1, desc_2, desc_3, desc_4, desc_5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		for($i=0;$i<5;$i++) {
			if(empty($file_names[$i])) {
				$file_names[$i] = '';
			}
		}
		$listing_image_details = array($file_names[0], $file_names[1], $file_names[2], $file_names[3], $file_names[4], $checklist_id, $file_details['featured_image'], $file_details['desc_1'], $file_details['desc_2'], $file_details['desc_3'], $file_details['desc_4'], $file_details['desc_5']);
		
		$this->db->query($sql, $listing_image_details);
		if($this->db->affected_rows()>0) {
			return $checklist_id;
		} else {
			return false;
		}
	}
	
	function listing_count() 
	{
		
		$group_id = $this->session->userdata('temp_id');
		if(!empty($group_id)) {
			$sql = "SELECT COUNT(id) AS total FROM listings WHERE owner = ? AND contact_id = ?";
		} else {
			$sql = "SELECT COUNT(id) AS total FROM listings WHERE owner = ? AND (contact_id IS NULL OR contact_id = 0)";
		}
		$id = $this->session->userdata('user_id');
		$query = $this->db->query($sql, array($id, $group_id));
		$row = $query->row();
		return $row->total;	
	}
	
	function listing_avaliable_count() 
	{
		$sql = "SELECT COUNT(id) AS total FROM listings WHERE owner = ? AND active = 'y'";
		$group_id = $this->session->userdata('temp_id');
		if(!empty($group_id)) {
			$this->db->select('main_admin_id');
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				$id = $row->main_admin_id;
			} else {
				return false;
			}
		} else {
			$id = $this->session->userdata('user_id');
		}
		$query = $this->db->query($sql, array($id));
		$row = $query->row();
		return $row->total;	
	}
	
	function manage_listing_header()
	{
		$temp_id = $this->session->userdata('temp_id');
		if($temp_id>0) {
			$this->db->select('sub_b_name');
			$result = $this->db->get_where('admin_groups', array('id'=>$temp_id));
			if($result->num_rows()>0) {
				$row = $result->row();
				return $row->sub_b_name;
			}
		}
		
	}
	
	function update_listing_group_id($group_id, $listing_id) 
	{
		if($group_id >0) {
			//check to make sure there is a valid connection before assigning the property over to manager
			$results = $this->db->get_where('admin_groups', array('main_admin_id'=>$this->session->userdata('user_id'), 'id'=>$group_id ));
			if($results->num_rows()>0) {
				$row = $results->row();
				//Manager relationship found
				$this->db->where('id', $listing_id);
				$this->db->update('listings', array('contact_id'=>$row->sub_admins));
				if($this->db->affected_rows()>0) {
					$this->load->model('special/add_activity');
					$this->add_activity->add_new_activity('New property add to a group you manage', $row->sub_admins, 'landlords', $listing_id, $row->id);
					return array('success'=>'Listing transferred to the manager successfully');
				} else {
					return array('error'=>'Listing failed to update, try again');
				}
			} else {
				return array('error'=>'You must be the owner of this property in order to switch it to another user');
			}
		} else {
			$this->db->limit(1);
			$this->db->where('owner', $this->session->userdata('user_id'));
			$this->db->where('id', $listing_id);
			$this->db->update('listings', array('contact_id'=>NULL));
			if($this->db->affected_rows()>0) {
				return array('success'=>'Listing transferred to the manager successfully');
			} else {
				return array('error'=>'Listing failed to update, try again');
			}
		}
	}
	
	function manage_all_listings($limits, $starts) 
	{
		$id = $this->session->userdata('user_id');
		$group_id = $this->session->userdata('temp_id');
		
		if(!empty($group_id)) {
			$id = $this->get_admin_id($group_id);
			$this->db->where('contact_id', $group_id);
		} else {
			$where="(`contact_id` IS NULL OR `contact_id` = '0')";
			$this->db->where($where, NULL, FALSE);
		}
		
		$this->db->select('active, id, address, zipCode, title, bedrooms, bathrooms, city, stateAbv');
		$this->db->order_by('id', 'desc');
		
		$this->db->limit($limits, $starts);
		$query = $this->db->get_where('listings', array('owner'=>$id, 'deleted'=>'n'));
		
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row) {
				$this->db->select('image1, image2, image3, image4, image5, featured_image, desc_1, desc_2, desc_3, desc_4, desc_5');
				$this->db->where('listingId', $row->id);
				$queryer = $this->db->get('listing_images');
				$img_return = '';
				if ($queryer->num_rows() > 0) {
					$rower = $queryer->row();
		
					switch($rower->featured_image) {
						case '1':
							$img_return = $rower->image1;
							break;
						case '2':
							$img_return = $rower->image2;
							break;
						case '3':
							$img_return = $rower->image2;
							break;
						case '4':
							$img_return = $rower->image4;
							break;
						case '5':
							$img_return = $rower->image5;
							break;
					}
				}
				$row->img_show = $img_return;
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	function manage_all_listings_avaliable($limits, $starts) 
	{
		$id = $this->session->userdata('user_id');
		$group_id = $this->session->userdata('temp_id');
		
		
		
		if(!empty($group_id)) {
			$id = $this->get_admin_id($group_id);
			$this->db->where('contact_id', $group_id);
		} else {
			$this->db->where('contact_id', NULL);
		}
		$this->db->where('active', 'y');
		$this->db->select('active, id, address, zipCode, title, bedrooms, bathrooms, city, stateAbv');
		$this->db->order_by('id', 'desc');
		
		$this->db->limit($limits, $starts);
		$query = $this->db->get_where('listings', array('owner'=>$id, 'deleted'=>'n'));
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row) {
				$this->db->select('image1, image2, image3, image4, image5, featured_image, desc_1, desc_2, desc_3, desc_4, desc_5');
				$this->db->where('listingId', $row->id);
				$queryer = $this->db->get('listing_images');
				$img_return = '';
				if ($queryer->num_rows() > 0) {
					$rower = $queryer->row();
		
					switch($rower->featured_image) {
						case '1':
							$img_return = $rower->image1;
							break;
						case '2':
							$img_return = $rower->image2;
							break;
						case '3':
							$img_return = $rower->image2;
							break;
						case '4':
							$img_return = $rower->image4;
							break;
						case '5':
							$img_return = $rower->image5;
							break;
					}
					
				}
				$row->img_show = $img_return;
				$data[] = $row;
				
			}
			return $data;
		}
		return false;
	}
	
	function change_listing_status($id, $state) 
	{
		$landlord_id = $this->session->userdata('user_id');
		$group_id = $this->session->userdata('temp_id');
		if(!empty($group_id)) {
			$this->db->select('main_admin_id');
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				$landlord_id = $row->main_admin_id;
			} 
		}
		$sql = "UPDATE listings SET active = ? WHERE id = ? AND owner = ? LIMIT 1";
		if($state == 1) {
			$status = 'y';
		} else {
			$status = 'n';
		}

		$query = $this->db->query($sql, array($status, $id, $landlord_id));
		if($this->db->affected_rows() > 0) {
			return $status;
		} else {
			return $status;
		}
	}
	
	function public_link_caller()
	{
		$temp_id = $this->session->userdata('temp_id');
		if(!empty($temp_id)) {
			$id = $this->get_admin_id($temp_id);
			return $this->get_public_link_landlord($id);
		} else {
			return $this->get_public_link_landlord($this->session->userdata('user_id'));
		}
	}
	
	function get_public_link_landlord($id) 
	{
		$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$id, 'type'=>'landlord'));
		if($query->num_rows()>0) {
			$row = $query->row();
			return $row->unique_name;
		}
	}
	
	function get_listing_images($image_id) 
	{	
		$this->db->select('image1, image2, image3, image4, image5, featured_image, desc_1, desc_2, desc_3, desc_4, desc_5');
		$this->db->where('listingId', $image_id);
		$this->db->limit(1);
		$query = $this->db->get('listing_images');
		if($query->num_rows()>0){
			$row = $query->row_array();
			return $row;
		} else {
			return false;
		}
	}
	
	function edit_listing($id) 
	{
		$group_id = $this->session->userdata('temp_id');
		if(!empty($group_id)) {
			$this->db->select('main_admin_id');
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				$this->db->where('owner', $row->main_admin_id);
			} else {
				$this->db->where('owner', $this->session->userdata('user_id'));
			}
		} else {
			$this->db->where('owner', $this->session->userdata('user_id'));
		}
		$this->db->where('id', $id);
		
		$this->db->limit(1);
		$query = $this->db->get('listings');
		if($query->num_rows() > 0){
			$row = $query->row_array();
			$images_details = $this->get_listing_images($row['id']);
			if(!empty($images_details)) {
				foreach($images_details as $key => $val) {
					$row[$key] = $val;
				}
			} else {
				$row['image1'] = '';
				$row['image2'] = '';
				$row['image3'] = '';
				$row['image4'] = '';
				$row['image5'] = '';
				$row['featured_image'] = '';
				$row['desc_1'] = '';
				$row['desc_2'] = '';
				$row['desc_3'] = '';
				$row['desc_4'] = '';
				$row['desc_5'] = '';
			}
			return $row;
		} else {
			return false;
		}
	}
	
	function update_listing_info($listing_info, $img_details)
	{	
		$query = $this->db->get_where('zips', array('zipCode'=>$listing_info['zipCode']));
		if ($query->num_rows() > 0) {
			$row = $query->row(); 
			$listing_info['latitude'] = $row->latitude;
			$listing_info['longitude'] = $row->longitude;
		}
		
		$superId = $listing_info['id'];
		unset($listing_info['id']);
		$group_id = $this->session->userdata('temp_id');
		if(!empty($group_id)) {
			$this->db->select('main_admin_id');
			$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
			if($query->num_rows()>0) {
				$row = $query->row();
				$this->db->where('owner', $row->main_admin_id);
			} else {
				$this->db->where('owner', $this->session->userdata('user_id'));
			}
		} else {
			$listing_info['contact_id'] = NULL;
			$this->db->where('owner', $this->session->userdata('user_id'));
		}
		
		
		$this->db->where('id', $superId);
		$this->db->update('listings', $listing_info);
	
		$this->db->where('listingId', $superId);
		$this->db->update('listing_images', $img_details);
		if($this->db->affected_rows()<1) {
			$img_details['listingId'] = $superId;
			$this->db->insert('listing_images', $img_details);
		}
			
		return true;
	}
	
	function get_listing_info($id) 
	{
		$this->db->limit(1);
		$query = $this->db->get_where('listings', array('owner'=>$this->session->userdata('user_id'), 'id'=>$id));
		if ($query->num_rows() > 0){
			return $query->row();
			
		} else {
			return false;
		}
	}
	
	function delete_listing($id)
    {
        $this->db->where("id", $id);
        $this->db->where("owner", $this->session->userdata('user_id'));
		
		$group_id = $this->session->userdata('temp_id');
		if(!empty($group_id)) {
			$this->db->or_where("contact_id", $this->session->userdata('temp_id'));
		}
		$this->db->limit(1);
        $this->db->update('listings', array('deleted'=>'y', 'active'=>'n'));
		if($this->db->affected_rows() > 0) {
			/*
			$images = $this->get_listing_images($id);
			$img_delete_array = array($images['image1'], $images['image2'], $images['image3'], $images['image4'], $images['image5']);
			foreach($img_delete_array as $val) {
				if(!empty($val)) {
					$path = APPPATH.'../listing-images/'.$val;
					unlink($path);
				}
			}
			/*
			$this->db->delete('listing_images', array('id' => $id)); 			
			// update renter_history to set the listing id to null
			$this->db->where('listing_id', $id);
			$this->db->update('renter_history', array('listing_id'=>NULL)); 
			// update all service request to null that are attached to this property
			$this->db->where('listing_id', $id);
			$this->db->update('all_service_request', array('listing_id'=>NULL)); 
			// Delete all home items that below to this listing
			$this->db->where('listing_id', $id);
			$this->db->delete('home_items');
			// Delete Service Request That The Landlord Has Added That Go With This Property Listing (not ones the tenant added)
			$this->db->where('listing_id', $id);
			$this->db->where('rental_id', NULL);
			$this->db->delete('all_service_request');
			*/
			return true;
		} else {
			return false;
		}
    }
	
	function add_tenant_property($data)
	{
		$temp_id = $this->session->userdata('temp_id');
		if(empty($temp_id)) {
			$temp_id = NULL;
			$id = $this->session->userdata('user_id');
		} else {
			$id = $this->get_admin_id($this->session->userdata('temp_id'));
		}
		
		if(empty($data['existing'])) { //create new listing
			$data['owner'] = $id;
			$data['contact_id'] = $temp_id;
			$rental_id = $data['rental_id'];
			
			$images_array = $data['images'];
			$featured_image = $data['featured_image'];
			unset($data['rental_id']);
			unset($data['images']);
			unset($data['existing']);
			unset($data['featured_image']);
			$query = $this->db->get_where('zips', array('zipCode'=>$data['zipCode']));
			if ($query->num_rows() > 0) {
				$row = $query->row(); 
				$data['latitude'] = $row->latitude;
				$data['longitude'] = $row->longitude;
			}
	
			$this->db->insert('listings', $data); 
			$insert_id = $this->db->insert_id();
			if(!empty($insert_id)) {
				$this->db->where('id', $rental_id);
				$this->db->update('renter_history', 
					array(
						'listing_id'=>'', 
						'address_locked'=>'0',
						'rental_address'=>$data['address'],
						'rental_city'=>$data['city'],
						'rental_state'=>$data['stateAbv'],
						'rental_zip'=>$data['zipCode']
					)
				);

				$img_list = array('listingId'=>$insert_id, 'featured_image'=>$featured_image);
				if(!empty($images_array)) {
					$count = 1;
					foreach($images_array as $val) {
						$img_list['image'.$count] = $val;
						$count++;
					}
				}
				
				$this->db->insert('listing_images', $img_list); 
				
			
					
				$this->db->where('id', $rental_id);
				$this->db->update('renter_history', 
					array(
						'address_locked'=>'1',
						'rental_address'=>$data['address'],
						'rental_city'=>$data['city'],
						'rental_state'=>$data['stateAbv'],
						'rental_zip'=>$data['zipCode'],
						'listing_id'=>$insert_id
					)
				);
					
				
				return true;
			} else {
				return false;
			}
		} else { //adding id to the rental id and locking the address on the tenant side
			$query = $this->db->get_where('listings', array('id'=>$data['existing'], 'owner'=>$id));
			if ($query->num_rows() > 0) {
				$row = $query->row(); 
				$update = array(
					'listing_id'=>$data['existing'], 
					'address_locked'=>'1',
					'rental_address'=>$row->address,
					'rental_city'=>$row->city,
					'rental_state'=>$row->stateAbv,
					'rental_zip'=>$row->zipCode,
					'group_id' => $this->session->userdata('temp_id')
				);
				$this->db->where('id', $data['rental_id']);
				$this->db->update('renter_history', $update);
				return true;
			} else {
				return false;
			}
		}
	}
	
	function edit_rental_info($data, $id)
	{

		$updated = true;
		$move_in = date('Y-m-d', strtotime(str_replace('-', '/', $data['move_in'])));
		
		if(!empty($data['move_out'])) {
			$move_out = date('Y-m-d', strtotime(str_replace('-', '/', $data['move_out'])));
			$current_residence = 'n';
		} else {
			$move_out = '';
			$current_residence = 'y';
		}
		$info = array('rental_address'=>$data['address'], 'rental_city'=>$data['city'], 'rental_state'=>$data['state'], 'rental_zip'=>$data['zip'], 'move_in'=>$move_in, 'move_out'=>$move_out, 'lease'=>$data['lease'], 'payments'=>$data['payments'], 'address_locked'=>'1', 'deposit'=>$data['deposit'], 'group_id'=>$data['group_id'], 'current_residence'=>$current_residence, 'day_rent_due'=>$data['day_rent_due']);
		
		if(isset($data['lease_upload'])) {
			$info['lease_upload'] = $data['lease_upload'];
		}
		
		$this->db->where('id', $id);
		$this->db->update('renter_history', $info);
		if ($this->db->trans_status() === FALSE) {
			$updated = false;
		} else {
			$this->db->select('listing_id');
			$this->db->where('id', $id);
			$query = $this->db->get('renter_history');
			if ($query->num_rows() > 0) {
				$row = $query->row(); 
				if(!empty($row->listing_id)) {
					$listing_info = array('address'=>$data['address'], 'city'=>$data['city'], 'stateAbv'=>$data['state'], 'zipCode'=>$data['zip'], 'price'=>$data['payments']);
					$query = $this->db->get_where('zips', array('zipCode'=>$listing_info['zipCode']));
					if ($query->num_rows() > 0) {
						$row = $query->row(); 
						$listing_info['latitude'] = $row->latitude;
						$listing_info['longitude'] = $row->longitude;
					}
			
					$this->db->where('id', $row->listing_id);
					$this->db->update('listings', $listing_info); 
					if ($this->db->trans_status() === FALSE) {
						$updated = false;
					}
				}
			} 
		}
		if($updated) {
			return true;
		} else {
			return false;
		}
	}
	
	function add_rental_item($data)
	{
		$temp_id = $this->session->userdata('temp_id');
		if(empty($temp_id)) {
			$id = $this->session->userdata('user_id');
			$query = $this->db->get_where('renter_history', array('id'=>$data['renter_id'], 'link_id'=>$id));
		} else {
			$id = $this->session->userdata('temp_id');
			$query = $this->db->get_where('renter_history', array('id'=>$data['renter_id']));
		}
	
		if ($query->num_rows() > 0) {
		
			$row = $query->row(); 
			if(empty($temp_id)) {
				$listing_id = $row->listing_id;
				if(empty($listing_id)) {
					$listing_id = $data['listing_id'];
				}
				$query = $this->db->get_where('listings', array('id'=>$listing_id, 'owner'=>$id));
				
			} else {
				$id = $this->get_admin_id($id); 
				$query = $this->db->get_where('listings', array('id'=>$data['renter_id'], 'owner'=>$id));
			}
			
			if ($query->num_rows() > 0) {
				unset($data['renter_id']);
				$row = $query->row();
				$data['listing_id'] = $row->id;
				$query = $this->db->insert('home_items', $data); 
				if ($this->db->trans_status() === FALSE) {
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		} else {
			$query = $this->db->get_where('listings', array('id'=>$data['renter_id'], 'owner'=>$id));
			if ($query->num_rows() > 0) {
				unset($data['renter_id']);
				$row = $query->row();
				$data['listing_id'] = $row->id;
				$query = $this->db->insert('home_items', $data); 
				if ($this->db->trans_status() === FALSE) {
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
	}
	
	function get_items_at_property($id) 
	{
		$query = $this->db->get_where('home_items', array('listing_id'=>$id));
		if ($query->num_rows() > 0) {
			$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
			$row = $query->result_array();
	
			return $row;
		} else {
			return false;
		}
	}
	
	function get_items_at_property_json($id) 
	{
		$query = $this->db->get_where('home_items', array('listing_id'=>$id));
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			return json_encode($query->result_array());
		} else {
			return false;
		}
	}
	
	public function edit_rental_item($data) 
	{
		$this->db->where('id', $data['id']);
		unset($data['id']);
		$this->db->update('home_items', $data);
		if($this->db->affected_rows()>0) {
			return true;
		}
		return false;
	}
	
	function get_single_property_item($id) 
	{
		$query = $this->db->get_where('home_items', array('id'=>$id));
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}
	
	function delete_rental_item($ids) 
	{
		$temp_id = $this->session->userdata('temp_id');
		if(empty($temp_id)) {
			$id = $this->session->userdata('user_id');
		} else {
			$id = $this->session->userdata('temp_id');
		}
		$query = $this->db->get_where('home_items', array('id'=>$ids));
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$query = $this->db->get_where('listings', array('id'=>$row->listing_id));
			if ($query->num_rows() > 0) {
				$row = $query->row();
				if($row->owner == $id) {
					$this->db->where('id', $ids);
					$this->db->delete('home_items'); 
					return true;
				}
				return false;
			}
			return false;
		}
		return false;
	}
	
	function get_admin_id($group_id) 
	{	
		$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
		if($query->num_rows()>0) {
			$row = $query->row();
			return $row->main_admin_id;
		}
	}
	
	function get_sub_admin_id($group_id) 
	{	
		$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
		if($query->num_rows()>0) {
			$row = $query->row();
			return $row->sub_admins;
		}
	}
	
	function delete_listing_image($id) 
	{
		
	}
	
	public function searchForListing($search) 
	{			
		$this->db->select('active, id, address, zipCode, title, bedrooms, bathrooms, city, stateAbv');
		$this->db->like('address', $search);
		
		$query = $this->db->get_where('listings', array('owner'=>$this->session->userdata('user_id'), 'deleted'=>'n'));
	
		
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row) {
				$this->db->select('image1, image2, image3, image4, image5, featured_image, desc_1, desc_2, desc_3, desc_4, desc_5');
				$this->db->where('listingId', $row->id);
				$queryer = $this->db->get('listing_images');
				$img_return = 'https://network4rentals.com/network/listing-images/comingSoon.jpg';
				if ($queryer->num_rows() > 0) {
					$rower = $queryer->row();
		
					switch($rower->featured_image) {
						case '1':
							$img_return = ltrim($rower->image1, '../');
							break;
						case '2':
							$img_return = ltrim($rower->image2, '../');
							break;
						case '3':
							$img_return = ltrim($rower->image2, '../');
							break;
						case '4':
							$img_return = ltrim($rower->image4, '../');
							break;
						case '5':
							$img_return = ltrim($rower->image5, '../');
							break;
					}
				}
				$row->img_show = $img_return;
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	//CRON JOB FUNCTION
	public function remove_old_listings()
	{
		$this->load->model('special/admin_settings');
		$this->load->model('landlords/user_account_handler');
		
		$settings = $this->admin_settings->getAdminSettings(array('landlord_listing_expire_length'));
	
		$this->listing_expires = $settings[0]->setting_value;
		$this->db->select('address, zipCode, contact_id, id, owner, lastmodified, city, stateAbv');
		$this->db->where("active = 'y' AND lastmodified < (NOW() - INTERVAL  ".$settings[0]->setting_value." DAY)");
		$query = $this->db->get('listings');
		
		$contact_users = array();
		
		foreach($query->result() as $row) {
			echo 'Remove ID: '.$row->id.' | Landlord ID: '.$row->owner.' | Contact ID: '.$row->contact_id.' | MODIFIED '.$row->lastmodified.'<br>';
			
			if(!empty($row->contact_id)) {
				//notify manager
				$mgr = $this->user_account_handler->getAdminLandlordId($row->contact_id);
				$row->owner = $mgr->sub_admins;
			}
			
			$landlord_details = '';
			$landlord_details = $this->user_account_handler->get_landlord_email($row->owner);
			
			if(!empty($landlord_details)) {
				$landlordCheck = $this->checkForDupArray($contact_users, 'landlord_id', $row->owner);
				if($landlordCheck === false) {
					
					$id = $row->id;
					$address = $row->address.' '.$row->city.', '.$row->stateAbv.' '.$row->zipCode;
					
					$contact_users[] = array(
					
						'property' => array(
							array(
								'id' => $id,
								'address' => $address,
							)
						),
						
						'landlord_email' => $landlord_details->email,
						'landlord_name' => $landlord_details->name,
						'landlord_id' => $row->owner,
						'address' => array($row->address.' '.$row->city.', '.$row->stateAbv.' '.$row->zipCode),
					);
				} else {
					$contact_users[$landlordCheck]['property'][]= array('id'=>$row->id, 'address'=>$row->address.' '.$row->city.', '.$row->stateAbv.' '.$row->zipCode);
				}
			}
			
			$this->turnOffListing($row->id);
		}
	
		$this->formatExpiredEmail($contact_users);

	}
	
	private function formatExpiredEmail($listings) 
	{
		$this->load->model('special/send_email');
		foreach($listings as $row) {
		
			$name = explode(' ', $row['landlord_name']);
			$message = '';
			$message .= '<h4>Hello '.ucwords($name[0]).',</h4>';
			$message .= '<p>This is a reminder that you have listings that have been active for more than 60 days. Please review the properties below and if any of the properties are still vacant, update to reactivate listing.</p>';
			$message .= '<table width="100%" cellspacing="20"><thead><tr><td><b>Address</b></td><td><b>View Listing</b></td><td><b>Edit Listing</b></td></tr></thead><tbody>';
			
			foreach($row['property'] as $val) {
				$message .= '<tr style="border:1px solid #444444;">';
					$message .= '<td>'.$val['address'].'</td>';
					$message .= '<td>
						<table border="0" cellpadding="0" cellspacing="0" style="background-color:#4F85BB; border:1px solid #4ABFEE; border-radius:5px;">
							<tr>
								<td align="center" valign="middle" style="color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-weight:bold; letter-spacing:-.5px; line-height:150%; padding-top:5px; padding-right:15px; padding-bottom:5px; padding-left:15px;">
									<a href="https://network4rentals.com/network/listings/view-listing/'.$val['id'].'" target="_blank" style="color:#FFFFFF; text-decoration:none;">View Listing</a>
								</td>
							</tr>
						</table>
					</td>';
					$message .= '<td>
						<table border="0" cellpadding="0" cellspacing="0" style="background-color:#4ABFEE; border:1px solid #4F85BB; border-radius:5px;">
							<tr>
								<td align="center" valign="middle" style="color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-weight:bold; letter-spacing:-.5px; line-height:150%; padding-top:5px; padding-right:15px; padding-bottom:5px; padding-left:15px;">
									<a href="https://network4rentals.com/network/landlords/edit-listing/'.$val['id'].'" target="_blank" style="color:#FFFFFF; text-decoration:none;">Update Listing</a>
								</td>
							</tr>
						</table>
					</td>';
				$message .= '</tr>';
				
				
			}
			
			
			$message .= '</tbody></table>';
			$subject = count($row['property']).' Property Listings Expired On N4R';
			$this->send_email->sendEmail($row['landlord_email'], $message, $subject);
		
		}
	}
	
	private function turnOffListing($id)
	{
		$this->db->limit(1);
		$this->db->where('id', $id);
		$this->db->update('listings', array('active' => 'n'));
	}
	
	private function checkForDupArray($array, $key, $value) 
	{
		$count = 0;
		foreach($array as $k => $val) {
			if($val[$key] == $value) {
				return $count;
			}
			$count++;
		}
		return false;
	}
	
}