<?php

namespace Unika\Foundation;

abstract class Controller
{
	protected $container;
	
	public function __construct($c)
	{
		$this->container = $c;
	}

	public function __get($param)
	{
		return $this->container[$param];
	}
}