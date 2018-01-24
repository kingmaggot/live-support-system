<?php

namespace application\controllers\admin;

class Chat_history extends Admin {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load_model('admin/chat_history');
		
	}
	
	public function index() {

		$filter = (isset($_POST['filter'])) ? $_POST['filter'] : null;
		
		$this->view->set('site_title', get_text('chat_history'));
		$this->view->set('chats', $this->chat_history->get_chats($this->options->get_option('records_per_page'), $this->options->get_option('date_format'), $filter));
		$this->view->display('admin/chat_history');

	}
	
	public function page($page) {
		
		$filter = (isset($_POST['filter'])) ? $_POST['filter'] : null;

		$this->view->set('site_title', get_text('chat_history'));
		$this->view->set('chats', $this->chat_history->get_chats($this->options->get_option('records_per_page'), $this->options->get_option('date_format'), $filter, $page));
		$this->view->display('admin/chat_history');

	}
	
	public function view_chat($chat_id) {
		
		$this->view->set('site_title', get_text('chat_history'));
		$this->view->set('ip_tracker_url', $this->options->get_option('ip_tracker_url'));
		$this->view->set('chat', $this->chat_history->get_chat($chat_id, $this->options->get_option('date_format'), $this->options->get_option('time_format')));
		$this->view->display('admin/view_chat');

	}
	
	public function delete() {
		
		if (isset($_POST['cb']) && is_array($_POST['cb'])) {
			
			$this->chat_history->delete($_POST['cb']);

		}
		
		header('Location: ' . $this->options->get_option('absolute_url') . 'admin/chat_history');
		
	}
	
}

?>
