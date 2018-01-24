<?php

namespace application\controllers\admin;

class Blocked_visitors extends Admin {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_model('admin/blocked_visitors');

	}
	
	public function index() {
		
		$this->view->set('success', $this->session->get('success'));
		$this->view->set('site_title', get_text('blocked_visitors'));
		$this->view->set('blocked_visitors', $this->blocked_visitors->get_blocked_visitors($this->options->get_option('records_per_page'), $this->options->get_option('date_format'), $this->options->get_option('time_format')));
		$this->view->display('admin/blocked_visitors');
		
		$this->session->delete('success');

	}
	
	public function page($page) {
		
		$this->view->set('success', $this->session->get('success'));
		$this->view->set('site_title', get_text('blocked_visitors'));
		$this->view->set('blocked_visitors', $this->blocked_visitors->get_blocked_visitors($this->options->get_option('records_per_page'), $this->options->get_option('date_format'), $this->options->get_option('time_format'), $page));
		$this->view->display('admin/blocked_visitors');

	}
	
	public function add($ip_address = null) {
		
		if (isset($_POST['submit'])) {

			$this->validator->ip($_POST['ip_address'], sprintf(get_text('validator_ip'), get_text('ip_address')));
			$this->validator->required($_POST['description'], sprintf(get_text('validator_required'), get_text('description')));
			
			if ($this->blocked_visitors->check_ip_address($_POST['ip_address'])) {
				
				$this->validator->set_error(get_text('error_ip_address_used'));

			}
			
			if (!$this->validator->has_errors()) {
				
				$this->blocked_visitors->add($_POST['ip_address'], $_POST['description']);
				
				$this->session->set('success', true);
				
				header('Location: ' . $this->options->get_option('absolute_url') . 'admin/blocked_visitors');
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}
		
		$this->view->set('ip_address', $ip_address);
		$this->view->set('site_title', get_text('blocked_visitors'));
		$this->view->display('admin/add_ip_address');
		
	}
	
	public function delete() {
		
		if (isset($_POST['cb']) && is_array($_POST['cb'])) {
			
			$this->blocked_visitors->delete($_POST['cb']);
			
		}
		
		header('Location: ' . $this->options->get_option('absolute_url') . 'admin/blocked_visitors');
		
	}
	
}

?>
