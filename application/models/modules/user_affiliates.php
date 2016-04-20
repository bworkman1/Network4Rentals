<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_affiliates extends CI_Model
{
    private $mSalt = '94$32#049Avb'; //IF CHANGED THIS IS ALSO LOCATED IN  - models/modules/Auth_signin.php -

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function searchForUser($what, $column, $limit = null)
    {
        if (!empty($limit)) {
            $this->db->limit($limit);
        }
        $this->db->where($column, $what);
        $query = $this->db->get('affiliate_users');
        if ($query->num_rows()>0) {
            if($limit>1) {
                return $query->result();
            } else {
                return $query->row();
            }
        } else {
            return false;
        }
    }

    public function clearHash($hash)
    {
        $this->db->where('reset_hash', $hash);
        $this->db->update('affiliate_users', array('reset_hash'=>''));
    }

    public function matchUserHashId($hash, $id)
    {
        $query = $this->db->get_where('affiliate_users', array('id'=>$id, 'reset_hash'=>$hash));
        if ($query->num_rows()>0) {
            return true;
        }
        return false;
    }

    public function updatePassword($id, $password)
    {
        $password = md5($this->mSalt.$password);
        $this->db->where('id', $id);
        $this->db->update('affiliate_users', array('password'=>$password));
        return true;
    }

    public function setUserHash($id)
    {
        $hash = $this->generateRandomString(30);
        $this->db->limit(1);
        $this->db->where('id', $id);
        $this->db->update('affiliate_users', array('reset_hash'=>$hash));
        if ($this->db->affected_rows()>0) {
            return $hash;
        }
        return false;
    }

    public function getAffiliateById($id)
    {
        $query = $this->db->get_where('affiliate_users', array('id'=>$id));
        return $query->row();
    }

    public function updateAffiliate($data, $id)
    {
        if (isset($data['password'])) {
           $data['password'] = md5($this->mSalt.$data['password']);
        }
        if(isset($data['image'])) {
            $this->session->set_userdata('image', $data['image']);
        }
        $this->db->where('id', $id);
        $this->db->update('affiliate_users', $data);
        if($this->db->affected_rows()>0) {
            $this->session->set_flashdata('success', 'Your account has been updated!');
            return true;
        }
        $this->session->set_flashdata('error', 'Your account was not updated, are you sure you changed something?');
        return false;
    }

    public function addAffiliate($data)
    {
        $sendEmail = $data['send'];
        unset($data['send']);

        $pass = $this->generateRandomString(10);
        $data['password'] = md5($this->mSalt.$pass);
        $data['unique_id'] = $this->generateRandomString(30);
        $data['phone'] = preg_replace("/[^0-9,.]/", "", $data['phone']);
        $data['created'] = date('Y-m-d H-i-s');

        $this->db->insert('affiliate_users', $data);
        if($this->db->insert_id()>0) {

            $this->load->model('special/send_email');
            $subject = 'N4R | Your affiliate account has been created!';
            $message = '<h4>Hello</h4><p>Your affiliate account has been created and you can now login to your account and start making some money through your affiliate account. The first step is to login ot your account and change your password to something you will remember. After that step you will want to setup your website which is quick and easy to do for anyone. If you need help there is a help section in and our support is here to help as well.</p><p><b>Login Details:</b><br><b>Email:</b> '.$data['email'].'<br><b>Password:</b> '.$pass.'<br><a href="https://network4rentals.com/network/affiliates/login">https://network4rentals.com/network/affiliates/login</a></p>';
            $this->send_email->sendEmail($data['email'], $message, $subject);

            return $feedback = array('success' => 'User added successfully and an email has been sent to the user with their login details');

        } else {
            return $feedback = array('error' => 'Something went wrong, try again');
        }
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getAllAffiliates($offset, $limit)
    {
        if(empty($offset)) {
            $offset = 0;
        }
        $this->db->select('id, first_name, last_name, email, created, last_viewed, unique_id');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('affiliate_users');
        return $query->result();
    }

    public function formatAllAffiliates($data)
    {
        $returnData = array();
        foreach($data as $row) {
            $returnData[] = array(
                'id' => $row->id,
                'name' => $row->first_name.' '.$row->last_name,
                'email' => $row->email,
                'created' => date('m-d-Y', strtotime($row->created)),
                'last_viewed' => date('m-d-Y', strtotime($row->last_viewed)),
                'sign_ups' => $this->getAffiliatesDownline($row->unique_id),
                'options' => '<a href="'.base_url('n4radmin/view-affiliate/'.$row->id).'" class="btn btn-primary btn-sm">View</a>'
            );
        }
        return $returnData;
    }

    private function getAffiliatesDownline($affiliate_id)
    {
        $this->db->where('affiliate_id', $affiliate_id);
        $this->db->from('contractors');
        return $this->db->count_all_results();
    }

    public function countAllAffiliates()
    {
        return $this->db->count_all_results('affiliate_users');
    }

 

}