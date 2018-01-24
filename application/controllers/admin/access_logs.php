<?php

namespace application\controllers\admin;

class Access_logs extends Admin {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_model('admin/access_logs');

	}
	
	public function index() {
		
		$this->view->set('site_title', get_text('access_logs'));
		$this->view->set('access_logs', $this->access_logs->get_access_logs($this->options->get_option('records_per_page'), $this->options->get_option('date_format'), $this->options->get_option('time_format')));
		$this->view->display('admin/access_logs');

	}
	
	public function page($page) {

		$this->view->set('site_title', get_text('access_logs'));
		$this->view->set('access_logs', $this->access_logs->get_access_logs($this->options->get_option('records_per_page'), $this->options->get_option('date_format'), $this->options->get_option('time_format'), $page));
		$this->view->display('admin/access_logs');

	}
	
}

?>
