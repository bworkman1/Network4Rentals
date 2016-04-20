<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_page_table extends CI_Model
{
    private $userId;
    private $type;

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getUserPage($userId, $type)
    {
        $query = $this->db->get_where('landlord_page_settings', array(
            'landlord_id' => $userId,
            'type' => $type,
        ));
        return $query->row();
    }

    public function checkForUniqueName($name, $userId)
    {
        $this->db->where('landlord_id !=', $userId);
        $query = $this->db->get_where('landlord_page_settings', array(
            'unique_name' => $name
        ));
        if($query->num_rows()>0) {
            return true;
        }
        return false;
    }

    public function updateUserPage($userId, $type, $dataObj)
    {
        $this->userId = $userId;
        $this->type = $type;

        $wheres = array(
            'landlord_id' => $userId,
            'type' => $type,
        );

        $analyticsAdded = false;

        if( !empty($dataObj->analytics_id) || !empty($dataObj->account_id) ) {
            $analytics = array(
                'analytics_id' => $dataObj->analytics_id,
                'account_id' => $dataObj->account_id,
            );
            $analyticsAdded = $this->addAnalyticsData($analytics);
        }
        unset($dataObj->analytics_id);
        unset($dataObj->account_id);

        $query = $this->db->get_where('landlord_page_settings', $wheres);
        if($query->num_rows()>0) {
			$this->session->set_userdata('unique_name', $dataObj->unique_name);
            if($analyticsAdded) {
                return true;
            }
            return $this->updatePage($dataObj);
        } else {
            if($analyticsAdded) {
                return true;
            } else {
                return $this->addPage($dataObj);
            }
        }
    }

    private function updatePage($dataObj)
    {
        $this->db->where('landlord_id', $this->userId);
        $this->db->where('type', $this->type);
        $this->db->update('landlord_page_settings', $dataObj);
        if($this->db->affected_rows()>0) {
            return true;
        }
        return false;
    }

    private function addPage($dataObj)
    {
        $dataObj->landlord_id = $this->userId;
        $dataObj->active = 'y';
        $dataObj->type = $this->type;
        $this->db->insert('landlord_page_settings', $dataObj);

        return $this->db->insert_id() ? true : false;

    }

    private function addAnalyticsData($data)
    {
        $this->db->where('id', $this->session->userdata('user_id'));
        $this->db->update('affiliate_users', $data);
        if($this->db->affected_rows()>0) {
            return true;
        }
        return false;
    }
	
	public function removeImageFromPage($userType, $user_id, $imageType) 
	{
		$this->db->where('landlord_id', $user_id);
		$this->db->where('type', $userType);
		if($imageType == 'profile') {
			$update = array('image' => '');
		} else {
			$update = array('background' => '');
		}
		$this->db->limit(1);
		$this->db->update('landlord_page_settings', $update);
		return true;
	}
	
}
