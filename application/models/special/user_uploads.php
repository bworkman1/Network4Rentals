<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_uploads extends CI_Model { 
	
	var $base_path;
	var $mUserFolderName; 
	var $mUploadPath;
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('upload');
		
		$id = $this->session->userdata('user_id');
		if(empty($id)) {
			echo 'Get OUT!';
			exit;
		}
		
    }
	
	public function upload_image($img, $feildName, $profile = false, $args = false) 
	{
		$this->set_image_path();
		
		$config['upload_path'] = $this->mUploadPath;
		$config['allowed_types'] = 'gif|jpg|png|JPEG|JPG|JPEG|jpeg';
	
		$this->upload->initialize($config);
		
		$file = $feildName;
		if (!$this->upload->do_upload($file)) {
			$error = array('error' => $this->upload->display_errors('',''));
			return $error;
		} else {
			$data = $this->upload->data();
			if(isset($args['resize'])) {
				$this->resizeImage($data);
			}

			$data['system_path'] = ltrim($this->mUploadPath.$data['file_name'], '.');
			
			$data = array('success' => $data);
			
			return $data;
		}
		
	}
	
	public function uploadPDF($img, $feildName) 
	{
		$this->set_image_path();
		
		$config['upload_path'] = $this->mUploadPath;
		$config['allowed_types'] = 'pdf';
	
		$this->upload->initialize($config);
		
		$file = $feildName;
		if (!$this->upload->do_upload($file)) {
			$error = array('error' => $this->upload->display_errors('',''));
			return $error;
		} else {
			$data = $this->upload->data();
			if(isset($args['resize'])) {
				$this->resizeImage($data);
			}

			$data['system_path'] = ltrim($this->mUploadPath.$data['file_name'], '.');
			
			$data = array('success' => $data);
			
			return $data;
		}
		
	}
	
	public function uploadFile($file)
	{
		$this->set_image_path();
		
		$config['upload_path'] = $this->mUploadPath;
		$config['allowed_types'] = 'gif|jpg|png|JPEG|JPG|pdf|doc|docx';
	
		$this->upload->initialize($config);
		$file = "file";
		
		if (!$this->upload->do_upload($file)) {
			$error = array('error' => $this->upload->display_errors('',''));
			return $error;
		} else {
			$data = $this->upload->data();
			$data['system_path'] = ltrim($this->mUploadPath.'/'.$data['file_name'], '.');
			$data = array('success' => $data);
			echo json_encode($data);
			exit;
			
			return $data;
		}
	}
	
	public function uploadFileCallback($file)
	{
		$this->set_image_path();
		
		$config['upload_path'] = $this->mUploadPath;
		$config['allowed_types'] = 'gif|jpg|png|JPEG|JPG|pdf|doc|docx';
	
		$this->upload->initialize($config);
		$file = "file";
		
		if (!$this->upload->do_upload($file)) {
			$error = array('error' => $this->upload->display_errors('<span>','</span>'));
			echo json_encode($error);
			exit;
			return $error;
		} else {
			$data = $this->upload->data();
			$data['system_path'] = ltrim($this->mUploadPath.'/'.$data['file_name'], '.');
			$data = array('success' => $data);
			
			return $data;
		}
	}
	
	private function set_image_path()
	{
		/*
			All images will be stored in user-files according to id and type, then encrypted to hide file names. This should create a unique folder just for that user that will then be able to see all the files they have uploaded later.
		*/	
		
		$user_folder = './uploads/';
			
		$m = date('m');
		$y = date('Y');
		
		if (!file_exists($user_folder)) {
			mkdir($user_folder, 0777);
			$this->createIndexFile($user_folder);
		}
		
		if (!file_exists($user_folder.'/'.$y)) {
			mkdir($user_folder.'/'.$y, 0777);
			$this->createIndexFile($user_folder.'/'.$y);
		}
		
		if (!file_exists($user_folder.'/'.$y.'/'.$m)) {
			mkdir($user_folder.'/'.$y.'/'.$m, 0777);
			$this->createIndexFile($user_folder.'/'.$y.'/'.$m);
		}
		
		$this->mUploadPath = './uploads/'.$y.'/'.$m.'/';
	}
	
	private function createIndexFile($path)
	{		
		$content = "ACCESS DENIED";
		$fp = fopen($path."/index.php","wb");
		fwrite($fp,$content);
		fclose($fp);
	}
	
	private function resizeImage($data) 
	{
		if($data['image_width']>800 || $data['image_height']>800) {
			
			$config['image_library'] = 'gd2';
			$config['source_image']	= $data['full_path'];
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']	= $data['image_width']/2;
			$config['height']	= $data['image_height']/2;
			
			$this->load->library('image_lib', $config); 
			
			$this->image_lib->resize();
		}
	}

}