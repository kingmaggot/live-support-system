<?php

namespace application\models\admin;

class User_permissions extends \system\Model {

	public function __construct() {
		
		parent::__construct();
		
	}

	public function check_permissions($user_id, $current_page, $pages) {

		$parameters = array(
			'user_id' => sanitize($user_id, 'integer')
		);
		
		$result = $this->db->select('SELECT group_name FROM ' . config_item('database', 'table_prefix') . 'users u JOIN ' . config_item('database', 'table_prefix') . 'user_groups ug ON u.group_id = ug.group_id WHERE u.user_id = :user_id', $parameters);

		$parameters = array(
			'group_name' => $result[0]['group_name']
		);

		foreach ($pages as $value) {
			
			if ($value == $current_page) {
				
				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'user_groups', 'group_permissions', 'group_name = :group_name', $parameters);
				
				$permissions = unserialize($result['group_permissions']);
				
				if (!in_array($value, $permissions)) {

					return false;
					
				} else {
					
					return true;
					
				}
				
			}
			
		}
		
	}
	
}

?>
