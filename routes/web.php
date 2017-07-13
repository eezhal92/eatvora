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
    return view('home');
});

Route::get('/meals/{date}/{menuId}', 'Employee\MealController@show');

Auth::routes();

Route::group(['middleware' => ['auth', 'company']], function () {

    Route::get('/cart', 'Employee\CartController@index');

    Route::get('/meals', 'Employee\MealController@index');

    Route::get('/meals/{date}/{mealId}', 'Employee\MealController@show');

    Route::get('/home', 'HomeController@index')->name('home');

});



Route::group(['prefix' => 'api/v1', 'namespace' => 'Api\V1', 'middleware' => 'auth'], function () {

    Route::get('/meals', 'MealController@index');

    Route::post('/cart', 'CartController@store');

    Route::get('/cart', 'CartController@index');

});
