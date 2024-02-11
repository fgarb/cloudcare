<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Enum\TokenAbilityEnum;
use \App\Http\Controllers\ProxyController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'ability:' . TokenAbilityEnum::ISSUE_ACCESS_TOKEN->value])->group(function () {
    Route::get('/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::middleware(['auth:sanctum', 'ability:' . TokenAbilityEnum::ACCESS_API->value])->group(function () {
    Route::get('/logout',[AuthController::class,'logout']);
    Route::get('/proxy/{alias}',[ProxyController::class,'get']);
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Route not found'], 404);
});
