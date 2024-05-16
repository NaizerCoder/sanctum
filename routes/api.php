<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth:sanctum', 'namespace' => 'App\Http\Controllers'], function(){

    Route::get('/get','GetController');

    Route::group(['prefix'=>'posts', 'namespace'=>'Dropzone\Post'], function(){

        Route::post('/','StoreController');
        Route::get('/','IndexController');
        Route::patch('/{post}','UpdateController');

        Route::group(['prefix'=>'images', 'namespace'=>'Image'], function(){
            Route::post('/','StoreController');

        });
    });


});


