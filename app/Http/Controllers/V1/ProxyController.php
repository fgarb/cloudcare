<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\ProxyAPIException;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{

    /**
     * @var string the base URL of your API
     */
    protected $baseUrl;

    /**
     * @var string the GET Url of your API (only GET method supported)
     */
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
                // TODO invoke a method in the controller in order to process response

                if ($response->status() == 200){
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
