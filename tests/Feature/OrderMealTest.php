<?php

namespace Tests\Feature;

use App\User;
use App\Menu;
use App\Cart;
use App\Order;
use App\Balance;
use App\Employee;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderMealTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $employee;

    private $cart;

    private $meals;

    public function setUp()
    {
        parent::setUp();

        $this->setEmployeeAndCart();
    }

    private function setEmployeeAndCart()
    {
        $this->user = factory(User::class)->create();

        $this->employee = factory(Employee::class)->create([
            'user_id' => $this->user->id,
        ]);

        $this->cart = Cart::of($this->employee);
    }

    private function setMealSchedule()
    {
        $menus = $this->menus();

        $this->meals = collect([
            [
                'menu' => $menus['Burger'],
                'date' => '2017-08-14',
                'cart_qty' => 1,
            ],
            [
                'menu' => $menus['Nasi Goreng'],
                'date' => '2017-08-14',
                'cart_qty' => 2,
            ],
            [
                'menu' => $menus['Sate Kambing'],
                'date' => '2017-08-15',
                'cart_qty' => 1,
            ],
            [
                'menu' => $menus['Salad Buah'],
                'date' => '2017-08-16',
                'cart_qty' => 1,
            ],
            [
                'menu' => $menus['Kebab Turki'],
                'date' => '2017-08-17',
                'cart_qty' => 1,
            ],
            [
                'menu' => $menus['Bento Deluxe'],
                'date' => '2017-08-18',
                'cart_qty' => 1,
            ],
        ])->map(function ($item) {
            $item['menu']->scheduleMeals($item['date'], 20);

            return $item;
        });
    }

    private function addMealsToCart()
    {
        $this->meals->each(function ($item) {
            $this->cart->addItem($item['menu'], $item['cart_qty'], Carbon::parse($item['date']));
        });
    }


    private function menus()
    {
        return collect([
            'Burger' =>  18000,
            'Nasi Goreng' => 20000,
            'Sate Kambing' => 25000,
            'Salad Buah' => 30000,
            'Kebab Turki' => 19000,
            'Bento Deluxe' => 22000,
        ])->map(function ($price, $name) {
            return factory(Menu::class)->create(compact('name', 'price'));
        });
    }

    /** @test */
    public function user_can_order_meals_in_cart()
    {
        Balance::employeeTopUp($this->employee, 200000);

        $this->setMealSchedule();

        $this->addMealsToCart();

        $response = $this->actingAs($this->user)->json('post', '/api/v1/orders', [
            'employee_id' => $this->employee->id,
        ]);

        $order = Order::first();

        $orderAmount = $this->meals->reduce(function ($total, $item) {
            return $total + $item['cart_qty'] * $item['menu']->final_price;
        }, 0);

        $vendorBill = $this->meals->reduce(function ($total, $item) {
            return $total + $item['cart_qty'] * $item['menu']->price;
        }, 0);

        $revenue = $orderAmount - $vendorBill;

        $balance = Balance::all()->last();

        $this->assertEquals(7, $order->meals()->count());
        // assert order amount
        // assert vendor bill of order
        // assert revenue of order
    }
}
