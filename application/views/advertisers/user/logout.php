<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        session_destroy();
        redirect('local-partner/login');
        exit;
    }


}
