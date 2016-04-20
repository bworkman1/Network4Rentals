<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->init();
    }

    private function init()
    {
        $title = ucwords(str_replace('-', ' ', end($this->uri->segment_array())));
        $this->output->set_title($title);  // SETS TITLE OF THE PAGE

        $this->output->set_template('renters/logged-in');

        $data['title'] = "Dashboard";
        $data['sub_nav'] = array(
            'Dashboard' => base_url('affiliates/dashboard'),
            'My Account' => base_url('affiliates/my-account'),
            'Payments' => base_url('affiliates/payments')
        );
		
		$this->load->css('assets/themes/blue-moon/css/renter.css');
		
        $this->load->section('header', 'renters/common/header');
        $this->load->section('nav', 'renters/common/nav', $data);

        $side = $this->session->userdata('side');
		
        if ($this->session->userdata('user_online') !== true && $side !== 'renter' && $this->sessiondata('user_id')>0) {
            redirect('renter/logout');
            exit;
        }

        //Updates last viewed timestamp in db
        $this->load->model('modules/user_common');
        $this->user_common->update_last_viewed('affiliate_users');
    }

    public function index()
    {
        $this->load->js('assets/themes/blue-moon/js/jquery-ui-v1.10.3.js');
        $this->load->js('assets/themes/blue-moon/js/justgage/justgage.js');
        $this->load->js('assets/themes/blue-moon/js/justgage/raphael.2.1.0.min.js');

        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.js');
        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.orderBar.min.js');
        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.stack.min.js');
        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.pie.min.js');
        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.tooltip.min.js');
        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.resize.min.js');

        $this->load->js('assets/themes/blue-moon/js/flot/test.js');



        

        $this->load->view('renters/user/dashboard', $data);
    }
	
	
	
}
