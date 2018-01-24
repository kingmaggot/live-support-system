<?php

if (defined('ENVIRONMENT')) {
	
	switch (ENVIRONMENT) {
		
		case 'development':
			error_reporting(E_ALL);
		break;
		
		case 'production':
			error_reporting(0);
		break;
		
		default:
			error('Uygulama ortamı doğru ayarlanmamış.');
			
	}
	
}

function elapsed_time($start, $end, $full_string = false) {
	
	$diff = $start - $end;
	
	if (1 > $diff) {
		
		return '--';
		
	} else {
		
		$periods = array(
			'week'		=> floor($diff / 86400 / 7),
			'day'		=> floor($diff / 86400 % 7),
			'hour'		=> floor($diff / 3600 % 24),
			'minute' 	=> floor($diff / 60 % 60),
			'second' 	=> floor($diff % 60)
		);

		$output = '';
		
		$i = 1;
		
		$count = count($periods);
		
		foreach ($periods as $key => $value) {
			
			if ($full_string) {
				
				if ($count == $i) {
					
					$output .= $value;
					
				} else {
					
					$output .= $value . ':';
					
				}
				
				$i++;
				
			} else {
				
				if ($value > 0 && $value == 1) {
					
					$output .= $value . ' ' . $key . ' ';
					
				} elseif ($value > 0 && $value != 1) {
					
					$output .= $value . ' ' . $key . 's ';
					
				}
				
			}

		}
		
		return $output;
		
	}

}

//Load a config file
function config_load($file) {

	if (file_exists(dirname(__FILE__) . '/config/' . $file . '.php')) {
		
		$data = array();
		
		require(dirname(__FILE__) . '/config/' . $file . '.php');

		if (!isset($config) || !is_array($config)) {

			error('Dosya: ' . dirname(__FILE__) . '/config/' . $file . '.php ayarlanmış gibi görünmüyor.');
		
		}

		$data = array_merge($data, $config);

		return $data;
		
	} else {

		error('Dosya: ' . dirname(__FILE__) . '/config/' . $file . '.php bulunamıyor.');
		
	}
	
}

//Load a config item
function config_item($file, $key) {
	
	$config = config_load($file);

	return (isset($config[$key])) ? $config[$key] : false;

}

//Get the text from the language file
function get_text($key, $language_code = null) {

	$language_code = is_null($language_code) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : $language_code;
	
	$data = (file_exists(dirname(__FILE__) . '/' . APPLICATION_FOLDER . '/languages/' . $language_code . '.php')) ? include(dirname(__FILE__) . '/' . APPLICATION_FOLDER . '/languages/' . $language_code . '.php') : include(dirname(__FILE__) . '/' . APPLICATION_FOLDER . '/languages/en.php');

	return (!is_array($data)) ? $key : (array_key_exists($key, $data) ? $data[$key] : $key);

}

//Detect the platform
function platform($user_agent) {
	
	$platforms = array(
		'Android'			=> 'Android',
		'iPhone'			=> 'iPhone',
		'iPad'				=> 'iPad',
		'BlackBerry'		=> 'BlackBerry',
		'Linux'				=> 'Linux',
		'Macintosh'			=> 'Mac OS X',
		'Windows NT 6.3' 	=> 'Windows 8.1',
		'Windows NT 6.2' 	=> 'Windows 8',
		'Windows NT 6.1' 	=> 'Windows 7',
		'Windows NT 6.0' 	=> 'Windows Vista',
		'Windows NT 5.2' 	=> 'Windows 2003',
		'Windows NT 5.1' 	=> 'Windows XP'
	);

	foreach ($platforms as $key => $value) {

		if (preg_match('/' . $key . '/i', $user_agent)) {

			$platform = $value;

			break;

		} else {

			$platform = 'Bilinmeyen';

		}

	}
	
	return $platform;
	
}

//Detect the browser
function browser($user_agent) {
	
	$browsers = array(
		'Firefox'	=> 'Firefox',
		'Chrome'	=> 'Chrome',
		'Safari'	=> 'Safari',
		'Opera'		=> 'Opera',
		'MSIE'		=> 'Internet Explorer'
	);

	foreach ($browsers as $key => $value) {

		if (preg_match('/' . $key . '/i', $user_agent)) {

			$browser = $value;

			break;

		} else {

			$browser = 'Bilinmeyen';

		}

	}
	
	return $browser;
	
}
	
//Generate the token
function generate_token($name) {

	if (!session_id()) {
		
		session_start();
		
	}

	if (isset($_SESSION[$name . '_token'])) {
		
		return $_SESSION[$name . '_token'];
		
	} else {
		
		$token =  sha1(time() . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
		
		$_SESSION[$name . '_token'] = $token;
		
		return $token;
	
	}
	
}

//Check the token
function check_token($name, $token) {

	if (!session_id()) {
		
		session_start();
		
	}

	if (!isset($_SESSION[$name . '_token'])) {
		
		return false;
		
	}
	
	if (isset($token) && $_SESSION[$name . '_token'] == $token) {
		
		unset($_SESSION[$name . '_token']);
		
		return true;
		
	}
	
	return false;
	
}

//Sanitize the data
function sanitize($data, $filter) {
	
	switch ($filter) {
		
		case 'string':
			return filter_var($data, FILTER_SANITIZE_STRING);
		break;
		
		case 'email':
			return filter_var($data, FILTER_SANITIZE_EMAIL);
		break;
		
		case 'integer':
			return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
		break;
		
		case 'float':
			return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		break;

		case 'url':
			return filter_var($data, FILTER_SANITIZE_URL);
		break;
		
	}
	
	error('Bilinmeyen filtreleri sanitize.');
	
}
	
//Autoload
function autoload($class_name) {
	
	$file = dirname(__FILE__) . '/' . str_replace('\\', '/', strtolower($class_name));
	
	(file_exists($file . '.php')) ? require_once($file . '.php') : error('Dosya: ' . $file . '.php mevcut değil.');
	
}

spl_autoload_register('autoload');

//Show the error
function error($message, $template = 'error') {

	if (file_exists(dirname(__FILE__) . '/' . APPLICATION_FOLDER . '/views/errors/' . $template . '.php')) {
		
		if (is_array($message)) {
			
			extract($message);
			
		}
		
		ob_start();

		include(dirname(__FILE__) . '/' . APPLICATION_FOLDER . '/views/errors/' . $template . '.php');

		$output = ob_get_contents();

		ob_end_clean();

		echo $output;
		
		exit;
		
	} else {

		exit('Dosya: ' . dirname(__FILE__) . '/' . APPLICATION_FOLDER . '/views/errors/' . $template . '.php mevcut değil.');
		
	}
	
}

//Exception handler
function exception_handler($exception) {

	$exception = array(
		'message'	=> $exception->getMessage(),
		'code'		=> $exception->getCode(),
		'file'		=> $exception->getFile(),
		'line'		=> $exception->getLine()
	);
	
	error($exception, 'exception');

}

set_exception_handler('exception_handler');

//Error handler
function error_handler($code, $message, $file, $line) {
	
	throw new ErrorException($message, $code, 0, $file, $line);
	
}

set_error_handler('error_handler');

?>
