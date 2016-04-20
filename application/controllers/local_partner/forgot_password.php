<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot_password extends CI_Controller
{

    public function __construct() {
        parent::__construct();

        $this->output->set_title('Local Partner Login');  // SETS TITLE OF THE PAGE
        $this->output->set_meta('description', 'Local Partner login page'); //SETS META DESCRIPTION
        $this->output->set_meta('keywords', 'Local Partner Login'); //SETS META KEYWORDS1

        $this->output->set_template('advertisers/non-user');

        $this->load->library('recaptcha');
    }

    public function index()   //changed
    {
        $data['captcha'] = $this->recaptcha->recaptcha_get_html();

        $this->load->view('advertisers/non-user/forgot-password', $data);
    }

    public function reset()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|min_length[3]|max_length[100]|xss_clean|valid_email');

        $this->recaptcha->recaptcha_check_answer();

        if ( $this->form_validation->run() && $this->recaptcha->getIsValid() ) {

            extract($_POST);

            $this->load->model('advertisers/reset_password');
            $hash = $this->reset_password->check_user_email($email);

            if($hash !== false) {

                // DB Updated with hash now need to email user that hash
                $message = '
                    <h3>Reset Your Password</h3>
                    <p>You have requested to reset your password. If you did not request to have your password reset
                     you can ignore this email. Else click the link below to go through the steps to reset your
                     password.</p><a href="'.base_url('local-partner/forgot-password/password/'.$hash).'">Reset Password</a>';
                $subject = 'N4R | Password Reset Instructions';

                $this->load->model('special/send_email');
                $this->send_email->sendEmail($email, $message, $subject);

                $this->session->set_flashdata('success', 'An email has been set to your email address with instructions on how to reset your password');

            } else {
                $this->session->set_flashdata('error', 'Invalid email, try again');
            }

        } else {
            $captchaError = $this->recaptcha->getError();
            if(!empty($captchaError)) {
                $error = 'Incorrect Captcha, try again';
            } else {
                $error = validation_errors();
            }
            $this->session->set_flashdata('error', $error);
        }
			
        redirect('local-partner/forgot-password');
        exit;
    }

    public function password()
    {
        $hash = $this->uri->segment(4);
        $hash = $this->security->xss_clean($hash);
        if($hash) {
            if (ctype_alnum($hash)) {
                $this->load->model('advertisers/reset_password');
                if($this->reset_password->check_token($hash)) {
                    $this->session->set_userdata('hash', $hash);

                    $this->load->view('advertisers/non-user/change-password');
                } else {
                    $this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
                    redirect('local-partner/forgot-password');
                    exit;
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
                redirect('local-partner/forgot-password');
                exit;
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid Token From Email Used, Try Again');
            redirect('local-partner/forgot-password');
            exit;
        }

    }

    public function newpass()
    {
        $hash = $this->session->userdata('hash');
        if (!empty($hash)) {
            $this->load->model('advertisers/reset_password');

            $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[20]|xss_clean|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', 'Password Confirm', 'required|trim|min_length[6]|max_length[20]|xss_clean|matches[password]');

            if ($this->form_validation->run() !== false) {
                extract($_POST);

                if($this->reset_password->change_password($hash, $password)) {
                    $this->session->set_flashdata('success', 'Your password has been changed, you can now login');
                    redirect('local-partner/login');
                    exit;
                } else {
                    $this->session->set_flashdata('error', 'Password not changed, something went wrong. Try again');
                    redirect('local-partner/forgot-password/password/'.$hash);
                    exit;
                }

            } else {
                $this->session->set_flashdata('error', validation_errors());
                redirect('local-partner/forgot-password/password/'.$hash);
                exit;
            }

        } else {
            $this->session->set_flashdata('error', 'Invalid selection, try again');
            redirect('local-partner/forgot-password');
            exit;
        }
    }


}




