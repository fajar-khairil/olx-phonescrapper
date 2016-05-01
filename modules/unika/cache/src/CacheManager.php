<?php

namespace Unika\Cache;

use Slim\Container;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class CacheManager
{
	protected $container;
	// resolved stores
	protected $stores;
	// application cache prefix
	protected $prefix;

	protected $defaultDriver = null;

	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->prefix = $this->container['config']['cache']['prefix'];
	}

	public function get($name = null)
	{
		if( null === $name ){
			$name = $this->getDefaultDriver();
		}

		return isset($this->stores[$name]) ? $this->stores[$name] : $this->resolve($name);
	}

	public function getDefaultDriver()
	{
		if( null === $this->defaultDriver )
		{
			$this->defaultDriver = $this->container['config']['cache']['default'];	
		}

		return $this->defaultDriver;
	}

	public function resolve($name)
	{
		$config = Arr::get($this->container['config']['cache']['stores'],$name);

		if( null === $config )
		{
			throw new InvalidArgumentException('cache driver '.$name.' is not supported.');
		}

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            $this->stores[$name] = $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException('cache driver '.$name.' is not supported.');
        }		

		return $this->stores;
	}

	protected function createFileDriver($config)
	{
		return $this->repository(new \Illuminate\Cache\FileStore($this->container['files'], $config['path']));
	}

	protected function createMemcachedDriver($config)
	{
		return $this->repository(new \Illuminate\Cache\MemcachedStore(new \Illuminate\Cache\MemcachedConnector, $this->prefix));
	}

	protected function createRedisDriver($config)
	{
		return $this->repository( new \Illuminate\Cache\RedisStore($this->container['redis'], $this->prefix,$config['connection']) );
	}

	protected function createArrayDriver()
	{
		return $this->repository(new \Illuminate\Cache\ArrayStore);
	}

	protected function createApcDriver($config)
	{
		return $this->repository(new \Illuminate\Cache\ApcStore(new \Illuminate\Cache\ApcWrapper, $this->prefix));
	}

	protected function repository($store)
	{
		$repo =  new \Illuminate\Cache\Repository($store);
		$repo->setEventDispatcher($this->container['events']);

		return $repo;
	}
}