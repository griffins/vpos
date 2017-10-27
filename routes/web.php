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

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

Route::get('/', function () {
    if (auth()->check()) {
        return view('welcome');
    } else {
        return view('login');
    }
});
Route::get('profile', function () {
//    dd(auth()->user());
    return view('profile');
});
Route::auth();