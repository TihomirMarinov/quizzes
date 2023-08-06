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

Route::group(['prefix' => 'quizs', 'as' => 'quiz.'], function() {
    Route::get('/questions', 'QuizQuestionController@index')->name('index.questions');
    Route::post('/start', 'QuizController@start')->name('start');
    Route::post('/finish/{id}', 'QuizController@finish')->name('finish');
    Route::get('/results/{id}', 'QuizController@show')->name('show.results');
});

Route::group(['middleware' => ['auth:api']], function() {
    // Quizs endpoints

    Route::group(['prefix' => 'quizs', 'as' => 'quiz.'], function() {
        Route::get('/results', 'QuizController@index')->name('index.results');
        Route::get('/questions/{id}', 'QuizQuestionController@show')->name('show.questions');
        Route::post('/questions', 'QuizQuestionController@store')->name('store.questions');
        Route::post('/questions/{id}', 'QuizQuestionController@update')->name('update.questions');
        Route::delete('/questions/{id}', 'QuizQuestionController@destroy')->name('destroy');
        Route::delete('/results/{id}', 'QuizController@destroy')->name('destroy.results');
    });
});