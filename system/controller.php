<?php

namespace system;

class Controller {
	
	//The view
	protected $view;

	//Constructor
	protected function __construct() {

		$this->view = new \system\View();

	}
	
	//Load the model
	protected function load_model($model, $object_name = null) {

		$model = APPLICATION_FOLDER . '\models\\' . ucfirst(str_replace('/', '\\', $model));
		
		if (is_null($object_name)) {
			
			$object_name = strtolower(trim(substr($model, strrpos($model, '\\')), '\\'));

		}
		
		$this->$object_name = new $model();

	}

	//Load the library
	protected function load_library($library, $object_name = null) {
		
		$library = '\libraries\\' . ucfirst($library);

		if (is_null($object_name)) {
			
			$object_name = strtolower(trim(substr($library, strrpos($library, '\\')), '\\'));
			
		}
		
		$this->$object_name = new $library();
		
	}
	
}

?>
