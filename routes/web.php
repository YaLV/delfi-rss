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
    if(Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/register/success', 'Auth\RegisterController@showThankYou')->name('thankyou');
Route::post('/verifyEmail', 'Auth\RegisterController@checkUserExistance')->name('verifyEmail');
Route::get('/register/verify/{token}', 'Auth\RegisterController@verifyEmail')->name('validate');