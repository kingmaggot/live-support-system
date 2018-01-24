<?php

namespace application\controllers\admin;

class Logout extends \system\Controller {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_library('authentication');
		
	}
	
	public function index() {
		
		$this->authentication->logout();
		
		header('Location: login');
		
	}
	
}

?>
