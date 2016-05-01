<?php

namespace Unika\Scrapper;

use Unika\Foundation\ServiceProviderInterface;
use Slim\Container;

class ScrapperServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container['ScrapperManager'] = function($c){
			return new ProccessManager($c['illuminate_db']);
		};
	
		$container['ScrapperHelpers'] = function($c){
			return new \Unika\Scrapper\Helpers();
		};
	}
}