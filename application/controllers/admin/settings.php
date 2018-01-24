<?php

namespace application\controllers\admin;

class Settings extends Admin {
	
	public function __construct() {
		
		parent::__construct();

		$this->load_model('admin\settings');
	
	}
	
	public function index() {

		if (isset($_POST['submit'])) {
			
			$this->validator->required($_POST['site_title'], sprintf(get_text('validator_required'), get_text('site_title')));
			$this->validator->email($_POST['admin_email'], sprintf(get_text('validator_email'), get_text('email_address')));
			$this->validator->required($_POST['charset'], sprintf(get_text('validator_required'), get_text('charset')));
			$this->validator->integer($_POST['max_connections'], sprintf(get_text('validator_integer'), get_text('max_connections')));
			$this->validator->integer($_POST['max_visitors'], sprintf(get_text('validator_integer'), get_text('max_visitors')));
			$this->validator->integer($_POST['records_per_page'], sprintf(get_text('validator_integer'), get_text('records_per_page')));

			if ($_POST['ip_tracker_url']) {
				
				$this->validator->url($_POST['ip_tracker_url'], sprintf(get_text('validator_url'), get_text('ip_tracker_url')));
				
				if (filter_var($_POST['ip_tracker_url'], FILTER_VALIDATE_URL)) {
					
					$data = file_get_contents(str_replace('ip_address', $_SERVER['REMOTE_ADDR'], $_POST['ip_tracker_url']));
					$data = json_decode($data, true);
					
					if (!isset($data['latitude']) && !isset($data['longitude'])) {
						
						$this->validator->set_error(get_text('error_geolocation'));
						
					}
					
				}
			
			}

			if (!$this->validator->has_errors()) {
				
				$this->settings->save($_POST);
				
				$this->view->set('success', true);
				
			} else {
				
				$this->view->set('has_errors', true);
				$this->view->set('errors', $this->validator->display_errors());
				
				$this->validator->clear_errors();
				
			}
			
		}

		$timezones = array (
			'Pacific/Midway' 					=> '(UTC-11:00) Midway Island',
			'Pacific/Samoa' 					=> '(UTC-11:00) Samoa',
			'Pacific/Honolulu' 					=> '(UTC-10:00) Hawaii',
			'US/Alaska' 						=> '(UTC-09:00) Alaska',
			'America/Los_Angeles' 				=> '(UTC-08:00) Pacific Time (US & Canada)',
			'America/Tijuana' 					=> '(UTC-08:00) Tijuana',
			'US/Arizona' 						=> '(UTC-07:00) Arizona',
			'America/Chihuahua' 				=> '(UTC-07:00) La Paz',
			'America/Mazatlan' 					=> '(UTC-07:00) Mazatlan',
			'US/Mountain' 						=> '(UTC-07:00) Mountain Time (US & Canada)',
			'America/Managua' 					=> '(UTC-06:00) Central America',
			'US/Central' 						=> '(UTC-06:00) Central Time (US & Canada)',
			'America/Mexico_City' 				=> '(UTC-06:00) Mexico City',
			'America/Monterrey' 				=> '(UTC-06:00) Monterrey',
			'Canada/Saskatchewan' 				=> '(UTC-06:00) Saskatchewan',
			'America/Bogota' 					=> '(UTC-05:00) Quito',
			'US/Eastern' 						=> '(UTC-05:00) Eastern Time (US & Canada)',
			'US/East-Indiana' 					=> '(UTC-05:00) Indiana (East)',
			'America/Lima' 						=> '(UTC-05:00) Lima',
			'Canada/Atlantic' 					=> '(UTC-04:00) Atlantic Time (Canada)',
			'America/Caracas' 					=> '(UTC-04:30) Caracas',
			'America/La_Paz' 					=> '(UTC-04:00) La Paz',
			'America/Santiago' 					=> '(UTC-04:00) Santiago',
			'Canada/Newfoundland' 				=> '(UTC-03:30) Newfoundland',
			'America/Sao_Paulo' 				=> '(UTC-03:00) Brasilia',
			'America/Argentina/Buenos_Aires' 	=> '(UTC-03:00) Georgetown',
			'America/Godthab' 					=> '(UTC-03:00) Greenland',
			'America/Noronha' 					=> '(UTC-02:00) Mid-Atlantic',
			'Atlantic/Azores' 					=> '(UTC-01:00) Azores',
			'Atlantic/Cape_Verde' 				=> '(UTC-01:00) Cape Verde Is.',
			'Africa/Casablanca' 				=> '(UTC+00:00) Casablanca',
			'Europe/London' 					=> '(UTC+00:00) London',
			'Etc/Greenwich' 					=> '(UTC+00:00) Greenwich Mean Time : Dublin',
			'Europe/Lisbon' 					=> '(UTC+00:00) Lisbon',
			'Africa/Monrovia' 					=> '(UTC+00:00) Monrovia',
			'UTC' 								=> '(UTC+00:00) UTC',
			'Europe/Amsterdam' 					=> '(UTC+01:00) Amsterdam',
			'Europe/Belgrade' 					=> '(UTC+01:00) Belgrade',
			'Europe/Berlin' 					=> '(UTC+01:00) Bern',
			'Europe/Bratislava' 				=> '(UTC+01:00) Bratislava',
			'Europe/Brussels' 					=> '(UTC+01:00) Brussels',
			'Europe/Budapest' 					=> '(UTC+01:00) Budapest',
			'Europe/Copenhagen' 				=> '(UTC+01:00) Copenhagen',
			'Europe/Ljubljana'					=> '(UTC+01:00) Ljubljana',
			'Europe/Madrid' 					=> '(UTC+01:00) Madrid',
			'Europe/Paris' 						=> '(UTC+01:00) Paris',
			'Europe/Prague' 					=> '(UTC+01:00) Prague',
			'Europe/Rome' 						=> '(UTC+01:00) Rome',
			'Europe/Sarajevo' 					=> '(UTC+01:00) Sarajevo',
			'Europe/Skopje' 					=> '(UTC+01:00) Skopje',
			'Europe/Stockholm' 					=> '(UTC+01:00) Stockholm',
			'Europe/Vienna' 					=> '(UTC+01:00) Vienna',
			'Europe/Warsaw' 					=> '(UTC+01:00) Warsaw',
			'Africa/Lagos' 						=> '(UTC+01:00) West Central Africa',
			'Europe/Zagreb' 					=> '(UTC+01:00) Zagreb',
			'Europe/Athens' 					=> '(UTC+02:00) Athens',
			'Europe/Bucharest' 					=> '(UTC+02:00) Bucharest',
			'Africa/Cairo' 						=> '(UTC+02:00) Cairo',
			'Africa/Harare' 					=> '(UTC+02:00) Harare',
			'Europe/Helsinki' 					=> '(UTC+02:00) Kyiv',
			'Europe/Istanbul' 					=> '(UTC+02:00) Istanbul',
			'Asia/Jerusalem' 					=> '(UTC+02:00) Jerusalem',
			'Africa/Johannesburg' 				=> '(UTC+02:00) Pretoria',
			'Europe/Riga' 						=> '(UTC+02:00) Riga',
			'Europe/Sofia' 						=> '(UTC+02:00) Sofia',
			'Europe/Tallinn' 					=> '(UTC+02:00) Tallinn',
			'Europe/Vilnius' 					=> '(UTC+02:00) Vilnius',
			'Asia/Baghdad' 						=> '(UTC+03:00) Baghdad',
			'Asia/Kuwait' 						=> '(UTC+03:00) Kuwait',
			'Europe/Minsk' 						=> '(UTC+03:00) Minsk',
			'Africa/Nairobi' 					=> '(UTC+03:00) Nairobi',
			'Asia/Riyadh' 						=> '(UTC+03:00) Riyadh',
			'Europe/Volgograd' 					=> '(UTC+03:00) Volgograd',
			'Asia/Tehran' 						=> '(UTC+03:30) Tehran',
			'Asia/Muscat' 						=> '(UTC+04:00) Muscat',
			'Asia/Baku' 						=> '(UTC+04:00) Baku',
			'Europe/Moscow'						=> '(UTC+04:00) St. Petersburg',
			'Asia/Tbilisi' 						=> '(UTC+04:00) Tbilisi',
			'Asia/Yerevan' 						=> '(UTC+04:00) Yerevan',
			'Asia/Kabul' 						=> '(UTC+04:30) Kabul',
			'Asia/Karachi' 						=> '(UTC+05:00) Karachi',
			'Asia/Tashkent' 					=> '(UTC+05:00) Tashkent',
			'Asia/Calcutta' 					=> '(UTC+05:30) Sri Jayawardenepura',
			'Asia/Kolkata' 						=> '(UTC+05:30) Kolkata',
			'Asia/Katmandu' 					=> '(UTC+05:45) Kathmandu',
			'Asia/Almaty' 						=> '(UTC+06:00) Almaty',
			'Asia/Dhaka' 						=> '(UTC+06:00) Dhaka',
			'Asia/Yekaterinburg' 				=> '(UTC+06:00) Ekaterinburg',
			'Asia/Rangoon' 						=> '(UTC+06:30) Rangoon',
			'Asia/Bangkok' 						=> '(UTC+07:00) Hanoi',
			'Asia/Jakarta' 						=> '(UTC+07:00) Jakarta',
			'Asia/Novosibirsk' 					=> '(UTC+07:00) Novosibirsk',
			'Asia/Hong_Kong' 					=> '(UTC+08:00) Hong Kong',
			'Asia/Chongqing' 					=> '(UTC+08:00) Chongqing',
			'Asia/Krasnoyarsk' 					=> '(UTC+08:00) Krasnoyarsk',
			'Asia/Kuala_Lumpur' 				=> '(UTC+08:00) Kuala Lumpur',
			'Australia/Perth' 					=> '(UTC+08:00) Perth',
			'Asia/Singapore' 					=> '(UTC+08:00) Singapore',
			'Asia/Taipei' 						=> '(UTC+08:00) Taipei',
			'Asia/Ulan_Bator' 					=> '(UTC+08:00) Ulaan Bataar',
			'Asia/Urumqi' 						=> '(UTC+08:00) Urumqi',
			'Asia/Irkutsk' 						=> '(UTC+09:00) Irkutsk',
			'Asia/Tokyo' 						=> '(UTC+09:00) Tokyo',
			'Asia/Seoul' 						=> '(UTC+09:00) Seoul',
			'Australia/Adelaide' 				=> '(UTC+09:30) Adelaide',
			'Australia/Darwin' 					=> '(UTC+09:30) Darwin',
			'Australia/Brisbane' 				=> '(UTC+10:00) Brisbane',
			'Australia/Canberra'				=> '(UTC+10:00) Canberra',
			'Pacific/Guam' 						=> '(UTC+10:00) Guam',
			'Australia/Hobart' 					=> '(UTC+10:00) Hobart',
			'Australia/Melbourne' 				=> '(UTC+10:00) Melbourne',
			'Pacific/Port_Moresby' 				=> '(UTC+10:00) Port Moresby',
			'Australia/Sydney' 					=> '(UTC+10:00) Sydney',
			'Asia/Yakutsk' 						=> '(UTC+10:00) Yakutsk',
			'Asia/Vladivostok' 					=> '(UTC+11:00) Vladivostok',
			'Pacific/Auckland' 					=> '(UTC+12:00) Wellington',
			'Pacific/Fiji' 						=> '(UTC+12:00) Marshall Is.',
			'Pacific/Kwajalein' 				=> '(UTC+12:00) International Date Line West',
			'Asia/Kamchatka' 					=> '(UTC+12:00) Kamchatka',
			'Asia/Magadan' 						=> '(UTC+12:00) Solomon Is.',
			'Pacific/Tongatapu' 				=> '(UTC+13:00) Nuku\'alofa'
		);

		$timeout_1 = array(
			'1800' 		=> '30 ' . get_text('minutes'),
			'3600' 		=> '60 ' . get_text('minutes'),
			'7200' 		=> '120 ' . get_text('minutes')
		);

		$timeout_2 = array(
			'600' 		=> '10 ' . get_text('minutes'),
			'1200' 		=> '20 ' . get_text('minutes')
		);
		
		$timeout_3 = array(
			'864000'	=> '10 ' . get_text('days'),
			'1728000' 	=> '20 ' . get_text('days'),
			'2592000' 	=> '30 ' . get_text('days')
		);
		
		$date_format = array(
			'F d, Y'	=> date('F d, Y', time()),
			'm/d/Y'		=> date('m/d/Y', time()),
			'd/m/Y'		=> date('d/m/Y', time())
		);
		
		$time_format = array(
			'h:i:s a' 	=> date('h:i:s a', time()),
			'h:i:s A'	=> date('h:i:s A', time()),
			'H:i:s' 	=> date('H:i:s', time())
		);
		
		$embed_code  = '<!-- Start Live Chat Code -->';
		$embed_code .= '&#10;';
		$embed_code .= '<script type="text/javascript" src="' . $this->options->get_option('absolute_url') . 'assets/js/jquery.min.js"></script>';
		$embed_code .= '&#10;';
		$embed_code .= '<script type="text/javascript">jQuery.noConflict();</script>';
		$embed_code .= '&#10;';
		$embed_code .= '<script type="text/javascript">';
		$embed_code .= 'jQuery(document).ready(function($) {';
		$embed_code .= '$.ajaxSetup({';
		$embed_code .= 'xhrFields: {';
		$embed_code .= 'withCredentials: true';
		$embed_code .= '},';
		$embed_code .= 'headers: {';
		$embed_code .= '"X-Requested-With": "XMLHttpRequest"';
		$embed_code .= '}';
		$embed_code .= '});';
		$embed_code .= '$.ajax({';
		$embed_code .= 'type: "GET",';
		$embed_code .= 'url: "' . $this->options->get_option('absolute_url') . 'live_chat",';
		$embed_code .= 'dataType: "html",';
		$embed_code .= 'success: function(data) {';
		$embed_code .= '$("body").append(data);';
		$embed_code .= '}';
		$embed_code .= '});';
		$embed_code .= '});';
		$embed_code .= '</script>';
		$embed_code .= '&#10;';
		$embed_code .= '<!-- End Live Chat Code -->';

		$url = parse_url($this->options->get_option('absolute_url'));
		
		$options = array(
			'site_title' 			=> $this->options->get_option('site_title'),
			'admin_email' 			=> $this->options->get_option('admin_email'),
			'ip_tracker_url'		=> $this->options->get_option('ip_tracker_url'),
			'charset'				=> $this->options->get_option('charset'),
			'absolute_url' 			=> $url['scheme'],
			'charset' 				=> $this->options->get_option('charset'),
			'operator_timeout'		=> $this->options->get_option('operator_timeout'),
			'max_connections'		=> $this->options->get_option('max_connections'),
			'max_visitors' 			=> $this->options->get_option('max_visitors'),
			'chat_timeout' 			=> $this->options->get_option('chat_timeout'),
			'records_per_page'		=> $this->options->get_option('records_per_page'),
			'timezone' 				=> $this->options->get_option('timezone'),
			'date_format' 			=> $this->options->get_option('date_format'),
			'time_format' 			=> $this->options->get_option('time_format'),
			'user_timeout' 			=> $this->options->get_option('user_timeout'),
			'access_logs' 			=> $this->options->get_option('access_logs'),
			'background_color_1'	=> $this->options->get_option('background_color_1'),
			'background_color_2' 	=> $this->options->get_option('background_color_2'),
			'background_color_3' 	=> $this->options->get_option('background_color_3'),
			'color_1' 				=> $this->options->get_option('color_1'),
			'color_2' 				=> $this->options->get_option('color_2'),
			'color_3' 				=> $this->options->get_option('color_3'),
			'chat_position'			=> $this->options->get_option('chat_position')
		);
		
		$this->view->set('timezones', $timezones);
		$this->view->set('timeout_1', $timeout_1);
		$this->view->set('timeout_2', $timeout_2);
		$this->view->set('timeout_3', $timeout_3);
		$this->view->set('date_format', $date_format);
		$this->view->set('time_format', $time_format);
		$this->view->set('embed_code', $embed_code);
		$this->view->set('options', $options);
		$this->view->set('site_title', get_text('settings'));
		$this->view->display('admin/settings');

	}
	
}

?>
