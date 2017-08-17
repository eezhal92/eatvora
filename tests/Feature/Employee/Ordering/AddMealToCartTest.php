<?php

namespace Tests\Feature\Employee\Ordering;

use App\User;
use App\Menu;
use App\Cart;
use App\Vendor;
use App\Office;
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

    private $nextWeekDayDates;

    public function setUp()
    {
        parent::setUp();

        $scheduleService = $this->app->make(ScheduleService::class);

        $this->nextWeekDayDates = $scheduleService->nextWeekDayDates();
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
    public function employee_can_add_meal_to_cart()
    {
        $scheduleService = $this->app->make(ScheduleService::class);

        $nextMonday = $this->nextWeekDayDates->first();

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
    public function requires_authentication_with()
    {
        $this->withExceptionHandling();

        $nextMonday = $this->nextWeekDayDates->first();

        $menu = factory(Menu::class)->create($this->validParams(['name' => 'Nasi Kuning']));

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
}
