<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function(){
    Route::post('register' , [App\Http\Controllers\users::class , 'register']);
});


Route::prefix('products')->group(function(){
    Route::post('/' , [App\Http\Controllers\products::class , 'store']);
    Route::get('/' , [App\Http\Controllers\products::class , 'indx']);
    Route::get('/{productId}' , [App\Http\Controllers\products::class , 'show']);
    Route::post('/search' , [App\Http\Controllers\products::class , 'search']);
    Route::delete('/{productId}' , [App\Http\Controllers\products::class , 'destroy']);
    Route::put('/{productId}' , [App\Http\Controllers\products::class , 'update']);
});

