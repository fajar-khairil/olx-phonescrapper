<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/scrap','\App\Http\Controllers\ScrapperController:olxAction');
$app->get('/lists','\App\Http\Controllers\ProvidersController:getOlxAction');

$app->get('/logs','\App\Http\Controllers\ProvidersController:getOlxLogAction');
$app->post('/jobs','\App\Http\Controllers\ScrapperController:postOlxJob');