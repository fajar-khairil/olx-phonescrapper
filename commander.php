<?php
date_default_timezone_set('Asia/Jakarta');
require_once __DIR__.'/vendor/autoload.php';

$foundation = new \Unika\Foundation\Application();
$foundation->init(dirname(__FILE__));

use Unika\Foundation\ConsoleApplication;

$app = new ConsoleApplication($foundation->getContainer(),'Commander','1.0');
$app->add(new \App\Command\ScrapCommand());
$app->add(new \App\Command\ScrapRunnerCommand());

$app->run();