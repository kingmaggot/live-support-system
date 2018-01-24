<?php

namespace application\controllers\admin;

class Profile extends Admin {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_model('admin\profile');
		
	}
	
	public function index() {

		if (isset($_POST['submit'])) {
			
			$this->validator->required($_POST['first_name'], sprintf(get_text('validator_required'), get_text('first_name')));
			$this->validator->required($_POST['last_name'], sprintf(get_text('validator_required'), get_text('last_name')));
			$this->validator->email($_POST['user_email'], sprintf(get_text('validator_email'), get_text('email_address')));
			
			if (!empty($_POST['user_password']) || !empty($_POST['confirm_password'])) {
				
				$this->validator->required($_POST['user_password'], sprintf(get_text('validator_required'), get_text('password')));
				$this->validator->matches($_POST['user_password'], $_POST['confirm_password'], sprintf(get_text('validator_matches'), get_text('confirm_password'), get_text('password')));
				
			}

			$result = $this->profile->get_operator_email($this->session->get('operator_id'));
			
			if ($result['success']) {
				
				if ($result['user_email'] != sanitize($_POST['user_email'], 'email')) {
					
					if (!$this->authentication->check_email($_POST['user_email'])) {
						
						$this->validator->set_error(get_text('error_email_used'));
						
					}
					
				}
				
			}
		
			if ($_FILES['profile_image']['size'] > 0) {
				
				$extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
				
				if ($_FILES['profile_image']['size'] > 512000) {
					
					$this->validator->set_error(get_text('error_upload_size'));
					
				} elseif (!in_array($extension, array('png'))) {
					
					$this->validator->set_error(get_text('error_upload_file'));
					
				} elseif (!is_writable($this->options->get_option('absolute_path') . 'uploads/images/')) {
					
					$this->validator->set_error('The folder ' . $this->options->get_option('absolute_path') . 'uploads/images/ is not writeable.');
					
				}
				
			}

			if (!$this->validator->has_errors()) {
				
				$result = $this->profile->get_operator_email($this->session->get('operator_id'));
				
				if ($result['user_email'] != sanitize($_POST['user_email'], 'email')) {
					
					if (file_exists($this->options->get_option('absolute_path') . 'uploads/images/' . md5($result['user_email']) . '.png')) {
						
						unlink($this->options->get_option('absolute_path') . 'uploads/images/' . md5($result['user_email']) . '.png');
					
					}
					
				}
				
				if ($_FILES['profile_image']['size'] > 0) {

					$file_name =  md5(sanitize($_POST['user_email'], 'email')) . '.' . $extension;
					
					copy($_FILES['profile_image']['tmp_name'], $this->options->get_option('absolute_path') . 'uploads/images/' . $file_name);

					$image = imagecreatefrompng($this->options->get_option('absolute_path') . 'uploads/images/' . $file_name);
					$new_image = imagecreatetruecolor(60, 60);

					imagecolortransparent($new_image, imagecolorallocatealpha($new_image, 0, 0, 0, 127));
					imagealphablending($new_image, false);
					imagesavealpha($new_image, true);
					imagecopyresampled($new_image, $image, 0, 0, 0, 0, 60, 60, imagesx($image), imagesy($image));
					imagepng($new_image, $this->options->get_option('absolute_path') . 'uploads/images/' . $file_name);

				}
				
				$user_password = (!empty($_POST['user_password'])) ? $_POST['user_password'] : null;
				
				$additional_data = array(
					'first_name'	=> $_POST['first_name'],
					'last_name'		=> $_POST['last_name']
				);
				
				$this->authentication->update($_POST['user_id'], $_POST['user_email'], $user_password, null, null, $additional_data);
				$this->profile->update($this->session->get('operator_id'), $_POST['first_name'], $_POST['last_name'], $_POST['hide_online']);

				$this->session->set('user_email', sanitize($_POST['user_email'], 'email'));
				$this->session->set('full_name', sanitize($_POST['first_name'] . ' ' . $_POST['last_name'], 'string'));
				
				$this->view->set('success', true);
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}
		
		$this->view->set('site_title', get_text('profile'));
		$this->view->set('operator', $this->profile->get_operator($this->session->get('operator_id')));
		$this->view->display('admin/profile');

	}

	public function delete_image() {
		
		if (file_exists($this->options->get_option('absolute_path') . 'uploads/images/' . md5($this->session->get('user_email')) . '.png')) {
			
			unlink($this->options->get_option('absolute_path') . 'uploads/images/' . md5($this->session->get('user_email')) . '.png');
		
		}
		
		header('Location: ' . $this->options->get_option('absolute_url') . 'admin/profile');
		
	}
	
}

?>
