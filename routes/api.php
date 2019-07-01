<?php

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

Route::namespace('Api')
    ->middleware('auth:api')
    ->group(function () {
        Route::post('resource/search', 'ResourceController@search');

        Route::resource('collections', 'CollectionController')->except(['create', 'edit']);

        Route::get('personal/collections', 'CollectionController@indexPersonal')->name('collections.personal.index');

        Route::post('logs', 'LogController@store')->name('logs.store');
    });
