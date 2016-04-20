<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listings extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->_init();
	}
	
	private function _init()
	{
		$title = 'Homes For Rent | Network 4 Rentals';
		$description = 'Looking for homes for rent?, Our rental listings is easy to use and is nationwide.';
		$keywords = 'Homes For Rent, Rentals, For Rent, Rental Homes';
		
		$this->output->set_common_meta($title, $description, $keywords);
	
		$this->load->model('special/ads_output');
		$data['result'] = $this->ads_output->get_ads_in_location();
		$this->load->vars($data);
		if($this->session->userdata('side_logged_in') == '8468086465404') { //landlords
			$this->output->set_template('logged-in-landlord');
		} else if($this->session->userdata('side_logged_in') == '898465406540564') { //renters
			$this->output->set_template('logged-in');
		} else { //not logged in
			$this->output->set_template('landlord-not-logged-in');
		}
		$this->load->js('https://maps.googleapis.com/maps/api/js');
		$this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
		$this->load->js('assets/themes/default/js/animate/wow.min.js');
		$this->load->js('assets/themes/default/js/bootstrap.min.js');		
		$this->load->js('assets/themes/default/js/custom-listings.js');
		
		$this->load->css('assets/themes/default/css/listings.css');
		
	}
	
	public function index()
	{
		$this->load->js('assets/themes/default/js/listings/listings.js');
		$this->output->set_template('listings/main-shell-home');
		$this->load->view('listings/home-2', $data);
	}
	
	public function search()
	{
		$this->output->set_template('listings/main-shell');
		$this->load->js('assets/themes/default/js/listings/listings.js');
		$this->load->model('listings/listings_handler');
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = $this->listings_handler->search_listings();
		}
		
		$this->load->view('listings/search', $data);
	}	
	
	
	
	public function view_listing() 
	{		
		$this->output->set_template('listings/main-shell');
		$this->load->js('assets/themes/default/js/listings/listings.js');
		
		$id = (int)$this->uri->segment(3);
		$this->load->model('listings/listings_handler');
		$data['listing'] = $this->listings_handler->get_listing($id);
		if(empty($data['listing'])) {
			$data['listing'] = $this->listings_handler->get_inactive_listing($id);
		}
		if(empty($data['listing'])) {
			$this->session->set_flashdata('error', 'No Listing Found, Try Using The Search Form Below');
			redirect('listings');
			exit;
		}
		
		$facebook_desc = 'Property for rent in the '.$data['listing']->city.' area. '.$data['listing']->details.' To learn more about this listing call '.$data['listing']->landlord->phone;
		
		$this->output->set_meta('og:description',$facebook_desc);
	
		if(!empty($data['listing']->images->image1)) {
			$this->output->set_meta('og:image',base_url().'listing-images/'.$data['listing']->images->image1);
		} else {
			$this->output->set_meta('og:image','https://network4rentals.com/network/public-images/N4R-Profile.png');
		}
		$data['associations'] = $this->listings_handler->getListingAssociations();
	
		$this->load->js('assets/themes/default/js/lightboxdistrib.min.js');
		$this->load->css('assets/themes/default/css/easybox.min.css');
		
		$this->load->library('recaptcha');
        $data['recaptcha_html'] = $this->recaptcha->recaptcha_get_html();
		
		$this->load->view('listings/listing', $data);
	}
	
	function detect_city($ip) 
	{
        $default = 'UNKNOWN';
        if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost') {
            $ip = '8.8.8.8';
			$curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';
			$url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode($ip);
			$ch = curl_init();
			$curl_opt = array(
				CURLOPT_FOLLOWLOCATION  => 1,
				CURLOPT_HEADER      => 0,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_USERAGENT   => $curlopt_useragent,
				CURLOPT_URL       => $url,
				CURLOPT_TIMEOUT         => 1,
				CURLOPT_REFERER         => 'http://' . $_SERVER['HTTP_HOST'],
			);
			curl_setopt_array($ch, $curl_opt);
			$content = curl_exec($ch);
			if (!is_null($curl_info)) {
				$curl_info = curl_getinfo($ch);
			}
			curl_close($ch);
			if ( preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs) )  {
				$city = $regs[1];
			}
			if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) )  {
				$state = $regs[1];
			}
			if( $city!='' && $state!='' ){
				$location = $city . ', ' . $state;
				return $location;
			}else{
				return $default;
			}
		}
    }
	
	function update_zips()
	{
		$this->load->model('listings/listings_handler');
		$this->listings_handler->update_zips();
	}
	
	function testing_map()
	{
		$this->load->js('assets/themes/default/js/listings/map-listings.js');
		$this->output->set_template('listings/main-shell-home');
		
		$this->load->model('listings/listings_handler');
		echo $this->listings_handler->update_listing_lats_longs();
		$this->load->view('listings/test');
	}
	
	


	
}