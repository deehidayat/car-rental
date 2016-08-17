<?php

// use App\Http\Controllers\API\ClientController as APIClientController;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('cars/free', 'API\CarController@free');

Route::resource('clients', 'API\ClientController');
Route::resource('cars', 'API\CarController');
Route::resource('rentals', 'API\RentalController');

Route::get('histories/client/{id}', 'API\ClientController@histories');
Route::get('histories/car/{id}', 'API\CarController@histories');
