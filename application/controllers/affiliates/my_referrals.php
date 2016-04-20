<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_referrals extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->init();
    }

    private function init()
    {
        $title = ucwords(str_replace('-', ' ', end($this->uri->segment_array())));
        $this->output->set_title($title);  // SETS TITLE OF THE PAGE

        $this->output->set_template('affiliates/logged-in');

        $data['title'] = "My Referrals";
        $data['sub_nav'] = array(
                'My Referrals' => '',
                'Contractors' => base_url('affiliates/my-referrals/contractors/'),
                'Landlords' => base_url('affiliates/my-referrals/landlords/'),
                'Renters' => base_url('affiliates/my-referrals/renters/'));

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
        redirect('affiliates/my-referrals/contractors/');
        exit;
    }

    public function contractors()
    {
        $contractorId = (int)$this->uri->segment(4);
        $this->output->set_title('My Affiliate Contractors');
        $this->load->model('modules/Contractor_table');

        if ($contractorId>0) {
            $data['contractor'] = $this->Contractor_table->getContractorDetails(
                $contractorId,
                $this->session->userdata('unique_id')
            );
            if(empty($data['contractor'])) {
                $this->session->set_flashdata('error', 'User is not an affiliate of yours');
                redirect('affiliates/my-referrals/contractors');
                exit;
            }
            $this->load->model('affiliates/Affiliate_payments');
            $data['payments'] = $this->Affiliate_payments->getUserPayments(
                $contractorId,
                'contractor',
                $this->session->userdata('unique_id')
            );

            $this->load->view('affiliates/user/referrals/view-contractor', $data);
        } else {
            $data = $this->Contractor_table->getContractorAffiliates($this->session->userdata('unique_id'));
            $this->load->view('affiliates/user/referrals/my-contractors', $data);
        }

    }

    public function landlords()
    {
        $this->output->set_title('My Affiliate Landlords');
        $this->load->model('modules/Landlord_table');
        $data = $this->Landlord_table->get_landlord_affiliates($this->session->userdata('unique_id'));
        $this->load->view('affiliates/user/referrals/my-landlords', $data);
    }

    public function renters()
    {
        $this->output->set_title('My Affiliate Renters');
        $this->load->model('modules/Renters_table');
        $data = $this->Renters_table->get_renter_affiliates($this->session->userdata('unique_id'));
        $this->load->view('affiliates/user/referrals/my-renters', $data);
    }
}