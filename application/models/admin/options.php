<?php

namespace application\models\admin;

class Options extends \system\Model {

	public function __construct() {
		
		parent::__construct();
		
	}

	public function get_option($option_name) {
		
		$parameters = array(
			'option_name' => sanitize($option_name, 'string')
		);
		
		$result = $this->db->get_field(config_item('database', 'table_prefix') . 'options', 'option_value', 'option_name = :option_name', $parameters);

		return $result['option_value'];

	}
	
}

?>
