<?php

namespace application\models\admin;

class Profile extends \system\Model {

	public function __construct() {
		
		parent::__construct();
		
	}

	public function get_operator($operator_id) {
		
		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		if ($this->db->count('SELECT operator_id FROM ' . config_item('database', 'table_prefix') . 'operators WHERE operator_id = :operator_id', $parameters) > 0) {

			$operator = array();

			foreach ($this->db->select('SELECT u.user_id, u.user_email, u.user_status, o.first_name, o.last_name, o.hide_online FROM ' . config_item('database', 'table_prefix') . 'operators o JOIN ' . config_item('database', 'table_prefix') . 'users u ON o.user_id = u.user_id WHERE o.operator_id = :operator_id', $parameters) as $row) {

				$operator[] = array(
					'user_id'		=> $row['user_id'],
					'first_name'	=> $row['first_name'],
					'last_name'		=> $row['last_name'],
					'user_email'	=> $row['user_email'],
					'hide_online'	=> $row['hide_online']
				);

			}

			$data = array(
				'success'	=> true,
				'data'		=> $operator
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function get_operator_email($operator_id) {
		
		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		if ($this->db->count('SELECT u.user_email FROM ' . config_item('database', 'table_prefix') . 'users u JOIN ' . config_item('database', 'table_prefix') . 'operators o ON u.user_id = o.user_id WHERE o.operator_id = :operator_id', $parameters) > 0) {
			
			$result = $this->db->select('SELECT u.user_email FROM ' . config_item('database', 'table_prefix') . 'users u JOIN ' . config_item('database', 'table_prefix') . 'operators o ON u.user_id = o.user_id WHERE o.operator_id = :operator_id', $parameters);
			
			$data = array(
				'success'		=> true,
				'user_email' 	=> $result[0]['user_email']
			);
			
		} else {
			
			$data = array(
				'success' => false
			);
			
		}
		
		return $data;
		
	}

	public function update($operator_id, $first_name, $last_name, $hide_online) {

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		if ($this->db->count('SELECT operator_id FROM ' . config_item('database', 'table_prefix') . 'operators WHERE operator_id = :operator_id', $parameters) > 0) {

			$parameters = array(
				'first_name'	=> sanitize($first_name, 'string'),
				'last_name'		=> sanitize($last_name, 'string'),
				'hide_online'	=> sanitize($hide_online, 'integer'),
				'operator_id'	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operators', 'first_name = :first_name, last_name = :last_name, hide_online = :hide_online', 'operator_id = :operator_id', $parameters);
			
			return true;
			
		} else {
			
			return false;
			
		}
	
	}

}

?>
