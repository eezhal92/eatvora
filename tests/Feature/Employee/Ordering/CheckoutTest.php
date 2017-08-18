<?php

namespace Tests\Feature\Employee\Ordering;

use App\Cart;
use App\Menu;
use App\User;
use App\Order;
use App\Balance;
use MenuFactory;
use App\Employee;
use Carbon\Carbon;
use Tests\TestCase;
use App\Lib\BalancePaymentGateway;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @todo
 * Refactor setup methods
 */
class CheckoutTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $employee;

    private $cart;

    private $meals;

    private $paymentGateway;

    public function setUp()
    {
        parent::setUp();

        $now = Carbon::create(2017, 8, 7);

        Carbon::setTestNow($now);

        $this->paymentGateway = new BalancePaymentGateway;

        $this->app->instance(BalancePaymentGateway::class, $this->paymentGateway);

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
            $this->cart->addItem($item['menu'], $item['cart_qty'], Carbon::parse($item['date'])->format('Y-m-d'));
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
        // $this->withExceptionHandling();

        Balance::employeeTopUp($this->employee, 200000);

        session(['employee_id' => $this->employee->id]);

        $this->setMealSchedule();

        $this->addMealsToCart();

        $response = $this->actingAs($this->user)->json('post', '/api/v1/orders');

        $response->assertStatus(200);

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
        $this->assertEquals($orderAmount, $order->amount);
        $this->assertEquals(-$orderAmount, $balance->amount);
        $this->assertEquals($vendorBill, $order->vendor_bill);
        $this->assertEquals($revenue, $order->revenue);
    }

    /** @test */
    public function cannot_order_more_meals_than_remain()
    {
        Balance::employeeTopUp($this->employee, 200000);

        $this->setMealSchedule();

        // Add meal to cart
        $item = $this->meals->first();

        $this->cart->addItem($item['menu'], 30, Carbon::parse($item['date']));

        $response = $this->actingAs($this->user)
            ->withSession(['employee_id' => $this->employee->id])
            ->json('post', '/api/v1/orders');

        $response->assertStatus(422);

        $order = Order::first();

        $balance = Balance::all()->last();

        $this->assertNull($order);
        $this->assertEquals(200000, $this->employee->user->balance());
        $this->assertEquals(200000, $balance->amount);
    }

    /** @test */
    function cannot_order_meals_another_customer_is_already_trying_to_order()
    {
        $knownDate = Carbon::create(2017, 8, 7);
        Carbon::setTestNow($knownDate);

        $menu = MenuFactory::createWithMeals(['price' => 20000], '2017-08-14', 2);

        $employeeA = factory(Employee::class)->create();

        $cart = Cart::of($employeeA);

        Balance::employeeTopUp($employeeA, 200000);

        $cart->addItem($menu, 2, '2017-08-14');

        $this->paymentGateway->beforeFirstCharge(function ($paymentGateway) use ($menu) {
            $employeeB = factory(Employee::class)->create();

            Balance::employeeTopUp($employeeB, 200000);

            $cart = Cart::of($employeeB);

            $cart->addItem($menu, 2, '2017-08-14');

            $response = $this->actingAs($employeeB->user)
                ->withSession(['employee_id' => $employeeB->id])
                ->json('post', '/api/v1/orders');

            $response->assertStatus(422);
            $this->assertEquals(0, $this->paymentGateway->totalCharges());
            // assert there is no order for this user
        });

        $response = $this->actingAs($employeeA->user)
                ->withSession(['employee_id' => $employeeA->id])
                ->json('post', '/api/v1/orders');

        $this->assertEquals(44000, $this->paymentGateway->totalCharges());
        $this->assertCount(1, Order::where('user_id', $employeeA->user_id)->get());
        $this->assertCount(2, Order::where('user_id', $employeeA->user_id)->first()->meals()->get());
    }
}
