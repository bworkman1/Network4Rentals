<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_signin extends CI_Model {

    private $mSalt = '94$32#049Avb';  //IF CHANGED THIS IS ALSO LOCATED IN  - models/modules/User_affiliates.php -

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function checkForUser($data)
    {
        $query = $this->db->get_where('affiliate_users', array('email'=>$data['email']));
        if($query->num_rows()>0) {
            $user_data = $query->row();

            $this->updateUserDetails($data, $user_data->id);
            $this->signUserIn($user_data);

            if($user_data->password == 'PASSWORDNEEDSSET') {
                $this->session->set_userdata('set_password', true);
            }

            redirect('affiliates/dashboard');
            exit;
        } else {
			/*
            if($this->createUserAccount($data)) {
                redirect('affiliates/dashboard');
                exit;
            } else {
                $this->session->set_flashdata('error',
                    'There was an error creating your account, try again. If the problem persists contact our
                    <a href="https://network4rentals.com/help-support/">support team</a>');

                redirect('affiliates/login');
                exit;
            } */
			$this->session->set_flashdata('error',
				'Invalid user name and/or password');
			redirect('affiliates/login');
			exit;
        }
    }

    private function updateUserDetails($data, $id)
    {
        $update = array(
            'login_type'=> $data['login_type'],
            'uid'=> $data['uid'],
            'ip'=> $_SERVER['REMOTE_ADDR'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'image' => $data['image']
        );

        $this->db->where('id', $id);
        $this->db->update('affiliate_users', $update);
    }

    private function signUserIn($data)
    {
        if(is_object($data)) {
            $img = $data->image;
            $name = $data->first_name;
        } else {
            $img = $data['image'];
            $name = $data['first_name'];
        }
		
		$this->load->model('modules/public_page_table');
		$userPage = $this->public_page_table->getUserPage($data->id, 'affiliate');
		
        $saveArray = array(
            'user_id' => $data->id,
            'email' => $data->email,
            'logged_in' => TRUE,
            'side' => 'affiliate',
            'image' => $img,
            'name' => $name,
            'unique_id' => $data->unique_id,
            'created' => $data->created,
			'unique_name' => $userPage->unique_name
        );
        $this->session->set_userdata($saveArray);
    }

    private function createUserAccount($data) {
        $insertData = array(
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'email'         => $data['email'],
            'ip'            => $_SERVER['REMOTE_ADDR'],
            'created'       => date('Y-m-d H:i'),
            'uid'           => $data['uid'],
            'first_name'    => $data['first_name'],
            'login_type'    => $data['login_type'],
            'image'         => $data['image'],
            'password'      => 'PASSWORDNEEDSSET',
            'unique_id'     => random_string('unique'),
        );
        $this->db->insert('affiliate_users', $insertData);
        if($this->db->insert_id()>0) {
            $this->session->userdata('set_password', true);
            $this->signUserIn($data);
            return true;
        } else {
            return false;
        }
    }

    public function validateUser($data) {
        $data['password'] = md5($this->mSalt.$data['password']);
        $query = $this->db->get_where('affiliate_users', $data);
        if($query->num_rows()>0) {
            $row = $query->row();
            $this->load->model('modules/Auth_signin');
            $this->Auth_signin->signUserIn($row);

            return true;
        } else {
            return false;
        }
    }

}