<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Renters extends CI_Controller {
	
	private $salt = "GGeAHUybn6V4WF=HgjC";
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('encrypt');
		$this->output->set_template('json');
	}
	
	public function login() 
	{
		$this->load->model("android/renters/login_handler");
		echo $this->login_handler->userLogin($_POST["user"], $_POST["password"], $_POST["app_id"], $this->salt);
	}
	
	
}