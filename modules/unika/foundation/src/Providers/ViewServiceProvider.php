<?php

namespace Unika\Foundation\Providers;

use Slim\Container;
use Unika\Foundation\ServiceProviderInterface;

use Illuminate\View\Engines\PhpEngine;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;

class ViewServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$resolver = new EngineResolver;
		$resolver->register('blade',function()use($container){
			return new CompilerEngine( new BladeCompiler(new Filesystem(),$container['storage_path'].'/views') );
		});

		$container['viewFinder'] = function($c){
			$finder = new FileViewFinder(new Filesystem, [$c['base_path'].'/themes']);
			$finder->addNamespace('default',$c['base_path'].'/themes/'.$c['config']['app']['default_view']);
			return $finder;
		};

		$container['view'] = function($c) use($resolver){
			$env = new Factory($resolver, $c['viewFinder'],new Dispatcher());
			$env->share('container',$c);

			return $env;
		};
	}
}