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
        Route::post('resource/search', 'ResourceController@search')->name('resource.search');

        Route::resource('collections', 'CollectionController')->except(['create', 'edit']);

        Route::get('personal/collections', 'CollectionController@indexPersonal')->name('collections.personal.index');
        Route::get('personal/logs', 'LogController@indexPersonal')->name('logs.personal.index');

        Route::get('{user}/collections', 'CollectionController@showUser')->name('collections.show.user');

        Route::post('logs', 'LogController@store')->name('logs.store');
    });
