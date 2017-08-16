<?php

namespace Tests\Unit;

use App\Cart;
use App\Menu;
use App\Order;
use App\Employee;
use Carbon\Carbon;
use Tests\TestCase;
use App\Services\ScheduleService;
use App\Exceptions\NotEnoughMealsException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @todo
 * - Assert given example. get/create cart for next week, not get cart for this week or past weeks
 */
class CartTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function create_cart_for_next_week()
    {
        $pretendedCurrentDate = Carbon::create(2017, 8, 14);

        Carbon::setTestNow($pretendedCurrentDate);

        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $this->assertEquals($cart->employee_id, $employee->id);
        $this->assertEquals($cart->start_date, '2017-08-21');
        $this->assertEquals($cart->end_date, '2017-08-25');
    }

    /** @test */
    public function can_retrieve_cart_for_next_week()
    {
        $pretendedCurrentDate = Carbon::create(2017, 8, 14);

        Carbon::setTestNow($pretendedCurrentDate);

        $schedule = app()->make(ScheduleService::class);

        $currentWeekDays = $schedule->nextWeekDayDates()->map->subWeek();
        $lastWeekDays = $schedule->nextWeekDayDates()->map->subWeek(2);

        $employee = factory(Employee::class)->create();

        // create this week and last week cart
        factory(Cart::class)->create([
            'employee_id' => $employee->id,
            'start_date' => $lastWeekDays->first()->format('Y-m-d'),
            'end_date' => $lastWeekDays->last()->format('Y-m-d'),
        ]);

        factory(Cart::class)->create([
            'employee_id' => $employee->id,
            'start_date' => $currentWeekDays->first()->format('Y-m-d'),
            'end_date' => $currentWeekDays->last()->format('Y-m-d'),
        ]);

        // create next week cart
        $cart = factory(Cart::class)->create(['employee_id' => $employee->id ]);

        $foundCart = Cart::of($employee);

        $this->assertEquals($cart->id, $foundCart->id);
    }

    /** @test */
    public function able_to_add_item()
    {
        $menuA = factory(Menu::class)->create([
            'name' => 'Sate Sapi',
        ]);

        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $nextModay = Carbon::now()->addWeek()->startOfWeek();
        $cart->addItem($menuA->id, 2, $nextModay);

        $this->assertDatabaseHas('cart_items', [
            'menu_id' => $menuA->id,
            'qty' => 2,
            'date' => $nextModay->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function bump_qty_when_same_menu_and_date_already_exists()
    {
        $menuA = factory(Menu::class)->create([
            'name' => 'Sate Sapi',
        ]);

        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $nextMonday = Carbon::now()->addWeek()->startOfWeek();
        $cart->addItem($menuA->id, 2, $nextMonday);
        $cart->addItem($menuA->id, 3, $nextMonday);

        $this->assertDatabaseHas('cart_items', [
            'menu_id' => $menuA->id,
            'qty' => 5,
            'date' => $nextMonday->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function able_to_retrieve_correct_cart_items()
    {
        $menuA = factory(Menu::class)->create([
            'name' => 'Sate Sapi',
        ]);
        $menuB = factory(Menu::class)->create([
            'name' => 'Kaledo Spesial',
        ]);

        $employee = factory(Employee::class)->create();

        // This week cart
        $thisMonday = Carbon::now()->startOfWeek();
        $cart = factory(Cart::class)->create([
            'employee_id' => $employee->id,
            'start_date' => $thisMonday->format('Y-m-d'),
            'end_date' => $thisMonday->addDay(5)->format('Y-m-d'),
        ]);
        $cart->addItem($menuA->id, 90, $thisMonday);

        // Next week cart
        $cart = Cart::of($employee);

        $nextMonday = $thisMonday->copy()->addWeek();
        $nextTuesday = $nextMonday->copy()->addDay();

        $cart->addItem($menuA->id, 2, $nextMonday);
        $cart->addItem($menuA->id, 3, $nextMonday);
        $cart->addItem($menuB->id, 1, $nextTuesday->addDay());

        $items = $cart->items()
            ->map(function ($item) {
                return ['menu_id' => $item->id, 'qty' => $item->qty, 'date' => $item->date];
            })
            ->toArray();

        $this->assertEquals([
            [
                'menu_id' => $menuA->id,
                'qty' => 5,
                'date' => $nextMonday->format('Y-m-d'),
            ],
            [
                'menu_id' => $menuB->id,
                'qty' => 1,
                'date' => $nextTuesday->format('Y-m-d'),
            ],
        ], $items);
    }

    /** @test */
    public function cannot_reserve_meals_that_have_already_been_ordered()
    {
        $employee = factory(Employee::class)->create();

        $menu = factory(Menu::class)->create();

        $meals = $menu->scheduleMeals('2017-08-26', 3);
        $order = factory(Order::class)->create();

        $order->meals()->saveMany($meals->take(2));

        $cart = Cart::of($employee);

        $cart->addItem($menu, 2, '2017-08-26');

        try {
            $cart->reserveMeals();
        } catch (NotEnoughMealsException $e) {
            return;
        }

        $this->fail("Reserving tickets succeeded even though the tickets were already sold.");
    }

    /** @test */
    public function cannot_reserve_meals_that_have_already_been_reserved()
    {
        $employee = factory(Employee::class)->create();

        $menu = factory(Menu::class)->create();

        $meals = $menu->scheduleMeals('2017-08-26', 2);
        $meals->each->reserve();

        $cart = Cart::of($employee);

        $cart->addItem($menu, 1, '2017-08-26');

        try {
            $cart->reserveMeals();
        } catch (NotEnoughMealsException $e) {
            return;
        }

        $this->fail("Reserving tickets succeeded even though the tickets were already reserved.");
    }

      /** @test */
    public function determine_whether_cart_already_has_order_placed()
    {
        $menuA = factory(Menu::class)->create(['name' => 'Sate Sapi']);
        $menuB = factory(Menu::class)->create(['name' => 'Sate Ayam']);

        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $order = factory(Order::class)->create();

        $cart->update(['order_id' => $order->id]);

        $this->assertTrue($cart->already_placed_order);
    }
}
