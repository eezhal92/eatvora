<?php

use App\Cart;
use App\Menu;
use App\User;
use App\Vendor;
use App\Company;
use App\Office;
use App\Employee;
use App\Schedule;

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

$factory->state(User::class, 'admin', function ($faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'is_admin' => 1,
    ];
});

$factory->define(Vendor::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'phone' => $faker->phoneNumber,
        'address' => $faker->streetAddress,
        'email' => $faker->safeEmail,
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

$factory->define(Office::class, function (Faker\Generator $faker) {
    return [
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'name' => $faker->company,
        'address' => 'Some address',
    ];
});

$factory->state(Office::class, 'main', function (Faker\Generator $faker) {
    return [
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'name' => $faker->company,
        'address' => 'Some address',
        'is_main' => true,
    ];
});

$factory->define(Employee::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'office_id' => function () {
            return factory(Office::class)->create()->id;
        },
    ];
});


$factory->state(Employee::class, 'admin', function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'office_id' => function () {
            return factory(Office::class)->create()->id;
        },
        'is_admin' => true,
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
