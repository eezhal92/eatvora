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

Route::get('/meals', 'Employee\MealController@index');

Route::get('/something', function () {
    dd(auth()->user());
});

Route::get('/meals/{date}/{menuId}', 'Employee\MealController@show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
