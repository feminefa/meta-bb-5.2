<?php

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

Route::auth();
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);;
Route::get('/home', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/send', 'HomeController@send');
Route::get('/home', 'HomeController@index');
Route::get('/send', 'HomeController@send');
Route::post('/send', 'HomeController@doSend')->name('send');;
Route::get('/response/{org}', 'GuestController@respond')->name('respond');

Route::get('/upcoming', 'GuestController@upcoming')->name('inaweek');

Route::post('/response/{org}', 'GuestController@responded')->name('respond.save');
Route::get('/organizations', 'HomeController@organizations')->name('organizations');
Route::get('/organizations/{id}/process', 'HomeController@process')->name('process');
Route::auth();

