<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonCardsController extends ProxyController
{

    protected $baseUrl = 'https://api.tcgdex.net/v2/en/';
    protected $getMethod = 'sets';

    /*function getData(Request $request, $alias)
    {
        try {
            $response = Http::baseUrl($this->baseUrl)->get($this->getMethod, $request);
            return response($response, $response->status())
                ->header('Content-Type', 'application/json');
        } catch (Throwable $ex) {
            throw $ex;
        }
    }*/
}
