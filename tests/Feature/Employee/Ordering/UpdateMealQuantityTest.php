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

/**
 * @todo
 * - cannot update meal quantity with negative number
 */
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

        $cart->addItem($menu->id, 2, $nextWeekDayDates->first());

        $response = $this->actingAs($employee->user)->json('PATCH', '/api/v1/cart', [
            'menu_id' => $menu->id,
            'qty' => 1,
            'date' => $nextWeekDayDates->first()->format('Y-m-d'),
        ]);

        $this->assertEquals(1, $cart->items()->first()->qty);
    }
}
