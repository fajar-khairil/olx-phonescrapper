<?php

namespace Unika\Scrapper\Proccessor;

use Unika\Scrapper\AbstractProccessor;
use Symfony\Component\DomCrawler\Crawler;

class JualoProccessor extends AbstractProccessor
{
	public function proccess()
	{		
		$this->validateUrl();
		$this->manager->markAsStarted($this->job->id);
		
		try
		{
			$this->login();
			while( !$this->finish )
			{
				$this->doProccess($this->fetchList());
				$this->nextPage();
			}
			$this->logout();
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
			exit(1);
		}
	}

	/**
	 *
	 *	proccess lists of ads and save to DB
	 *
	 */
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

		foreach($lists as $url)
		{
			try
			{
				$response = $this->guzzle->request('GET',$url,[
					'cookies'	=>	$this->cookie
				]);

				$html = $response->getBody()->getContents();

				$ad_phone = $this->getAdPhone($html);
				$ad_location = $this->getAdLocation($html);
				$ad_content = $this->getAdContent($html);

				$db->table($this->table)->insert([
					'source'	=>	'jualo',
					'url'		=>	$url,
					'phone'		=>  $ad_phone,
					'city'		=>  $ad_location,
					'content'	=>	$ad_content,
					'created_at'	=> $now
				]);
			}
			catch(\Exception $e)
			{
				// do noting or log to system
			}

			sleep(0.10);
		}
	}

	/**
	 *
	 *	mogrify -crop 155x19+0x0 jp1.jpg && tesseract jp1.jpg out && cat out.txt
	 *
	 */
	protected function getAdPhone($html)
	{
		$crawler = new Crawler();
		$crawler->add($html);
		$img_url = $crawler->filter('img.phone_number_image')->extract('src')[0];

		$arr = explode('/', $img_url);
		$uid = $arr[count($arr) - 2];//get user_id

		$response = $this->guzzle->request('GET',$img_url,[
			'cookies'	=>	$this->cookie
		]);

		$img = $response->getBody()->getContents();
		$c = $this->container;

		$path = $c['storage_path'].'/cache/'.$uid.'.jpg';
		file_put_contents($path, $img);

		$tmp = shell_exec('mogrify -crop 155x19+0x0 '.$path.' && tesseract '.$path.' out && cat out.txt');
		unlink('out.txt');
		$phone_number = trim($tmp);

		$patterns = ['/g/','/O/','/o/','/s/','/l/'];
		$replacements = ['9','0','0','5','1'];
		$phone_number = preg_replace($patterns,$replacements,$phone_number);
		return $phone_number;
	}

	protected function getAdLocation($html)
	{
		$crawler = new Crawler();
		$crawler->add($html);
		return $crawler->filter('span.location_name')->text();
	}

	protected function getAdContent($html)
	{
		$crawler = new Crawler();
		$crawler->add($html);
		return $crawler->filter('div.ad_show_detail')->html();
	}

	/*
	 *
	 *	Fetch lists of ads
	 *  @todo : optimize this code
	 *
	 */
	protected function fetchList()
	{
		if( (int)$this->current_page === 1 )
		{
			$response = $this->guzzle->request('GET',$this->url,[
				'cookies'	=>	$this->cookie
			]);

			$html = $response->getBody()->getContents();
			$crawler = new Crawler();
			$crawler->add($html);

			$count = $crawler->filter('div.pagination a')->count();
			$this->total_page = $count - 2;

			$nodes = $crawler->filter('td.product-description-right-block a');

			$results = $nodes->each(function($node,$i){
				return $node->extract('href')[0];
			});
		}
		else
		{
			$url = $this->url.'&page='.$this->current_page;
			$response = $this->guzzle('GET',$url,[
				'cookies'	=>	$this->cookie
			]);

			$html = $response->getBody()->getContents();
			$crawler = new Crawler();
			$crawler->add($html);

			$nodes = $crawler->filter('td.product-description-right-block a');

			$results = $nodes->each(function($node,$i){
				return $node->extract('href')[0];
			});
		}
		$this->total_records += count($results);
		return $results;
	}

	protected function nextPage()
	{
		$this->current_page++;
	}

	protected function logout()
	{
		$logout_url = 'https://www.jualo.com/users/sign_out';
		$response = $this->guzzle('GET',$logout_url);
	}

	protected function login()
	{
		$login_url = 'https://www.jualo.com/users/sign_in';
		
		// get CSRF token first
		$r1 = $this->guzzle->request('GET',$login_url,[
			'cookies'	=> $this->cookie
		]);

		$html = $r1->getBody()->getContents();
		$crawler = new Crawler();
		$crawler->add($html);
		$csrf_token = $crawler->filter('input[name="authenticity_token"]')->extract('value')[0];
		// end get csrf token

		$r = $this->guzzle->request('POST',$login_url,[
			'cookies'	=> $this->cookie,
			'form_params'		=>	[
				'authenticity_token'	=>	$csrf_token,
				'user[email]'			=>	'fajar.khairil@gmail.com',
				'user[password]'		=>	'm4st3rk3y90',
				'user[remember_me]'		=>	1
			]
		]);

		if( 200 === $r->getStatusCode() ){
			return true;
		}else{
			throw new \RuntimeException($e->getReasonPhrase());
		}
	}

	protected function validateUrl()
	{
		if( false === strpos($this->url,'jualo.com') )
		{
			throw new \RuntimeException('Invalid jualo.com url.');
		}

		return true;
	}
}