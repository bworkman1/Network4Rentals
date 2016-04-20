<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct() {
        parent::__construct();

        $this->output->set_template('advertisers/user');
        $this->load->model('advertisers/security_check');
        if(!$this->security_check->check()) {
			redirect('local-partner/login');
			exit;
        }
    }

    public function index()   //changed
    {
        $this->output->set_title('Local Partner Home');  // SETS TITLE OF THE PAGE
        $this->output->set_meta('description', 'Local Partner Home'); //SETS META DESCRIPTION
        $this->output->set_meta('keywords', 'Local Partner, Home'); //SETS META KEYWORDS1
		
		
		$this->load->section('sidebar', 'advertiser-sidebar');
        
		$this->load->view('advertisers/user/home');
    }


}