<?php

namespace Tests\Feature\Employee\Ordering;

use App\Cart;
use App\Menu;
use MealFactory;
use App\Employee;
use Tests\TestCase;
use App\Services\ScheduleService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateMealQuantityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_update_cart_item_quantity()
    {
        $employee = factory(Employee::class)->create();

        session(['employee_id' => $employee->id]);

        $menu = factory(Menu::class)->create();

        $nextWeekDayDates = $this->app->make(ScheduleService::class)->nextWeekDayDates();

        MealFactory::createWithDates([
            $nextWeekDayDates->first()->format('Y-m-d') => [$menu],
        ]);

        $cart = Cart::of($employee);

        $cart->addItem($menu, 2, $nextWeekDayDates->first());

        $response = $this->actingAs($employee->user)->json('PATCH', '/api/v1/cart', [
            'menu_id' => $menu->id,
            'qty' => 1,
            'date' => $nextWeekDayDates->first()->format('Y-m-d'),
        ]);

        $this->assertEquals(1, $cart->items()->first()->qty);
    }

    /** @test */
    public function cannot_update_nonexistent_cart_item()
    {
        $this->withExceptionHandling();

        $employee = factory(Employee::class)->create();

        session(['employee_id' => $employee->id]);

        $menu = factory(Menu::class)->create();

        $nextWeekDayDates = $this->app->make(ScheduleService::class)->nextWeekDayDates();

        MealFactory::createWithDates([
            $nextWeekDayDates->first()->format('Y-m-d') => [$menu],
        ]);

        $cart = Cart::of($employee);

        $cart->addItem($menu, 2, $nextWeekDayDates->first());

        $response = $this->actingAs($employee->user)->json('PATCH', '/api/v1/cart', [
            'menu_id' => 999,
            'qty' => 'not-valid-number',
            'date' => $nextWeekDayDates->first()->format('Y-m-d'),
        ]);

        $response->assertStatus(422);
        $this->assertEquals(2, $cart->items()->first()->qty);
    }

    /** @test */
    public function update_cart_item_quantity_must_be_valid_number()
    {
        $this->withExceptionHandling();

        $employee = factory(Employee::class)->create();

        session(['employee_id' => $employee->id]);

        $menu = factory(Menu::class)->create();

        $nextWeekDayDates = $this->app->make(ScheduleService::class)->nextWeekDayDates();

        MealFactory::createWithDates([
            $nextWeekDayDates->first()->format('Y-m-d') => [$menu],
        ]);

        $cart = Cart::of($employee);

        $cart->addItem($menu, 2, $nextWeekDayDates->first());

        $response = $this->actingAs($employee->user)->json('PATCH', '/api/v1/cart', [
            'menu_id' => $menu->id,
            'qty' => 'not-valid-number',
            'date' => $nextWeekDayDates->first()->format('Y-m-d'),
        ]);

        $response->assertStatus(422);
        $this->assertEquals(2, $cart->items()->first()->qty);
    }

    /** @test */
    public function cannot_update_cart_item_quantity_with_zero_number()
    {
        $this->withExceptionHandling();

        $employee = factory(Employee::class)->create();

        session(['employee_id' => $employee->id]);

        $menu = factory(Menu::class)->create();

        $nextWeekDayDates = $this->app->make(ScheduleService::class)->nextWeekDayDates();

        MealFactory::createWithDates([
            $nextWeekDayDates->first()->format('Y-m-d') => [$menu],
        ]);

        $cart = Cart::of($employee);

        $cart->addItem($menu, 2, $nextWeekDayDates->first());

        $response = $this->actingAs($employee->user)->json('PATCH', '/api/v1/cart', [
            'menu_id' => $menu->id,
            'qty' => 0,
            'date' => $nextWeekDayDates->first()->format('Y-m-d'),
        ]);

        $response->assertStatus(422);
        $this->assertEquals(2, $cart->items()->first()->qty);
    }
}
