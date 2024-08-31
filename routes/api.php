<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'] ,function(){
    Route::get('/get-product', [ProductController::class, 'index']);
    Route::post('/product', [ProductController::class,'store']);
    Route::get('/show/{id}',[ProductController::class, 'show']);

    Route::post('/update/{id}',[ProductController::class, 'update']);
    Route::delete('/delete/{id}',[ProductController::class, 'destroy']);


    Route::get('/get-products', [ProductController::class, 'getData']);

    Route::get('/getbuy/{id}',[BuyController::class,'getBuy']);

    Route::post('/buy/{id}',[BuyController::class,'ToggleBuy']);

    Route::get('/getBuyAll',[BuyController::class,'getBuyAll']);

    Route::get('/getOwners',[ProductController::class, 'Owner']);

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login',[AuthController::class, 'login']);
    Route::get('me',[AuthController::class, 'me']);
    Route::post('register',[AuthController::class, 'register']);
    Route::post('logout',[AuthController::class, 'logout']);
});

