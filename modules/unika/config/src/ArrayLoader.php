<?php

namespace Unika\Config;

use Symfony\Component\Config\Loader\Loader;

Class ArrayLoader extends Loader
{
	public function load($resource, $type = null)
	{
		return require $resource;
	}

	public function supports($resource, $type = null)
	{
        return is_string($resource) && 'php' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
	}
}