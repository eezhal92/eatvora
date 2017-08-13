<?php

namespace Tests\Unit;

use Mockery;
use App\Meal;
use App\Order;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MealTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_meal_can_be_claimed_for_an_order()
    {
        $employee = factory(Employee::class)->create();
        $order = Order::create([
            'user_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'amount' => 50000,
        ]);
        $meal = factory(Meal::class)->create();

        $meal->claimFor($order);

        $this->assertContains($meal->id, $order->meals->pluck('id'));
    }
}
