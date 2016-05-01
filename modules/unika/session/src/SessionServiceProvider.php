<?php

namespace Unika\Session;

use Unika\Foundation\ServiceProviderInterface;
use Slim\Container;
use \Illuminate\Support\Arr;

class SessionServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$me = $this;
		$container['session'] = function($c)use($me){
			$valid_drivers = ['file','database','memcached','redis','array'];
			$driver = $c['config']['session']['driver'];

			if( !in_array($driver, $valid_drivers) ){
				throw new \RuntimeException('Invalid driver in session config driver.');
			}

			$createMethod = 'create'.ucfirst($driver).'Handler';
			$handler = $me->{$createMethod}($c);

			$options = array();
			$config = $c['config']['session'];
			$options['name'] = $config['cookie'];
			$options['cookie_lifetime'] = $config['lifetime'];
			$options['cookie_path'] = $config['path'];
			$options['cookie_domain'] = $config['domain'];
			$options['cookie_secure'] = $config['secure'];

			$storage = new Storage\NativeSessionStorage($options,$handler);
			$session = new Session($storage);
			return $session;
		};
	}

	protected function createFileHandler(Container $c)
	{
		return new Storage\Handler\NativeFileSessionHandler($c['config']['session']['files']);
	}

	protected function createDatabaseHandler(Container $c)
	{
		$config = $c['config']['session'];
		
		$connection = $config['connection'];

		if( !is_string($connection) ){
			$connection = $c['config']['database']['default'];
		}

		$table = $config['table'];

		$dbconfig = Arr::get($c['config']['database']['connections'],$connection,null);
		if( null === $dbconfig ){
			throw new \RuntimeException('connection '.$connection.' not found.');	
		}
		
		$dsn = $dbconfig['driver'].':dbname='.$dbconfig['database'].';host='.$dbconfig['host'];
		$pdo = new \PDO($dsn,$dbconfig['username'],$dbconfig['password']);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return new Storage\Handler\PdoSessionHandler($pdo,[
			'table' => $config['table'],
			'db_username'	=> $dbconfig['username'],
			'db_password'	=> $dbconfig['password']
		]);
	}

	protected function createdMemcachedHandler(Container $c)
	{
		return Storage\Handler\MemcachedSessionHandler(new \Memcached('app_session'));
	}

	protected function createRedisHandler(Container $c)
	{
		if( $c->has('redis') )
			$redis = $c['redis'];
		else
			$redis = new \Illuminate\Redis\Database($c['config']['database']['redis']);

		return new Storage\Handler\RedisSessionHandler($redis);
	}

	protected function createArrayHandler(Container $c)
	{
		return new Storage\Handler\ArraySessionHandler();
	}
}