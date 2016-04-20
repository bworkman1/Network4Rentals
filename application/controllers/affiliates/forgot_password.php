<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot_password extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_init();
    }

    private function _init()
    {
        $title = ucwords(str_replace('-', ' ', end($this->uri->segment_array())));
        $this->output->set_title($title);  // SETS TITLE OF THE PAGE
        $this->output->set_meta('description', 'Affiliates login page'); //SETS META DESCRIPTION
        $this->output->set_meta('keywords', 'Affiliates, login, page'); //SETS META KEYWORDS1

        $this->output->set_template('affiliates/basic');

        if ($this->session->userdata('user_online')) {
            redirect('affiliates/dashboard');
            exit;
        }

    }

    public function index() {
        $this->load->view('affiliates/non-user/forgot-password');
    }

    public function reset_password() {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[50]|xss_clean|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
            redirect('affiliates/forgot-password');
            exit;
        } else {
            extract($_POST);
            $this->load->model('modules/User_affiliates');
            $user = $this->User_affiliates->searchForUser($username, 'email', 1);

            if(!empty($user)) {
                $hash = $this->User_affiliates->setUserHash($user->id);

                if($hash !== false) {

                    $this->load->model('modules/outside_communications');
                    $msg = '<h3>Password Reset Request</h3><p>It looks like you forgot your password and was having trouble logging into the website. No problem we can help you get back in right away. All you have to do is click ont the link below and enter your new username and password and you\'re all set. If you didn\'t request then you can ignore this and your password stays the same.</p><p><a href="'.base_url('affiliates/forgot-password/update-my-password/'.$hash).'"></a></p>';
                    $this->outside_communications->sendSingleEmail($msg);

                    $this->session->set_flashdata('success', 'Check your email for instructions on how to reset your email');
                    redirect('affiliates/forgot-password');
                    exit;
                } else {
                   $this->session->set_flashdata('error', 'Failed to generate secure hash for user, try again');
                   redirect('affiliates/forgot-password');
                   exit;
                }
            } else {
                $this->session->set_flashdata('error', 'User not found, try again');
                redirect('affiliates/forgot-password');
                exit;
            }
        }
    }

    public function update_my_password()
    {
        $hash = $this->uri->segment(4);

        $this->load->model('modules/User_affiliates');
        $data['user'] = $this->User_affiliates->searchForUser($hash, 'reset_hash', 1);
        if(!empty($data['user'])) {
            $this->load->view('affiliates/non-user/reset-password', $data);
        } else {
            $this->session->set_flashdata('error', 'User not found, try again');
            redirect('affiliates/forgot-password');
            exit;
        }
    }

    public function update_password_submit() {
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[7]|max_length[20]|xss_clean|matches[password2]');
        $this->form_validation->set_rules('password2', 'Password Confirm', 'trim|required|min_length[7]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('user', 'user', 'trim|required|min_length[1]|max_length[12]|xss_clean|integer');
        $this->form_validation->set_rules('hash', 'hash', 'trim|required|min_length[20]|max_length[40]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
            redirect('affiliates/forgot-password/update-my-password/'.$_POST['hash']);
            exit;
        } else {
            extract($_POST);
            $this->load->model('modules/User_affiliates');

            if($this->User_affiliates->matchUserHashId($hash, $user)) {
                if($this->User_affiliates->updatePassword($user, $password)) {
                    $this->User_affiliates->clearHash($hash);
                    $this->session->set_flashdata('success', 'You can now login with your new password');
                    redirect('affiliates/login');
                    exit;
                } else {
                    $this->session->set_flashdata('error', 'Updating your password failed, try again');
                    redirect('affiliates/forgot-password/update-my-password/'.$_POST['hash']);
                    exit;
                }
            } else {
                $this->User_affiliates->clearHash($hash);
                $this->session->set_flashdata('error', 'User not found!');
                redirect('affiliates/login');
                exit;
            }


        }

    }

}