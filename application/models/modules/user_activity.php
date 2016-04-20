<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_activity extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	

	public function recent_user_activity($type) {
		$this->db->limit(5);
		$this->db->order_by('id', 'desc');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('type', $type);
		$query = $this->db->get('activity');
		return $this->formatRecentActivity($query->result(), $type);
	}
		
	private function formatRecentActivity($data, $type)
	{
		if(!empty($data)) {
			$out = '<div class="list-group">';
			foreach($data as $row) {
				$out .= '<a href="'.base_url($type.'/').'" class="list-group-item"><i class="fa fa-wrench text-info circle-icon"></i> Submitted Service Request';
				$out .= '<span class="time">'.$this->formatShortDate($row->created).'</span>';
				$out .='</a>';
			}
			$out .= '</div>';
		} else {
			$out = '<div class="notice blue">
                      <p>Sorry we have no recent activity from you</p>
                    </div>';
		}
		return $out;
	}
	
	private function formatShortDate($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	public function allRecentActivity($type)
	{
		$offset = 0;
		$this->db->limit(30, $offset);
		$query = $this->db->get_where('activity', array('user_id'=>$this->session->userdata('user_id'), 'type'=>$type));
		return $query->result();
	}
	
}
