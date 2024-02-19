<?php

namespace App\Traits;

use App\Exceptions\ProxyAPIException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait ProxyTrait
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
    final public function get(Request $request)
    {
        try{
            $response = Http::baseUrl($this->baseUrl)->get($this->getUrl, $request);
            if ($response->status() == 200){
                $proxyResponse = [
                    'data' => json_decode($response->body())
                ];
                return response()->json($proxyResponse);
            }
            return response()->json(json_decode($response->body()), $response->status());
        }catch(\Throwable $ex){
            throw new ProxyAPIException($ex->getMessage());
        }
    }
}
