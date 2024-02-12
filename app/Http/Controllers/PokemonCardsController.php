<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonCardsController extends ProxyController
{

    protected $baseUrl = 'https://api.tcgdex.net/v2/en/';
    protected $getUrl = 'sets';

}
