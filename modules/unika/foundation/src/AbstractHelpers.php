<?php

namespace Unika\Foundation;

abstract class AbstractHelpers
{
	protected $container;

	public function __construct(\Slim\Container $container)
	{
		$this->container = $container;
	}
}