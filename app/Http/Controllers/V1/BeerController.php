<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ProxyAPIException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;
use App\Http\Controllers\Controller;
use App\Traits\ProxyTrait;

class BeerController extends Controller
{

    /**
     * Starting from ProxyTrait you can set just these 2 properties for every new controller
     *
     * @see PokemonCardsController::$baseUrl
     * @see PokemonCardsController::$getUrl
     */
    protected $baseUrl = 'https://api.punkapi.com/v2';
    protected $getUrl = 'beers';

    /**
     * Gets Data from external API.
     * @see ProxyTrait::get() to use another method to get the same result
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ProxyAPIException
     */
    public function getData(Request $request)
    {
        try {
            // use a macro
            $response = Http::beers()->get('/beers', $request);

            // normally in API I would use a Resource to return data
            // in this case, we are just a proxy for the external api so I don't use a Resource
            if ($response->status() == 200){
                $proxyResponse = [
                    'data' => json_decode($response->body())
                ];
                return response()->json($proxyResponse);
            }
            return response()->json(json_decode($response->body()), $response->status());

        } catch (Throwable $ex) {
            throw new ProxyAPIException($ex);
        }
    }
}
