<?php

namespace Unika\Scrapper;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Slim\Container;
use Unika\Scrapper\ProccessJob;

abstract class AbstractProccessor
{
	protected $guzzle;
	protected $cookie;
	protected $container;
	protected $table;
	protected $manager;

	protected $job; // job instance
	protected $url;
	protected $total_page;
	protected $current_page = 1;
	protected $finish = false;

	protected $total_records = 0; // total fetched records

	public function __construct(Container $container,ProccessJob $job)
	{
		$this->container = $container;
		$this->guzzle = new Client(['cookies' => true]);
		$this->cookie = new CookieJar();
		$this->table = $this->container['config']['scrapper']['database']['table'];

		$this->job = $job;
		$this->url = $this->job->getUrl();

		$this->manager = $container['ScrapperManager'];
	}
}