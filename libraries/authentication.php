<?php

namespace libraries;

use \system\Database;
use \libraries\Session;

class Authentication {
	
	private $db;
	
	private $session;

	public function __construct() {
		
		$this->db = Database::connection();
		$this->session = new Session();
		
		$this->auto_login();
		
	}
	
	public function insert($user_email, $user_password, $user_status, $group_id, $additional_data = null) {
		
		$data = array(
			'user_email' 	=> sanitize($user_email, 'email'),
			'user_password' => $this->hash_password(sanitize($user_password, 'string')),
			'user_created' 	=> time(),
			'user_status'	=> sanitize($user_status, 'integer'),
			'user_approved'	=> 1,
			'group_id'		=> sanitize($group_id, 'integer')
		);
		
		$this->db->insert(config_item('database', 'table_prefix') . 'users', $data);
		
		$user_id = $this->db->last_insert_id();
		
		if (is_array($additional_data)) {
			
			$data = array();
			
			foreach ($additional_data as $key => $value) {
				
				$data[$key] = sanitize($value, 'string');
				
			}
			
			$this->db->insert(config_item('database', 'table_prefix') . 'user_profiles', $data);
			
			$parameters = array(
				'user_id' 		=> $user_id,
				'profile_id' 	=> $this->db->last_insert_id()
			);
			
			$this->db->update(config_item('database', 'table_prefix') . 'user_profiles', 'user_id = :user_id', 'profile_id = :profile_id', $parameters);
			
		}
		
	}
	
	public function update($user_id, $user_email, $user_password = null, $user_status = null, $group_id = null, $additional_data = null) {

		$parameters = array(
			'user_email'	=> sanitize($user_email, 'email'),
			'user_id'		=> sanitize($user_id, 'integer')
		);
		
		$fields[] = 'user_email = :user_email';

		if (!empty($user_password)) {

			$parameters['user_password'] = $this->hash_password(sanitize($user_password, 'string'));
			
			$fields[] = 'user_password = :user_password';
			
		}

		if (!empty($user_status) || $user_status === '0') {

			$parameters['user_status'] = sanitize($user_status, 'integer');
			
			$fields[] = 'user_status = :user_status';
			
		}

		if (!empty($group_id)) {

			$parameters['group_id'] = sanitize($group_id, 'integer');
			
			$fields[] = 'group_id = :group_id';
			
		}

		$this->db->update(config_item('database', 'table_prefix') . 'users', implode(', ', $fields), 'user_id = :user_id', $parameters);

		if (is_array($additional_data)) {

			$fields = array();

			$parameters = array(
				'user_id' => sanitize($user_id, 'integer')
			);

			foreach ($additional_data as $key => $value) {
				
				$parameters[$key] = sanitize($value, 'string');

				$fields[] = $key . ' = :' . $key;
				
			}

			$this->db->update(config_item('database', 'table_prefix') . 'user_profiles', implode(', ', $fields), 'user_id = :user_id', $parameters);
			
		}

	}
	
	public function delete($user_id) {

		$parameters = array(
			'user_id' => sanitize($user_id, 'integer')
		);

		$this->db->delete(config_item('database', 'table_prefix') . 'users', 'user_id = :user_id', $parameters);
		$this->db->delete(config_item('database', 'table_prefix') . 'user_profiles', 'user_id = :user_id', $parameters);
		
	}
	
	public function token() {
		
		if (!$this->session->get('secret_key')) {
			
			$this->session->set('secret_key', sha1(uniqid(true) . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));
		
		}

		return $this->session->get('secret_key');
		
	}
	
	public function login($user_email, $user_password, $user_timeout, $remember = false, $access_logs = false) {

		$parameters = array(
			'user_email' => sanitize($user_email, 'email')
		);

		$result = $this->db->get_field(config_item('database', 'table_prefix') . 'users', 'user_password', 'user_email = :user_email AND user_status = 1 AND user_approved = 1', $parameters);

		if ($this->check_password(sanitize($user_password, 'string'), $result['user_password'])) {
			
			session_regenerate_id(true);
			
			$this->session->set('token', $this->token());
			$this->session->set('logged_in', true);

			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'users', 'user_id', 'user_email = :user_email', $parameters);

			$this->session->set('user_id', $result['user_id']);

			$parameters = array(
				'last_login'	=> time(),
				'last_ip'		=> $_SERVER['REMOTE_ADDR'],
				'user_id'		=> $result['user_id']
			);
			
			$this->db->update(config_item('database', 'table_prefix') . 'users', 'last_login = :last_login, last_ip = :last_ip', 'user_id = :user_id', $parameters);
			
			if ($remember) {
				
				$this->remember_user($result['user_id'], $user_timeout);
				
			}

			if ($access_logs) {
				
				$data = array(
					'user_id'		=> $result['user_id'],
					'ip_address'	=> $_SERVER['REMOTE_ADDR'],
					'time'			=> time()
				);

				$this->db->insert(config_item('database', 'table_prefix') . 'access_logs', $data);
				
			}
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	public function remember_user($user_id, $user_timeout) {
		
		$cookie_value = sha1(uniqid(true));
		
		$parameters = array(
			'remember_code'	=> $cookie_value,
			'user_id'		=> sanitize($user_id, 'integer')
		);

		$this->db->update(config_item('database', 'table_prefix') . 'users', 'remember_code = :remember_code', 'user_id = :user_id', $parameters);

		setcookie('remember_code', $cookie_value, time() + $user_timeout);
		
	}
	
	public function logged_in() {
		
		if ($this->session->get('logged_in') && $this->session->get('token') == $this->token()) {
			
			return true;
		}
		
		return false;
		
	}
	
	public function auto_login() {
		
		if (!$this->logged_in()) {
			
			if (isset($_COOKIE['remember_code'])) {

				$parameters = array(
					'remember_code'	=> $_COOKIE['remember_code']
				);
				
				if ($this->db->count('SELECT user_id FROM ' . config_item('database', 'table_prefix') . 'users WHERE remember_code = :remember_code AND user_status = 1 AND user_approved = 1', $parameters) > 0) {
					
					session_regenerate_id(true);
					
					$this->session->set('token', $this->token());
					$this->session->set('logged_in', true);
					
					$result = $this->db->get_field(config_item('database', 'table_prefix') . 'users', 'user_id', 'remember_code = :remember_code', $parameters);
					
					$this->session->set('user_id', $result['user_id']);
					
				}

			}
			
		}
		
		return false;
		
	}
	
	public function logout() {

		$parameters = array(
			'remember_code' => null,
			'user_id' 		=> $this->session->get('user_id')
		);
		
		$this->db->update(config_item('database', 'table_prefix') . 'users', 'remember_code = :remember_code', 'user_id = :user_id', $parameters);

		$this->session->destroy();
		unset($_COOKIE['remember_code']);
		setcookie('remember_code', '', time() - 1);
		
	}
	
	public function new_password($user_email, $from, $subject, $message, $charset) {
		
		$parameters = array(
			'user_email' => sanitize($user_email, 'email')
		);
		
		if ($this->db->count('SELECT user_email FROM ' . config_item('database', 'table_prefix') . 'users WHERE user_email = :user_email', $parameters) > 0) {
			
			$password = substr(md5(uniqid(rand())), 0, 8);
			
			$parameters = array(
				'user_password'	=> $this->hash_password($password),
				'remember_code' => null,
				'user_email'	=> sanitize($user_email, 'email')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'users', 'user_password = :user_password, remember_code = :remember_code', 'user_email = :user_email', $parameters);

			$headers  = (is_array($from)) ? 'From: ' . $from[1] . '<' . $from[0] . '>' . "\r\n" : 'From: ' . $from . "\r\n";
			$headers .=	'Reply-To: ' . $from[0] . "\r\n";
			$headers .=	'X-Mailer: PHP/' . phpversion() . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=' . $charset . "\r\n";
			
			$message = '<html><head></head><body> ' . sprintf($message, $password) . '</body></html>';

			if (mail($user_email, $subject, html_entity_decode($message, ENT_QUOTES), $headers)) {
				
				return true;
				
			} else {
				
				return false;
				
			}
			
		} else {
			
			return false;
			
		}
		
	}
	
	public function check_email($user_email) {

		$parameters = array(
			'user_email' => sanitize($user_email, 'email')
		);
		
		if ($this->db->count('SELECT user_email FROM ' . config_item('database', 'table_prefix') . 'users WHERE user_email = :user_email', $parameters) > 0) {
			
			return false;
			
		} else {
			
			return true;
			
		}

	}

	function hash_password($password) {
		
		if (function_exists('openssl_random_pseudo_bytes')) {
			
			$salt = openssl_random_pseudo_bytes(16);
			
		} else {
			
			$salt = str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 5));
			
		}

		$salt = substr(str_replace('+', '.', base64_encode($salt)), 0, 22);

		return crypt($password, '$2y$12$' . $salt);
		
	}

	function check_password($password, $hash) {
		
		return crypt($password, $hash) === $hash;
		
	}
	
}

?>
