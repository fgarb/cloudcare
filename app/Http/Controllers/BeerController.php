<?php

namespace App\Http\Controllers;

use App\Exceptions\ProxyAPIException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Throwable;

class BeerController extends ProxyController
{

    // using the ProxyController you can set just these 2 properties for every new controller
    // this is just an experiment
    protected $baseUrl = 'https://api.punkapi.com/v2';
    protected $getUrl = 'beers';

    /**
     * This is more standard way to get data from API
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|void
     * @throws ProxyAPIException
     */
    function getData(Request $request){
        try {
            // use a macro
            $response = Http::beers()->get('/beers', $request);

            // normally in API I would use a Resource to return data
            // in this case, we are just a proxy for the external api so I don't use a Resource
            return response($response, $response->status())
                ->header('Content-Type', 'application/json');
        } catch (Throwable $ex) {
            throw new ProxyAPIException($ex);
        }
    }
}
