<?php

namespace Unika\Foundation\Providers;

use Unika\Foundation\ServiceProviderInterface;
use Slim\Container;
use Illuminate\Encryption\Encrypter;
use Illuminate\Encryption\McryptEncrypter;

class EncryptionServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container['encrypt'] = function($c){
			$key = $c['config']['app']['key'];
			$chiper = MCRYPT_RIJNDAEL_128;

	        if (Encrypter::supported($key, $cipher)) {
	            return new Encrypter($key, $cipher);
	        } elseif (McryptEncrypter::supported($key, $cipher)) {
	            return new McryptEncrypter($key, $cipher);
	        } else {
	            throw new RuntimeException('No supported encrypter found. The cipher and / or key length are invalid.');
	        }
		};
	}
}