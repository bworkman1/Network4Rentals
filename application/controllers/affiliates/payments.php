<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payments extends CI_Controller
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
        $this->output->set_meta('description', 'Affiliates dashboard page'); //SETS META DESCRIPTION
        $this->output->set_meta('keywords', 'Affiliates, dashboard, page'); //SETS META KEYWORDS1

        $this->output->set_template('affiliates/logged-in');

        $data['title'] = "Payments";
        $data['sub_nav'] = array(
                'Dashboard' => base_url('affiliates/dashboard'),
                'My Account' => base_url('affiliates/my-account'),
                'Payments' => base_url('affiliates/payments'));

        $this->load->section('header', 'affiliates/common/header');
        $this->load->section('nav', 'affiliates/common/nav', $data);

        $side = $this->session->userdata('side');
        if ($this->session->userdata('user_online') !== true && $side !== 'affiliate') {
            redirect('affiliates/logout');
            exit;
        }

        $this->load->js('assets/themes/blue-moon/js/affiliates/payments.js');

        //Updates last viewed timestamp in db
        $this->load->model('modules/user_common');
        $this->user_common->update_last_viewed('affiliate_users');

        $this->session->set_userdata('created', '2012-10-28 00:00:00');

    }

    public function index()
    {
        $this->load->model('affiliates/affiliate_payments');
        $data = $this->affiliate_payments->eligibleNewReferralsLastMonth($this->session->userdata('unique_id'));
        $this->load->view('affiliates/user/payments', $data);
    }

    public function custom()
    {
        $this->output->set_title('Payments By Month');
        $year = (int)$this->uri->segment(4);
        $month = (int)$this->uri->segment(5);

        if($this->isValidYear($year)&&$this->isValidMonth($month)) {

            $this->load->model('affiliates/affiliate_payments');
            $data = $this->affiliate_payments->eligibleNewReferralsLastMonth(
                $this->session->userdata('unique_id'),
                $month,
                $year
            );

            $this->load->view('affiliates/user/payments', $data);

        } else {
            $this->session->set_flashdata('error', 'Invalid selection, try again');
            redirect('affiliates/payments');
            exit;
        }
    }

    private function isValidYear($year)
    {
        if($year>2013&&$year<2030) {
            return true;
        }
        return false;
    }

    private function isValidMonth($month) {
        if($month>0&&$month<13) {
            return true;
        }
        return false;
    }

}