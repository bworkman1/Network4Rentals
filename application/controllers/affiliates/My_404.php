<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_404 extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->_init();
    }

    private function _init()
    {
        $this->output->set_status_header('404');

        $this->output->set_title('Affiliates 404 Page');  // SETS TITLE OF THE PAGE

        $this->output->set_template('logged-in');

        $data['title'] = "404 Error";
        $data['sub_nav'] = array('404 Error' => '');

        $this->load->section('header', 'affiliates/common/header');
        $this->load->section('nav', 'affiliates/common/nav', $data);

        $side = $this->session->userdata('side');
        if ($this->session->userdata('user_online') !== true && $side !== 'affiliate') {
            redirect('affiliates/logout');
            exit;
        }

        //Updates last viewed timestamp in db
        $this->load->model('modules/user_common');
        $this->user_common->update_last_viewed('affiliate_users');

    }

    public function index()
    {
        $this->load->view('affiliates/user/my-404');
    }

}