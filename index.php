<?php

define('ENVIRONMENT', 'production');

define('APPLICATION_FOLDER', 'application');

require_once('common.php');

$router = new system\Router();

$router->add_routes(config_item('routes', 'routes'));

$router->dispatch();

?>
