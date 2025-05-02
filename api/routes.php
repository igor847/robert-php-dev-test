<?php

use Api\Controller\MainController;
use Api\Controller\SegmentsController;

$router->get('/', [MainController::class, 'index']);

// Segments
$router->get('/api/segments', [SegmentsController::class, 'read']);
$router->post('/api/segments', [SegmentsController::class, 'create']);
$router->put('/api/segments/{id}', [SegmentsController::class, 'update']);
