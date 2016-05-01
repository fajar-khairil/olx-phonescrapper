<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';

// turn the light on
$app = new \Unika\Foundation\Application();

$app->init(dirname( dirname(__FILE__) ));

// setup dependencies
$c = $app->getContainer();
$c['PageHelper'] = new \Unika\Foundation\PageHelpers($c);

// put the main routes
require_once __DIR__.'/Http/routes.php';

$app->run();