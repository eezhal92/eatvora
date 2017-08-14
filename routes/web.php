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

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::group(['middleware' => ['auth', 'company']], function () {

    Route::get('/meals/{date}/{menuId}', 'Employee\MealController@show');

    Route::get('/cart', 'Employee\CartController@index');

    Route::get('/meals', 'Employee\MealController@index');

    Route::get('/meals/{date}/{mealId}', 'Employee\MealController@show');

    Route::get('/home', 'HomeController@index')->name('home');

});

Route::group(['prefix' => '/ap'], function () {

    Route::get('/login', 'Admin\AuthController@showLoginForm');

    Route::post('/login', 'Admin\AuthController@login');

    Route::group(['middleware' => ['eatvora-admin']], function () {

        Route::get('/dashboard', 'Admin\DashboardController@index');

        Route::get('/companies/create', 'Admin\CompanyController@create');

        Route::get('/companies', 'Admin\CompanyController@index');

        Route::post('/companies', 'Admin\CompanyController@store');

        Route::get('/companies/{id}', 'Admin\CompanyController@show');

        Route::get('/companies/{id}/edit', 'Admin\CompanyController@edit');

        Route::patch('/companies/{id}', 'Admin\CompanyController@update');

        Route::get('/companies/{companyId}/offices/create', 'Admin\OfficeController@create');

        Route::post('/companies/{companyId}/offices', 'Admin\OfficeController@store');

        Route::get('/companies/{companyId}/offices/{id}/edit', 'Admin\OfficeController@edit');

        Route::patch('/companies/{companyId}/offices/{id}', 'Admin\OfficeController@update');

        Route::get('/companies/{companyId}/employees', 'Admin\CompanyController@employees');

        Route::get('/companies/{companyId}/payments', 'Admin\CompanyController@payments');

        Route::get('/vendors', 'Admin\VendorController@index');

        Route::post('/vendors', 'Admin\VendorController@store');

        Route::get('/vendors/create', 'Admin\VendorController@create');

        Route::get('/vendors/{id}', 'Admin\VendorController@show');

        Route::get('/vendors/{id}/edit', 'Admin\VendorController@edit');

        Route::patch('/vendors/{id}', 'Admin\VendorController@update');

        Route::get('/menus', 'Admin\MenuController@index');

        Route::get('/menus/create', 'Admin\MenuController@create');

        Route::post('/menus', 'Admin\MenuController@store');

        Route::get('/menus/{id}', 'Admin\MenuController@show');

        Route::get('/menus/{id}/edit', 'Admin\MenuController@edit');

        Route::patch('/menus/{id}', 'Admin\MenuController@update');

        Route::get('/payments', 'Admin\PaymentController@index');
    });
});

Route::group(['prefix' => 'api/v1', 'namespace' => 'Api\V1', 'middleware' => 'auth'], function () {

    Route::get('/meals', 'MealController@index');

    Route::post('/cart', 'CartController@store');

    Route::get('/cart', 'CartController@index');

    Route::patch('/cart', 'CartController@update');

    Route::get('/employees', 'EmployeeController@index');

    Route::get('/menus', 'MenuController@index');

    Route::group(['middleware' => ['eatvora-admin']], function () {

        Route::post('/employees', 'EmployeeController@store');

        Route::post('/employees/bulk', 'EmployeeController@bulkStore');

        Route::patch('/employees/{id}', 'EmployeeController@update');

        Route::get('/employees-count', 'EmployeeController@employeeCount');

        Route::patch('/employees/{id}/active', 'EmployeeController@updateActive');

        Route::delete('/employees/{id}', 'EmployeeController@delete');

        Route::delete('/menus/{id}', 'MenuController@destroy');

        Route::get('/companies/{id}/offices', 'OfficeController@index');

        Route::post('/balances', 'BalanceController@store');

        Route::patch('/payments/{id}', 'PaymentController@update');

    });

});
