<?php

namespace Unika\Cache;

use Slim\Container;
use Unika\Foundation\ServiceProviderInterface;

class CacheServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container['redis'] = function($c){
			return new \Illuminate\Redis\Database($c['config']['database']['redis']);
		};

		$container['cacheManager'] = function($c){
			return new CacheManager($c);
		};

		$container['cache'] = function($c){
			return $c['cacheManager']->get();
		};
	}	
}