<?php

namespace application\controllers\admin;

class Index extends Admin {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_library('chat');

		if (!$this->session->get('operator_id')) {

			$this->session->set('operator_id', $this->chat->operator_get_id($this->session->get('user_id')));
			$this->session->set('full_name', $this->chat->operator_get_full_name($this->session->get('operator_id')));
			$this->session->set('user_email', $this->chat->operator_get_email($this->session->get('operator_id')));
			
			$this->view->set('full_name', $this->session->get('full_name'));
			
		}

	}
	
	public function index() {

		$this->view->set('site_title', get_text('chat_panel'));
		$this->view->set('canned_messages', $this->chat->get_canned_messages());
		$this->view->display('admin/index');

	}
	
	public function update_status() {
		
		$this->chat->operator_update_status($this->session->get('operator_id'), $this->options->get_option('operator_timeout'));
		
	}
	
	public function get_online_departments() {
		
		$data = $this->chat->get_online_departments($this->options->get_option('operator_timeout'));
		$departments = ($data['success']) ? $data['departments'] : get_text('departments_not_found');
		
		$this->view->set('online_departments', $departments);

		echo json_encode($this->view->display('admin/online_departments', true));
		
	}
	
	public function typing() {

		echo json_encode($this->chat->operator_typing($this->session->get('operator_id'), $_POST['typing']));
		
	}
	
	public function send_message() {

		echo json_encode($this->chat->operator_send_message($this->session->get('operator_id'), $_POST['message']));
		
	}
	
	public function transfer_chat() {

		echo json_encode($this->chat->operator_transfer_chat($this->session->get('operator_id'), $_POST['department_id'], get_text('message_chat_transferred')));
		
	}
	
	public function start_chat() {

		echo json_encode($this->chat->operator_start_chat($this->session->get('operator_id'), $_POST['chat_id'], get_text('message_operator_joined')));
		
	}
	
	public function stop_chat() {

		echo json_encode($this->chat->operator_stop_chat($this->session->get('operator_id'), get_text('message_operator_exit')));

	}
	
	public function watch_chat() {

		echo json_encode($this->chat->operator_watch_chat($this->session->get('operator_id'), $_POST['chat_id']));

	}
	
	public function send_invitation() {
		
		echo json_encode($this->chat->operator_send_invitation($_POST['ip_address'], $_POST['invitation_message']));
		
	}
	
	public function update() {

		$timeout = time() + 120;
		
		set_time_limit(120);
		
		if (!$this->session->get('operator_key')) {
			
			$key = uniqid();
			
			$this->chat->operator_update($this->session->get('operator_id'), $this->options->get_option('chat_timeout'), $key);
			$this->session->set('operator_key', $key);
			
		} else {

			if (!$this->chat->operator_update($this->session->get('operator_id'), $this->options->get_option('chat_timeout'))) {
				
				$key = uniqid();
				
				$this->chat->operator_update($this->session->get('operator_id'), $this->options->get_option('chat_timeout'), $key);
				$this->session->set('operator_key', $key);
				
			} else {
				
				$key = $this->session->get('operator_key');
			
			}
			
		}

		session_write_close();

		while(time() < $timeout) {

			if ($key == $this->chat->operator_update($this->session->get('operator_id'), $this->options->get_option('chat_timeout'))) {
				
				$data = array(
					'pending_chats'		=> $this->chat->operator_get_pending_chats($this->session->get('operator_id'), $this->options->get_option('chat_timeout')),
					'chat_messages'		=> $this->chat->operator_get_chat_messages($this->session->get('operator_id'), $this->options->get_option('chat_timeout'), $this->options->get_option('date_format'), $this->options->get_option('time_format')),
					'online_visitors'	=> $this->chat->get_online_visitors(),
					'operator_status'	=> $this->chat->operator_get_status($this->session->get('operator_id'), $this->options->get_option('operator_timeout'))
				);

				$output = array();
				
				$operator_status = (empty($_POST['operator_status'])) ? null : filter_var($_POST['operator_status'], FILTER_VALIDATE_BOOLEAN);
				
				if ($data['pending_chats']['last_activity'] > $_POST['pending_chats_last_activity'] || $data['chat_messages']['last_activity'] > $_POST['chat_last_activity'] || $data['chat_messages']['chat_expired'] || $data['online_visitors']['last_activity'] != $_POST['visitors_last_activity'] || $data['operator_status']['success'] !== $operator_status) {

					$this->view->set('pending_chats', $data['pending_chats']);
					$this->view->set('online_visitors', $data['online_visitors']);
					$this->view->set('chat_messages', $data['chat_messages']);
					$this->view->set('ip_tracker_url', $this->options->get_option('ip_tracker_url'));

					$output = array(
						'pending_chats' => array(
							'success'		=> $data['pending_chats']['success'],
							'chat'			=> $this->view->display('admin/pending_chats', true),
							'last_activity'	=> $data['pending_chats']['last_activity'],
							'new_chat'		=> $data['pending_chats']['new_chat']
						),
						'chat_messages' => array(
							'success'			=> $data['chat_messages']['success'],
							'messages'			=> $this->view->display('admin/chat_messages', true),
							'visitor_details'	=> $this->view->display('admin/visitor_details', true),
							'user_typing'		=> $data['chat_messages']['user_typing'],
							'new_message'		=> $data['chat_messages']['new_message'],
							'last_activity'		=> $data['chat_messages']['last_activity'],
							'chat_expired'		=> $data['chat_messages']['chat_expired'],
							'chat_active'		=> $data['chat_messages']['chat_active']
						),
						'online_visitors' => array(
							'success'		=> $data['online_visitors']['success'],
							'visitors'		=> $this->view->display('admin/online_visitors', true),
							'last_activity'	=> $data['online_visitors']['last_activity']
						),
						'operator_status' => array(
							'success' => $data['operator_status']['success']
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
