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
}
