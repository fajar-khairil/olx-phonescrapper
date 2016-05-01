<?php

return array(
	'debug'			=>	true,
	'key'			=>	'zwsyWYPiZZrhWqhSDC6PQAnkmpRYdslu',
	'timezone'		=> 	'Asia/Jakarta',
	'locale'		=> 'en',
	'providers'		=> 	[
		'\Unika\IlluminateDb\DatabaseServiceProvider',
		'\Unika\Cache\CacheServiceProvider',
		'\Unika\Session\SessionServiceProvider',
		'\Unika\Scrapper\ScrapperServiceProvider'
	],
	'default_view'	=>	'default'
);