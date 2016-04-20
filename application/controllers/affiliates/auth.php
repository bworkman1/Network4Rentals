<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Auth extends CI_Controller
    {	
		function __construct()
		{
			parent::__construct();
			$this->load->library('oauth2');
		}
		
        public function session($provider)
        {	
            $this->load->helper('url_helper');

		
            if($provider == 'google') {
                $provider = $this->oauth2->provider($provider, array(
                    'id' => '474343487201-rfesikhd4ovnoh2atv7o8fm3pe8vks38.apps.googleusercontent.com',
                    'secret' => 'dD25Jp5PcqwSBogEiYHooJkR',
                ));
            } else if($provider == 'facebook') {
                $provider = $this->oauth2->provider($provider, array(
                    'id' => '766407000148765',
                    'secret' => '35d051237bcc272822014f3ba9eaddef',
                ));
            }



            if ( ! $this->input->get('code'))
            {
                // By sending no options it'll come back here
                $provider->authorize();
            }
            else
            {
                try
                {
                    $token = $provider->access($_GET['code']);

                    $user = $provider->get_user_info($token);

                    $user['login_type'] = $this->uri->segment(4);

                    $this->load->model('modules/auth_signin');
                    $this->auth_signin->checkForUser($user);

                }

                catch (OAuth2_Exception $e)
                {
                    show_error('That didnt work: '.$e);
                }

            }
        }
    }