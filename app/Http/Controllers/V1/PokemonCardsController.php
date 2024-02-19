<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Traits\ProxyTrait;

class PokemonCardsController extends Controller
{

    protected $baseUrl = 'https://api.tcgdex.net/v2/en/';
    protected $getUrl = 'sets';

}
