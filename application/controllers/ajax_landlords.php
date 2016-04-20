<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class ajax_Landlords extends CI_Controller {

		var $imgUrl;
		var $imgInitW;
		var $imgInitH;
		var $imgW;
		var $imgH;
		var $imgY1;
		var $imgX1;
		var $cropW;
		var $cropH;
		var $angle;
		var $isEdit;
		
		function __construct()
		{
			parent::__construct();
		}
		
		function check_if_loggedin() 
		{
			if($this->session->userdata('logged_in') == false)
			{
				$cookie = array(
					'name'   => 'logged_in',
					'domain' => '.network4rentals.com',
					'path'   => '/',
				);
				delete_cookie($cookie);
				redirect('landlords/login');
				exit;
			}
			if($this->session->userdata('side_logged_in') != '8468086465404') {
				$cookie = array(
					'name'   => 'logged_in',
					'domain' => '.network4rentals.com',
					'path'   => '/',
				);
				delete_cookie($cookie);
				$this->session->sess_destroy();
				redirect('landlords/login');
				exit;
			}
		}
		
		function invite_tenant()
		{
			$this->check_if_loggedin();
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('sendBy', 'sendBy', 'required|trim|min_length[1]|max_length[5]|xss_clean');
				$this->form_validation->set_rules('behalf', 'behalf', 'trim|max_length[12]|integer|xss_clean');
				if(isset($_POST['cell'])) {
					$this->form_validation->set_rules('cell', 'cell', 'required|trim|min_length[14]|max_length[14]|xss_clean');
				} else {
					$this->form_validation->set_rules('email', 'email', 'required|trim|max_length[60]|valid_email|xss_clean');
					$this->form_validation->set_rules('email2', 'email', 'required|trim|max_length[60]|valid_email|matches[email]|xss_clean');
				}
				if($this->form_validation->run() == TRUE) {
					
					extract($_POST);
		
					$this->load->model('landlords/user_invite');
					$data = $this->user_invite->invite_tenant($_POST);
				} else {
					$data = array('error'=>validation_errors());
				}
				echo json_encode($data);
			}
		}
		
		public function check_if_email_exists()
		{
			if ($this->input->is_ajax_request()) {
				$email = $_POST['email'];
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					echo '3';
				} else {
					$this->load->model('landlords/create_user_model');
					$results = $this->create_user_model->check_unique_email($email);
					if($results) {
						echo '1';
					} else {
						echo '2';
					}
				}
				
			}
		}
		
		public function get_single_property_item()
		{
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('id', 'id', 'required|trim|min_length[1]|max_length[12]|integer');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('landlords/listings_handler');
					$item = $this->listings_handler->get_single_property_item($id);
					echo json_encode($item);
				}
			}
		}
		
		public function suggested_contractors()
		{
			$id = (int)$this->uri->segment(3);
			$this->load->model('landlords/suggested_contractors');
			$data = $this->suggested_contractors->pull_suggestions($id);
			//array_multisort($data, SORT_DESC);
			//uasort($data, array($this,'compare_score'));
			//usort($data, function($a, $b) {
				//return $a['order'] - $b['order'];
			//});
			echo json_encode($data);
			
		}
			
		public function assoc_invite()
		{
			$this->check_if_loggedin();
			if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('id', 'id', 'required|trim|min_length[1]|max_length[12]|integer');
				if($this->form_validation->run() == TRUE) {
					extract($_POST);
					$this->load->model('landlords/associations');
					$data = $this->associations->get_association_details($id);
					if($data != false) {
						echo json_encode($data);
					} else {
						echo 0;
					}
				} else {
					echo 0;
				}
			} else {
				echo 0;
			}
		}
			
			
		/* IMAGE CROPPER FUNCTIONS */	
	public function image_uploader()
	{				

		$dir = $this->setImagePath();
		$config['upload_path'] = './uploads/'.$dir;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		
		$file = "image";
		if($this->upload->do_upload($file)) {
			$uploaded = $this->upload->data();
			unset($config);

			$image = '../uploads/'.$dir.$uploaded['file_name'];
			
			
			$this->load->library('image_lib'); 
		
			$this->image_lib->clear();
			unset($config);
			
			$resized = true;
			if($uploaded['image_height']>1000 || $uploaded['image_width'] > 1000) {
				$width = 750;
				$height = 750;
				$config['source_image']	= './uploads/'.$dir.$uploaded['file_name'];
				$config['image_library'] = 'gd2';
				$config['maintain_ratio'] = true;
				$config['width']	= $width;
				$config['height']	= $height;
				$config['quality']	= 75;
				$this->image_lib->initialize($config); 
				
				
				if(!$this->image_lib->resize()) {
					$resized = false;
				}
			}
			if($resized == false) {
				$response = Array(
					"status" => 'error',
					"message" => $this->image_lib->display_errors(' ', ' ').' '.$image,
				);
			} else {
				$response = array(
					"status" => 'success',
					"url" => $image,
					"width" => $uploaded['image_width']/2,
					"height" => $uploaded['image_height']/2,
				);
			}
			
		} else {
			$response = Array(
				"status" => 'error',
				"message" => $this->upload->display_errors(' ', ' '),
			);
		}
		echo json_encode($response);
	
	}
	
	private function setImagePath()
	{
		$year = date("Y");   
		$month = date("m");   
		$filename = "./uploads/".$year;
		$filename2 = "./uploads/".$year."/".$month;

		if(file_exists($filename)){
			if(file_exists($filename2)==false){
				mkdir($filename2,0777);
				
				$my_file = $filename2.'/index.html';
				$handle = fopen($my_file, 'w');
			}
		}else{
			mkdir($filename,0777);
			$my_file = $filename.'/index.html';
			$handle = fopen($my_file, 'w');
			if(file_exists($filename2)==false){
				mkdir($filename2,0777);
				
				$my_file = $filename2.'/index.html';
				$handle = fopen($my_file, 'w');
			}
		}
		return $year.'/'.$month.'/';
	}
	

	
	public function image_cropper()
	{
		$this->imgUrl = $_POST['imgUrl'];
		$imgOnly = explode('?', $this->imgUrl);
		$this->imgUrl = $imgOnly[0];
		if($imgOnly[2]=='edit') {
			$this->imgUrl = substr($imgOnly[0], 3);
			$this->isEdit = true;
		} else {
			$this->isEdit = false;
		}
		
		// original sizes
		$this->imgInitW = $_POST['imgInitW'];
		$this->imgInitH = $_POST['imgInitH'];
		// resized sizes
		$this->imgW = $_POST['imgW'];
		$this->imgH = $_POST['imgH'];
		// offsets
		$this->imgY1 = $_POST['imgY1'];
		$this->imgX1 = $_POST['imgX1'];
		// crop box
		$this->cropW = $_POST['cropW'];
		$this->cropH = $_POST['cropH'];
		// rotation angle
		$this->angle = $_POST['rotation'];
		
		$this->load->library('image_lib'); 		
		$sized = true;
	
		
		if(!empty($this->angle)) {
			if(!$this->rotate_image()) {
				$sized = false;
			}
		}
		
		$cropped = $this->crop_image();
		if($cropped !== true) {
			$sized = false;
		}
		if($sized) {
			$response = Array(
				"status" => 'success',
				"url" => $imgOnly[0].'?'.rand(1,99999999999).'f'.$this->cropH.'f'.$this->cropW.'f'.$this->imgH.'f'.$this->imgW.'f'.$this->imgX1.'f'.$this->imgY1
			);
		} else {
			$response = Array(
				"status" => 'error',
				"message" => 'Cant write cropped Files '.$this->imgUrl
			);	
		}
		
		echo json_encode($response);
		
	}
	
	private function rotate_image() 
	{
		$jpeg_quality = 100;
		
		$imgName = substr($this->imgUrl, 0, -4);
		$output_filename = $imgName;		
		$imgType = substr($this->imgUrl, -3);

		switch(strtolower($imgType))
		{
			case 'png':
				$img_r = imagecreatefrompng(substr($this->imgUrl, 1));
				$source_image = imagecreatefrompng(substr($this->imgUrl, 1));
				$type = '.png';
				break;
			case 'jpg':
				$img_r = imagecreatefromjpeg(substr($this->imgUrl, 1));
				$source_image = imagecreatefromjpeg(substr($this->imgUrl, 1));
				$type = '.jpg';
				break;
			case 'gif':
				$img_r = imagecreatefromgif(substr($this->imgUrl, 1));
				$source_image = imagecreatefromgif(substr($this->imgUrl, 1));
				$type = '.gif';
				break;
			default: 
				$response = Array(
					"status" => 'error',
					"message" => 'Invalid image type'
				);	
				echo json_encode($response);
				exit;
		}

		//Check write Access to Directory

		if(!is_writable(substr($this->imgUrl, 1))){
				$response = Array(
					"status" => 'error',
					"message" => 'Cant write cropped File '.$output_filename
				);	
				echo json_encode($response);
				exit;
		}else{
			$resizedImage = imagecreatetruecolor($this->imgW, $this->imgH);
			imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $this->imgW, $this->imgH, $this->imgInitW, $this->imgInitH);
			$rotated_image = imagerotate($resizedImage, -$this->angle, 0);
			imagejpeg($rotated_image, substr($this->imgUrl, 1), $jpeg_quality);
			return true;
		}
	}
	
	private function resize_image()
	{
		$this->image_lib->clear();
		$config['image_library'] = 'gd2';
		$config['source_image']	= substr($this->imgUrl, 1);
		$config['width']	= $this->imgW;
		$config['height']	= $this->imgH;
		$config['maintain_ratio'] = true;
		$this->image_lib->initialize($config);
		if($this->image_lib->resize()) {
			return true;
		}		
		
		return false;
	}
	
	private function crop_image() 
	{
		if(!$this->resize_image()) {
			return $false;
		}
		
		$this->image_lib->clear();
		$config['image_library'] = 'gd2';
		$config['source_image']	= substr($this->imgUrl, 1);
		$config['width']	= $this->cropW;
		$config['height']	= $this->cropH;
		$config['x_axis']	= $this->imgX1;
		$config['y_axis']	= $this->imgY1;
		$config['maintain_ratio'] = true;
		$this->image_lib->initialize($config);
		if($this->image_lib->crop()) {
			return true;
		}
		return false;
	}
			
	public function search_listings()
	{
		$this->form_validation->set_rules('search', 'Search', 'required|trim|min_length[3]|max_length[15]|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			echo json_encode( array( 'error'=>validation_errors() ) );
			exit;
		} else {
			extract($_POST);
			
			$this->load->model('landlords/listings_handler');
			$data = $this->listings_handler->searchForListing($search);
			echo json_encode($data);
			exit;
		}
	}
			
}