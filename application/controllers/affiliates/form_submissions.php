<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_submissions extends CI_Controller
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

        $this->output->set_template('affiliates/logged-in');

        $data['title'] = "My Form Submissions";
        $data['sub_nav'] = array(
            'Form Submissions' => '#',
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
        $this->load->model('modules/affiliate_form_subs');
        $this->load->model('modules/User_common');

        $offset = 0;
        $data = $this->affiliate_form_subs->getFormSubmissions($this->session->userdata('user_id'), $offset);

        $headings = array('ID', 'Email', 'Name', 'Phone', 'Timestamp', 'View');
        $classes = array('table-hover');
        $data['table'] = $this->user_common->createTableData($headings, $data, $classes);
        $this->load->view('affiliates/user/form-submissions', $data);
    }

    public function view_form()
    {
        $this->output->set_title('User Submitted Form');
        $this->load->model('modules/affiliate_form_subs');
        $id = (int)$this->uri->segment(4);
        if($id>0) {
            $data['user'] =
                $this->affiliate_form_subs->getSingleFormSubmission($id, $this->session->userdata('user_id'));
            if(empty($data)) {
                $this->session->set_flashdata('error', 'Form submission not found, try again');
                redirect('affiliates/form-submissions');
                exit;
            }

            $this->load->view('affiliates/user/view-user-form', $data);
        } else {
            $this->session->set_flashdata('error', 'Invalid selection try again');
            redirect('affiliates/form-submissions');
            exit;
        }
    }

}