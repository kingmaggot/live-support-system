<?php

namespace libraries;

class Session {

  	public function __construct() {	
		
		if (!session_id()) {
			
			session_start();
			
		}
		
	}

	public function set($name, $value) {
	
		$_SESSION[$name] = $value;
	
	}

	public function get($name) {
		
		return (isset($_SESSION[$name])) ? $_SESSION[$name] : false;
		
	}

	public function delete($name) {
	
		unset($_SESSION[$name]);
	
	}

	public function destroy() {
		
		$_SESSION = array();
		session_destroy();
		
	}
	
}

?>
