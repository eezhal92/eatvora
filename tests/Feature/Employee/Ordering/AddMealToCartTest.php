<?php

namespace Tests\Feature\Employee\Ordering;

use App\Meal;
use App\Cart;
use App\Menu;
use App\User;
use App\Order;
use App\Office;
use App\Vendor;
use App\Company;
use MealFactory;
use App\Employee;
use Carbon\Carbon;
use Tests\TestCase;
use App\Services\ScheduleService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * What to assert:
 * - It should able to retrieve correct items based on current date. eg. last week has cart but not checkout, it should create new one when friday 3pm - friday 2.59pm
 * - It should not able to add item when in not schedule / correct week range
 * - It should not able to order when is employee not active
 */
class AddMealToCartTest extends TestCase
{
    use DatabaseMigrations;

    private $nextWeekDays;

    private $mondayThisWeek;

    public function setUp()
    {
        parent::setUp();

        $this->mondayThisWeek = Carbon::create(2017, 8, 14);

        Carbon::setTestNow($this->mondayThisWeek);

        $scheduleService = $this->app->make(ScheduleService::class);

        $this->nextWeekDays = $scheduleService->nextWeekDayDates();
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Example Meal',
            'price' => 25000,
            'description' => 'Example meal description',
            'contents' => 'Example meal',
        ], $overrides);
    }

    /** @test */
    function employee_can_add_meal_to_cart()
    {
        $scheduleService = $this->app->make(ScheduleService::class);

        $nextMonday = $this->nextWeekDays->first();

        $menu = factory(Menu::class)->create($this->validParams(['name' => 'Nasi Kuning']));

        MealFactory::createWithDates([$nextMonday->format('Y-m-d') => [$menu]]);

        $employee = factory(Employee::class)->create()->load('office.company');

        // Act
        $response = $this->actingAs($employee->user)
            ->withSession([
                'employee_id' => $employee->id,
                'company_id' => $employee->office->company->id
            ])
            ->json('post', '/api/v1/cart', [
                'menuId' => $menu->id,
                'date' => $nextMonday->format('Y-m-d'),
                'qty' => 2,
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Nasi Kuning',
            'qty' => (string) 2,
            'date' => $nextMonday->format('Y-m-d'),
        ]);

        // Make sure product were added with correct quantity and date
        $cart = Cart::of($employee);
        $cart->menus->assertContains($menu);

        $cartItems = $cart->items();

        $this->assertEquals($cartItems->first()->date, $nextMonday->format('Y-m-d'));
        $this->assertEquals($cartItems->first()->qty, 2);
    }

    /** @test */
    function requires_authentication()
    {
        $this->withExceptionHandling();

        $nextMonday = $this->nextWeekDays->first();

        $menu = factory(Menu::class)->create($this->validParams());

        MealFactory::createWithDates([
            $nextMonday->format('Y-m-d') => [$menu],
        ]);

        $response = $this->json('post', '/api/v1/cart', [
            'menuId' => $menu->id,
            'date' => $nextMonday->format('Y-m-d'),
            'qty' => 2,
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    function cannot_add_meal_when_cart_has_been_ordered()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create($this->validParams());

        $employee = factory(Employee::class)->create();

        $cart = Cart::of($employee);

        $order = factory(Order::class)->create();

        $cart->update(['order_id' => $order->id]);

        $response = $this->actingAs($employee->user)
            ->withSession([
                'employee_id' => $employee->id,
            ])
            ->json('post', '/api/v1/cart', [
                'menuId' => $menu->id,
                'date' => $this->nextWeekDays->first()->format('Y-m-d'),
                'qty' => 2,
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    function cannot_add_meal_that_is_not_in_next_week_schedule()
    {
        // $this->withExceptionHandling();

        $menuA = factory(Menu::class)->create($this->validParams(['name' => 'Nasi Kuning']));
        $menuB = factory(Menu::class)->create($this->validParams(['name' => 'Nasi Ayam']));

        $employee = factory(Employee::class)->create();

        $menuA->scheduleMeals($this->mondayThisWeek, 3);
        $menuB->scheduleMeals($this->mondayThisWeek->addDay(14), 3);

        $cart = Cart::of($employee);

        $responseA = $this->actingAs($employee->user)
            ->withSession([
                'employee_id' => $employee->id,
            ])
            ->json('post', '/api/v1/cart', [
                'menuId' => $menuA->id,
                'date' => $this->nextWeekDays->first()->format('Y-m-d'),
                'qty' => 2,
            ]);

        $responseA->assertStatus(422);

        $responseB = $this->actingAs($employee->user)
            ->withSession([
                'employee_id' => $employee->id,
            ])
            ->json('post', '/api/v1/cart', [
                'menuId' => $menuB->id,
                'date' => $this->nextWeekDays->first()->format('Y-m-d'),
                'qty' => 2,
            ]);

        $responseB->assertStatus(422);
    }
}
