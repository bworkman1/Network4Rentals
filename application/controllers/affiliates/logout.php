<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->session->sess_destroy();

        $this->session->set_flashdata('success', 'Logged Out Successfully');
        redirect('affiliates/login');
        exit;
    }

}