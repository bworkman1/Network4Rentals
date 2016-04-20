<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Post_handler extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
	
		public function edit_post($data) 
		{
			if(!empty($data)) {
				if(empty($data['ad_image'])) {
					$this->db->select('ad_image');
					$query = $this->db->get_where('contractor_ads', array('active'=>'y', 'ref_id'=>$data['id']));
					$row = $query->row();
					$data['ad_image'] = $row->ad_image;
				}
			
				$query = $this->db->get_where('contractor_zips', array('active'=>'y', 'id'=>$data['id'], 'contractor_id'=>$this->session->userdata('user_id')));
				if($query->num_rows()>0) {
					$og_data = $query->row();
					if($data['apply_post']=="1") { //add to only this post
						$query = $this->db->get_where('contractor_ads', array('active'=>'y', 'ref_id'=>$data['id']));
						if($query->num_rows()>0) { //update ad
							$this->db->where('ref_id', $data['id']);
							$this->db->update('contractor_ads', array('title'=>$data['title'], 'desc'=>$data['desc'], 'service_type'=>$og_data->service_purchased, 'ad_image'=>$data['ad_image']));
						} else { //insert ad
							$this->db->insert('contractor_ads', array('ref_id'=>$data['id'], 'title'=>$data['title'], 'desc'=>$data['desc'], 'service_type'=>$og_data->service_purchased, 'ad_image'=>$data['ad_image']));
						}
						if($this->db->affected_rows()>0) {
							return '4'; // post updated/inserted
						} else {
							return '3'; // post not inserted
						}
					} elseif($data['apply_post']=="2") {//add to post of this type
						$query = $this->db->get_where('contractor_zips', array('active'=>'y', 'contractor_id'=>$this->session->userdata('user_id'), 'id'=>$data['id']));
						if($query->num_rows()>0) {
							$main_ad_info = $query->row(); 
							$query = $this->db->get_where('contractor_zips', array('active'=>'y', 'contractor_id'=>$this->session->userdata('user_id')));
							if($query->num_rows()>0) {
								foreach ($query->result() as $row) {
									if($row->service_purchased == $main_ad_info->service_purchased) { //if services match submitted service
										$q = $this->db->get_where('contractor_ads', array('active'=>'y', 'ref_id'=>$row->id));
										if($q->num_rows()>0) { //update ad
											$this->db->where('ref_id', $row->id);
											$this->db->update('contractor_ads', array('title'=>$data['title'], 'desc'=>$data['desc'], 'service_type'=>$row->service_purchased, 'ad_image'=>$data['ad_image']));
										} else { //insert ad
											$this->db->insert('contractor_ads', array('ref_id'=>$row->id, 'title'=>$data['title'], 'desc'=>$data['desc'], 'service_type'=>$row->service_purchased, 'ad_image'=>$data['ad_image']));
										}
									}
								}
								if($this->db->affected_rows()>0) {
									return '4'; // post updated/inserted
								} else {
									return '3'; // post not inserted
								}
							} else {
								return '6'; // no post found for user (should not return this ever)
							}
						} else {
							return '5'; //no active post found (should not return this ever
						}
						
					} else { //apply to all posts
						$query = $this->db->get_where('contractor_zips', array('active'=>'y', 'contractor_id'=>$this->session->userdata('user_id'), 'id'=>$data['id']));
						if($query->num_rows()>0) {
							$query = $this->db->get_where('contractor_zips', array('active'=>'y', 'contractor_id'=>$this->session->userdata('user_id')));
							if($query->num_rows()>0) {
								foreach ($query->result_array() as $row) {
									if($row['service_purchased'] == 0) {
										$service_purchased = 0;
									} else {
										$service_purchased = $row['service_purchased'];
									}
									$q = $this->db->get_where('contractor_ads', array('active'=>'y', 'ref_id'=>$row['id']));
									if($q->num_rows()>0) { //update ad
										$this->db->where('ref_id', $row['id']);
										$this->db->update('contractor_ads', array('title'=>$data['title'], 'desc'=>$data['desc'], 'service_type'=>$service_purchased, 'ad_image'=>$data['ad_image']));
									} else { //insert ad
										$this->db->insert('contractor_ads', array('ref_id'=>$row['id'], 'title'=>$data['title'], 'desc'=>$data['desc'], 'service_type'=>$service_purchased, 'ad_image'=>$data['ad_image']));
									}
								}
								if($this->db->affected_rows()>0) {
									return '4'; // post updated/inserted
								} else {
									return '3'; // post not inserted
								}
							} else {
								return '6'; // no post found for user (should not return this ever)
							}
						} else {
							return '6'; //no active post found (should not return this ever
						}
					}

				} else {
					return '2'; //No Active Ad Found
				}
			} else {
				return '1'; //empty data
			}
		}
		
		function get_ad_info($id)
		{
			$query = $this->db->get_where('contractor_zips', array('active'=>'y', 'id'=>$id, 'contractor_id'=>$this->session->userdata('user_id')));
			if($query->num_rows()>0) {
				$query = $this->db->get_where('contractor_ads', array('active'=>'y', 'ref_id'=>$id));
				return $query->row();
			} else {
				return '55'; //no ad found
			}
		}
		
    }


