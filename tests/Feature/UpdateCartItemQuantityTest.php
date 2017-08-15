<?php

namespace Tests\Feature;

use App\Menu;
use App\Cart;
use App\Employee;
use App\Schedule;
use Tests\TestCase;
use App\Services\ScheduleService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @todo
 * Test input validation like negative qty number
 */
class UpdateCartItemQuantityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_update_cart_item_quantity()
    {
        $employee = factory(Employee::class)->create();

        session(['office_id' => $employee->office->id]);

        $menu = factory(Menu::class)->create();

        $nextWeekDayDates = $this->app->make(ScheduleService::class)->nextWeekDayDates();

        $schedule = factory(Schedule::class)->create([
            'menu_id' => $menu->id,
            'date' => $nextWeekDayDates->first()->format('Y-m-d'),
        ]);

        $cart = Cart::of($employee);

        $cart->addItem($menu->id, 2, $nextWeekDayDates->first());

        $response = $this->actingAs($employee->user)->json('PATCH', '/api/v1/cart', [
            'menu_id' => $menu->id,
            'qty' => 1,
            'date' => $nextWeekDayDates->first()->format('Y-m-d'),
        ]);

        $this->assertEquals(1, $cart->items()->first()->qty);
    }
}
