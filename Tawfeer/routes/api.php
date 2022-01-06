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
    Route::post('login' , [App\Http\Controllers\users::class , 'login']);
});


Route::middleware(['auth:api'])->group(function(){
    // auth route
    Route::prefix('auth')->group(function(){
        Route::get('/profile' , [App\Http\Controllers\users::class , 'profile']);
        Route::get('/logout' , [App\Http\Controllers\users::class , 'logout']);
        Route::get('/' , [App\Http\Controllers\users::class , 'checkToken']);
        Route::get('/{userId}' , [App\Http\Controllers\users::class , 'getUser']);
        Route::post('/updatePhoto' , [App\Http\Controllers\users::class , 'updatePhoto']);
    });
    // product Route
    Route::prefix('products')->group(function(){
        Route::get('/' , [App\Http\Controllers\products::class , 'index']);
        Route::post('/' , [App\Http\Controllers\products::class , 'store']);
        Route::get('/myProducts' , [App\Http\Controllers\products::class , 'myProducts']);
        Route::get('/{productId}' , [App\Http\Controllers\products::class , 'show']);
        Route::delete('/{productId}' , [App\Http\Controllers\products::class , 'destroy']);
        Route::post('/{productId}' , [App\Http\Controllers\products::class , 'update']);
        Route::post('/addComment/{productId}' , [App\Http\Controllers\products::class , 'comment']);
        Route::get('/like/{productId}', [App\Http\Controllers\products::class , 'like']);
        Route::get('/getComments/{productId}' , [App\Http\Controllers\products::class , 'showComments']);
    });
});
