<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\V1\AuthController;
use \App\Enum\TokenAbilityEnum;
use \App\Http\Controllers\V1\ProxyController;
use \App\Http\Controllers\V1\BeerController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/v1/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'ability:' . TokenAbilityEnum::ISSUE_ACCESS_TOKEN->value])->prefix('v1')->group(function () {
    Route::get('/auth/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::middleware(['auth:sanctum', 'ability:' . TokenAbilityEnum::ACCESS_API->value])->prefix('v1')->group(function () {
    Route::get('/auth/logout',[AuthController::class,'logout']);
    // this is a test I have done...
    //Route::get('/proxy/{alias}',[ProxyController::class,'get']);
    Route::get('/beers', [BeerController::class, 'get']);
    Route::get('/pokemons', [\App\Http\Controllers\V1\PokemonCardsController::class, 'get']);

    // this is without using Traits
    Route::get('/beers', [BeerController::class, 'getData']);
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Route not found'], 404);
});
