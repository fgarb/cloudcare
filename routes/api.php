<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Enum\TokenAbilityEnum;
use \App\Http\Controllers\ProxyController;
use \App\Http\Controllers\BeerController;
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

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'ability:' . TokenAbilityEnum::ISSUE_ACCESS_TOKEN->value])->group(function () {
    Route::get('/auth/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::middleware(['auth:sanctum', 'ability:' . TokenAbilityEnum::ACCESS_API->value])->group(function () {
    Route::get('/auth/logout',[AuthController::class,'logout']);
    // this is a test I have done...
    Route::get('/proxy/{alias}',[ProxyController::class,'get']);

    // this is a more standard way
    Route::get('/beers', [BeerController::class, 'getData']);
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Route not found'], 404);
});
