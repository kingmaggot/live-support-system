<?php

namespace system;

class Router {
	
	//The request methods
	private $methods = array();
	
	//The routes
	private $routes = array();
	
	//The callbacks
	private $callbacks = array();
	
	//Patterns
	private $patterns = array(
		':any' => '[^/]+',
		':num' => '[0-9]+',
		':all' => '.*'
	);
	
	//Add route
	public function add_route($method, $route, $callback) {
		
		if (is_array($method)) {
			
			foreach ($method as $value) {

				$this->methods[] = strtoupper($value);
				$this->routes[] = rtrim(dirname($_SERVER['PHP_SELF']), '/') . rtrim($route, '/');
				$this->callbacks[] = $callback;
		
			}
			
		} else {
			
			$this->methods[] = strtoupper($method);
			$this->routes[] = rtrim(dirname($_SERVER['PHP_SELF']), '/') . rtrim($route, '/');
			$this->callbacks[] = $callback;
			
		}

	}
	
	//Add routes
	public function add_routes($routes) {
		
		foreach ($routes as $value) {

			call_user_func_array(array($this, 'add_route'), $value);

		}

	}
	
	//Dispatch the request to the route
	public function dispatch() {

		$url_path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
		$request_method = $_SERVER['REQUEST_METHOD'];

		$not_found = false;
		
		if (in_array($url_path, $this->routes)) {

			foreach (array_keys($this->routes, $url_path) as $value) {
				
				if ($this->methods[$value] == $request_method) {
					
					$not_found = true;
					
					if (!is_object($this->callbacks[$value])) {

						$method = trim(substr($this->callbacks[$value], strrpos($this->callbacks[$value], '#')), '#');

						$controller = APPLICATION_FOLDER . substr($this->callbacks[$value], 0, strrpos($this->callbacks[$value], '#'));
						$controller = new $controller();
						
						if (method_exists($controller, $method)) {
							
							$controller->$method();
							
						} else {

							error('Method ' . $method . ' does not exist.');
							
						}
						
					} else {

						call_user_func($this->callbacks[$value]);
						
					}
					
				}
				
			}
			
		} else {

			foreach ($this->routes as $key => $value) {

				$route = str_replace(array_keys($this->patterns), array_values($this->patterns), $value);
				
				if ($this->methods[$key] == $request_method) {
					
					if (preg_match('#^' . $route . '$#', $url_path, $matches)) {
						
						$not_found = true;
						
						array_shift($matches);
						
						if (!is_object($this->callbacks[$key])) {
							
							$method = trim(substr($this->callbacks[$key], strrpos($this->callbacks[$key], '#')), '#');
							
							$controller = APPLICATION_FOLDER . substr($this->callbacks[$key], 0, strrpos($this->callbacks[$key], '#'));
							$controller = new $controller();

							if (method_exists($controller, $method)) {
								
								call_user_func_array(array($controller, $method), $matches);
								
							} else {

								error('Method ' . $method . ' does not exist.');
								
							}

						} else {
							
							call_user_func_array($this->callbacks[$key], $matches);
							
						}
						
					}
					
				}
				
			}
			
		}
		
		if (!$not_found) {
			
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
			
			error('The resource you requested could not be found.', '404');
			
		}
		
	}
	
}

?>
