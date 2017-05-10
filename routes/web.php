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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', 'HomeController@index');
Route::get('/send', 'HomeController@send');
Route::post('/send', 'HomeController@doSend')->name('send');;
Route::get('/response/{org}', 'GuestController@respond')->name('respond');
Route::post('/response/{org}', 'GuestController@responded')->name('respond.save');
Route::get('/organizations', 'HomeController@organizations')->name('organizations');
Route::get('/organizations/{id}/process', 'HomeController@process')->name('process');