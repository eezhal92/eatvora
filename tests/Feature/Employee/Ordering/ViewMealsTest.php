<?php

namespace Tests\Feature\Employee\Ordering;

use App\Menu;
use App\User;
use App\Vendor;
use MealFactory;
use MenuFactory;
use App\Employee;
use App\Schedule;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @todo
 * - user can see by category
 */
class ViewMealsTest extends TestCase
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
    function requires_authentication()
    {
        $this->withExceptionHandling();

        $response = $this->get('/meals');

        $response->assertStatus(302);
    }

    /** @test */
    function requires_to_be_employee_related_to_company()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/meals');

        $response->assertStatus(302);
    }

    /** @test */
    public function user_can_view_scheduled_meal_list_for_a_specific_date()
    {
        $user = factory(User::class)->create();

        $menuA = factory(Menu::class)->create($this->validParams(['name' => 'Nasi Padang']));

        $menuB = factory(Menu::class)->create($this->validParams(['name' => 'Nasi Kuning']));

        MealFactory::createWithDates([
            '2017-06-12' => [$menuA],
            '2017-06-13' => [$menuB],
        ]);

        $responseDayOne = $this->actingAs($user)->json('GET', '/api/v1/meals?date=2017-06-12');
        $responseDayTwo = $this->actingAs($user)->json('GET', '/api/v1/meals?date=2017-06-13');

        $responseDayOne->assertStatus(200);
        $responseDayOne->assertJsonFragment(['name' => 'Nasi Padang']);
        $responseDayOne->assertJsonMissing(['name' => 'Nasi Kuning']);

        $responseDayTwo->assertStatus(200);
        $responseDayTwo->assertJsonFragment(['name' => 'Nasi Kuning']);
        $responseDayTwo->assertJsonMissing(['name' => 'Nasi Padang']);
    }

    /** @test */
    public function user_can_see_meal()
    {
        $vendor = factory(Vendor::class)->create(['name' => 'Dapur Lulu', 'capacity' => 200]);

        $menu = MenuFactory::createWithMeals($this->validParams([
            'name' => 'Nasi Padang',
            'price' => 30000,
            'vendor_id' => $vendor->id,
        ]), '2017-06-12');

        $employee = factory(Employee::class)->create()->load('user');

        $response = $this->actingAs($employee->user)->get("/meals/{$menu->id}");

        $this->assertEquals('Nasi Padang', $response->data('menu')->name);
        $this->assertEquals('Dapur Lulu', $response->data('menu')->vendor->name);
        $this->assertEquals(30000, $response->data('menu')->price);
    }

    /** @test */
    public function user_can_see_add_to_cart_button_when_it_does_in_next_week_schedule()
    {
        $knownDate = Carbon::create(2017, 8, 7);

        Carbon::setTestNow($knownDate);

        $menu = MenuFactory::createWithMeals($this->validParams(), '2017-08-14');

        $employee = factory(Employee::class)->create()->load('user');

        $response = $this->actingAs($employee->user)->get("/meals/{$menu->id}");

        $response->assertViewHas('renderAddToCartButton', true);
    }

    /** @test */
    public function user_cannot_see_add_to_cart_button_when_it_does_not_in_next_week_schedule()
    {
        $knownDate = Carbon::create(2017, 8, 7);

        Carbon::setTestNow($knownDate);

        $menuA = MenuFactory::createWithMeals($this->validParams(), '2017-08-07');

        $employee = factory(Employee::class)->create()->load('user');

        $responseA = $this->actingAs($employee->user)->get("/meals/{$menuA->id}");

        $responseA->assertViewHas('renderAddToCartButton', false);

        $menuB = MenuFactory::createWithMeals($this->validParams(['name' => 'Nasi Ayam']), '2017-08-21');

        $responseB = $this->actingAs($employee->user)->get("/meals/{$menuB->id}");

        $responseB->assertViewHas('renderAddToCartButton', false);
    }
}
