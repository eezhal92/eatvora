<?php

namespace Tests\Unit;

use App\Cart;
use App\Meal;
use App\Menu;
use App\Balance;
use App\Employee;
use Tests\TestCase;
use App\Reservation;
use App\Lib\BalancePaymentGateway;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function calculating_the_total_cost()
    {
        $employee = factory(Employee::class)->create();
        $cart = factory(Cart::class)->create();

        $meals = collect([
            json_decode(json_encode(['final_price' => 1200])),
            json_decode(json_encode(['final_price' => 1200])),
            json_decode(json_encode(['final_price' => 1200])),
        ]);

        $reservation = new Reservation($cart, $meals, $employee);

        $this->assertEquals(3600, $reservation->totalCost());
    }

    /** @test */
    function completing_a_reservation()
    {
        config(['commission_percentage' => 0.1, 'rupiah_per_point' => 500]);

        $menu = factory(Menu::class)->create(['price' => 20000]);
        $employee = factory(Employee::class)->create();
        $cart = factory(Cart::class)->create();

        Balance::employeeTopUp($employee, 100000);

        $meals = factory(Meal::class, 3)->create(['menu_id' => $menu->id, 'price' => $menu->price]);

        $reservation = new Reservation($cart, $meals, $employee);

        $order = $reservation->complete(new BalancePaymentGateway);

        $this->assertEquals($employee->id, $order->employee_id);
        $this->assertEquals($employee->user->id, $order->user_id);
        $this->assertEquals(66000, $order->amount);
    }
}
