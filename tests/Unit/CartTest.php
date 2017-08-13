<?php

namespace Tests\Unit;

use App\Cart;
use App\Employee;
use App\Menu;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseMigrations;

    /** @atest */
    public function able_to_retrieve_a_cart_by_employee()
    {
        $employee = factory(Employee::class)->create();
        $cart = factory(Cart::class)->create([
            'employee_id' => $employee->id,
        ]);

        $foundCart = Cart::of($employee);

        $this->assertEquals($cart->id, $foundCart->id);

        $employeeB = factory(Employee::class)->create();

        $foundCart = Cart::of($employeeB);

        $this->assertNull($foundCart);
    }

    /** @atest */
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
            'date' => $nextModay->format('Y-m-d H:i:s'),
        ]);
    }

    /** @atest */
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
    public function can_retrieve_meals()
    {
        $menuA = factory(Menu::class)->create([
            'price' => 20000,
        ]);

        $menuA->scheduleMeals('2017-08-7', 20);

        $pretendedCurrentDate = Carbon::create(2017, 8, 7);

        Carbon::setTestNow($pretendedCurrentDate);

        $menuB = factory(Menu::class)->create([
            'price' => 30000,
        ]);
        $menuC = factory(Menu::class)->create();

        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $nextMonday = Carbon::parse();

        $menuA->scheduleMeals('2017-08-14', 20);
        $menuB->scheduleMeals('2017-08-17', 20);

        $cart->addItem($menuA, 2, '2017-08-14');
        $cart->addItem($menuB, 1, '2017-08-17');

        $meals = $cart->meals();

        $this->assertEquals(3, $meals->count());
        $this->assertContains($menuA->id, $meals->pluck('menu_id'));
        $this->assertContains($menuB->id, $meals->pluck('menu_id'));
        $this->assertNotContains($menuC->id, $meals->pluck('menu_id'));
    }
}
