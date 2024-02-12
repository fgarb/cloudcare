<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ProxyAPIException;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{
    // the base URL of your API
    protected $baseUrl;

    // the GET Url of your API (only GET method supported)
    protected $getUrl;

    /**
     * This is just an experiment I have done to call from this ProxyController every Controllers that is a subclass
     * just by setting two variables
     *
     * @param Request $request
     * @param $alias
     * @return \Illuminate\Http\JsonResponse
     * @throws ProxyAPIException
     */
    final public function get(Request $request, $alias)
    {
        try{
            if (is_subclass_of($alias, $this::class)) {
                $aliasController = new $alias();
                $response = Http::baseUrl($aliasController->baseUrl)->get($aliasController->getUrl, $request);

                if ($response->status() == 200){

                    // should use a resource class defined in the controller or a model
                    $proxyResponse = [
                        'data' => json_decode($response->body())
                    ];
                    return response()->json($proxyResponse);
                }
                return response()->json(json_decode($response->body()), $response->status());
            }
            else{
                $proxyResponse = [
                    'message' => 'Proxy not set'
                ];
                return response()->json($proxyResponse, 404);
            }
        }catch(\Throwable $ex){
            throw new ProxyAPIException($ex->getMessage());
        }
    }

}
