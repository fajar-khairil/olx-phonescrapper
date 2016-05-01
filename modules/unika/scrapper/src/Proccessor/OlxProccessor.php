<?php

namespace Unika\Scrapper\Proccessor;

use Unika\Scrapper\AbstractProccessor;
use Symfony\Component\DomCrawler\Crawler;

/**
 *
 *	#1 http://olx.co.id/iklan/rentcar-mobil-semarang-IDgqPFZ.html (parse the id)
 *	#2 http://olx.co.id/ajax/misc/contact/phone/(ad_id)/
 *  #3 call returned url from #1 
 *
 */

class OlxProccessor extends AbstractProccessor
{	
	public function proccess()
	{		
		$this->manager->markAsStarted($this->job->getId());
		
		while(!$this->finish)
		{
			$this->doProccess($this->fetchLists());
			$this->nextPage();	
		}
		$this->manager->markAsDone($this->job->getId(),$this->total_records);
	}

	// fetch,parse and save to DB
	protected function doProccess($lists)
	{
		if( (int)$this->current_page >= (int)$this->job->limit ){
			$this->finish = true;
		}

		if( (int)$this->current_page >= (int)$this->total_page ){
			$this->finish = true;
		}

		$db = $this->container['illuminate_db'];

		$now = date('Y-m-d H:i:s');
		foreach ($lists as $idx => $url) 
		{
			$url = $this->cleanUrl($url);
			$ad_phone = $this->getAdPhone($url);

			$rAd = $this->guzzle->request('get',$url,[
				'headers' => [
					'X-Requested-With' => 'XMLHttpRequest',
					'Referer'		   => $url,
					'Accept-Encoding'  => 'gzip, deflate, sdch'
				],
				'cookies'	=> $this->cookie
			]);

			$html = $rAd->getBody()->getContents();		
			$ad_content = $this->getAdContent($html);
			$ad_location = $this->getAdLocation($html);

			try
			{
				$db->table($this->table)->insert([
					'source'	=>	'olx',
					'url'		=>	$url,
					'phone'		=>  $ad_phone,
					'city'		=>  $ad_location,
					'content'	=>	$ad_content,
					'created_at'	=> $now
				]);
			}
			catch(\Exception $e)
			{
				if( 23000 === (int)$e->getCode() ){
					echo "duplicate entry url : ".$url.PHP_EOL;
				}else{
					echo $e->getMessage().PHP_EOL;
				}
			}
			sleep(0.10);
		}
	}

	protected function nextPage()
	{
		$this->current_page++;
	}

	protected function fetchLists()
	{
		if( (int)$this->current_page === 1 )
		{
			$response = $this->guzzle->request('get',$this->url,[
				'cookies'	=> $this->cookie
			]);

			if( (int)$response->getStatusCode() !== 200 ){
				throw new \RuntimeException('failed to get response : '.$response->getReasonPhrase());
			}

			// OK
			$body = $response->getBody(true);
			$html = $body->getContents();

			$crawler = new Crawler();
			$crawler->add($html);
			$this->total_page = trim( $crawler->filter('.pager span.item')->last()->text() );

			if( null === $this->job->limit ){
				$this->job->limit = $this->total_page;
			}

			$doms = $crawler->filter('td.offer a.link');
		
			$results = array();
			$results = $doms->each(function($node,$i){
				return $node->extract('href')[0];
			});
		}
		else
		{
			$url = $this->url.'?page='.$this->current_page;

			$response = $this->guzzle->request('get',$url,[
				'cookies'	=> $this->cookie
			]);

			if( (int)$response->getStatusCode() !== 200 ){
				throw new \RuntimeException('failed to get response : '.$response->getReasonPhrase());
			}

			// OK
			$body = $response->getBody(true);
			$html = $body->getContents();

			$crawler = new Crawler();
			$crawler->add($html);
			$doms = $crawler->filter('td.offer a.link');
		
			$results = array();
			$results = $doms->each(function($node,$i){
				return $node->extract('href')[0];
			});
		}
		$this->total_records += count($results);
		return $results;
	}

	public function cleanUrl($url)
	{
		return substr($url, 0,strpos($url, '.html')+5);
	}

	protected function getAdPhone($url)
	{
		// step 1 get url to get image phone number
		$ad_id = $this->parseId($url);
		$r1 = $this->guzzle->request('get','http://olx.co.id/ajax/misc/contact/phone/'.$ad_id.'/',[
			'headers' => [
				'X-Requested-With' => 'XMLHttpRequest',
				'Referer'		   => $url,
				'Accept-Encoding'  => 'gzip, deflate, sdch'
			],
			'cookies'	=> $this->cookie
		]);

		if( (int)$r1->getStatusCode() !== 200 ){
			// just ignore and log to system
			return 0;
		}

		$vurl = json_decode($r1->getBody()->getContents(),true);

		// step 2 get the image of phone number
		$r2 = $this->guzzle->request('get',$vurl['value'],[
			'headers' => [
				'X-Requested-With' => 'XMLHttpRequest',
				'Referer'		   => $url,
				'Accept-Encoding'  => 'gzip, deflate, sdch'
			],
			'cookies'	=> $this->cookie
		]);

		if( (int)$r2->getStatusCode() !== 200 ){
			// just ignore and log to system
			return 0;
		}

		$c = $this->container;

		$img = $r2->getBody()->getContents();
		$imgPath = $c['storage_path'].'/cache/olx-phone-'.$ad_id.'.png';
		file_put_contents($imgPath, $img);

		$output = array();
		$strout = trim(exec('gocr '.$imgPath,$output,$retCode));

		if( 0 === (int)$retCode )
		{
			$patterns = ['/g/','/O/','/o/','/s/','/l/','/_/'];
			$replacements = ['9','0','0','5','1','44'];
			$strout = preg_replace($patterns,$replacements,$strout);
			return $strout;
		}
		else
		{
			return 0;
		}
	}

	// #textContent
	protected function getAdContent($html)
	{
		$crawler = new Crawler();
		$crawler->add($html);

		try
		{
			$content = $crawler->filter('#textContent')->html();
		}
		catch(\Exception $e)
		{
			// just ignore or log to system
		}
		
		return $content;
	}

	// .c2b a
	protected function getAdLocation($html)
	{
		$crawler = new Crawler();
		$crawler->add($html);

		try
		{
			$location = $crawler->filter('.c2b a')->text();
		}
		catch(\Exception $e)
		{
			// just ignore or log to system
		}
		
		return $location;
	}

	/**
	 *
	 *	return id of ad
	 *
	 */
	protected function parseId($url)
	{
		$arr = explode('-',$url);
		$part = $arr[count($arr)- 1];
		return substr($part, 2,strpos($part,'.html') - 2);
	}

	protected function validateUrl()
	{
		if( false === strpos($this->url,'olx.co.id') )
		{
			throw new \RuntimeException('Invalid olx.co.id url.');
		}

		return true;
	}
}