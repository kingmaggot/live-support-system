<?php

namespace application\controllers\admin;

class Departments extends Admin {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load_model('admin/departments');
		
	}
	
	public function index() {
		
		if ($this->session->get('success')) {
			
			$this->view->set('success', $this->session->get('success'));
			
		}
		
		$this->view->set('site_title', get_text('departments'));
		$this->view->set('department_name', $this->session->get('department_name'));
		$this->view->set('departments', $this->departments->get_departments($this->options->get_option('records_per_page')));
		$this->view->set('has_errors', $this->session->get('has_errors'));
		$this->view->display('admin/departments');
		
		$this->session->delete('success');
		$this->session->delete('has_errors');
		$this->session->delete('department_name');
		
	}

	public function page($page) {

		$this->view->set('site_title', get_text('departments'));
		$this->view->set('departments', $this->departments->get_departments($this->options->get_option('records_per_page'), $page));
		$this->view->display('admin/departments');

	}
	
	public function add() {
		
		if (isset($_POST['submit'])) {
			
			$this->validator->required($_POST['department_name'], sprintf(get_text('validator_required'), get_text('department_name')));
			$this->validator->email($_POST['department_email'], sprintf(get_text('validator_required'), get_text('email_address')));
			
			if (!$this->validator->has_errors()) {
				
				$this->departments->add($_POST['department_name'], $_POST['department_email']);
				
				$this->session->set('success', true);
				
				header('Location: ' . $this->options->get_option('absolute_url') . 'admin/departments');
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('display_errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}
		
		$this->view->set('site_title', get_text('departments'));
		$this->view->display('admin/add_department');
		
	}
	
	public function edit($department_id) {
		
		if (isset($_POST['submit'])) {
			
			$this->validator->required($_POST['department_name'], sprintf(get_text('validator_required'), get_text('department_name')));
			$this->validator->email($_POST['department_email'], sprintf(get_text('validator_email'), get_text('email_address')));
			
			if (!$this->validator->has_errors()) {
				
				if ($this->departments->update($_POST['department_id'], $_POST['department_name'], $_POST['department_email'])) {
					
					$this->session->set('success', true);
					
				} else {
					
					$this->session->set('success', false);
					
				}
				
				header('Location: ' . $this->options->get_option('absolute_url') . 'admin/departments');
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('display_errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}
		
		$this->view->set('site_title', get_text('departments'));
		$this->view->set('department', $this->departments->get_department($department_id));
		$this->view->display('admin/edit_department');
		
	}
	
	public function delete() {
		
		if (isset($_POST['cb']) && is_array($_POST['cb'])) {
			
			foreach ($_POST['cb'] as $value) {
				
				$result = $this->departments->check_department($value);
				
				if ($result['success']) {
					
					$this->session->set('has_errors', true);
					$this->session->set('department_name', $result['department_name']);

					$success = false;
					
				} else {
					
					$success = true;
					
				}
				
			}
			
			if ($success) {
				
				$this->departments->delete($_POST['cb']);

			}
			
		}
		
		header('Location: ' . $this->options->get_option('absolute_url') . 'admin/departments');
		
	}
	
}

?>
