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

Route::resource('clients', 'API\ClientController');
Route::resource('cars', 'API\CarController');
Route::resource('rentals', 'API\RentalController');
