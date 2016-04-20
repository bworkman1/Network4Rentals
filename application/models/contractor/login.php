<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Login extends CI_Model {

        public function __construct() {

            parent::__construct();

        }
		
		public function check_creditials($data)
		{
			$results = $this->db->get_where('contractors', $data);
			if($results->num_rows()>0) {
				$row = $results->row();
				
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('side_logged_in', '203020320389822');
				$this->session->set_userdata('logged_in', true);
				
				$this->session->set_userdata('full_name', $row->f_name.' '.$row->l_name);
				$this->session->set_userdata('user_email', $row->email);
				$this->session->set_userdata('user_created', strtotime($row->created));

                $cords = $this->getCords($row->zip);
                $this->session->set_userdata('lat', $cords['lat']);
                $this->session->set_userdata('long', $cords['lng']);

				$this->db->where('id', $row->id);
				$this->db->update('contractors', array('last_login'=>date('Y-m-d H:i:s')));
				$this->setNoticesForNextSteps($row->id);
				return true;
			} else {
				return false;
			}
		}

        private function getCords($zip)
        {
            $this->db->where('latitude !=', '');
            $query = $this->db->get_where('zips', array('zipCode'=>$zip));
            $row = $query->row();
            if(!empty($row->latitude) && !empty($row->longitude)) {
                $lat = $row->latitude;
                $lng = $row->longitude;
            } else {
                $lat = '40.079117';
                $lng = '-82.400543';
            }
            return array(
                'lat' => $lat,
                'lng' => $lng,
            );
        }
		
		private function setNoticesForNextSteps($id) 
		{
			$query = $this->db->get_where('landlord_page_settings', array('landlord_id' => $id, 'type' => 'contractor'));
			if($query->num_rows()==0) {
				$this->session->set_flashdata('warning', 'You still need to setup your public profile. <br><a href="'.base_url('contractor/public-page').'">Fix This</a>');
			}
		}
		
    }