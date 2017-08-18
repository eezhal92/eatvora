<?php

namespace Tests\Unit;

use Mockery;
use App\Order;
use App\Employee;
use App\Lib\Cost;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function creating_an_order_from_meals_employee_and_amount()
    {
        $employee = factory(Employee::class)->create();

        $meals = collect([
            Mockery::spy(Meal::class),
            Mockery::spy(Meal::class),
            Mockery::spy(Meal::class),
        ]);

        $mealMenus = collect([
            new MenuStub(),
            new MenuStub(),
            new MenuStub(),
        ]);

        $order = Order::forMeals($meals, $employee, new Cost($mealMenus));

        $this->assertEquals(66000, $order->amount);
        $this->assertEquals($employee->id, $order->employee_id);
        $this->assertEquals($employee->user->id, $order->user_id);
        $meals->each->shouldHaveReceived('claimFor', [$order]);
    }
}

class MenuStub
{
    public $final_price = 22000;

    public $price = 20000;
}
