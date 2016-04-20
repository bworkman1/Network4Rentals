<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landlord_table extends CI_Model { 
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function getLandlordByRow($id) 
	{
		$query = $this->db->get_where('landlords', array('id'=>$id));
		return $query->row();
	}

    public function get_landlord_affiliates($unique_id) {
        if(!empty($unique_id)) {
            $perPage = 20;
            $this->db->order_by('id', 'desc');
            $this->db->limit($perPage, $this->uri->segment(4));
            $this->db->select('id, name, email, zip, phone, sign_up');
            $query = $this->db->get_where('landlords', array('affiliate_id'=>$unique_id));
            if($query->num_rows()>0) {
                $data['affiliates'] = $query->result();

                $data['totalRows'] = $this->countAllAffiliates($unique_id);

                $this->load->model('modules/User_common');
                $data['links'] = $this->User_common->pagination(4, $data['totalRows'], base_url('affiliates/my-referrals/landlords'), $perPage);
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
        $this->db->from('landlords');
        return $this->db->count_all_results();
    }

}