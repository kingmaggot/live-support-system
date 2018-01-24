<?php

namespace application\controllers\admin;

class Login extends \system\Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load_model('admin/options');
		
		$this->load_library('authentication');
		$this->load_library('session');
		$this->load_library('validator');

		if ($this->authentication->logged_in()) {
			
			header('Location: ' . $this->options->get_option('absolute_url') . 'admin/index');
			
			exit;
			
		}

		$absolute_url = parse_url($this->options->get_option('absolute_url'));
		
		if ($absolute_url['host'] != $_SERVER['HTTP_HOST']) {
			
			header('Location: ' . $this->options->get_option('absolute_url') . 'admin');
			
			exit;
			
		}
		
	}
	
	public function index() {
		
		if (isset($_POST['submit'])) {
			
			$this->validator->email($_POST['user_email'], sprintf(get_text('validator_email'), get_text('email_address')));
			$this->validator->required($_POST['user_password'], sprintf(get_text('validator_required'), get_text('password')));
			
			if (!$this->validator->has_errors()) {
				
				$remember = (isset($_POST['remember'])) ? true : false;
				
				if ($this->authentication->login($_POST['user_email'], $_POST['user_password'], $this->options->get_option('user_timeout'), $remember, $this->options->get_option('access_logs'))) {
					
					header('Location: ' . $this->options->get_option('absolute_url') . 'admin/index');

				} else {
					
					$this->view->set('success', false);
					
				}
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}
		
		$this->view->set('absolute_url', $this->options->get_option('absolute_url'));
		$this->view->set('site_title', get_text('login'));
		$this->view->display('admin/login');
		
	}
	
	public function reset_password() {
		
		if (isset($_POST['submit'])) {
			
			$this->validator->email($_POST['user_email'], sprintf(get_text('validator_email'), get_text('email_address')));
			
			if (!$this->validator->has_errors()) {
				
				if ($this->authentication->new_password($_POST['user_email'], array($this->options->get_option('admin_email'), $this->options->get_option('site_title')), get_text('new_password'), get_text('message_new_password'), $this->options->get_option('charset'))) {
					
					$this->view->set('success', true);
					
				} else {
					
					$this->view->set('success', false);
					
				}
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}
		
		$this->view->set('absolute_url', $this->options->get_option('absolute_url'));
		$this->view->set('site_title', get_text('reset_password'));
		$this->view->display('admin/reset_password');

	}
	
}

?>
