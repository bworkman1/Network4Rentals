<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Stats extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->output->set_template('advertisers/user');
		$this->output->set_title('My Premium Ad Stats');  // SETS TITLE OF THE PAGE
        $this->load->model('advertisers/security_check');
        if (!$this->security_check->check()) {
            redirect('local-partner/login');
            exit;
        }

    }

    function index()
    {
        $this->load->css('assets/themes/default/css/TableBarChart.css');
        $this->load->js('assets/themes/default/js/TableBarChart.js');

        $this->load->model('advertisers/ad_handler');

        $data['page_setting'] = $this->ad_handler->check_public_page();
        $data['my_zips'] = $this->ad_handler->get_my_zips();

        $this->load->view('advertisers/user/my-stats', $data);
    }

}