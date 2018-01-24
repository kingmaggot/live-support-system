<?php

namespace application\controllers\admin;

class Admin extends \system\Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load_model('admin/user_permissions');
		$this->load_model('admin/options');
		
		date_default_timezone_set($this->options->get_option('timezone'));

		$this->load_library('authentication');
		$this->load_library('session');
		$this->load_library('validator');
		
		$this->view->set('absolute_url', $this->options->get_option('absolute_url'));
		$this->view->set('full_name', $this->session->get('full_name'));
		
		if (!$this->authentication->logged_in()) {
			
			header('Location: ' . $this->options->get_option('absolute_url') . 'admin/login');
			
			exit;
			
		}

		$pages = array(
			'index',
			'chat_history',
			'canned_messages',
			'departments',
			'operators',
			'groups',
			'blocked_visitors',
			'access_logs',
			'settings',
			'profile'
		);
		
		$current_page = explode('/', trim(str_replace(rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/admin', '', $_SERVER['REQUEST_URI']), '/'));
		
		$this->view->set('current_page', $current_page[0]);

		if (!$this->user_permissions->check_permissions($this->session->get('user_id'), $current_page[0], $pages)) {

			$this->view->set('site_title', get_text('oops'));
			$this->view->display('admin/permission_denied');
			
			exit;
			
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && $current_page[0] != 'index') {

			if (!isset($_POST['token']) || !check_token('form', $_POST['token'])) {
				
				$this->view->set('site_title', get_text('oops'));
				$this->view->set('message', get_text('error_token'));
				$this->view->display('admin/error');
				
				exit;
				
			}
			
		}
		
	}
	
}

?>
