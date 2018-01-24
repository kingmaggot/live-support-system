<?php

namespace libraries;

use \libraries\Session;

class Validator {

	private $errors = array();
	
	private $session;

	public function __construct() {
		
		$this->session = new Session();

		if (!$this->session->get('errors')) {
			
			$this->session->set('errors', array());
			
		}
		
	}

	public function set_error($message) {
		
		$this->errors[] = $message;
		
		$this->session->set('errors', $this->errors);

	}
	
	public function display_errors() {

		return $this->session->get('errors');
		
	}

	public function has_errors() {
		
		return (count($this->session->get('errors')) > 0) ? true : false;
		
	}

	public function clear_errors() {

		$this->session->delete('errors');
		$this->session->set('errors', array());
		
	}

	public function required($value, $message) {
	
		if (empty($value)) {
			
			$this->set_error($message);
			
		}
		
	}

	public function email($value, $message) {
	
		if (empty($value)) {
			
			$this->set_error($message);
			
		} elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			
			$this->set_error($message);
			
		}
		
	}

	public function integer($value, $message) {
	
		if (empty($value)) {
			
			$this->set_error($message);
			
		} elseif (!filter_var($value, FILTER_VALIDATE_INT)) {
			
			$this->set_error($message);
			
		}
		
	}

	public function float($value, $message) {
	
		if (empty($value)) {
			
			$this->set_error($message);
			
		} elseif (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
			
			$this->set_error($message);
			
		}
		
	}

	public function url($value, $message) {
	
		if (empty($value)) {
			
			$this->set_error($message);
			
		} elseif (!filter_var($value, FILTER_VALIDATE_URL)) {
			
			$this->set_error($message);
			
		}
		
	}

	public function ip($value, $message) {
	
		if (empty($value)) {
			
			$this->set_error($message);
			
		} elseif (!filter_var($value, FILTER_VALIDATE_IP)) {
			
			$this->set_error($message);
			
		}
		
	}

	public function matches($value_1, $value_2, $message) {
	
		if (empty($value_1) || empty($value_2)) {
			
			$this->set_error($message);
			
		} elseif (!strcmp($value_1, $value_2) == 0) {
			
			$this->set_error($message);
			
		}
		
	}
	
}

?>
