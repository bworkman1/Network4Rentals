<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliate_form_subs extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getRecentFormSubmissions($id)
    {
        $this->db->order_by('id', 'desc');
        $this->db->limit(5);
        $this->db->select('id, email, name, timestamp');
        $query = $this->db->get_where('affiliate_form_subs', array(
            'affiliate_id' => $id
        ));
        return $query->result();
    }

    public function getTotalFormSubmissions($id)
    {
        $this->db->where('affiliate_id', $id);
        $this->db->from('affiliate_form_subs');
        return $this->db->count_all_results();
    }

    public function deleteFormSubmission($user_id, $form_id)
    {
        $this->db->delete('affiliate_form_subs', array('id' => $form_id, 'affiliate_id'=>$user_id));
        return true;
    }

    public function getFormSubmissions($user_id, $offset)
    {
        $this->db->select('id, email, name, phone, timestamp');
        $this->db->limit(30, $offset);
        $query = $this->db->get_where('affiliate_form_subs', array('affiliate_id'=>$user_id));
        return $this->formatSubmissionData($query->result_array());
    }

    public function getSingleFormSubmission($id, $user_id)
    {
        $query = $this->db->get_where('affiliate_form_subs', array('id'=>$id, 'affiliate_id'=>$user_id));
        return $query->row();
    }

    public function formatSubmissionData($data)
    {
        $newData = array();
        foreach($data as $key => $val) {
            $phone =  '('.substr($val['phone'], 0, 3) .') '.substr($val['phone'], 3, 3) .'-'.
                substr($val['phone'], 6);
            $date = date("m-d-Y", strtotime($val['timestamp']));
            $options = '<a href="'.base_url('affiliates/form-submissions/view-form/'.$val['id']).'"
                class="btn btn-warning btn-sm">View</a>';
            $newData[] = array(
                'id' => $val['id'],
                'email' => $val['email'],
                'name' => $val['name'],
                'phone' => $phone,
                'date' => $date,
                'options' => $options,
            );
        }
        return $newData;
    }
}