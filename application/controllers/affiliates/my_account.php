<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_account extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    private function init()
    {
        $title = ucwords(str_replace('-', ' ', end($this->uri->segment_array())));
        $this->output->set_title($title);  // SETS TITLE OF THE PAGE

        $this->output->set_template('affiliates/logged-in');

        $data['title'] = "My Account";
        $data['sub_nav'] = array(
            'Dashboard' => base_url('affiliates/dashboard'),
            'My Account' => base_url('affiliates/my-account'),
            'Payments' => base_url('affiliates/payments')
        );

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
        $this->load->model('modules/user_affiliates');
        $data['user'] = $this->user_affiliates->getAffiliateById($this->session->userdata('user_id'));
        $this->load->view('affiliates/user/my-account', $data);
    }

    public function mailingSettings()
    {
        $this->form_validation->set_rules('first_name', 'First Name'
            , 'required|min_length[2]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name'
            , 'required|min_length[2]|max_length[30]|xss_clean|alpha');
        $this->form_validation->set_rules('address', 'Address'
            , 'required|min_length[5]|max_length[35]|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'required|min_length[5]|max_length[35]|xss_clean');
        $this->form_validation->set_rules('zip', 'Zip', 'required|min_length[5]|max_length[5]|xss_clean|integer');
        $this->form_validation->set_rules('state', 'State', 'required|min_length[2]|max_length[2]|xss_clean|alpha');

        if ($this->form_validation->run() == FALSE) {
            $this->validation_errors('<span>', '</span>');
        } else {
            extract($_POST);
            $postData = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
            );
            $this->load->model('modules/user_affiliates');
            $this->user_affiliates->updateAffiliate($postData, $this->session->userdata('user_id'));
            redirect('affiliates/my-account');
            exit;
        }
    }

    public function accountSettings()
    {
        $this->form_validation->set_rules('email', 'Email'
            , 'required|min_length[3]|max_length[60]|xss_clean|valid_email');
        $this->form_validation->set_rules('phone', 'Phone'
            , 'required|min_length[10]|max_length[15]|xss_clean');
        $this->form_validation->set_rules('cell_phone', 'Cell Phone'
            , 'min_length[10]|max_length[15]|xss_clean');
        $this->form_validation->set_rules('password1', 'Password',
            'min_length[7]|max_length[20]|xss_clean|matches[password2]');
        $this->form_validation->set_rules('password2', 'Confirm Password',
            'min_length[7]|max_length[20]|xss_clean|matches[password1]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
        } else {
            extract($_POST);

            $this->load->model('modules/user_affiliates');
            $this->load->model('special/user_uploads');

            $postData = array(
                'email' => $email,
                'phone' => preg_replace("/[^0-9,.]/", "", $phone),
                'cell' => preg_replace("/[^0-9,.]/", "", $cell_phone),
            );

            if (isset($_FILES['profile']['name']) && !empty($_FILES['profile']['name'])) {
                $imgData = $this->user_uploads->upload_image($_FILES['profile'], 'profile', true);
                if (isset($imgData['success'])) {
                    $postData['image'] = $imgData['success']['system_path'];
                } else {
                    $this->session->set_flashdata('error', $imgData['error']);
                    redirect('affiliates/my-account');
                    exit;
                }
            }

            if(!empty($password1)) {
                $postData['password'] = $password1;
            }

            $this->user_affiliates->updateAffiliate($postData, $this->session->userdata('user_id'));

        }
        redirect('affiliates/my-account');
        exit;
    }
}
