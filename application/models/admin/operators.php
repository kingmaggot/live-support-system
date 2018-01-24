<?php

namespace application\models\admin;

class Operators extends \system\Model {

	public function __construct() {
		
		parent::__construct();
		
	}

	public function get_operators($records_per_page, $date_format, $time_format, $page = null) {

		if ($this->db->count('SELECT operator_id FROM ' . config_item('database', 'table_prefix') . 'operators') > 0) {

			$current_page = (!empty($page)) ? sanitize($page, 'integer') : 1;
			$start = ($current_page - 1) * $records_per_page;

			$operators = array();
			
			foreach ($this->db->select('SELECT *, CONCAT_WS(" ", first_name, last_name) AS full_name FROM ' . config_item('database', 'table_prefix') . 'operators ORDER BY operator_id DESC LIMIT ' . $start . ', ' . $records_per_page) as $row) {

				$operators[] = array(
					'operator_id'	=> $row['operator_id'],
					'full_name'		=> $row['full_name'],
					'last_activity'	=> ($row['last_activity']) ? date($date_format . ' ' . $time_format, $row['last_activity']) : '--'
				);

			}

			$data = array(
				'success'		=> true,
				'data'			=> $operators,
				'current_page'	=> $current_page,
				'pages'			=> ceil($this->db->count('SELECT operator_id FROM ' . config_item('database', 'table_prefix') . 'operators') / $records_per_page)
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function get_operator($operator_id) {
		
		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		if ($this->db->count('SELECT operator_id FROM ' . config_item('database', 'table_prefix') . 'operators WHERE operator_id = :operator_id', $parameters) > 0) {
			
			$groups = array();
			$departments = array();
			$department_operators = array();
			$operator = array();
			
			foreach ($this->db->select('SELECT group_id, group_name FROM ' . config_item('database', 'table_prefix') . 'user_groups') as $row) {
				
				$groups[] = array(
					'group_id'		=> $row['group_id'],
					'group_name'	=> $row['group_name']
				);
				
			}
			
			foreach ($this->db->select('SELECT department_id, department_name FROM ' . config_item('database', 'table_prefix') . 'departments') as $row) {
				
				$departments[] = array(
					'department_id'		=> $row['department_id'],
					'department_name'	=> $row['department_name']
				);
				
			}

			$parameters = array(
				'operator_id' => sanitize($operator_id, 'integer')
			);
		
			foreach ($this->db->select('SELECT department_id FROM ' . config_item('database', 'table_prefix') . 'department_operators WHERE operator_id = :operator_id', $parameters) as $row) {
				
				array_push($department_operators, $row['department_id']);
				
			}

			$parameters = array(
				'operator_id' => sanitize($operator_id, 'integer')
			);

			foreach ($this->db->select('SELECT u.user_id, u.user_email, u.user_status, u.group_id, o.operator_id, o.first_name, o.last_name, o.hide_online FROM ' . config_item('database', 'table_prefix') . 'operators o JOIN ' . config_item('database', 'table_prefix') . 'users u ON o.user_id = u.user_id WHERE o.operator_id = :operator_id', $parameters) as $row) {

				$operator[] = array(
					'user_id'				=> $row['user_id'],
					'user_email'			=> $row['user_email'],
					'user_status'			=> $row['user_status'],
					'group_id'				=> $row['group_id'],
					'operator_id'			=> $row['operator_id'],
					'first_name'			=> $row['first_name'],
					'last_name'				=> $row['last_name'],
					'hide_online'			=> $row['hide_online'],
					'groups'				=> $groups,
					'departments'			=> $departments,
					'department_operators'	=> $department_operators
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
				'success' 		=> true,
				'user_email'	=> $result[0]['user_email']
			);
			
		} else {
			
			$data = array(
				'success' => false
			);
			
		}
		
		return $data;
		
	}
	
	public function get_user_id($operator_id) {
		
		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		if ($this->db->count('SELECT u.user_id FROM ' . config_item('database', 'table_prefix') . 'users u JOIN ' . config_item('database', 'table_prefix') . 'operators o ON u.user_id = o.user_id WHERE o.operator_id = :operator_id', $parameters) > 0) {
			
			$result = $this->db->select('SELECT u.user_id FROM ' . config_item('database', 'table_prefix') . 'users u JOIN ' . config_item('database', 'table_prefix') . 'operators o ON u.user_id = o.user_id WHERE o.operator_id = :operator_id', $parameters);
			
			$data = array(
				'success' => true,
				'user_id' => $result[0]['user_id']
			);
			
		} else {
			
			$data = array(
				'success' => false
			);
			
		}
		
		return $data;
		
	}
	
	public function get_groups() {

		if ($this->db->count('SELECT group_id FROM ' . config_item('database', 'table_prefix') . 'user_groups') > 0) {
			
			$groups = array();

			foreach ($this->db->select('SELECT * FROM ' . config_item('database', 'table_prefix') . 'user_groups') as $row) {

				$groups[] = array(
					'group_id'		=> $row['group_id'],
					'group_name'	=> $row['group_name']
				);

			}

			$data = array(
				'success'	=> true,
				'data'		=> $groups
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function get_departments() {

		if ($this->db->count('SELECT department_id FROM ' . config_item('database', 'table_prefix') . 'departments') > 0) {
			
			$departments = array();

			foreach ($this->db->select('SELECT * FROM ' . config_item('database', 'table_prefix') . 'departments') as $row) {

				$departments[] = array(
					'department_id'		=> $row['department_id'],
					'department_name'	=> $row['department_name']
				);

			}

			$data = array(
				'success'	=> true,
				'data'		=> $departments
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}
	
	public function insert($user_email, $first_name, $last_name, $hide_online, $departments) {

		$parameters = array(
			'user_email' => sanitize($user_email, 'email')
		);

		$result = $this->db->get_field(config_item('database', 'table_prefix') . 'users', 'user_id', 'user_email = :user_email', $parameters);
		
		$data = array(
			'user_id'		=> $result['user_id'],
			'first_name'	=> sanitize($first_name, 'string'),
			'last_name'		=> sanitize($last_name, 'string'),
			'hide_online' 	=> sanitize($hide_online, 'integer')
		);

		$this->db->insert(config_item('database', 'table_prefix') . 'operators', $data);
		
		$operator_id = $this->db->last_insert_id();
		
		if (is_array($departments)) {
			
			foreach ($departments as $value) {
				
				$data = array(
					'department_id'	=> sanitize($value, 'integer'),
					'operator_id'	=> $operator_id
				);

				$this->db->insert(config_item('database', 'table_prefix') . 'department_operators', $data);
				
			}
			
		}
		
	}
	
	public function update($operator_id, $first_name, $last_name, $hide_online, $departments) {

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

			if (is_array($departments)) {

				$parameters = array(
					'operator_id' => sanitize($operator_id, 'integer')
				);
				
				$this->db->delete(config_item('database', 'table_prefix') . 'department_operators', 'operator_id = :operator_id', $parameters);
				
				foreach ($departments as $value) {
					
					$data = array(
						'department_id'	=> sanitize($value, 'integer'),
						'operator_id'	=> sanitize($operator_id, 'integer')
					);

					$this->db->insert(config_item('database', 'table_prefix') . 'department_operators', $data);
					
				}
				
			}
			
			return true;
			
		} else {
			
			return false;
			
		}
	
	}

	public function delete($operator_id) {

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		$this->db->delete(config_item('database', 'table_prefix') . 'operators', 'operator_id = :operator_id', $parameters);
		$this->db->delete(config_item('database', 'table_prefix') . 'department_operators', 'operator_id = :operator_id', $parameters);
		
	}
	
}

?>
