<?php

namespace application\models\admin;

class Groups extends \system\Model {

	public function __construct() {
		
		parent::__construct();
		
	}

	public function get_groups($records_per_page, $page = null) {

		if ($this->db->count('SELECT group_id FROM ' . config_item('database', 'table_prefix') . 'user_groups') > 0) {

			$current_page = (!empty($page)) ? sanitize($page, 'integer') : 1;
			$start = ($current_page - 1) * $records_per_page;

			$groups = array();
			
			foreach ($this->db->select('SELECT * FROM ' . config_item('database', 'table_prefix') . 'user_groups ORDER BY group_id DESC LIMIT ' . $start . ', ' . $records_per_page) as $row) {

				$groups[] = array(
					'group_id'		=> $row['group_id'],
					'group_name'	=> $row['group_name']
				);

			}

			$data = array(
				'success'		=> true,
				'data'			=> $groups,
				'current_page'	=> $current_page,
				'pages'			=> ceil($this->db->count('SELECT group_id FROM ' . config_item('database', 'table_prefix') . 'user_groups') / $records_per_page)
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function get_group($group_id) {
		
		$parameters = array(
			'group_id' => sanitize($group_id, 'integer')
		);

		if ($this->db->count('SELECT group_id FROM ' . config_item('database', 'table_prefix') . 'user_groups WHERE group_id = :group_id', $parameters) > 0) {
			
			$groups = array();

			foreach ($this->db->select('SELECT * FROM ' . config_item('database', 'table_prefix') . 'user_groups WHERE group_id = :group_id', $parameters) as $row) {

				$groups[] = array(
					'group_id'			=> $row['group_id'],
					'group_name'		=> $row['group_name'],
					'group_permissions'	=> unserialize($row['group_permissions'])
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
	
	public function check_group($group_id) {
		
		$parameters = array(
			'group_id'	=> sanitize($group_id, 'integer')
		);

		if ($this->db->count('SELECT group_id FROM ' . config_item('database', 'table_prefix') . 'users WHERE group_id = :group_id', $parameters) > 0) {
			
			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'user_groups', 'group_name', 'group_id = :group_id', $parameters);
			
			$data = array(
				'success'		=> true,
				'group_name'	=> $result['group_name']
			);
			
		} else {
			
			$data = array(
				'success' => false
			);
			
		}
		
		return $data;
		
	}
	
	public function add($group_name, $group_permissions) {
		
		$data = array(
			'group_name'		=> sanitize($group_name, 'string'),
			'group_permissions'	=> serialize($group_permissions)
		);

		$this->db->insert(config_item('database', 'table_prefix') . 'user_groups', $data);
		
	}
	
	public function update($group_id, $group_name, $group_permissions) {

		$parameters = array(
			'group_id'	=> sanitize($group_id, 'integer')
		);

		if ($this->db->count('SELECT group_id FROM ' . config_item('database', 'table_prefix') . 'user_groups WHERE group_id = :group_id', $parameters) > 0) {

			$parameters = array(
				'group_name'		=> sanitize($group_name, 'string'),
				'group_permissions'	=> serialize($group_permissions),
				'group_id'			=> sanitize($group_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'user_groups', 'group_name = :group_name, group_permissions = :group_permissions', 'group_id = :group_id', $parameters);
			
			return true;
			
		} else {
			
			return false;
			
		}
	
	}

	public function delete($data) {
		
		foreach ($data as $value) {

			$parameters = array(
				'group_id' => sanitize($value, 'integer')
			);

			$this->db->delete(config_item('database', 'table_prefix') . 'user_groups', 'group_id = :group_id', $parameters);
			
		}
		
	}
	
}

?>
