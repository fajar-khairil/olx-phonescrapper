<?php

namespace Unika\Config;

use Unika\Foundation\ServiceProviderInterface;
use Slim\Container;
use Symfony\Component\Config\FileLocator;

class ConfigServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container['config'] = function($c){
			$locator = new FileLocator($c->config_path);
			return new Config($locator);
		};
	}
}