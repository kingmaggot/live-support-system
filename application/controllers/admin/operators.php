<?php

namespace application\controllers\admin;

class Operators extends Admin {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_model('admin/operators');

	}
	
	public function index() {
		
		if ($this->session->get('success')) {
			
			$this->view->set('success', $this->session->get('success'));
			
		}
		
		$this->view->set('site_title', get_text('operators'));
		$this->view->set('operators', $this->operators->get_operators($this->options->get_option('records_per_page'), $this->options->get_option('date_format'), $this->options->get_option('time_format')));
		$this->view->set('has_errors', $this->session->get('has_errors'));
		$this->view->display('admin/operators');
		
		$this->session->delete('success');
		$this->session->delete('has_errors');

	}

	public function page($page) {
		
		$this->view->set('site_title', get_text('operators'));
		$this->view->set('operators', $this->operators->get_operators($this->options->get_option('records_per_page'), $this->options->get_option('date_format'), $this->options->get_option('time_format'), $page));
		$this->view->display('admin/operators');

	}
	
	public function add() {
		
		if (isset($_POST['submit'])) {

			$this->validator->required($_POST['first_name'], sprintf(get_text('validator_required'), get_text('first_name')));
			$this->validator->required($_POST['last_name'], sprintf(get_text('validator_required'), get_text('last_name')));
			$this->validator->email($_POST['user_email'], sprintf(get_text('validator_email'), get_text('email_address')));
			$this->validator->required($_POST['user_password'], sprintf(get_text('validator_required'), get_text('password')));
			$this->validator->matches($_POST['user_password'], $_POST['confirm_password'], sprintf(get_text('validator_matches'), get_text('confirm_password'), get_text('password')));
			
			if (empty($_POST['departments'])) {
				
				$this->validator->set_error(sprintf(get_text('validator_required'), get_text('departments')));
				
			}
			
			if (!$this->authentication->check_email($_POST['user_email'])) {
				
				$this->validator->set_error(get_text('error_email_used'));
				
			}
			
			if (!$this->validator->has_errors()) {
				
				$additional_data = array(
					'first_name'	=> $_POST['first_name'],
					'last_name'		=> $_POST['last_name']
				);
				
				$this->authentication->insert($_POST['user_email'], $_POST['user_password'], $_POST['user_status'], $_POST['group_id'], $additional_data);
				$this->operators->insert($_POST['user_email'], $_POST['first_name'], $_POST['last_name'], $_POST['hide_online'], $_POST['departments']);
				
				$this->session->set('success', true);
				
				header('Location: ' . $this->options->get_option('absolute_url') . 'admin/operators');
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}
		
		$this->view->set('site_title', get_text('operators'));
		$this->view->set('groups', $this->operators->get_groups());
		$this->view->set('departments', $this->operators->get_departments());
		$this->view->display('admin/add_operator');
		
	}

	public function edit($operator_id) {
		
		if (isset($_POST['submit'])) {
			
			$this->validator->required($_POST['first_name'], sprintf(get_text('validator_required'), get_text('first_name')));
			$this->validator->required($_POST['last_name'], sprintf(get_text('validator_required'), get_text('last_name')));
			$this->validator->email($_POST['user_email'], sprintf(get_text('validator_email'), get_text('email_address')));
			
			if (!empty($_POST['user_password']) || !empty($_POST['confirm_password'])) {
				
				$this->validator->required($_POST['user_password'], sprintf(get_text('validator_required'), get_text('password')));
				$this->validator->matches($_POST['user_password'], $_POST['confirm_password'], sprintf(get_text('validator_matches'), get_text('confirm_password'), get_text('password')));
				
			}
			
			if (empty($_POST['departments'])) {
				
				$this->validator->set_error(sprintf(get_text('validator_required'), get_text('departments')));
				
			}

			$result = $this->operators->get_operator_email($operator_id);
			
			if ($result['success']) {
				
				if ($result['user_email'] != sanitize($_POST['user_email'], 'email')) {
					
					if (!$this->authentication->check_email($_POST['user_email'])) {
						
						$this->validator->set_error(get_text('error_email_used'));
						
					}
					
				}
				
			}
			
			if (!$this->validator->has_errors()) {
				
				$result = $this->operators->get_operator_email($operator_id);
				
				if ($result['user_email'] != sanitize($_POST['user_email'], 'email')) {
					
					if (file_exists($this->options->get_option('absolute_path') . 'uploads/images/' . md5($result['user_email']) . '.png')) {
						
						unlink($this->options->get_option('absolute_path') . 'uploads/images/' . md5($result['user_email']) . '.png');
					
					}
					
				}

				$user_password = (!empty($_POST['user_password'])) ? $_POST['user_password'] : null;
				
				$additional_data = array(
					'first_name' 	=> $_POST['first_name'],
					'last_name'		=> $_POST['last_name']
				);
				
				$this->authentication->update($_POST['user_id'], $_POST['user_email'], $user_password, $_POST['user_status'], $_POST['group_id'], $additional_data);
				$this->operators->update($_POST['operator_id'], $_POST['first_name'], $_POST['last_name'], $_POST['hide_online'], $_POST['departments']);
				
				$this->session->set('success', true);
				
				header('Location: ' . $this->options->get_option('absolute_url') . 'admin/operators');
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}
		
		$this->view->set('site_title', get_text('operators'));
		$this->view->set('operator', $this->operators->get_operator($operator_id));
		$this->view->display('admin/edit_operator');
		
	}
	
	public function delete() {
		
		if (isset($_POST['cb']) && is_array($_POST['cb'])) {
			
			if (in_array($this->session->get('operator_id'), $_POST['cb'])) {
				
				$this->session->set('has_errors', true);
				
			} else {
				
				foreach ($_POST['cb'] as $value) {

					$result = $this->operators->get_operator_email($value);

					if (file_exists($this->options->get_option('absolute_path') . 'uploads/images/' . md5($result['user_email']) . '.png')) {
						
						unlink($this->options->get_option('absolute_path') . 'uploads/images/' . md5($result['user_email']) . '.png');
					
					}
					
					$result = $this->operators->get_user_id($value);

					$this->authentication->delete($result['user_id']);
					$this->operators->delete($value);
					
				}
			
			}

		}
		
		header('Location: ' . $this->options->get_option('absolute_url') . 'admin/operators');
		
	}
	
}

?>
