<?php

namespace system;

class Model {

	//Database connection
	protected $db;
	
	//Constructor
	public function __construct() {
		
		$this->db = Database::connection();
	
	}
	
}

?>
