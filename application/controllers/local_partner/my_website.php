<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class My_website extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->output->set_template('advertisers/user');
		
		$this->output->set_title('My Website');  // SETS TITLE OF THE PAGE
        $this->output->set_meta('description', 'Local Partner Website Settings'); //SETS META DESCRIPTION
        $this->output->set_meta('keywords', 'Local Partner, Website, Settings'); //SETS META KEYWORDS1
        $this->load->section('sidebar', 'advertiser-sidebar');
        $this->load->model('advertisers/security_check');
        if (!$this->security_check->check()) {
            redirect('local-partner/login');
            exit;
        }

    }

    function index()
    {
		$this->load->js('https://code.jquery.com/ui/1.11.2/jquery-ui.js');
		$this->load->js('assets/themes/default/js/masked-input.js'); 
		$this->load->js('assets/themes/default/js/contractors/bootstrap-tagsinput.min.js'); 
		$this->load->js('assets/themes/default/colorpicker/js/bootstrap-colorpicker.min.js'); 
		
		
		$this->load->css('assets/themes/default/css/contractors/bootstrap-tagsinput.css');
		$this->load->css('assets/themes/default/colorpicker/css/bootstrap-colorpicker.min.css');
		$this->load->css('https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
		
		$this->load->model('modules/public_page_table');
		$this->load->model('modules/website_pages');
		$this->load->model('advertisers/account_handler');
        $this->load->model('advertisers/public_page_handler');

		$data['settings'] = $this->public_page_table->getUserPage($this->session->userdata('user_id'), 'advertiser');
		$data['web_pages'] = $this->website_pages->getUserPages($this->session->userdata('user_id'), 'advertiser');
	    $data['categories'] = $this->public_page_handler->get_category_options();
        $data['userCategory'] = $this->public_page_handler->getUserCategory();

		if(empty($data['settings'])) {
			$data['settings'] = $this->account_handler->profile_info($this->session->userdata('user_id'));
		}

		if(isset($_POST)) {
			if(empty($data['settings']->unique_name)) {
				$this->form_validation->set_rules('unique_name', 'Unique Name', 'trim|max_length[100]|xss_clean|required|is_unique[landlord_page_settings.unique_name]');
			} else {
				$this->form_validation->set_rules('unique_name', 'Unique Name', 'trim|max_length[100]|xss_clean|required');
			}
		} 
	 
		$this->form_validation->set_rules('bName', 'Business Name', 'trim|max_length[100]|xss_clean|required');
		$this->form_validation->set_rules('desc', 'Business Description', 'trim|max_length[4000]|required');
		
		$this->form_validation->set_rules('facebook', 'Facebook Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('google', 'Google Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('twitter', 'Twitter Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('linkedin', 'Linkedin Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('youtube', 'Youtube Url', 'trim|max_length[255]|xss_clean|prep_url');
		$this->form_validation->set_rules('web_link', 'Website Link', 'trim|max_length[100]|xss_clean|prep_url');
		$this->form_validation->set_rules('link_title', 'Link Title', 'trim|max_length[30]|xss_clean');
	
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[100]|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[50]|xss_clean|required');
		$this->form_validation->set_rules('state', 'State', 'trim|max_length[2]|xss_clean|required');
		$this->form_validation->set_rules('zip', 'Zip Code', 'trim|max_length[5]|min_length[5]|xss_clean|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[100]|xss_clean|valid_email|required');
		$this->form_validation->set_rules('website', 'Website Url', 'trim|max_length[255]|xss_clean');
		$this->form_validation->set_rules('newsletter', 'Newsletter', 'trim|max_length[100]|xss_clean');
		
		$this->form_validation->set_rules('seo_analytics', 'Newsletter', 'trim|max_length[25]|xss_clean');
		$this->form_validation->set_rules('seo_description', 'SEO Description', 'trim|max_length[160]|xss_clean');
		$this->form_validation->set_rules('website_color', 'Website Color', 'trim|min_length[7]|max_length[7]|xss_clean');
		$this->form_validation->set_rules('seo_keywords', 'SEO Keywords', 'trim|max_length[255]|xss_clean');
        $this->form_validation->set_rules('category', 'Business Category', 'trim|max_length[2]|xss_clean|numeric');


		$background_select = '';
		if($this->form_validation->run() == true) {
			extract($_POST);
			
			
			foreach($_POST as $key => $val) {
				if($key == 'unique_name') {
					$val = str_replace('-', ' ', $val);
					$val = preg_replace('/[^\da-z ]/i', '', $val);
					$val = str_replace(' ', '-', $val);
				}
				if($key == 'phone') {
					$val = preg_replace("/[^0-9]/","",$val);
				}
				if($key != 'background_select') {
					$input[$key] = $val;
				}
			}				
		
			//Image Upload
			$this->load->model('special/user_uploads');
			
			if($background_select=="na") {
				if(isset($_FILES)) {
					if(!empty($_FILES['background']['name'])) {
						$background = $this->user_uploads->upload_image($_FILES['background'], 'background');
						if(isset($background['error'])) {
							$error = $background;
						} else {
							$background_select = $background['success']['system_path'];
						}
					}
				}
	
			} elseif($background_select>0) {	
				switch($background_select) {
					case '1':
						$background_select = 'default-1-bkg.jpg';
						break;
					case '2':
						$background_select = 'default-2-bkg.jpg';
						break;
					case '3':
						$background_select = 'default-3-bkg.jpg';
						break;
				}
			}
			$input['background'] = $background_select;
		
			if(isset($_FILES)) {
				if(!empty($_FILES['file']['name'])) {
					$logo = $this->user_uploads->upload_image($_FILES['file'], 'file');
					if(isset($background['error'])) {
						$error = $logo;
					} else {
						$input['image'] = $logo['success']['system_path'];
					}
				
				} 
			}
			
			$input['type'] = 'advertiser';


            $category = $input['category'];
            unset($input['category']);

			$updated = $this->public_page_table->updateUserPage($this->session->userdata('user_id'), 'advertiser', json_decode(json_encode($input), FALSE));

            $categoryChanged = $this->public_page_handler->updateUserCategory($this->session->userdata('user_id'), $category);

            if($categoryChanged > 0) {
                $updated = true;
            }
			if($updated) {
				$this->session->set_flashdata('success', 'Public Page Settings Added Successfully. To view page as seen publicly, click the "View Public Page" button in the upper right side of this page.');
				$fresh = $this->session->userdata('fresh');
				if($fresh) {
					$this->session->set_flashdata('success', 'Public Page Settings Added Successfully');
					redirect('local-partner/my-website');
				}
				redirect('local-partner/my-website');
				exit;
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, maybe you didn\'t change anything Try Again');
				redirect('local-partner/my-website');
				exit;
			}
		}

        $this->load->view('advertisers/user/website', $data);


    }
	
	function delete_public_image()
	{
		$this->output->set_template('json');
		if ($this->input->is_ajax_request()) {
			$this->load->model('advertisers/security_check');
			if($this->security_check->check()) {
				$this->form_validation->set_rules('id', 'id', 'required|min_length[1]|max_length[20]|numeric|xss_clean');
				if ($this->form_validation->run() == FALSE) {
					$feed = array('error'=>validation_errors());
				} else {
					extract($_POST);
					$this->load->model('modules/public_page_table');
					$deleted = $this->public_page_table->removeImageFromPage('advertiser', $this->session->userdata('user_id'), 'background');
					if($deleted) {
						$feed = array('success'=>'Image has been deleted, you can now add a new image');
					} else {
						$feed = array('error'=>'Image Not Found, Try Again');
					}
				}
				echo json_encode($feed);	
			}
		}
	}		
	
	public function addPage() 
	{
		$this->form_validation->set_rules('pagename', 'Page Name', 'trim|max_length[40]|xss_clean|required');
		if($this->form_validation->run() == true) {
			extract($_POST);
			$this->load->model('modules/website_pages');
			if($this->website_pages->addPage($this->session->userdata('user_id'), 'advertiser', $pagename)) {
				$this->session->set_flashdata('success', 'Page added successfully');
			} else {
				$this->session->set_flashdata('error', 'Page failed to add');
			}
		} else {
			$this->session->set_flashdata('error', validation_errors('<span>', '</span>'));
		}
		redirect('local-partner/my-website');
		exit;
	}
	
	public function edit() 
	{		
		$this->load->css('assets/themes/default/js/summernote/summernote.css');
		$this->load->css('assets/themes/default/css/contractors/bootstrap-tagsinput.css');
		$this->load->js('assets/themes/default/js/contractors/bootstrap-tagsinput.min.js'); 
		$this->load->css('assets/themes/default/js/summernote/summernote-bs3.css');
		$this->load->js('assets/themes/default/js/summernote/summernote.min.js');
		$this->load->js('assets/themes/default/js/assoc/wysiwyg.js');
		$this->load->js('assets/themes/default/js/notify.min.js'); 
		
		$id = $this->uri->segment(4);
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->load->model('contractors/edit_page');
			$this->edit_page->update($id);
			redirect('local-partner/my-website/edit/'.$id);
			exit;
		}
	
		
		if(!empty($id)) {
			$this->load->model('modules/website_pages');
			$data['details'] = $this->website_pages->getUserPageById($this->session->userdata('user_id'), 'advertiser', $id);
			if(!empty($data['details'])) {
				
				$this->load->view('advertisers/user/edit-page', $data);
			} else {
				$this->session->set_flashdata('error', 'No page found, try again');
				redirect('local-partner/my-website');
				exit;
			}
		} else {
			$this->session->set_flashdata('error', 'No page found, try again');
			redirect('local-partner/my-website');
			exit;
		}
	}
	
	public function deletepage()
	{
		$id = (int)$this->uri->segment(4);
		if(!empty($id)) {
			$this->load->model('modules/website_pages');
			if($this->website_pages->deletePage($this->session->userdata('user_id'), 'advertiser', $id)) {
				$this->session->set_flashdata('success', 'Page deleted successfully');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, try again');
			}
		} else {
			$this->session->set_flashdata('error', 'Invalid page selection, try again');	
		}
		redirect('local-partner/my-website');
		exit;
	}
	
	
	
}