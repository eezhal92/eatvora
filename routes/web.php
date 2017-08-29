<?php

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/register', function () {
    abort(404);
});

Route::get('/logout', 'Auth\LoginController@logout');

Route::group(['middleware' => ['auth', 'company'], 'namespace' => 'Employee'], function () {

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/meals', 'MealController@index');

    Route::get('/meals/{menuId}', 'MealController@show');

    Route::get('/cart', 'CartController@index');

    Route::get('/profile', 'ProfileController@show')->name('profile.show');

    Route::post('/profile', 'ProfileController@store')->name('profile.store');

    Route::get('/change-password', 'ChangePasswordController@show')->name('change-password.show');

    Route::post('/change-password', 'ChangePasswordController@store')->name('change-password.store');

});

Route::group(['prefix' => '/ap', 'namespace' => 'Admin'], function () {

    Route::get('/login', 'AuthController@showLoginForm');

    Route::post('/login', 'AuthController@login');

    Route::group(['middleware' => ['eatvora-admin']], function () {

        Route::get('/dashboard', 'DashboardController@index');

        Route::get('/companies/create', 'CompanyController@create');

        Route::get('/companies', 'CompanyController@index');

        Route::post('/companies', 'CompanyController@store');

        Route::get('/companies/{id}', 'CompanyController@show');

        Route::get('/companies/{id}/edit', 'CompanyController@edit');

        Route::patch('/companies/{id}', 'CompanyController@update');

        Route::get('/companies/{companyId}/offices/create', 'OfficeController@create');

        Route::post('/companies/{companyId}/offices', 'OfficeController@store');

        Route::get('/companies/{companyId}/offices/{id}/edit', 'OfficeController@edit');

        Route::patch('/companies/{companyId}/offices/{id}', 'OfficeController@update');

        Route::get('/companies/{companyId}/employees', 'CompanyController@employees');

        Route::get('/companies/{companyId}/payments', 'CompanyController@payments');

        Route::get('/vendors', 'VendorController@index');

        Route::post('/vendors', 'VendorController@store');

        Route::get('/vendors/create', 'VendorController@create');

        Route::get('/vendors/{id}', 'VendorController@show');

        Route::get('/vendors/{id}/edit', 'VendorController@edit');

        Route::patch('/vendors/{id}', 'VendorController@update');

        Route::get('/vendors/{id}/orders', 'VendorController@order');

        Route::get('/menus', 'MenuController@index');

        Route::get('/menus/create', 'MenuController@create');

        Route::post('/menus', 'MenuController@store');

        Route::get('/menus/{id}', 'MenuController@show');

        Route::get('/menus/{id}/edit', 'MenuController@edit');

        Route::patch('/menus/{id}', 'MenuController@update');

        Route::get('/payments', 'PaymentController@index');

        Route::get('/orders', 'OrderController@index');

        Route::get('/orders/{id}', 'OrderController@show');

        Route::get('/schedules/create', 'ScheduleController@create');

        Route::get('/schedules', 'ScheduleController@index');

        Route::post('/schedules', 'ScheduleController@store');

        Route::get('/users/{id}/balances', 'UserController@balance');

        Route::get('/categories', 'CategoryController@index');

        Route::post('/categories', 'CategoryController@store');

        Route::get('/categories/{id}/edit', 'CategoryController@edit');

        Route::patch('/categories/{id}', 'CategoryController@update');

        Route::delete('/categories/{id}', 'CategoryController@destroy');
    });
});

Route::group(['prefix' => 'api/v1', 'namespace' => 'Api\V1', 'middleware' => 'auth'], function () {

    Route::get('/meals', 'MealController@index');

    Route::post('/cart', 'CartController@store');

    Route::get('/cart', 'CartController@index');

    Route::patch('/cart', 'CartController@update');

    Route::delete('/cart', 'CartController@remove');

    Route::get('/employees', 'EmployeeController@index');

    Route::get('/menus', 'MenuController@index');

    Route::post('/orders', 'OrderController@store');

    Route::get('/my-meals', 'MyMealController@index');

    Route::get('/categories', 'CategoryController@index');

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
