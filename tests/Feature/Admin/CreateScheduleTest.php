<?php

namespace Tests\Feature\Admin;

use App\Meal;
use App\Menu;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateScheduleTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $now = Carbon::create(2017, 8, 21);
        Carbon::setTestNow($now);
    }

    /** @test */
    public function can_view_create_meal_schedule_form()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get('/ap/schedules/create');

        $response->assertStatus(200);
    }

    /** @test */
    public function can_create_schedule_for_meal()
    {
        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $response = $this->actingAs($admin)->post('/ap/schedules', [
            'menu_id' => $menu->id,
            'date' => '2017-08-28',
            'qty' => 10,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules');

        $this->assertEquals(10, Meal::where(['menu_id' => $menu->id, 'date' => '2017-08-28'])->count());
    }

    /** @test */
    public function scheduled_meal_quantity_get_bumped_when_already_scheduled()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $menu->scheduleMeals('2017-08-28', 10);

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => $menu->id,
                'date' => '2017-08-28',
                'qty' => 20,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules');

        $this->assertEquals(30, Meal::where(['menu_id' => $menu->id, 'date' => '2017-08-28'])->count());
    }

    /** @test */
    public function cannot_schedule_meals_for_current_week()
    {
        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => $menu->id,
                'date' => '2017-08-22',
                'qty' => 20,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules');

        $this->assertEquals(0, Meal::where(['menu_id' => $menu->id, 'date' => '2017-08-22'])->count());
    }

    /** @test */
    public function menu_id_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'date' => '2017-08-28',
                'qty' => 10,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules/create');
        $response->assertSessionHasErrors('menu_id');
    }

    /** @test */
    public function menu_id_must_be_numeric()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => 'not-numeric',
                'date' => '2017-08-28',
                'qty' => 10,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules/create');
        $response->assertSessionHasErrors('menu_id');
    }

    /** @test */
    public function cannot_create_schedule_of_nonexistent_menu()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => 999,
                'date' => '2017-08-28',
                'qty' => 10,
            ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function date_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => $menu->id,
                'qty' => 10,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules/create');
        $response->assertSessionHasErrors('date');
    }

    /** @test */
    public function date_must_be_a_date()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => $menu->id,
                'date' => 'not-valid-date',
                'qty' => 10,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules/create');
        $response->assertSessionHasErrors('date');
    }

    /** @test */
    public function date_format_must_be_Y_m_d()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => $menu->id,
                'date' => '28-08-2017',
                'qty' => 10,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules/create');
        $response->assertSessionHasErrors('date');
    }

    /** @test */
    public function qty_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => $menu->id,
                'date' => '28-08-2017',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules/create');
        $response->assertSessionHasErrors('qty');
    }

    /** @test */
    public function qty_must_be_numeric()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create();

        $response = $this->actingAs($admin)
            ->from('/ap/schedules/create')
            ->post('/ap/schedules', [
                'menu_id' => $menu->id,
                'date' => '28-08-2017',
                'qty' => 'not-valid-numeric',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/schedules/create');
        $response->assertSessionHasErrors('qty');
    }
}
