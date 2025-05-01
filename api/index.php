<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Api\Controller\TestController;
use Api\Routing\Router;

$router = new Router();

$router->get('/api/items/{id}', [TestController::class, 'test']);

$router->dispatch();
