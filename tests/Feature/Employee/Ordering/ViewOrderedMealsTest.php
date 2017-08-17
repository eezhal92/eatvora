<?php

namespace Tests\Feature\Employee\Ordering;

use App\Menu;
use App\Order;
use App\Employee;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewOrderedMealsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $now = Carbon::create(2017, 8, 14);

        Carbon::setTestNow($now);
    }

    /** @test */
    public function can_view_this_week_meals()
    {
        $employee = factory(Employee::class)->create()->load('user');

        $order = factory(Order::class)->create([
            'employee_id' => $employee->id,
            'user_id' => $employee->user_id,
        ]);

        $menuA = factory(Menu::class)->create();

        $meals = $menuA->scheduleMeals(Carbon::now(), 2);

        $order->meals()->saveMany($meals->take(2));

        $response = $this->actingAs($employee->user)->json('GET', '/api/v1/my-meals?for=this_week');

        $response->assertJsonFragment(['id' => $menuA->id, 'qty' => (string) 2]);
    }

    /** @test */
    public function can_view_next_week_meals()
    {
        $employee = factory(Employee::class)->create()->load('user');

        $order = factory(Order::class)->create([
            'employee_id' => $employee->id,
            'user_id' => $employee->user_id,
        ]);

        $menuA = factory(Menu::class)->create();

        $meals = $menuA->scheduleMeals(Carbon::now()->addWeek(), 2);

        $order->meals()->saveMany($meals->take(2));

        $response = $this->actingAs($employee->user)->json('GET', '/api/v1/my-meals?for=next_week');

        $response->assertJsonFragment(['id' => $menuA->id, 'qty' => (string) 2]);
    }
}
