<?php

use App\Http\Controllers\Api\CurrencyController as ApiCurrencyController;
use App\Http\Controllers\Api\PairController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post("logout", [UserController::class, 'logout']);
});

Route::post("login", [UserController::class, 'login']);
Route::post("register", [UserController::class, 'register']);
Route::apiResource('pairs', PairController::class);
Route::apiResource('currencies', CurrencyController::class);
Route::get('/convert/{currency_from}/{currency_to}/{amount}/{invert?}', [PairController::class, 'convert']);

