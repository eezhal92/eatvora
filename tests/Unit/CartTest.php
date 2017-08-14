<?php

namespace Tests\Unit;

use App\Cart;
use App\Menu;
use App\Order;
use App\Employee;
use Carbon\Carbon;
use Tests\TestCase;
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
    public function able_to_retrieve_a_cart_by_employee()
    {
        $employee = factory(Employee::class)->create();
        $cart = factory(Cart::class)->create([
            'employee_id' => $employee->id,
        ]);

        $foundCart = Cart::of($employee);

        $this->assertEquals($cart->id, $foundCart->id);
    }

    /** @test */
    public function create_new_cart_when_not_found()
    {
        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $this->assertEquals($employee->id, $cart->employee_id);
    }

    /** @test */
    public function able_to_add_item()
    {
        $menuA = factory(Menu::class)->create([
            'name' => 'Sate Sapi',
        ]);

        $employee = factory(Employee::class)->create();

        $cart = Cart::create([
            'employee_id' => $employee->id,
        ]);

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

        $cart = Cart::create([
            'employee_id' => $employee->id,
        ]);

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
    public function able_to_retrieve_cart_items()
    {
        $menuA = factory(Menu::class)->create([
            'name' => 'Sate Sapi',
        ]);
        $menuB = factory(Menu::class)->create([
            'name' => 'Kaledo Spesial',
        ]);

        $employee = factory(Employee::class)->create();

        $cart = Cart::create([
            'employee_id' => $employee->id,
        ]);

        $nextMonday = Carbon::now()->addWeek()->startOfWeek();
        $nextTuesday = $nextMonday->copy()->startOfWeek();

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
}
