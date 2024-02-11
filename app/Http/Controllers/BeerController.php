<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Throwable;

class BeerController extends ProxyController
{

    protected $baseUrl = 'https://api.punkapi.com/v2';
    protected $getMethod = 'beers';

    /*function getData(Request $request, $alias)
    {
        try {
            //$response = Http::beers()->get('/beers', $request);
            $response = Http::baseUrl($this->baseUrl)->get($this->getMethod, $request);
            return response($response, $response->status())
                ->header('Content-Type', 'application/json');
        } catch (Throwable $ex) {
            throw $ex;
        }
    }*/
}
