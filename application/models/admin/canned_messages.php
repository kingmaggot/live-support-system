<?php

namespace application\models\admin;

class Canned_messages extends \system\Model {

	public function __construct() {
		
		parent::__construct();
	
	}

	public function get_messages($records_per_page, $page = null) {

		if ($this->db->count('SELECT message_id FROM ' . config_item('database', 'table_prefix') . 'canned_messages') > 0) {

			$current_page = (!empty($page)) ? sanitize($page, 'integer') : 1;
			$start = ($current_page - 1) * $records_per_page;
			
			$canned_messages = array();

			foreach ($this->db->select('SELECT message_id, canned_message FROM ' . config_item('database', 'table_prefix') . 'canned_messages ORDER BY message_id DESC  LIMIT ' . $start . ', ' . $records_per_page) as $row) {

				$canned_messages[] = array(
					'message_id'		=> $row['message_id'],
					'canned_message'	=> $row['canned_message']
				);

			}

			$data = array(
				'success'		=> true,
				'data'			=> $canned_messages,
				'current_page'	=> $current_page,
				'pages'			=> ceil($this->db->count('SELECT message_id FROM ' . config_item('database', 'table_prefix') . 'canned_messages') / $records_per_page)
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function get_message($message_id) {
		
		$parameters = array(
			'message_id' => sanitize($message_id, 'integer')
		);

		if ($this->db->count('SELECT message_id FROM ' . config_item('database', 'table_prefix') . 'canned_messages WHERE message_id = :message_id', $parameters) > 0) {
			
			$canned_message = array();

			foreach ($this->db->select('SELECT message_id, canned_message FROM ' . config_item('database', 'table_prefix') . 'canned_messages WHERE message_id = :message_id', $parameters) as $row) {

				$canned_message[] = array(
					'message_id'		=> $row['message_id'],
					'canned_message'	=> $row['canned_message']
				);

			}

			$data = array(
				'success'	=> true,
				'data'		=> $canned_message
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}
	
	public function add($canned_message) {
		
		$data = array(
			'canned_message' => sanitize($canned_message, 'string')
		);

		$this->db->insert(config_item('database', 'table_prefix') . 'canned_messages', $data);
		
	}
	
	public function update($message_id, $canned_message) {

		$parameters = array(
			'message_id' => sanitize($message_id, 'integer')
		);

		if ($this->db->count('SELECT message_id FROM ' . config_item('database', 'table_prefix') . 'canned_messages WHERE message_id = :message_id', $parameters) > 0) {

			$parameters = array(
				'canned_message'	=> sanitize($canned_message, 'string'),
				'message_id'		=> sanitize($message_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'canned_messages', 'canned_message = :canned_message', 'message_id = :message_id', $parameters);
			
			return true;
			
		} else {
			
			return false;
			
		}
	
	}

	public function delete($data) {
		
		foreach ($data as $value) {
			
			$parameters = array(
				'message_id' => sanitize($value, 'integer')
			);

			$this->db->delete(config_item('database', 'table_prefix') . 'canned_messages', 'message_id = :message_id', $parameters);
			
		}
		
	}
	
}

?>
