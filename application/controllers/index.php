<?php

namespace application\controllers;

class Index extends \system\Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load_model('options');

		if (isset($_SERVER['HTTP_ORIGIN'])) {
			
			preg_match('/[a-z0-9\-]{1,63}\.[a-z\.]{2,6}$/', parse_url($this->options->get_option('absolute_url'), PHP_URL_HOST), $matches);
			
			if (preg_match('/' . $matches[0] . '/', parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST))) {
				
				header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
				header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
				header('Access-Control-Allow-Credentials: true');
				header('Access-Control-Max-Age: 86400');
				header('Access-Control-Allow-Headers: X-Requested-With');

				if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

					exit;
					
				}
			
			}

		}
		
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

			date_default_timezone_set($this->options->get_option('timezone'));
			
			$this->load_library('chat');
			$this->load_library('session');

			$this->chat->online_visitors();
			
			if (!$this->session->get('chat_hash')) {

				$this->session->set('chat_hash', uniqid());
				
			}
			
			$this->view->set('absolute_url', $this->options->get_option('absolute_url'));
			$this->view->set('background_color_1', $this->options->get_option('background_color_1'));
			$this->view->set('background_color_2', $this->options->get_option('background_color_2'));
			$this->view->set('background_color_3', $this->options->get_option('background_color_3'));
			$this->view->set('color_1', $this->options->get_option('color_1'));
			$this->view->set('color_2', $this->options->get_option('color_2'));
			$this->view->set('color_3', $this->options->get_option('color_3'));
			$this->view->set('chat_position', $this->options->get_option('chat_position'));
			
		} else {
			
			header('Location: http://' . $_SERVER['HTTP_HOST']);
			
			exit;
			
		}
	
	}
	
	public function index() {

		$this->view->display('live_chat');

	}

	public function get_token() {
		
		$data['token'] = generate_token('form');
		
		echo json_encode($data);
		
	}

	public function contact_operators() {

		$data = $this->chat->visitor_contact_operators($this->session->get('chat_hash'), $this->options->get_option('operator_timeout'), $this->options->get_option('max_visitors'));
		
		if ($data['success']) {

			$data['content'] = $this->view->display('chat', true);
			
		} else {

			$this->view->set('departments', $data['departments']);
			
			$data['content'] = ($data['online']) ?  $this->view->display('online', true) : $this->view->display('offline', true);
			
		}
		
		echo json_encode($data);
		
	}
	
	public function start_chat() {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			if (!isset($_POST['token']) || !check_token('form', $_POST['token'])) {
				
				exit;
				
			} else {
				
				$data = $this->chat->visitor_start_chat($_POST['department_id'], $_POST['username'], $_POST['email'], $_POST['message'], $this->session->get('chat_hash'), $this->options->get_option('operator_timeout'), $this->options->get_option('max_visitors'), $this->options->get_option('max_connections'));

				if ($data['success']) {

					$data['content'] = $this->view->display('chat', true);
					
				} else {
					
					$this->view->set('departments', $data['departments']);
					
					$data['content'] = $this->view->display('offline', true);
					
				}

				echo json_encode($data);
				
			}
			
		}

	}

	public function send_message() {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			if (!isset($_POST['token']) || !check_token('form', $_POST['token'])) {
				
				exit;
				
			} else {		
				
				echo json_encode($this->chat->visitor_send_message($this->session->get('chat_hash'), $_POST['message']));
		
			}
			
		}
		
	}
	
	public function send_email() {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			if (!isset($_POST['token']) || !check_token('form', $_POST['token'])) {
				
				exit;
				
			} else {
				
				$data = $this->chat->visitor_send_email($_POST['department_id'], $_POST['username'], $_POST['email'], get_text('question_from'), $_POST['message'], get_text('message_visitor_send_email'), $this->options->get_option('charset'));
				
				if ($data['success']) {

					$data['message'] = get_text('message_email_success');
					
				} else {
					
					$data['message'] = get_text('message_email_error');
					
				}
				
				echo json_encode($data);
		
			}
			
		}
		
	}
	
	public function stop_chat() {

		echo json_encode($this->chat->visitor_stop_chat($this->session->get('chat_hash'), get_text('message_visitor_exit')));
		
		$this->session->delete('chat_hash');
		
	}
	
	public function typing() {
		
		echo json_encode($this->chat->visitor_typing($this->session->get('chat_hash'), $_POST['typing']));
		
	}

	public function update() {
		
		$timeout = time() + 120;
		
		set_time_limit(120);
		
		if (!$this->session->get('visitor_key')) {
			
			$key = uniqid();
			
			$this->chat->visitor_update($this->session->get('chat_hash'), $this->options->get_option('chat_timeout'), $key);
			$this->session->set('visitor_key', $key);
			
		} else {

			if (!$this->chat->visitor_update($this->session->get('chat_hash'), $this->options->get_option('chat_timeout'))) {
				
				$key = uniqid();
				
				$this->chat->visitor_update($this->session->get('chat_hash'), $this->options->get_option('chat_timeout'), $key);
				$this->session->set('visitor_key', $key);
				
			} else {
				
				$key = $this->session->get('visitor_key');
			
			}
			
		}

		session_write_close();
		
		while(time() < $timeout) {

			if ($key == $this->chat->visitor_update($this->session->get('chat_hash'), $this->options->get_option('chat_timeout'))) {
				
				$data = array(
					'chat_messages'			=> $this->chat->visitor_get_chat_messages($this->session->get('chat_hash'), $this->options->get_option('chat_timeout'), $this->options->get_option('date_format'), $this->options->get_option('time_format')),
					'chat_status'			=> $this->chat->visitor_get_chat_status($this->session->get('chat_hash'), $this->options->get_option('operator_timeout'), $this->options->get_option('max_visitors')),
					'invitation_message'	=> $this->chat->visitor_get_invitation_message()
				);
				
				$output = array();

				$chat_status = (empty($_POST['chat_status'])) ? null : filter_var($_POST['chat_status'], FILTER_VALIDATE_BOOLEAN);
				
				if ($data['chat_messages']['last_activity'] > $_POST['last_activity'] || $data['chat_messages']['chat_expired'] || $data['chat_status']['success'] !== $chat_status || !is_null($data['invitation_message']['message'])) {
					
					$this->view->set('chat_messages', $data['chat_messages']);

					$output = array(
						'chat_messages' => array(
							'success'			=> $data['chat_messages']['success'],
							'messages'			=> $this->view->display('chat_messages', true),
							'operator_typing'	=> $data['chat_messages']['operator_typing'],
							'new_message'		=> $data['chat_messages']['new_message'],
							'queue'				=> $data['chat_messages']['queue'],
							'last_activity'		=> $data['chat_messages']['last_activity'],
							'chat_expired'		=> $data['chat_messages']['chat_expired']
						),
						'chat_status' => array(
							'success' => $data['chat_status']['success'],
							'message' => ($data['chat_status']['success']) ? (isset($data['chat_status']['department_name'])) ? get_text('message_visitor_chat') . $data['chat_status']['department_name'] : get_text('message_status_online') : get_text('message_status_offline')
						),
						'invitation_message' => array(
							'success' => $data['invitation_message']['success'],
							'message' => $data['invitation_message']['message']
						)
					);

					echo json_encode($output);
					
					break;
					
				} else {

					sleep(1);
					
				}

			} else {
				
				break;
				
			}
			
		}
		
	}
	
}

?>
