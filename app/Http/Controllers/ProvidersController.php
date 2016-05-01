<?php

namespace App\Http\Controllers;

use Unika\Foundation\Controller;
use Illuminate\Pagination\Paginator;

class ProvidersController extends Controller
{
    public function getOlxAction($request,$response,$args)
    {
        $page = $request->getQueryParam('page',1);

        if( (! is_numeric($page)) || (int)$page < 1 ){
            throw new \Slim\Exception\NotFoundException($request,$response);
        }

        $order_type = strtoupper( $request->getQueryParam('order_type','ASC') );
        
        if(! in_array($order_type, ['ASC','DESC']) ){
            throw new \Slim\Exception\NotFoundException($request,$response);
        }
        
        $this->PageHelper->addScripts(['js/scrap.js']);

        $col_order = $request->getQueryParam('col_order','id');
        // row per page default value
        $rpp = 20;

        $rows = $this->illuminate_db->table('src_providers')->select('*')
            ->orderBy($col_order,$order_type)
            ->paginate($rpp,['*'],'page',$page);

        $rows->setPath($request->getUri()->getBasePath().'/lists');

        $content = $this->view->make('default::result')
                ->with('order_type',$order_type)
                ->with('col_order',$col_order)
                ->with('page',$page)
                ->with('uri',$request->getUri()->getBasePath().'/lists')
                ->with('rows',$rows);

        $response->getBody()->write($content);
        return $response;
    }

    public function getOlxLogAction($request,$response,$args)
    {
        if(! $request->isXhr() ){
            throw new \Slim\Exception\NotFoundException($request,$response);
        }

        $page = $request->getQueryParam('page',1);

        if( ! is_numeric($page) ){
            throw new \Slim\Exception\NotFoundException($request,$response);
        }

        $order_type = strtoupper( $request->getQueryParam('order_type','DESC') );
        
        if(! in_array($order_type, ['ASC','DESC']) ){
            throw new \Slim\Exception\NotFoundException($request,$response);
        }
        
        $col_order = $request->getQueryParam('col_order','id');
        // row per page default value
        $rpp = 20;

        $rows = $this->illuminate_db->table('src_logs')->select('*')
            ->where('source','olx')
            ->orderBy('status','ASC')
            ->orderBy($col_order,$order_type)
            ->paginate($rpp,['*'],'page',$page);
    
        $rows->setPath($request->getUri()->getBasePath().'/logs');

        $results = [
            'status'    =>  "OK",
            'items'     =>  $rows->items(),
            'paginationHtml' => (string)$rows->links()
        ];

        $response->getBody()->write(json_encode($results));

        return $response;        
    }
}