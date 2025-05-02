<?php

define('PATH_ROOT', dirname(__DIR__));
define('PATH_PUBLIC', PATH_ROOT . DIRECTORY_SEPARATOR . 'public');

require_once __DIR__ . '/../vendor/autoload.php';

use Api\Routing\Router;

$router = new Router();
require_once __DIR__ . '/../api/routes.php';
$router->dispatch();
