<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Security_check extends CI_Model {

    function check()
    {
        $userId = $this->session->userdata('user_id');
        if(empty($userId)) {
            $this->session->sess_destroy();
            return false;
        }

        $side = $this->session->userdata('side_logged_in');
        if($side != 'local-partner') {
            $this->session->set_flashdata('createAccount', 'You must create an account or login to link to a landlord. Once you create your account you will be redirected to link to your landlord');
            $this->session->sess_destroy();
            return false;
        }

        return true;
    }

}