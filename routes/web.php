<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});
Auth::routes([
    'reset' => true,
    'confirm' => true,
    'verify' => true
]);
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/credentials', 'CredentialsController@store');
    Route::get('/credentials/create', 'CredentialsController@create');
    Route::get('/credentials/{credential}', 'CredentialsController@show');
    Route::delete('/credentials/{credential}', 'CredentialsController@destroy');
    Route::patch('/credentials/{credential}', 'CredentialsController@update');
    Route::get('/credentials', 'CredentialsController@index')->name('credentials');
    Route::get('/validations/create', 'ValidationController@create');
    Route::post('/validations', 'ValidationController@store');
    Route::get('/validations', 'ValidationController@index')->name('validations');
    Route::get('/validations/{validation}', 'ValidationController@show');
    Route::delete('/validations/{validation}', 'ValidationController@destroy');
    Route::patch('/validations/{validation}', 'ValidationController@update');
    Route::get('/receivers/create', 'ReceiverController@create');
    Route::post('/receivers', 'ReceiverController@store');
    Route::get('/receivers', 'ReceiverController@index')->name('receivers');
    Route::get('/receivers/{receiver}', 'ReceiverController@show');
    Route::delete('/receivers/{receiver}', 'ReceiverController@destroy');
    Route::patch('/receivers/{receiver}', 'ReceiverController@update');
    Route::group([], function () {
        Route::get('/users', 'UserController@index')->name('users');
        Route::delete('/users/{user}', 'UserController@destroy');
    });
});
