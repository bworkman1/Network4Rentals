<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_common extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function update_last_viewed($type)
	{
		$ts = date('Y-m-d H:i:s');
		$this->db->where('id', $this->session->userdata('user_id'));
		$this->db->update($type, array('last_viewed' => $ts));
	}

	public function pagination($uriSegment, $totalRows, $baseUrl, $perPage = 20)
	{
		$this->load->library('pagination');

		$config['base_url'] = $baseUrl;
		$config['total_rows'] = $totalRows;
		$config['per_page'] = $perPage;
		$config['uri_segment'] = $uriSegment; //Uri num that determines the page the user is on usually 4 maybe 5

		$config['full_tag_open'] = '<div><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div><!--pagination-->';
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&larr; Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active text-warning"><a href="">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		return $this->pagination->create_links();
	}

	/*
	 * $headings accepts a simple array that will be the head for the table ;) Make sure the data matches the table
	 * data flow and size.
	 *
	 */
	public function createTableData($headings, $data, $classes)
	{
		$this->load->library('table');

		$this->table->set_heading($headings);

		$classesString = implode(",", $classes);
		$tmpl = array (
			'table_open' => '<div class="table-responsive"><table class="table '.$classesString.'"
				border="0" cellpadding="4" cellspacing="0" style="white-space: nowrap">',

			'heading_row_start'   => '<tr>',
			'heading_row_end'     => '</tr>',
			'heading_cell_start'  => '<th>',
			'heading_cell_end'    => '</th>',

			'row_start'           => '<tr>',
			'row_end'             => '</tr>',
			'cell_start'          => '<td>',
			'cell_end'            => '</td>',

			'row_alt_start'       => '<tr>',
			'row_alt_end'         => '</tr>',
			'cell_alt_start'      => '<td>',
			'cell_alt_end'        => '</td>',

			'table_close'         => '</table></div>'
		);

		$this->table->set_template($tmpl);

		return $this->table->generate($data);
	}

}