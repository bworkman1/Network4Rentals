<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
				
	class Advertiser_sidebar extends CI_Model {
		
		private $localRadius = 75;
		private $lat = '40.079117'; // $this->session->userdata('lat');
		private $long = '-82.400543'; //$this->session->userdata('long');
		private $categories;
		
		// Call the Model constructor
		function __construct()
		{		
			parent::__construct();
			
			$this->load->css('assets/themes/default/css/sidebar-styles.css');
			$this->getCategories();
			$this->getAdvertisers();
		}
		
		public function getAdvertisers() 
		{
			$zips = $this->getNearbyZips();			
			$adObject = $this->getAdvertisersInZips($zips);
			
			$this->sortAdvertisers($adObject);
		}
		
		private function getCategories()
		{
			$query = $this->db->get('advertiser_categories');
			$this->categories = $query->result();
		}
		
		private function getAdvertisersInZips($zips) 
		{
			$this->db->select('landlord_page_settings.image, landlord_page_settings.unique_name, landlord_page_settings.bName, advertisers.category, advertisers.id, landlord_page_settings.address, landlord_page_settings.city, landlord_page_settings.state');
			$count = 0;
	
			foreach($zips as $zip) {
				if($count==0) {
					$this->db->where('landlord_page_settings.zip', $zip);
				} else {
					$this->db->or_where('landlord_page_settings.zip', $zip);
				}
				$count++;
			}
			$this->db->join('advertisers', 'landlord_page_settings.landlord_id = advertisers.id');
			$query = $this->db->get('landlord_page_settings');
			return $query->result();
		}
		
		private function getNearbyZips()
		{
			$sql = 'SELECT zipCode FROM zips WHERE (3958*3.1415926*sqrt((Latitude-'.$this->lat.')*(Latitude-'.$this->lat.') + cos(Latitude/57.29578)*cos('.$this->lat.'/57.29578)*(Longitude-'.$this->long.')*(Longitude-'.$this->long.'))/180) <= '.$this->localRadius;
			$result = $this->db->query($sql);
			if($result->num_rows()>0) {
				$zips = array();
				foreach($result->result() as $row) {
					$zips[] = $row->zipCode;
				}
			} else {
				/* Fail safe so if the zip code is invalid it wont screw up the flow */
				return array($this->session->userdata('zip'));
			}
			return $zips;
		}
		
		private function sortAdvertisers($adObject)
		{
			$data = array();
			foreach($adObject as $row) {
                if($row->id != 1) {
                    foreach ($this->categories as $val) {
                        if ($val->id == $row->category) {
                            $data[$val->category][] = $row;
                        }
                    }
                }
			}
			$this->formatAdOutput($data);
		}

		private function formatAdOutput($data) 
		{
			$count = 1;
			$randomOpened = rand(1, count($data));
			echo '<h3><i class="fa fa-map-marker text-primary"></i> Local Partners</h3><div id="locals">';
			foreach($data as $key => $val) {
				$open = '';
                $collapsed = 'collapsed';
				if($randomOpened == $count) {
					$open = 'in';
                    $collapsed = '';
				}
				echo '<div class="panel panel-default">';
					echo '<a class="accordion-toggle '.$collapsed.'" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$count.'" aria-expanded="true" aria-controls="collapse'.$count.'">';
						echo '<div class="panel-heading" role="tab" id="heading'.$count.'">';
							echo '<h4 class="panel-title">';
								echo $key;
							echo '</h4>';
						echo '</div>';
					echo '</a>';
					echo '<div id="collapse'.$count.'" class="panel-collapse collapse '.$open.'" role="tabpanel" aria-labelledby="headingOne">
					  <div class="panel-body">';
					echo '<ul style="margin: 0; padding: 0">';
						foreach($val as $row) {
							echo '<li><a target="_blank" href="http://n4rlocal.com/'.$row->unique_name.'">';
								echo '<h5><b>'.$row->bName.'</b></h5>';
							echo '</a></li>';
						}	
					echo '</ul>';
					
				echo '</div>
					</div>
				  </div>';
				$count++;
			}	
			echo '</div>';

		}
		
	}