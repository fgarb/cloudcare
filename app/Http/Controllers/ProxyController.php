<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ProxyAPIException;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{

    protected $baseUrl;
    protected $getMethod;

    final public function get(Request $request, $alias)
    {
        try{
            if (is_subclass_of($alias, $this::class)) {
                $aliasController = new $alias();
                $response = Http::baseUrl($aliasController->baseUrl)->get($aliasController->getMethod, $request);
                //$response = $aliasController->getData($request, $alias);
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

    protected function getData(){

    }
}
