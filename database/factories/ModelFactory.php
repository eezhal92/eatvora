<?php

use App\Cart;
use App\Meal;
use App\Menu;
use App\User;
use App\Order;
use App\Office;
use App\Vendor;
use App\Company;
use App\Category;
use App\Employee;
use Carbon\Carbon;
use App\CompanyPayment;
use App\Services\ScheduleService;

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
    $schedule = new ScheduleService();
    $dates = $schedule->nextWeekDayDates();

    return [
        'employee_id' => function () {
            return factory(Employee::class)->create()->id;
        },
        'start_date' => $dates->first()->format('Y-m-d'),
        'end_date' => $dates->last()->format('Y-m-d'),
    ];
});

$factory->define(CompanyPayment::class, function (Faker\Generator $faker) {
    $employeeCount = rand(100, 200);
    $amountPerEmployee = rand(20000, 50000);
    $totalAmount = $employeeCount * $amountPerEmployee;

    return [
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'employee_count' => $employeeCount,
        'amount_per_employee' => $amountPerEmployee,
        'total_amount' => $totalAmount,
        'note' => $faker->paragraph,
    ];
});

$factory->define(Meal::class, function (Faker\Generator $faker) {
    $meal = factory(Menu::class)->create();
    return [
        'date' => Carbon::now(),
        'menu_id' => function () use ($meal) {
            return $meal->id;
        },
        'price' => function () use ($meal) {
            return $meal->price;
        },
    ];
});

$factory->define(Order::class, function (Faker\Generator $faker) {
    $employee = factory(Employee::class)->create();

    return [
        'employee_id' => function () use ($employee) {
            return $employee->id;
        },
        'user_id' => function () use ($employee)  {
            return $employee->user->id;
        },
        'amount' => 220000,
        'vendor_bill' => 200000,
        'revenue' => 20000,
        'commission_percentage_per_meal' => '0.1',
        'delivery_address' => 'Fake delivery address',
    ];
});

$factory->define(Category::class, function (Faker\Generator $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});

