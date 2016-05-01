<?php

namespace Unika\Config;


class ConfigException extends \Exception
{
	public function __construct($file)
	{
		$this->message = 'Config File '.$file.' not found.';
	}
}