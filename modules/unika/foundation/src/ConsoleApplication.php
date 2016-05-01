<?php
namespace Unika\Foundation;

use Symfony\Component\Console\Application as BaseApp;
use Slim\Container;

class ConsoleApplication extends BaseApp
{
	protected $container;

	public function __construct(Container $container,$name = 'UNKNOWN', $version = 'UNKNOWN')
	{
		parent::__construct($name,$version);
		$this->container = $container;
	}

	public function getContainer()
	{
		return $this->container;
	}
}