<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Renters_table extends CI_Model { 
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	/* get a user by id,  OPTIONAL: selections should be an array of columns you want*/
	function get_renter_by_id($id, $selections = null) 
	{
		if(is_array($selections)) {
			$this->db->select($selections);
		}
		$result = $this->db->get_where('renters', array('id'=>$id));
		return $result->row();
	}
	
	function update_user_by_id($id, $data) 
	{
		$this->db->limit(1);
		$this->db->where('id', $id);
		$this->db->update('renters', $data);
		if($this->db->affected_rows()>0) {
			$this->session->set_userdata('timezone', $data['timezones']);
			return true;
		}
		return false;
	}

	public function get_renter_affiliates($unique_id) {
		if(!empty($unique_id)) {
            $perPage = 20;
            $this->db->order_by('id', 'desc');
            $this->db->limit($perPage, $this->uri->segment(4));
            $this->db->select('id, name, email, zip, phone, sign_up');
            $query = $this->db->get_where('renters', array('affiliate_id'=>$unique_id));
			if($query->num_rows()>0) {
                $data['affiliates'] = $query->result();

                $data['totalRows'] = $this->countAllAffiliates($unique_id);

                $this->load->model('modules/User_common');
                $data['links'] = $this->User_common->pagination(4, $data['totalRows'], base_url('affiliates/my-referrals/renters'), $perPage);
				return $data;
			}
			return false;
		} else {
            return false;
        }
	}

    private function countAllAffiliates($unique_id)
    {
        $this->db->where('affiliate_id', $unique_id);
        $this->db->from('renters');
        return $this->db->count_all_results();
    }

}