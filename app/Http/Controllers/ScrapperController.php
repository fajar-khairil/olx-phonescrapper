<?php

namespace App\Http\Controllers;

use Unika\Foundation\Controller;
use Unika\Scrapper\ProccessJob as Job;
use Illuminate\Support\Str;

class ScrapperController extends Controller
{
    public function olxAction($request,$response,$args)
    {
        $this->PageHelper->addScripts(['js/scrap.js']);

        $cities = $this->container['illuminate_db']->table('olx_cities')->select('*')->get();
        $categoriesHtml = $this->container['ScrapperHelpers']->renderOlxCategories();

    	$content = $this->view->make('default::scrap')
	    		->with('source','olx')
                ->with('cities',$cities)
                ->with('categoriesHtml',$categoriesHtml);

	    $response->getBody()->write($content);

	    return $response;
    }

    public function postOlxJob($request,$response,$args)
    {
        $this->ScrapperManager->put( new Job(
            $request->getParam('limit'),
            $request->getParam('city'),
            $request->getParam('keyword'),
            $request->getParam('category')
        ));

        $results = [
            'status'    =>  "OK",
            'message'   =>  "successfully added job to queue"
        ];

        $response->getBody()->write(json_encode($results));
    }
}