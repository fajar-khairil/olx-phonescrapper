<?php

namespace Unika\Scrapper;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Str;
use Unika\Scrapper\Models\OlxCategory;

class ProccessJob
{
	public $limit;
	public $city;
	public $category;
	public $keyword;
	public $source;

	protected $id;
	protected $url;

	protected $db;

	public function __construct($limit,$city,$keyword,$category)
	{
		$this->limit = $limit;
		$this->city = trim($city);
		$this->category = $category;
		$this->keyword = $keyword;	

		//leagcy
		$this->source = 'olx';	
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setDB(Capsule $db)
	{
		$this->db = $db;
	}

	protected function convertUrl()
	{
		// switch ($this->source) {
		// 	case 'olx':
				$this->url = $this->makeOlxUrl();
		// 		break;
		// 	case 'jualo':
		// 		$this->url = $this->makeJualoUrl();
		// 		break;
		// }		
	} 

	public function getUrl()
	{
		if( null === $this->url ){
			$this->convertUrl();
		}

		return $this->url;
	}

	protected function makeOlxUrl()
	{

		$url = 'http://olx.co.id/';

		if( $this->category )
		{
			$category = OlxCategory::where('id',$this->category)->first();
			
			$parent = $category->parent()->first();
			if( $parent ){
				$url .= Str::slug($parent->name).'/';
			}

			$url .= Str::slug($category->name).'/';
		}
		
		if( !$this->city )
		{
			$url .= 'q-'.Str::slug($this->keyword);
		}
		else
		{
			$city = $this->db->table('olx_cities')->where('slug',$this->city)->first();
			
			if( null === $city ){
				throw new \RuntimeException("Invalid City for olx Supplied.");
				
			}

			$url .= $city->slug.'/q-'.Str::slug($this->keyword);
		}

		return $url;
	}

	// protected function makeJualoUrl()
	// {
	// 	$url = 'https://www.jualo.com/search/?';

	// 	$city = $this->db->table('jualo_cities')->where('id',$this->city)->first();
		
	// 	if( null === $city ){
	// 		throw new \RuntimeException("Invalid City for jualo Supplied.");
			
	// 	}

	// 	$url .= http_build_query(['keyword' => $this->keyword]);
		
	// 	return $url;
	// }
}