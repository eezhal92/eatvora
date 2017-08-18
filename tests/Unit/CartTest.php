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
use App\Exceptions\CartItemNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @todo
 * - Consider for refactoring method signatures of addItem, updateItem, deleteItem.
 */
class CartTest extends TestCase
{
    use DatabaseMigrations;

    private $thisMonday;

    public function setUp()
    {
        parent::setUp();

        $this->thisMonday = Carbon::create(2017, 8, 14);

        Carbon::setTestNow($this->thisMonday);
    }

    private function getThisMonday()
    {
        return $this->thisMonday->copy();
    }

    /** @test */
    public function create_cart_for_next_week()
    {
        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $this->assertEquals($cart->employee_id, $employee->id);
        $this->assertEquals($cart->start_date, '2017-08-21');
        $this->assertEquals($cart->end_date, '2017-08-25');
    }

    /** @test */
    public function can_retrieve_correct_cart_for_next_week()
    {
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
        $menu = factory(Menu::class)->create();
        $menu->scheduleMeals($this->getThisMonday()->addWeek(), 4);

        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $nextModay = $this->getThisMonday()->addWeek();

        $cart->addItem($menu, 2, $nextModay);

        $this->assertDatabaseHas('cart_items', [
            'menu_id' => $menu->id,
            'qty' => 2,
            'date' => $nextModay->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function bump_qty_when_same_menu_and_date_already_exists()
    {
        $nextMonday = $this->getThisMonday()->addWeek();

        $menu = factory(Menu::class)->create();
        $menu->scheduleMeals($nextMonday, 4);

        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $cart->addItem($menu, 2, $nextMonday);
        $cart->addItem($menu, 3, $nextMonday);

        $this->assertDatabaseHas('cart_items', [
            'menu_id' => $menu->id,
            'qty' => 5,
            'date' => $nextMonday->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function able_to_retrieve_correct_cart_items()
    {
        $menuA = factory(Menu::class)->create(['name' => 'Sate Sapi']);
        $menuB = factory(Menu::class)->create(['name' => 'Kaledo Spesial']);

        $nextMonday = $this->getThisMonday()->addWeek();
        $nextTuesday = $nextMonday->copy()->addDay();

        $menuA->scheduleMeals($this->thisMonday, 5);
        $menuA->scheduleMeals($nextMonday, 5);
        $menuB->scheduleMeals($nextTuesday, 5);

        $employee = factory(Employee::class)->create();

        // Cart for this week meals
        $cartForThisWeek = factory(Cart::class)->create([
            'employee_id' => $employee->id,
            'start_date' => $this->getThisMonday()->format('Y-m-d'),
            'end_date' => $this->getThisMonday()->addDay(5)->format('Y-m-d'),
        ]);

        // We cannot use addItem to add items of this week
        $cartForThisWeek->cartItems()->create([
            'cart_id' => $cartForThisWeek->id,
            'menu_id' => $menuA->id,
            'qty' => 2,
            'date' => $this->thisMonday->format('Y-m-d'),
        ]);

        // Cart for next week meals
        $cartForNextWeek = Cart::of($employee);

        $cartForNextWeek->addItem($menuA, 2, $nextMonday);
        $cartForNextWeek->addItem($menuA, 3, $nextMonday);
        $cartForNextWeek->addItem($menuB, 1, $nextTuesday);

        $items = $cartForNextWeek->items()
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

        $nextMonday = $this->getThisMonday()->addWeek();

        $meals = $menu->scheduleMeals($nextMonday, 3);

        $order = factory(Order::class)->create();

        $order->meals()->saveMany($meals->take(2));

        $cart = Cart::of($employee);

        $cart->addItem($menu, 2, $nextMonday);

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

        $meals = $menu->scheduleMeals($this->getThisMonday()->addWeek(), 2);
        $meals->each->reserve();

        $cart = Cart::of($employee);

        $cart->addItem($menu, 1, $this->getThisMonday()->addWeek());

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

    /** @test */
    function cannot_update_non_existent_cart_item_throws_exception()
    {
        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        try {
            $cart->updateItem(1000, 2, Carbon::now());
        } catch (CartItemNotFoundException $e) {
            return;
        }

        $this->fail("Updating non existent cart item is not throwing CartItemNotFoundException");
    }

    /** @test */
    function cannot_remove_non_existent_cart_item_throws_exception()
    {
        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        try {
            $cart->removeItem(1000, Carbon::now());
        } catch (CartItemNotFoundException $e) {
            return;
        }

        $this->fail("Removing non existent cart item is not throwing CartItemNotFoundException");
    }
}
