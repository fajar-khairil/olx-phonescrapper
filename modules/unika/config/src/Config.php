<?php

namespace Unika\Config;

use Symfony\Component\Config\FileLocatorInterface;
use Illuminate\Support\Arr;

class Config implements \ArrayAccess
{
	/** instance of FileLocatorInterface */
	protected $locator;

	/** instance of loader */
	protected $loader;

	/** local array cache **/
	protected $items = array();

	public function __construct(FileLocatorInterface $locator)
	{
		$this->locator = $locator;
		$this->loader = new ArrayLoader($this->locator);
	}

	public function offsetSet($id, $value)
	{
		Arr::set($this->items,$id,$value);
	}

	public function offsetGet($id)
	{
		if( isset($this->items[$id]) )
		{
			return Arr::get($this->items,$id);
		}

		try{
			$this->items[$id] = $this->loader->load($this->locator->locate($id.'.php'));
			return Arr::get($this->items,$id);
		}catch(\Exception $e){
			throw new ConfigException($id.'.php');
		}
	}

	public function offsetExists($id)
	{
		return (null !== $this->offsetGet($id));
	}

	public function offsetUnset($id)
	{
		unset($this->items[$id]);
	}
}