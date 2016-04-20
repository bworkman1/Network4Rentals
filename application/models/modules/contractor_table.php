<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contractor_table extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getContractorAffiliates($unique_id) {
        if(!empty($unique_id)) {
            $perPage = 20;
            $this->db->order_by('id', 'desc');
            $this->db->limit($perPage, $this->uri->segment(4));
            $this->db->select('id, f_name, l_name, email, zip, phone, created');
            $query = $this->db->get_where('contractors', array('affiliate_id'=>$unique_id));
            if($query->num_rows()>0) {
                $data['affiliates'] = $query->result();

                $data['totalRows'] = $this->countAllAffiliates($unique_id);

                $this->load->model('modules/User_common');
                $data['links'] = $this->User_common->pagination(
                    4,
                    $data['totalRows'],
                    base_url('affiliates/my-referrals/contractors'),
                    $perPage
                );
                return $data;
            }
            return false;
        } else {
            return false;
        }
    }

    public function getContractorDetails($id, $affiliate_id = null)
    {
        $this->db->select('email, baddress, bcity, bstate, bzip, f_name, l_name, phone, created, last_login, bName');
        if(!empty($affiliate_id)) {
            $this->db->where('affiliate_id', $affiliate_id);
        }
        $query = $this->db->get_where('contractors', array('id'=>$id));
        return $query->row();
    }

    private function countAllAffiliates($unique_id)
    {
        $this->db->where('affiliate_id', $unique_id);
        $this->db->from('contractors');
        return $this->db->count_all_results();
    }



}