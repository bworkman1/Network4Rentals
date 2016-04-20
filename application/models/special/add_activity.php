<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
				
	class Add_activity extends CI_Model {
		
		// Call the Model constructor
		function ads_output()
		{		
			parent::__construct();
		}
		
		/*
			REQUIRED TO HAVE 
				-ACTION = STING (DESCRIBES THE NEW ACTIVITY)
				-USER ID = INT (SELF EXPLAINITORY)
				-TYPE = STRING (LANDLORD TENANT CONTRACTOR ADVERTISER)
			
			OPTIONAL
				-ACTION_ID = INT (ALLOWS FOR A LINK THE ACTION 'EXAMPLE LINK TO PROPERTY THAT WAS UPDATED')
				-GROUP_ID = INT (THE ID OF THE admin_groups ROW THE ACTION BELONGS TO)
		*/
		
		
		function add_new_activity($action, $user_id, $type, $action_id=null, $group_id=null, $link = null)
		{
			$ip = $_SERVER['REMOTE_ADDR'];
			$created = date('Y-m-d H-i-s');
			
			$this->db->insert('activity', array('action'=>ucwords($action), 'created'=>$created, 'user_id'=>$user_id, 'type'=>$type, 'ip'=>$ip, 'action_id'=>$action_id, 'group_id'=>$group_id, 'external_link'=>$link));
			if($this->db->insert_id()>0) {
				return true;
			} else {
				return false;
			}
		}
		
		
	}