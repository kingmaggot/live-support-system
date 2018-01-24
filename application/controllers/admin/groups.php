<?php

namespace application\controllers\admin;

class Groups extends Admin {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_model('admin/groups');
		
	}
	
	public function index() {
		
		if ($this->session->get('success')) {
			
			$this->view->set('success', $this->session->get('success'));
			
		}
		
		$this->view->set('site_title', get_text('groups'));
		$this->view->set('group_name', $this->session->get('group_name'));
		$this->view->set('groups', $this->groups->get_groups($this->options->get_option('records_per_page')));
		$this->view->set('has_errors', $this->session->get('has_errors'));
		$this->view->display('admin/groups');
		
		$this->session->delete('success');
		$this->session->delete('has_errors');
		$this->session->delete('group_name');

	}
	
	public function page($page) {

		$this->view->set('site_title', get_text('groups'));
		$this->view->set('groups', $this->groups->get_groups($this->options->get_option('records_per_page'), $page));
		$this->view->display('admin/groups');

	}
	
	public function add() {
		
		if (isset($_POST['submit'])) {
			
			$this->validator->required($_POST['group_name'], sprintf(get_text('validator_required'), get_text('group_name')));
			
			if (empty($_POST['group_permissions'])) {
				
				$this->validator->set_error(sprintf(get_text('validator_required'), get_text('permissions')));
				
			}
			
			if (!$this->validator->has_errors()) {
				
				$this->groups->add($_POST['group_name'], $_POST['group_permissions']);
				
				$this->session->set('success', true);
				
				header('Location: ' . $this->options->get_option('absolute_url') . 'admin/groups');
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}

		$pages = array(
			'index' 			=> get_text('chat_panel'),
			'chat_history' 		=> get_text('chat_history'),
			'canned_messages' 	=> get_text('canned_messages'),
			'departments' 		=> get_text('departments'),
			'operators' 		=> get_text('operators'),
			'groups' 			=> get_text('groups'),
			'blocked_visitors' 	=> get_text('blocked_visitors'),
			'access_logs' 		=> get_text('access_logs'),
			'settings' 			=> get_text('settings'),
			'profile' 			=> get_text('profile')
		);

		$this->view->set('site_title', get_text('groups'));
		$this->view->set('pages', $pages);
		$this->view->display('admin/add_group');
		
	}
	
	public function edit($group_id) {
		
		if (isset($_POST['submit'])) {
			
			$this->validator->required($_POST['group_name'], sprintf(get_text('validator_required'), get_text('group_name')));
			
			if (empty($_POST['group_permissions'])) {
				
				$this->validator->set_error(sprintf(get_text('validator_required'), get_text('permissions')));
				
			}
			
			if (!$this->validator->has_errors()) {
				
				if ($this->groups->update($_POST['group_id'], $_POST['group_name'], $_POST['group_permissions'])) {
					
					$this->session->set('success', true);
					
				} else {
					
					$this->session->set('success', false);
					
				}
				
				header('Location: ' . $this->options->get_option('absolute_url') . 'admin/groups');
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}

		$pages = array(
			'index' 			=> get_text('chat_panel'),
			'chat_history' 		=> get_text('chat_history'),
			'canned_messages' 	=> get_text('canned_messages'),
			'departments' 		=> get_text('departments'),
			'operators' 		=> get_text('operators'),
			'groups' 			=> get_text('groups'),
			'blocked_visitors' 	=> get_text('blocked_visitors'),
			'access_logs' 		=> get_text('access_logs'),
			'settings' 			=> get_text('settings'),
			'profile' 			=> get_text('profile')
		);

		$this->view->set('site_title', get_text('groups'));
		$this->view->set('group', $this->groups->get_group($group_id));
		$this->view->set('pages', $pages);
		$this->view->display('admin/edit_group');
		
	}
	
	public function delete() {
		
		if (isset($_POST['cb']) && is_array($_POST['cb'])) {
			
			foreach ($_POST['cb'] as $value) {
				
				$result = $this->groups->check_group($value);
				
				if ($result['success']) {
					
					$this->session->set('has_errors', true);
					$this->session->set('group_name', $result['group_name']);

					$success = false;
					
				} else {
					
					$success = true;
					
				}
				
			}
			
			if ($success) {
				
				$this->groups->delete($_POST['cb']);

			}
			
		}
		
		header('Location: ' . $this->options->get_option('absolute_url') . 'admin/groups');
		
	}
	
}

?>
