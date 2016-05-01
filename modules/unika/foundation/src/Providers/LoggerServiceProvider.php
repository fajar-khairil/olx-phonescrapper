<?php

namespace Unika\Foundation\Providers;

use Slim\Container;
use Unika\Foundation\ServiceProviderInterface;
use Monolog\Logger;

class LoggerServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container['log'] = function($c){
			$logger = new \Illuminate\Log\Writer(new Logger('app',$c['events']));
			$logger->useDailyFiles($c['storage_path'].'/log');
			return $logger;
		};
	}	
}