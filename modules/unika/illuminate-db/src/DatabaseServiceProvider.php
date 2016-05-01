<?php

namespace Unika\IlluminateDb;

use Unika\Foundation\ServiceProviderInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Container;

class DatabaseServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container['illuminate_db'] = function($c){
			$capsule = new Capsule();

			$default = $c['config']['database']['default'];
			$databases = $c['config']['database']['connections'];

			foreach($databases as $key=>$db)
			{
				$capsule->addConnection($db,$key);
			}

			$capsule->getDatabaseManager()->setDefaultConnection($default);

			$capsule->setEventDispatcher($c['events']);
			$capsule->setAsGlobal();
			$capsule->bootEloquent();

			return $capsule;
		};
	}
}