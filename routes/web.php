<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
    [],
    function () {
        Route::namespace('Auth')
            ->group(function () {
                Route::get('/', 'LoginController@showLoginForm');
                Route::get('login', 'LoginController@showLoginForm')->name('login');
                Route::post('login', 'LoginController@login');
                Route::post('logout', 'LoginController@logout')->name('logout');
                Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
                Route::post('register', 'RegisterController@register');

                Route::get('api_token', 'AuthController@getApiToken')->name('api_token');
            });


        Route::get('/dash', 'DashboardController@index')->name('dash');

        Route::resource('collections', 'CollectionController');

        Route::get('{user}/collections', 'CollectionController@showUser')->name('collections.show.user');
        Route::get('users', 'UserController@index')->name('users.index');

        Route::get('/logs', 'LogController@index')->name('logs.index');
    }
);
