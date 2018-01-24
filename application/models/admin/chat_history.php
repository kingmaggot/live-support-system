<?php

namespace application\models\admin;

class Chat_history extends \system\Model {

	public function __construct() {
		
		parent::__construct();
	
	}

	public function get_chats($records_per_page, $date_format, $filter = null, $page = null) {

		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats') > 0) {
			
			$filter = sanitize($filter, 'string');
			$current_page = (!empty($page)) ? sanitize($page, 'integer') : 1;
			$start = ($current_page - 1) * $records_per_page;
			
			$chats = array();
			
			$sql = 'SELECT chat_id, username, department_name, ip_address, time_start, time_end FROM ' . config_item('database', 'table_prefix') . 'chats WHERE time_end != 0';
			
			$sql .= (!empty($filter)) ? ' AND LCASE(email) LIKE "%' . strtolower($filter) . '%"' : '';
			
			$sql .= ' ORDER BY chat_id DESC';
			
			$sql .= (empty($filter)) ? ' LIMIT ' . $start . ', ' . $records_per_page : '';
			
			foreach ($this->db->select($sql) as $row) {

				$chats[] = array(
					'chat_id'			=> $row['chat_id'],
					'username'			=> $row['username'],
					'ip_address'		=> $row['ip_address'],
					'department_name'	=> $row['department_name'],
					'elapsed_time'		=> elapsed_time($row['time_end'], $row['time_start']),
					'date'				=> date($date_format, $row['time_start'])
				);

			}

			$data = array(
				'success'		=> true,
				'data'			=> $chats,
				'current_page'	=> $current_page,
				'pages'			=> (!empty($filter)) ? 0 : ceil($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats') / $records_per_page)
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function get_chat($chat_id, $date_format, $time_format) {

		$parameters = array(
			'chat_id' => sanitize($chat_id, 'integer')
		);

		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters) > 0) {

			$chat = array();

			foreach ($this->db->select('SELECT m.message, m.time, m.operator_name, m.operator_email, c.username, c.referer, c.user_agent, c.ip_address, c.email, c.department_name, c.time_start, c.time_end FROM ' . config_item('database', 'table_prefix') . 'messages m JOIN ' . config_item('database', 'table_prefix') . 'chats c ON m.chat_id = c.chat_id WHERE m.chat_id = :chat_id ORDER BY m.message_id ASC', $parameters) as $row) {

				$name = (empty($row['operator_name'])) ? $row['username'] : $row['operator_name'];
				
				$chat[] = array(
					'message'			=> $row['message'],
					'operator_name'		=> $row['operator_name'],
					'operator_email'	=> $row['operator_email'],
					'name'				=> $name,
					'date'				=> date($date_format, $row['time']),
					'time'				=> date($time_format, $row['time'])
				);
				
				$referer = parse_url($row['referer']);
				
				$visitor_details = array(
					'email'				=> $row['email'],
					'department_name'	=> $row['department_name'],
					'platform'			=> platform($row['user_agent']),
					'browser'			=> browser($row['user_agent']),
					'referer_url'		=> $row['referer'],
					'referer_from'		=> $referer['scheme'] . '://' .  $referer['host'],
					'ip_address'		=> $row['ip_address'],
					'elapsed_time'		=> elapsed_time($row['time_end'], $row['time_start'])
				);
				
			}

			$data = array(
				'success'			=> true,
				'data'				=> $chat,
				'visitor_details'	=> $visitor_details
			);

		} else {
			
			$data = array(
				'success' => false
			);
			
		}
		
		return $data;
		
	}

	public function delete($data) {
		
		foreach ($data as $value) {
			
			$parameters = array(
				'chat_id' => sanitize($value, 'integer')
			);

			$this->db->delete(config_item('database', 'table_prefix') . 'chats', 'chat_id = :chat_id', $parameters);
			$this->db->delete(config_item('database', 'table_prefix') . 'messages', 'chat_id = :chat_id', $parameters);
			
		}
		
	}
	
}

?>
