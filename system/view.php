<?php

namespace system;

class View {
	
	//Template path
	private $tpl_path;
	
	//Values
	private $values = array();
	
	//Constructor
	public function __construct() {

		$this->tpl_path = APPLICATION_FOLDER . '/views/';

	}
	
	//Set a template variable
	public function set($name, $value = null) {
		
		if (is_array($name)) {
		
			foreach ($name as $key => $value) {
			
				$this->values[$key] = $value;
			
			}
		
		} else {
			
			$this->values[$name] = $value;
			
		}
		
    }
	
	//Display the template file
	public function display($template, $return = false) {

		if ($this->values) {
			
			extract($this->values);
			
		}

		if (file_exists($this->tpl_path . $template . '.tpl')) {

			ob_start();

			include($this->tpl_path . $template . '.tpl');

			$output = ob_get_contents();

			ob_end_clean();
			
		} else {

			error('Template file ' . $this->tpl_path . $template . '.tpl not found.');
			
		}

		if ($return) {
			
			return $output;
			
		} else {
			
			echo $output;
			
		}
		
	}
	
}

?>
