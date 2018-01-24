<?php

namespace application\models\admin;

class Blocked_visitors extends \system\Model {

	public function __construct() {
		
		parent::__construct();
		
	}

	public function get_blocked_visitors($records_per_page, $date_format, $time_format, $page = null) {

		if ($this->db->count('SELECT blocked_visitor_id FROM ' . config_item('database', 'table_prefix') . 'blocked_visitors') > 0) {

			$current_page = (!empty($page)) ? sanitize($page, 'integer') : 1;
			$start = ($current_page - 1) * $records_per_page;

			$blocked_visitors = array();
			
			foreach ($this->db->select('SELECT * FROM ' . config_item('database', 'table_prefix') . 'blocked_visitors ORDER BY blocked_visitor_id DESC LIMIT ' . $start . ', ' . $records_per_page) as $row) {

				$blocked_visitors[] = array(
					'blocked_visitor_id'	=> $row['blocked_visitor_id'],
					'ip_address'			=> $row['ip_address'],
					'description'			=> $row['description'],
					'time'					=> ($row['time']) ? date($date_format . ' ' . $time_format, $row['time']) : '--'
				);

			}

			$data = array(
				'success'		=> true,
				'data'			=> $blocked_visitors,
				'current_page'	=> $current_page,
				'pages'			=> ceil($this->db->count('SELECT blocked_visitor_id FROM ' . config_item('database', 'table_prefix') . 'blocked_visitors') / $records_per_page)
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}
	
	public function check_ip_address($ip_address) {
		
		$parameters = array(
			'ip_address' => sanitize($ip_address, 'string')
		);

		if ($this->db->count('SELECT ip_address FROM ' . config_item('database', 'table_prefix') . 'blocked_visitors WHERE ip_address = :ip_address', $parameters) > 0) {
			
			return true;

		} else {

			return false;
			
		}
		
	}
	
	public function add($ip_address, $description) {
		
		$data = array(
			'ip_address'	=> sanitize($ip_address, 'string'),
			'description'	=> sanitize($description, 'string'),
			'time' 			=> time()
		);

		$this->db->insert(config_item('database', 'table_prefix') . 'blocked_visitors', $data);
		
	}

	public function delete($data) {
		
		foreach ($data as $value) {

			$parameters = array(
				'blocked_visitor_id' => sanitize($value, 'integer')
			);

			$this->db->delete(config_item('database', 'table_prefix') . 'blocked_visitors', 'blocked_visitor_id = :blocked_visitor_id', $parameters);
			
		}
		
	}
	
}

?>
