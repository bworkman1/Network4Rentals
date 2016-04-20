<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_website extends CI_Controller {

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

        $data['title'] = "My Website";
        $data['sub_nav'] = array(
            'My Website' => '#',
            'Website Stats' => base_url('affiliates/my-website/stats'),
            'Edit Website' => base_url('affiliates/my-website/edit'),
            'View Website' => '#',
           );
		   
		$pageSet = $this->session->userdata('unique_name');
		if(!empty($pageSet)) {
			$data['sub_nav']['View Website'] = 'http://n4r.rentals/'.$pageSet;
		}	
		
		if($this->session->userdata('analytics_connected')) {
			 $data['sub_nav']['Disconnect Analytics'] = base_url('affiliates/my-website/disconnectAnalytics');
		}


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
        redirect('affiliates/my-website/stats');
        exit;
    }

    public function stats()
    {
        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.js');
        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.tooltip.min.js');
        $this->load->js('assets/themes/blue-moon/js/flot/jquery.flot.resize.min.js');
        $this->load->js('assets/themes/blue-moon/js/affiliates/website.js');

        $this->config->load('ga_api');

        $this->load->model('modules/User_affiliates');
        $userData = $this->User_affiliates->getAffiliateById($this->session->userdata('user_id'));

        $data = array();
	
        if(!empty($userData->analytics_id) && !empty($userData->account_id)) {
			$this->session->set_userdata('analytics_connected', true);
            $ga_params = array(
                'applicationName' => $this->config->item('ga_api_applicationName'),
                'clientID' => $this->config->item('ga_api_clientId'),
                'clientSecret' => $this->config->item('ga_api_clientSecret'),
                'redirectUri' => $this->config->item('ga_api_redirectUri'),
                'developerKey' => $this->config->item('ga_api_developerKey'),
                'profileID' => $userData->account_id,
            );

            $this->load->library('GoogleAnalytics', $ga_params);

            $data = array(
                'users' => $this->googleanalytics->get_total('users'),
                'sessions' => $this->googleanalytics->get_total('sessions'),
                'browsers' => $this->googleanalytics->get_dimensions('browser', 'sessions'),
                'operatingSystems' => $this->googleanalytics->get_dimensions('operatingSystem', 'sessions'),
                'source' => $this->googleanalytics->get_dimensions('fullReferrer'),

                'newUsers' => $this->googleanalytics->get_dimensions('userType', 'newUsers'),
            );
            $data['setAnalyticsCode'] = true;
            $data['analyticsAccountId'] = $userData->account_id;
            $data['analyticsId'] = $userData->analytics_id;
        } else {
            $data['setAnalyticsCode'] = false;
        }

        $this->load->view('affiliates/user/my-website/website-stats', $data);
    }

    public function edit()
    {
		$this->load->js('assets/themes/blue-moon/js/affiliates/edit-website.js');
		$this->output->set_title('Edit Website Details');
        $this->load->model('modules/Public_page_table');
        $this->load->model('modules/User_affiliates');
        $data['page'] = $this->Public_page_table->getUserPage($this->session->userdata('user_id'), 'affiliate');
        $data['user'] = $this->User_affiliates->getAffiliateById($this->session->userdata('user_id'));
        $this->load->view('affiliates/user/my-website/edit-website', $data);
    }

    public function ajaxMonthlyVisits() //ajax function for month view
    {
        $this->load->model('modules/User_affiliates');
        $userData = $this->User_affiliates->getAffiliateById($this->session->userdata('user_id'));

        $this->output->set_template('json');
        $this->config->load('ga_api');
        $ga_params = array(
            'applicationName' => $this->config->item('ga_api_applicationName'),
            'clientID' => $this->config->item('ga_api_clientId'),
            'clientSecret' => $this->config->item('ga_api_clientSecret'),
            'redirectUri' => $this->config->item('ga_api_redirectUri'),
            'developerKey' => $this->config->item('ga_api_developerKey'),
            'profileID' => $userData->account_id,
        );
        $this->load->library('GoogleAnalytics', $ga_params);
        $data = $this->googleanalytics->get_dimensions('month');

        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $key = date("m", strtotime( date( 'Y-m-01' )." -$i months"))."";
            $months[$key] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
        }

        $feedback = array();
        foreach($months as $key => $val) {
            $ts = strtotime($val) * 1000;
            $feedback[] = array($ts, (int)$data[$key]);
        }

        echo json_encode($feedback);
    }

    public function disconnectAnalytics()
    {
        $this->config->load('ga_api');
        $ga_params = array(
            'applicationName' => $this->config->item('ga_api_applicationName'),
            'clientID' => $this->config->item('ga_api_clientId'),
            'clientSecret' => $this->config->item('ga_api_clientSecret'),
            'redirectUri' => $this->config->item('ga_api_redirectUri'),
            'developerKey' => $this->config->item('ga_api_developerKey'),
            'profileID' => '76489835',
        );
        $this->load->library('GoogleAnalytics', $ga_params);
        $this->googleanalytics->logout();
		$this->session->unset_userdata('analytics_connected');
        redirect('affiliates/dashboard');
        exit;
    }

    public function ajaxCheckUniqueName()
    {
        $this->form_validation->set_rules(
            'uniqueName',
            'Unique Name',
            'min_length[3]|max_length[70]|xss_clean|is_unique[landlord_page_settings.unique_name]'
        );

        if ($this->form_validation->run() == false) {
            echo json_encode(array('error'=>'Unique name is already taken'));
        } else {
            echo json_encode(array('success'=>true));
        }
    }

    public function save()
    {
		$this->load->js('assets/themes/blue-moon/js/affiliates/edit-website.js');
        $this->form_validation->set_rules(
            'unique_name',
            'Unique Name',
            'min_length[3]|max_length[70]|xss_clean]|trim'
        );
        $this->form_validation->set_rules(
            'desc',
            'Description',
            'min_length[10]|max_length[500]|xss_clean|required||trim'
        );
        $this->form_validation->set_rules(
            'google',
            'Google Url',
            'min_length[10]|max_length[200]|xss_clean|prep_url|trim|trim'
        );
        $this->form_validation->set_rules(
            'twitter',
            'Twitter Url',
            'min_length[10]|max_length[200]|xss_clean|prep_url|trim|trim'
        );
        $this->form_validation->set_rules(
            'facebook',
            'Facebook Url',
            'min_length[10]|max_length[200]|xss_clean|prep_url|trim'
        );
        $this->form_validation->set_rules(
            'linkedin',
            'Linkedin Url',
            'min_length[10]|max_length[200]|xss_clean|prep_url|trim'
        );
        $this->form_validation->set_rules(
            'youtube',
            'Youtube Url',
            'min_length[10]|max_length[200]|xss_clean|prep_url|trim'
        );
        $this->form_validation->set_rules(
            'analytics_id',
            'Tracking Id',
            'min_length[9]|max_length[16]|xss_clean|trim'
        );
        $this->form_validation->set_rules(
            'account_id',
            'Account Id',
            'min_length[6]|max_length[16]|xss_clean|trim'
        );
        $this->form_validation->set_message('is_unique', 'The unique name you selected has already been taken');
        extract($_POST);

        $data['page'] =  (object) array(
            'unique_name' => $unique_name,
            'desc' => $desc,
            'facebook' => $facebook,
            'linkedin' => $linkedin,
            'google' => $google,
            'youtube' => $youtube,
            'twitter' => $twitter,
            'analytics_id' => $analytics_id,
            'account_id' => $account_id,
        );

        $this->load->model('modules/Public_page_table');
        $this->load->model('modules/User_affiliates');
        $this->load->model('special/User_uploads');

        $data['user'] = $this->User_affiliates->getAffiliateById($this->session->userdata('user_id'));
        $data['user']->analytics_id = $analytics_id;
        $data['user']->account_id = $account_id;

        if ($this->form_validation->run() == false) {

            $data['error'] = array('error' => validation_errors('<span>', '</span>'));

        } else {
            if ($this->Public_page_table->checkForUniqueName(
                $data['page']->unique_name,
                $this->session->userdata('user_id')
            )) {
                $data['error'] = array('error' => 'Unique name is already taken, try another');
            }

            if(!empty($data['page']->analytics_id)) {
                if(!$this->isAnalytics($data['page']->analytics_id)) {
                    $data['error'] = array('error' => 'Analytics Tracking Id Is Invalid');
                }
            }

            if (!isset($data['error'])) {
                if (isset($_FILES['profile']['name']) && !empty($_FILES['profile']['name'])) {
                    $imgData = $this->User_uploads->upload_image($_FILES['profile'], 'profile', false);
                    if (isset($imgData['success'])) {
                        $data['page']->image = $imgData['success']['system_path'];
                    } else {
                        $data['error'] = array('error' => $imgData['error']);
                    }
                }
            }

            if (!isset($data['error'])) {
                if (isset($_FILES['background']['name']) && !empty($_FILES['background']['name'])) {
                    $imgData = $this->User_uploads->upload_image($_FILES['background'], 'background', false);
                    if (isset($imgData['success'])) {
                        $data['page']->background = $imgData['success']['system_path'];
                    } else {
                        $data['error'] = array('error' => $imgData['error']);
                    }
                }
            }

            if (!isset($data['error'])) {
                if ($this->Public_page_table->updateUserPage(
                    $this->session->userdata('user_id'),
                    'affiliate',
                    $data['page']
                )) {
                    $this->session->set_flashdata('success', 'Settings saved successfully');
                    redirect('affiliates/my-website/edit');
                    exit;
                } else {
                    $data['error'] = array(
                        'error'=>
                            'Failed to update website. If you didn\'t change anything you would see this error'
                    );
                }
            }
        }

        $this->load->view('affiliates/user/my-website/edit-website', $data);
    }

    function isAnalytics($str){
        return preg_match('/^ua-\d{4,9}-\d{1,4}$/i', strval($str)) ? true : false;
    }
	
	function ajaxDeletImage() 
	{
		$this->output->set_template('json');
		$type = $this->uri->segment(4);
		if($type=='background' || $type=='profile') {
			if($type == 'profile') {
				$type=='image';
			}
			$this->load->model('modules/public_page_table');
			$this->public_page_table->removeImageFromPage('affiliate', $this->session->userdata('user_id'), $type);
			echo json_encode(array('success'=>'Image Successfully Deleted'));
		} else {
			echo json_encode(array('error'=>'Invalid Selection'));
		}
	}
	
}
