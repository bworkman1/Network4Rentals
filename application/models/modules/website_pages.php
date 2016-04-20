<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Website_pages extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function countUserPages($userId, $type) 
	{
		$query = $this->db->get_where('public_pages', array('user_id'=>$userId, 'type' => $type));
		return $query->num_rows();
	}
	
	public function editPage($userId, $type, $data) 
	{
		$data['url_slug'] = url_title($data['name'], '-', TRUE);
		$this->db->where('user_id', $userId);
		$this->db->where('type', $type);
		$this->db->update('public_pages', $data);
		if($this->db->affected_rows()>0) {
			return true;
		}
		return false;
	}
	
	public function addPage($userId, $type, $name) 
	{
		$data = array(
			'user_id' => $userId,
			'name' => $name,
			'type' => $type,
			'url_slug' => url_title($name, '-', TRUE)
		);
		$this->db->insert('public_pages', $data);
		if($this->db->insert_id()>0) {
			return true;
		} 
		return false;
	}
	
	public function getUserPages($userId, $type)
	{
		$query = $this->db->get_where('public_pages', array('user_id'=>$userId, 'type'=>$type));
		return $query->result();
	}
	
	public function deletePage($userId, $type, $pageId) 
	{
		$this->db->where('id', $pageId);
		$this->db->where('user_id', $userId);
		$this->db->where('type', $type);
		$this->db->limit(1);
		$this->db->delete('public_pages');
		if($this->db->affected_rows()>0) {
			return true;
		}
		return false;
	}
	
	public function getUserPageById($userId, $type, $pageId)
	{
		$this->db->limit(1);
		$query = $this->db->get_where('public_pages', array('user_id'=>$userId, 'type'=>$type, 'id'=>$pageId));
		return $query->row();
	}
	
}
