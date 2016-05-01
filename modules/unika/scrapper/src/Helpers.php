<?php

namespace Unika\Scrapper;

//use Slim\Container;
use Unika\Scrapper\Models\OlxCategory;

class Helpers
{
	//protected $container;

	// public function __construct()
	// {
	// 	$this->container = $c;
	// }

	public function renderOlxCategories()
	{
		$roots = OlxCategory::roots()->get();
		$html = '<select name="category" style="widht:150px;" class="form-control select2" id="category">';
		$html .= '<option value="0">Semua Kategori</option>'.PHP_EOL;
		foreach ($roots as $cat) {
			if( 0 === (int)$cat->id )
				continue;

			$html .= '<optgroup label="'.$cat->name.'">';
			$html .= '<option value="'.$cat->id.'"><strong>Semua '.$cat->name.'</strong></option>'.PHP_EOL;
			$childs = $cat->children()->get();

			foreach ($childs as $child) {
				$html .= '<option value="'.$child->id.'">'.$child->name.'</option>'.PHP_EOL;
			}

			$html .= '</optgroup>';
		}

		$html .= '</select>';
		return $html;
	}
}