<?php

namespace application\models\admin;

class Access_logs extends \system\Model {

	public function __construct() {
		
		parent::__construct();
		
	}

	public function get_access_logs($records_per_page, $date_format, $time_format, $page = null) {

		if ($this->db->count('SELECT log_id FROM ' . config_item('database', 'table_prefix') . 'access_logs') > 0) {

			$current_page = (!empty($page)) ? sanitize($page, 'integer') : 1;
			$start = ($current_page - 1) * $records_per_page;

			$access_logs = array();
			
			foreach ($this->db->select('SELECT al.ip_address, al.time, u.user_email, CONCAT_WS(" ", up.first_name, up.last_name) AS full_name FROM ' . config_item('database', 'table_prefix') . 'access_logs al JOIN ' . config_item('database', 'table_prefix') . 'users u ON al.user_id = u.user_id JOIN ' . config_item('database', 'table_prefix') . 'user_profiles up ON al.user_id = up.user_id ORDER BY al.log_id DESC LIMIT ' . $start . ', ' . $records_per_page) as $row) {

				$access_logs[] = array(
					'full_name'		=> $row['full_name'],
					'user_email'	=> $row['user_email'],
					'ip_address'	=> $row['ip_address'],
					'time'			=> ($row['time']) ? date($date_format . ' ' . $time_format, $row['time']) : '--'
				);

			}

			$data = array(
				'success'		=> true,
				'data'			=> $access_logs,
				'current_page'	=> $current_page,
				'pages'			=> ceil($this->db->count('SELECT log_id FROM ' . config_item('database', 'table_prefix') . 'access_logs') / $records_per_page)
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}
	
}

?>
