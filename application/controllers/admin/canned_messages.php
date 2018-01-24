<?php

namespace application\controllers\admin;

class Canned_messages extends Admin {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_model('admin/canned_messages');

	}
	
	public function index() {

		if (isset($_POST['canned_message']) && !empty($_POST['canned_message'])) {

			$this->canned_messages->add($_POST['canned_message']);
			
			$this->session->set('success', true);
			
		}
		
		if ($this->session->get('success')) {
			
			$this->view->set('success', $this->session->get('success'));
			
		}
		
		$this->view->set('site_title', get_text('canned_messages'));
		$this->view->set('canned_messages', $this->canned_messages->get_messages($this->options->get_option('records_per_page')));
		$this->view->display('admin/canned_messages');
		
		$this->session->delete('success');
		
	}

	public function page($page) {

		if (isset($_POST['canned_message']) && !empty($_POST['canned_message'])) {

			$this->canned_messages->add($_POST['canned_message']);
			
			$this->session->set('success', true);
			
		}
		
		if ($this->session->get('success')) {
			
			$this->view->set('success', $this->session->get('success'));
			
		}
		
		$this->view->set('site_title', get_text('canned_messages'));
		$this->view->set('canned_messages', $this->canned_messages->get_messages($this->options->get_option('records_per_page'), $page));
		$this->view->display('admin/canned_messages');
		
		$this->session->delete('success');

	}
	
	public function edit($message_id) {
		
		if (isset($_POST['canned_message']) && !empty($_POST['canned_message'])) {

			if ($this->canned_messages->update($_POST['message_id'], $_POST['canned_message'])) {
				
				$this->session->set('success', true);
				
			} else {
				
				$this->session->set('success', false);
				
			}
			
			header('Location: ' . $this->options->get_option('absolute_url') . 'admin/canned_messages');
			
		}
		
		$this->view->set('site_title', get_text('canned_messages'));
		$this->view->set('canned_message', $this->canned_messages->get_message($message_id));
		$this->view->set('canned_messages', $this->canned_messages->get_messages($this->options->get_option('records_per_page')));
		$this->view->display('admin/edit_canned_message');
		
	}
	
	public function delete() {
		
		if (isset($_POST['cb']) && is_array($_POST['cb'])) {
			
			$this->canned_messages->delete($_POST['cb']);

		}
		
		header('Location: ' . $this->options->get_option('absolute_url') . 'admin/canned_messages');
		
	}
	
}

?>
