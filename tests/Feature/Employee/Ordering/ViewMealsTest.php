<?php

namespace Tests\Feature\Employee\Ordering;

use App\Menu;
use App\User;
use App\Vendor;
use App\Schedule;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

/**
 * To Assert
 * - requires authentication
 * - user cannot see past meal schedule
 * - can see by category etc
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
    public function requires_authentication_and_company_session()
    {
        $this->withExceptionHandling();
        $response = $this->get('/meals');

        $response->assertStatus(302);

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/meals');

        $response->assertStatus(302);
    }

    /** @test */
    public function user_can_view_meal_schedule_list_for_a_specific_date()
    {
        $user = factory(User::class)->create();
        $vendor = factory(Vendor::class)->create([
            'name' => 'Dapur Lulu',
            'capacity' => 200,
        ]);

        $menuA = factory(Menu::class)->create($this->validParams([
            'name' => 'Nasi Padang',
            'vendor_id' => $vendor->id,
        ]));

        $menuB = factory(Menu::class)->create($this->validParams([
            'name' => 'Nasi Kuning',
            'vendor_id' => $vendor->id,
        ]));

        $scheduleA = factory(Schedule::class)->create([
            'date' => Carbon::parse('2017-06-12'),
            'menu_id' => $menuA->id,
        ]);

        $scheduleB = factory(Schedule::class)->create([
            'date' => Carbon::parse('2017-06-13'),
            'menu_id' => $menuB->id,
        ]);

        $responseDayOne = $this->actingAs($user)->json('GET', '/api/v1/meals?date=2017-06-12');
        $responseDayTwo = $this->actingAs($user)->json('GET', '/api/v1/meals?date=2017-06-13');

        $responseDayOne
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Nasi Padang',
            ])
            ->assertJsonMissing([
                'name' => 'Nasi Kuning',
            ]);

        $responseDayTwo
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Nasi Kuning',
            ])
            ->assertJsonMissing([
                'name' => 'Nasi Padang',
            ]);
    }

    /** @test */
    public function user_can_see_a_scheduled_meal()
    {
        // date and meal id should be present
        $vendor = factory(Vendor::class)->create([
            'name' => 'Dapur Lulu',
            'capacity' => 200,
        ]);

        $menu = factory(Menu::class)->create($this->validParams([
            'name' => 'Nasi Padang',
            'price' => 30000,
            'vendor_id' => $vendor->id,
        ]));

        $schedule = factory(Schedule::class)->create([
            'date' => Carbon::parse('2017-06-12'),
            'menu_id' => $menu->id,
        ]);

        $user = factory(User::class)->create();
        factory(Employee::class)->create([
            'user_id' => $user,
        ]);

        $response = $this->actingAs($user)->get("/meals/2017-06-12/{$menu->id}");

        $this->assertEquals('Nasi Padang', $response->data('menu')->name);
        $this->assertEquals('Dapur Lulu', $response->data('menu')->vendor->name);
        $this->assertEquals(30000, $response->data('menu')->price);
    }

    /** @test */
    public function user_can_not_see_meal_detail_with_wrong_date_and_meal_id_combination()
    {
        // date and meal id should be present
        $vendor = factory(Vendor::class)->create([
            'name' => 'Dapur Lulu',
            'capacity' => 200,
        ]);

        $menu = factory(Menu::class)->create($this->validParams([
            'name' => 'Nasi Padang',
            'price' => 30000,
            'vendor_id' => $vendor->id,
        ]));

        $schedule = factory(Schedule::class)->create([
            'date' => Carbon::parse('2017-06-12'),
            'menu_id' => $menu->id,
        ]);

        $user = factory(User::class)->create();
        factory(Employee::class)->create([
            'user_id' => $user,
        ]);

        $response = $this->actingAs($user)->get("/meals/2017-06-12/100");

        $response->assertSee('Not found!');

        $response = $this->get("/meals/2017-06-13/{$menu->id}");

        $response->assertSee('Not found!');
    }
}
