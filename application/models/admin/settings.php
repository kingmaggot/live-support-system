<?php

namespace application\models\admin;

class Settings extends \system\Model {

	public function __construct() {
		
		parent::__construct();
		
	}

	public function save($data) {
		
		foreach ($data as $key => $value) {
			
			if ($key == 'charset') {
				
				$value = strtoupper($value);
				
			}
			
			$parameters = array(
				'option_value'	=> sanitize($value, 'string'),
				'option_name'	=> sanitize($key, 'string')
			);
			
			$this->db->update(config_item('database', 'table_prefix') . 'options', 'option_value = :option_value', 'option_name = :option_name', $parameters);
		
		}
		
	}
	
}

?>
