<?php

use App\Cart;
use App\Company;
use App\Employee;
use App\Menu;
use App\Schedule;
use App\User;
use App\Vendor;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Vendor::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'capacity' => rand(50, 200),
    ];
});

$factory->define(Menu::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'price' => rand(20000, 30000),
        'description' => $faker->paragraph,
        'contents' => implode(', ', $faker->words(5)),
        'vendor_id' => function () {
            return factory(Vendor::class)->create()->id;
        },
    ];
});

$factory->define(Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(Employee::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
    ];
});

$factory->define(Cart::class, function (Faker\Generator $faker) {
    return [
        'employee_id' => function () {
            return factory(Employee::class)->create()->id;
        },
    ];
});

$factory->define(Schedule::class, function (Faker\Generator $faker) {
    return [
        
    ];
});
