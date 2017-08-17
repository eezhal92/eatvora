<?php

namespace Tests\Feature\Employee\Ordering;

use App\Menu;
use App\Cart;
use MealFactory;
use App\Employee;
use Tests\TestCase;
use App\Services\ScheduleService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RemoveMealFromCartTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_remove_cart_item()
    {
        $employee = factory(Employee::class)->create();

        session(['employee_id' => $employee->id]);

        $menu = factory(Menu::class)->create();

        $nextWeekDayDates = $this->app->make(ScheduleService::class)->nextWeekDayDates();

        MealFactory::createWithDates([
            $nextWeekDayDates->first()->format('Y-m-d') => [$menu],
        ]);

        $cart = Cart::of($employee);

        $cart->addItem($menu->id, 2, $nextWeekDayDates->first());

        $response = $this->actingAs($employee->user)->json('DELETE', '/api/v1/cart', [
            'menu_id' => $menu->id,
            'date' => $nextWeekDayDates->first()->format('Y-m-d'),
        ]);

        $this->assertNull($cart->items()->first());
    }
}
