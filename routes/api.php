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

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {
    Route::post('/login', 'AuthController@login')->name('login');
    Route::post('/logout', 'AuthController@logout')->middleware(['auth:api'])->name('logout');
});

Route::group(['prefix' => 'rents', 'as' => 'rent.'], function() {
    Route::get('/', 'RentController@index')->name('index');
});

Route::group(['middleware' => ['auth:api']], function() {
    // Rents endpoints
    Route::group(['prefix' => 'rents', 'as' => 'rent.'], function() {
        Route::get('/{id}', 'RentController@show')->name('show');
        Route::post('/', 'RentController@store')->name('store');
        Route::post('/{id}', 'RentController@update')->name('update');
        Route::delete('/{id}', 'RentController@destroy')->name('destroy');
    });
});