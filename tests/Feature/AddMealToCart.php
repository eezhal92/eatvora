<?php

namespace Tests\Feature;

use App\Cart;
use App\Company;
use App\Employee;
use App\Menu;
use App\Schedule;
use App\Services\ScheduleService;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

/**
 * What to assert:
 * - It should not able to add 3 item in one day
 * - It should not able to add item when vendor capacity is exceeded
 * - It should not able to add item when not in correct week range
 * - It should not able to add item when in not schedule
 * - It should not able to checkout when the cart is less than 3 day (It should be in dedicated CheckoutTest)
 */
class AddMealToCart extends TestCase
{
    use DatabaseMigrations;

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

        $schedule = factory(Schedule::class)->create([
            'menu_id' => $menu->id,
            'date' => $nextMonday->format('Y-m-d'),
        ]);

        $company = factory(Company::class)->create([
            'name' => 'Traveloka',
        ]);

        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);

        // Act
        $response = $this->json('post', '/api/v1/cart', [
            'employeeId' => $employee->id,
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
                    'date' => $nextMonday->format('Y-m-d H:i:s'),
                ]
            ]); // todo maybe should create another assertion method like seeJsonSubset

        // Make sure product were added with correct quantity and date
        $cart = Cart::of($employee);
        $cart->menus->assertContains($menu);

        $cartItems = $cart->items();

        $this->assertEquals($cartItems->first()->date, $nextMonday->format('Y-m-d H:i:s'));
        $this->assertEquals($cartItems->first()->qty, 2);
    }
}
