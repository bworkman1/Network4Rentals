<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function index()   //changed
    {
        $this->output->set_title('Local Partner Dashboard');  // SETS TITLE OF THE PAGE
        $this->output->set_meta('description', 'Local Partner Dashboard'); //SETS META DESCRIPTION
        $this->output->set_meta('keywords', 'Local Partner, Dashboard'); //SETS META KEYWORDS1
        $this->load->section('sidebar', 'advertiser-sidebar');
        $this->output->set_template('advertisers/user');

      
    }


}