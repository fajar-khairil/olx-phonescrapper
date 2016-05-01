<?php
namespace Unika\Foundation;

use Slim\App as BaseApp;
use Unika\Foundation\ServiceProviderInterface;

class Application extends BaseApp
{
	/** register ServiceProvider */
	public function register(ServiceProviderInterface $provider)
	{
		$provider->register($this->getContainer());
	}

	public function init($basePath)
	{
		$container = $this->getContainer();

		$container['base_uri'] = $container['request']->getUri()->getBasePath();

		$container['base_path'] = $basePath;
		$container['config_path'] = $basePath.'/config';
		$container['public_path'] = $basePath.'/public';
		$container['storage_path'] = $basePath.'/storage';

		$container['events'] = function($c){
			return new \Illuminate\Events\Dispatcher();
		};
		
		$this->register(new \Unika\Foundation\Providers\LoggerServiceProvider());

		$container['files'] = function($c){
			return new \Illuminate\Filesystem\Filesystem();
		};
		
		$this->register(new \Unika\Config\ConfigServiceProvider());
		
		date_default_timezone_set($container['config']['app']['timezone']);

		$container['settings']['displayErrorDetails'] = $container['config']['app']['debug'];
		
		$this->register(new \Unika\Foundation\Providers\ViewServiceProvider());
		$this->register(new \Unika\Foundation\Providers\EncryptionServiceProvider());

		$providers = $container['config']['app']['providers'];

		foreach($providers as $provider)
		{
			$this->register(new $provider);
		}

		if( $container->has('session') )
			$container->session->start();
	}
}