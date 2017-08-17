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
 * - It should not able to add item when in not schedule
 * - It should not able to order when is not active
 * - It should not able to add item when not in correct week range
 * - It should not able to add 3 item in one day
 * - It should not able to add item when vendor capacity is exceeded (It should be in dedicated CheckoutTest)
 * - It should not able to checkout when the cart is less than 3 day (It should be in dedicated CheckoutTest)
 * - It should not able to checkout when user balance is not enough total order (It should be in dedicated CheckoutTest)
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

        $nextWeekDayDates = $scheduleService->nextWeekDayDates();

        $nextMonday = $nextWeekDayDates->first();

        $vendor = factory(Vendor::class)->create();

        $user = factory(User::class)->create([
            'name' => 'John Doe',
        ]);

        $menu = factory(Menu::class)->create($this->validParams([
            'name' => 'Nasi Kuning',
            'vendor_id' => $vendor->id,
        ]));

        MealFactory::createWithDates([
            $nextMonday->format('Y-m-d') => [$menu],
        ]);

        $company = factory(Company::class)->create([
            'name' => 'Traveloka',
        ]);

        $office = factory(Office::class)->create([
            'company_id' => $company->id,
        ]);

        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
            'office_id' => $office->id,
        ]);

        // Act
        $response = $this->actingAs($user)
            ->withSession([
                'employee_id' => $employee->id,
                'company_id' => $company->id
            ])
            ->json('post', '/api/v1/cart', [
                'menuId' => $menu->id,
                'date' => $nextMonday->format('Y-m-d'),
                'qty' => 2,
            ]);

        // Assert
        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'name' => 'Nasi Kuning',
                    'qty' => 2,
                    'date' => $nextMonday->format('Y-m-d'),
                ]
            ]); // todo maybe should create another assertion method like seeJsonSubset

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

        $nextWeekDayDates = $this->nextWeekDayDates;

        $nextMonday = $nextWeekDayDates->first();

        $vendor = factory(Vendor::class)->create();

        $menu = factory(Menu::class)->create($this->validParams([
            'name' => 'Nasi Kuning',
            'vendor_id' => $vendor->id,
        ]));

        MealFactory::createWithDates([
            $nextMonday->format('Y-m-d') => [$menu],
        ]);

        // Act
        $response = $this->json('post', '/api/v1/cart', [
            'menuId' => $menu->id,
            'date' => $nextMonday->format('Y-m-d'),
            'qty' => 2,
        ]);

        // Assert
        $response
            ->assertStatus(401);
    }
}
