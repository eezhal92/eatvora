<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewMenuTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_view_menu_detail()
    {
        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create([
            'name' => 'Good Menu',
        ]);

        $response = $this->actingAs($admin)->get("/ap/menus/{$menu->id}");

        $response->assertStatus(200);
        $this->assertEquals('Good Menu', $response->data('menu')->name);
    }

    /** @test */
    public function cannot_view_detail_of_non_existing_menu()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get('/ap/menus/1000');

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus');
        $response->assertSessionHas('error', 'Menu was not found.');
    }
}
