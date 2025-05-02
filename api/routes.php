<?php

use Api\Controller\MainController;
use Api\Controller\TestController;

$router->get('/', [MainController::class, 'index']);
$router->get('/api/test/{id}', [TestController::class, 'testId']);
$router->get('/api/segments', [TestController::class, 'testId']);
