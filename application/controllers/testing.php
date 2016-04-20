<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testing extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->_init();
	}
	
	private function _init()
	{
		$this->output->set_template('json');
	}

	public function index()
	{
		echo 'No Access';
	}

	function check_if_loggedin() 
	{
		$user_id = $this->session->userdata('user_id');
		if($this->session->userdata('logged_in') !== true)
		{
			return false;
		} elseif($this->session->userdata('side_logged_in') != '8468086465404') {
			return false;
		} elseif(empty($user_id)) {
			return false;
		} else {
			return true;
		}
	}
	
	public function  phonegap_data() 
	{
		
		//$this->session->unset_userdata('login_tries');
		$login_tries = $this->session->userdata('login_tries'); //Login security that checks how many attempts they have used to login
		if(empty($login_tries)) {
			$this->session->set_userdata('login_tries', 1);
		} else {
			$count = $login_tries+1;
			$this->session->set_userdata('login_tries', $count);
		}
		$error = false;
		$last_login = $this->session->userdata('login_tries');
		

		if($last_login>4) {
			$time_failed = $this->session->userdata('time_failed');
			$time_now = date('ymdhi');
			if(empty($time_failed)) {
				$this->session->set_userdata('time_failed', date('ymdhi'));
			} else {
				$time_remaining = $time_now-$time_failed;
				if($time_remaining<1) {
					$error = true;
				} else {
					$this->session->set_userdata('login_tries', 1);	
					$this->session->unset_userdata('time_failed');	
				}
			}
		}
		
		$last_login = $this->session->userdata('login_tries');
		$tries_left = 5-$last_login;
		if($tries_left == '0') {
			$error = true;
		}
		
		if($error) {
			$data = array('error'=>'Your account has been locked due to too many failed login attempts. Please close the app and try again again in '.(5-$time_remaining).' minutes. ', 'locked'=>TRUE);
		} else {
			$username = $this->validate_username($_GET['username']);
			$password = $this->validate_username($_GET['password']);
			if($username !== false) {
				if($username !== false) {
					$results = $this->db->get_where('landlords', array('user'=>$username, 'pwd'=>md5($password)));
					if($results->num_rows()>0) {
						$user_data = $results->row();
						$this->session->set_userdata('user_id', $user_data->id);
						$this->session->set_userdata('side_logged_in', '8468086465404');
						$this->session->set_userdata('logged_in', TRUE);						
						$data = array('success'=>'Check Login Details '.$this->session->userdata('test_cookie'));
					} else {
						$data = array('error'=>'Invalid username or password. You have '.$tries_left.' attempts left before being locked out for 5 mins');
					}
				} else {
					$data = array('error'=>'Invalid username or password You have '.$tries_left.' attempts left before being locked out for 5 mins');
				}
			} else {
				$data = array('error'=>'Invalid username or password You have '.$tries_left.' attempts left before being locked out for 5 mins');
			}
		}
		echo $_GET['jsoncallback'] . '(' . json_encode($data) . ');';
	}
	
	private function validate_username($str) {
		// each array entry is an special char allowed
		// besides the ones from ctype_alnum
		$allowed = array(".", "-", "_");
		if(strlen($str)>20) {
			return false;
		}
		if ( ctype_alnum( str_replace($allowed, '', $str ) ) ) {
			return $str;
		} else {
			return false;
		}
	}
	
	public function fetch_activity()
	{
		
		$perms = $this->check_if_loggedin();
		if($perms) {
			$user_id = $this->session->userdata('user_id');
			/////
			$this->load->model('landlords/fetch_activity_model');			
			$d = $this->fetch_activity_model->fetch_recent_activity(20, 1, $user_id, null, null);
			$data = array();
			foreach($d as $key => $val) {
				array_push($data, array('action' => $val->action, 'created'=>date('m-d-Y h:i', strtotime($val->created)+3600),  'action_id'=>$val->action_id));
			}
			
			///
		} else {
			$data = array('error', 'Not allowed here');
		}
		//$this->output->enable_profiler(TRUE);
		echo $_GET['jsoncallback'] . '(' . json_encode($data) . ');';
	}
	
	
	/// AUTHORIZE NET SETTINGS
	public function silent_post_url() {
		//CHECK WHO THE INCOMING TRANSACTIONS ARE COMING FROM
		$this->load->library('encrypt');
		$hash = $this->uri->segment(3); //ENCRYPTED USER ID AND USERNAME
		if(!empty($hash)) { // MAKE SURE HASH IS NOT EMPTY
			$hash = str_replace(array('-', '_', '~'), array('+', '/', '='), $hash); //CONVERTED TO UNFRIENDLY URL FROM FRIENDLY URL
			$un_encrypt = $this->encrypt->decode($hash); 
			$hash_array = explode('|', $un_encrypt); // $hash_array[0] == id // $hash_array[1] == username
			if($hash_array[0]>0 && !empty($hash_array[1])) { //CHECK TO MAKE SURE BOTH HOLD PROPER VALUES
				$this->load->model('landlords/payment_handler');
				if($this->payment_handler->check_silent_post_link($hash_array)) { //IF USERNAME AND ID MATCH UP IN DATABASE
					
					if(!empty($_POST)) { //IF POST VARIABLES ARE PRESENT
						foreach($_POST as $key => $val) {
							$data[$key] = $val;
						}
						$data['landlord_id'] = $hash_array[0];
						$this->payment_handler->log_auth_data($data);
						$autopay = 'n';
						if($_POST['x_response_code'] == 1) {
							$update_data['status'] = 'Complete';
							$autopay = 'y';
						} elseif($_POST['x_response_code'] == 2) {
							$update_data['status'] = 'Payment Declined';
						} elseif($_POST['x_response_code'] == 3) {
							$update_data['status'] = 'Payment Expired';
						} elseif($_POST['x_response_code'] == 4) { 
							$update_data['status'] = 'Held For Review';
							$autopay = 'y';
						} else {
							$update_data['status'] = 'Unknown';
						}
						
						$update_data['name'] = $_POST['x_first_name'].' '.$_POST['x_last_name'];
						$update_data['trans_id'] = $_POST['x_trans_id'];
						$update_data['id'] = $hash_array[0];
						$update_data['amount'] = $_POST['x_amount'];
						$update_data['landlord_id'] = $hash_array[0];
						$update_data['recurring_payment'] = $autopay;
						
					
						
						$this->payment_handler->update_payment_history($update_data);
						
						//NOW UPDATE THE payment_history DATABASE WITH THE RESPONSE 
					} //end if post not empty	
					  else {
						echo '<form action="'.$_SERVER['request_uri'].'" method="post">
    <input type="hidden" name="x_response_code" value="1"/>
    <input type="hidden" name="x_response_subcode" value="1"/>
    <input type="hidden" name="x_response_reason_code" value="1"/>
    <input type="hidden" name="x_response_reason_text" value="This transaction has been approved."/>
    <input type="hidden" name="x_auth_code" value=""/>
    <input type="hidden" name="x_avs_code" value="P"/>
    <input type="hidden" name="x_trans_id" value="2229724204"/>
    <input type="hidden" name="x_invoice_num" value=""/>
    <input type="hidden" name="x_description" value=""/>
    <input type="hidden" name="x_amount" value="9.95"/>
    <input type="hidden" name="x_method" value="CC"/>
    <input type="hidden" name="x_type" value="auth_capture"/>
    <input type="hidden" name="x_cust_id" value="1"/>
    <input type="hidden" name="x_first_name" value="John"/>
    <input type="hidden" name="x_last_name" value="Smith"/>
    <input type="hidden" name="x_company" value=""/>
    <input type="hidden" name="x_address" value=""/>
    <input type="hidden" name="x_city" value=""/>
    <input type="hidden" name="x_state" value=""/>
    <input type="hidden" name="x_zip" value=""/>
    <input type="hidden" name="x_country" value=""/>
    <input type="hidden" name="x_phone" value=""/>
    <input type="hidden" name="x_fax" value=""/>
    <input type="hidden" name="x_email" value=""/>
    <input type="hidden" name="x_ship_to_first_name" value=""/>
    <input type="hidden" name="x_ship_to_last_name" value=""/>
    <input type="hidden" name="x_ship_to_company" value=""/>
    <input type="hidden" name="x_ship_to_address" value=""/>
    <input type="hidden" name="x_ship_to_city" value=""/>
    <input type="hidden" name="x_ship_to_state" value=""/>
    <input type="hidden" name="x_ship_to_zip" value=""/>
    <input type="hidden" name="x_ship_to_country" value=""/>
    <input type="hidden" name="x_tax" value="0.0000"/>
    <input type="hidden" name="x_duty" value="0.0000"/>
    <input type="hidden" name="x_freight" value="0.0000"/>
    <input type="hidden" name="x_tax_exempt" value="FALSE"/>
    <input type="hidden" name="x_po_num" value=""/>
    <input type="hidden" name="x_MD5_Hash" value="A375D35004547A91EE3B7AFA40B1E727"/>
    <input type="hidden" name="x_cavv_response" value=""/>
    <input type="hidden" name="x_test_request" value="false"/>
    <input type="hidden" name="x_subscription_id" value="365314"/>
    <input type="hidden" name="x_subscription_paynum" value="1"/>
    <input type="submit"/>
</form>';
					 } 
				}
			}
		}
		$this->output->enable_profiler(TRUE);
	}
}