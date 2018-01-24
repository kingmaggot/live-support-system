<?php

namespace application\models\admin;

class Departments extends \system\Model {

	public function __construct() {
		
		parent::__construct();
	
	}

	public function get_departments($records_per_page, $page = null) {

		if ($this->db->count('SELECT department_id FROM ' . config_item('database', 'table_prefix') . 'departments') > 0) {

			$current_page = (!empty($page)) ? sanitize($page, 'integer') : 1;
			$start = ($current_page - 1) * $records_per_page;
			
			$departments = array();

			foreach ($this->db->select('SELECT department_id, department_name, department_email FROM ' . config_item('database', 'table_prefix') . 'departments ORDER BY department_id DESC LIMIT ' . $start . ', ' . $records_per_page) as $row) {

				$departments[] = array(
					'department_id'		=> $row['department_id'],
					'department_name'	=> $row['department_name'],
					'department_email'	=> $row['department_email']
				);

			}

			$data = array(
				'success'		=> true,
				'data'			=> $departments,
				'current_page'	=> $current_page,
				'pages'			=> ceil($this->db->count('SELECT department_id FROM ' . config_item('database', 'table_prefix') . 'departments') / $records_per_page)
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function get_department($department_id) {
		
		$parameters = array(
			'department_id' => sanitize($department_id, 'integer')
		);

		if ($this->db->count('SELECT department_id FROM ' . config_item('database', 'table_prefix') . 'departments WHERE department_id = :department_id', $parameters) > 0) {
			
			$department = array();

			foreach ($this->db->select('SELECT department_id, department_name, department_email FROM ' . config_item('database', 'table_prefix') . 'departments WHERE department_id = :department_id', $parameters) as $row) {

				$department[] = array(
					'department_id'		=> $row['department_id'],
					'department_name'	=> $row['department_name'],
					'department_email'	=> $row['department_email']
				);

			}

			$data = array(
				'success'	=> true,
				'data'		=> $department
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function check_department($department_id) {
		
		$parameters = array(
			'department_id'	=> sanitize($department_id, 'integer')
		);

		if ($this->db->count('SELECT department_id FROM ' . config_item('database', 'table_prefix') . 'department_operators WHERE department_id = :department_id', $parameters) > 0) {
			
			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'departments', 'department_name', 'department_id = :department_id', $parameters);
			
			$data = array(
				'success'			=> true,
				'department_name'	=> $result['department_name']
			);
			
		} else {
			
			$data = array(
				'success' => false
			);
			
		}
		
		return $data;
		
	}

	public function add($department_name, $department_email) {
		
		$data = array(
			'department_name' 	=> sanitize($department_name, 'string'),
			'department_email' 	=> sanitize($department_email, 'email')
		);

		$this->db->insert(config_item('database', 'table_prefix') . 'departments', $data);
		
	}
	
	public function update($department_id, $department_name, $department_email) {

		$parameters = array(
			'department_id'	=> sanitize($department_id, 'integer')
		);

		if ($this->db->count('SELECT department_id FROM ' . config_item('database', 'table_prefix') . 'departments WHERE department_id = :department_id', $parameters) > 0) {

			$parameters = array(
				'department_name'	=> sanitize($department_name, 'string'),
				'department_email'	=> sanitize($department_email, 'email'),
				'department_id'		=> sanitize($department_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'departments', 'department_name = :department_name, department_email = :department_email', 'department_id = :department_id', $parameters);
			
			return true;
			
		} else {
			
			return false;
			
		}
	
	}

	public function delete($data) {
		
		foreach ($data as $value) {
			
			$parameters = array(
				'department_id' => sanitize($value, 'integer')
			);

			$this->db->delete(config_item('database', 'table_prefix') . 'departments', 'department_id = :department_id', $parameters);
			
		}
		
	}
	
}

?>
