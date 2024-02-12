<?php

namespace App\Http\Controllers\V1;

class PokemonCardsController extends ProxyController
{

    protected $baseUrl = 'https://api.tcgdex.net/v2/en/';
    protected $getUrl = 'sets';

}
