<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
				
	class User_activity_page extends CI_Model {
		
		private $word;
		private $type;
		private $baseUrl;
		private $perPage = 25;
		private $actionArray;
		private $datesFrom;
		private $datesTo;
		
		function __contructor()
		{		
			parent::__construct();
		}
		
		public function activity($type, $offset) 
		{
			// Set dates
			$this->datesFrom = $this->session->userdata('date_to');
			$this->datesTo = $this->session->userdata('date_from');
			
			$this->type = $type; 
			$this->db->limit($this->perPage, $offset);
			$this->db->order_by('id', 'desc');
			
			//check if user is narrowing down by date
			if(!empty($this->datesFrom) || !empty($this->datesTo)) {
				$this->db->where('created <=', date('Y-m-d', strtotime($this->datesFrom)));
				$this->db->where('created >=', date('Y-m-d', strtotime($this->datesTo)));
			} 
			
			// Get the users activity
			$query = $this->db->get_where('activity', array('type'=>$this->type, 'user_id' => $this->session->userdata('user_id')));
			$activity = $query->result();
			
			$this->setBaseUrl();
			
			// Determins icons and links accroding to user type
			if($this->type ==  'landlords') {
				$this->setLandlordValues();
			} elseif($this->type == 'renters') {
				$this->setRenterValues();
			} elseif($this->type == 'contractor') {
				$this->setContractorValues();
			}
			// generate activity
			$data['activity'] = $this->generateAction($activity);
			
			// create pagination links 
			$data['links'] = $this->paginationActivity();
			
			return $data;
		}
		
		private function generateAction($activity)
		{
			$actionArray = $this->actionArray;
			$data = '';
			
			if($this->type == 'landlords') {
				$btnType = 'btn-primary';
			} elseif($this->type == 'renters') {
				$btnType = 'btn-warning';
			} elseif($this->type == 'contractor') {
				$btnType = 'btn-success';
			}
			
			foreach($activity as $row) {
			
				$found = false;
				
				foreach($actionArray as $key => $val) {
					$icon = '<i class="fa fa-check fa-fw fa-lg"></i> ';
					$link = '';
					if (strpos(strtolower($row->action), strtolower($val['name'])) !== false) {
						$icon = $val['icon'];
						if(!empty($val['link'])) {
							$link = '<a href="'.base_url($val['link'].$row->action_id).'" class="btn '.$btnType.' btn-sm pull-right">View</a>';
						}
						$found = true;
						break;
					}	
				}
				
				$data .= '<div class="line-under">'.$link.'<div class="pull-left activity-icon">'.$icon.'</div> '.$row->action.'<br><div class="activity-time"><i class="fa fa-clock-o"></i> '.date('m-d-Y h:i a', strtotime($row->created) + 60*60).' <small>EST</small></div><div class="clearfix"></div></div>';
			
			}
			
			return $data;
			
		}
		
		private function paginationActivity()
		{
			$this->load->model('modules/user_common');
			return $this->user_common->pagination(3, $this->countAllActivity(), $this->baseUrl, $this->perPage);
		}
		
		private function countAllActivity()
		{
			if(!empty($this->datesFrom) || !empty($this->datesTo)) {
				$this->db->where('created <=', date('Y-m-d', strtotime($this->datesFrom)));
				$this->db->where('created >=', date('Y-m-d', strtotime($this->datesTo)));
			} 
			$this->db->where('type', $this->type);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			
			return $this->db->count_all_results('activity');
		}
		
		private function setBaseUrl()
		{
			if($this->type == 'landlords') {
				$this->baseUrl = base_url('landlords/activity/');
			} elseif($this->type == 'renters') {
				$this->baseUrl = base_url('renters/activity/');
			} elseif($this->type == 'contractor') {
				$this->baseUrl = base_url('contractor/notifications/');
			}
		}
		
		private function setLandlordValues()
		{
			$list_actions = array(
				
				array(
					'name' => 'Rental Payments Processed Today',
					'link' => 'landlords/payment-data/',
					'icon' => '<i class="fa fa-calendar-plus fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Invited Tenant',
					'link' => '',
					'icon' => '<i class="fa fa-user-plus fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Invite Tenant',
					'link' => '',
					'icon' => '<i class="fa fa-user-plus fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Message',
					'link' => 'landlords/message-tenant/',
					'icon' => '<i class="fa fa-comment-o fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Forwarding Email',
					'link' => 'landlords/edit-account/',
					'icon' => '<i class="fa fa-envelope-square fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Service Request',
					'link' => 'landlords/view-service-request/',
					'icon' => '<i class="fa fa-wrench fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Messages',
					'link' => 'landlords/message-tenant/',
					'icon' => '<i class="fa fa-comments-o fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Admin',
					'link' => 'landlords/accounts/',
					'icon' => '<i class="fa fa-user-plus fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Assigned Manager',
					'link' => 'landlords/accounts/',
					'icon' => '<i class="fa fa-user-plus fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Payments',
					'link' => 'landlords/view-tenant-info/',
					'icon' => '<i class="fa fa-money fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Rental Check-list',
					'link' => '',
					'icon' => '<i class="fa fa-check-circle fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Landlord Association',
					'link' => '',
					'icon' => '<i class="fa fa-university fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Public Page',
					'link' => 'landlords/public-page-settings/',
					'icon' => '<i class="fa fa-desktop fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Public Page',
					'link' => 'landlords/public-page-settings/',
					'icon' => '<i class="fa fa-desktop fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Renter Paid',
					'link' => 'landlords/view-tenant-info/',
					'icon' => '<i class="fa fa-money fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Added New Property',
					'link' => 'landlords/edit-listing/',
					'icon' => '<i class="fa fa-home fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Personal Info',
					'link' => 'landlords/edit-account/',
					'icon' => '<i class="fa fa-user fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'New Tenant',
					'link' => 'landlords/view-tenant-info/',
					'icon' => '<i class="fa fa-user-plus fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'note left on payment',
					'link' => 'landlords/view-tenant-info/',
					'icon' => '<i class="fa fa-money fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'One Time Rent Payment',
					'link' => 'landlords/view-tenant-info/',
					'icon' => '<i class="fa fa-money fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'New Rental Listing',
					'link' => 'landlords/edit-listing/',
					'icon' => '<i class="fa fa-home fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Changes On Property',
					'link' => 'landlords/edit-listing/',
					'icon' => '<i class="fa fa-home fa-fw fa-lg"></i>',
				)
				
			);
			
			$this->actionArray = $list_actions;
		}
		
		private function setRenterValues()
		{
			$list_actions = array(
				array(
					'name' => 'Forwarding Email',
					'link' => '',
					'icon' => '<i class="fa fa-envelope-o fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Payment',
					'link' => 'renters/view-payment-history/',
					'icon' => '<i class="fa fa-money fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'ISO',
					'link' => 'renters/in-search-of/',
					'icon' => '<i class="fa fa-search fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Status Update',
					'link' => 'renters/view-request/',
					'icon' => '<i class="fa fa-refresh fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Service Request',
					'link' => 'renters/view-request/',
					'icon' => '<i class="fa fa-wrench fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Message',
					'link' => 'renters/view-messages/',
					'icon' => '<i class="fa fa-comment-o fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Changed Account Details',
					'link' => '',
					'icon' => '<i class="fa fa-user fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Changed Password',
					'link' => '',
					'icon' => '<i class="fa fa-lock fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Rental Check-list',
					'link' => 'renters/view_checklist/',
					'icon' => '<i class="fa fa-check-circle-o fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'New Landlord Added',
					'link' => 'renters/current-landlord/',
					'icon' => '<i class="fa fa-user-plus fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Rent Is Due',
					'link' => 'renters/rent-receipt/',
					'icon' => '<i class="fa fa-calendar-o fa-fw fa-lg"></i>',
				),
			);
			
			$this->actionArray = $list_actions;
		}
		
		private function setContractorValues()
		{
			$list_actions = array(
				array(
					'name' => 'Added a note',
					'link' => 'contractor/view-service-request/',
					'icon' => '<i class="fa fa-pencil fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Recieved Payment From Website',
					'link' => 'contractor/view-payments/',
					'icon' => '<i class="fa fa-dollar fa-fw fa-lg"></i>',
				),
				
				array(
					'name' => 'Profile Page',
					'link' => 'contractor/public-page/',
					'icon' => '<i class="fa fa-laptop fa-fw fa-lg"></i>',
				),
				array(
					'name' => 'Service Request',
					'link' => 'contractor/view-service-request/',
					'icon' => '<i class="fa fa-wrench fa-fw fa-lg"></i>',
				),				
				array(
					'name' => 'Added Zip',
					'link' => '',
					'icon' => '<i class="fa fa-plus fa-fw fa-lg"></i>',
				),
			);
			$this->actionArray = $list_actions;
		}
				/*
			NOT YET BEING USED PLANNED ON USING TO ADD ICONS TO THE ACTIVITY PAGE TO SPICE IT A BIT 01/07/2016
		 
	x	Messages					(A Tenant Viewed Your Messages)
	x	Forwarding Email 			(Added A Forwarding Email)
	x	Service Request				(Added A Service Request, Forwarded Service Request To - {email})
	x	Added Admin           		(Added {email} As Admin)
	x	Assigned Manager			(Assigned Manager To Tenant)
	x	Payments                   	(Cancelled Auto Payments)
	X	Added New Property			(Added New Property)
	x	Added Manager  				(Added jon@shirer2.com As Manager)
	x	Rental Check-list			(Completed Rental Check-list)
	x	Invited Tenant				(Invited Tenant To Join - {phone or email})
	x	Landlord Association		(Verify Membership Landlord Association Membership, You Have Been Deleted From A Landlord Association, You Have Been Invited To Join A Landlord Association)
	x	Public Page					(Updated Public Page Setting)
	x	Renter Paid					(Renter Paid Their Rent, Renter Paid Offline Payment)
		Message 					(Message Sent To Tenant, Message Received From Tenant, 	Landlord Viewed Your Message, A Tenant Viewed Your Messages)
		Added New Property			(Added New Property)
		*/
		
		
	}

	