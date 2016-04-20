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
            $this->session->set_flashdata('error', 'You must first login');
            $this->session->sess_destroy();
            return false;
        }
 
        return true;
    }

}