<?php

namespace Tests\Feature\Admin;

use App\Menu;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteMenuTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_cannot_delete_menu()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create();

        $response = $this->json('DELETE', '/api/v1/menus/' . $menu->id);

        $response->assertStatus(401);
        $this->assertEquals(1, Menu::count());
    }

    /** @test */
    public function non_admin_user_cannot_delete_menu()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('DELETE', '/api/v1/menus/' . $menu->id);

        $response->assertStatus(401);
        $this->assertEquals(1, Menu::count());
    }

    /** @test */
    public function admin_can_delete_menu()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create();

        $admin = factory(User::class)->states('admin')->create();

        $this->assertEquals(1, Menu::count());

        $response = $this->actingAs($admin)->json('DELETE', '/api/v1/menus/' . $menu->id);

        $response->assertStatus(200);
        $this->assertEquals(0, Menu::count());
    }

    /** @test */
    public function should_be_soft_deleted()
    {
        $menu = factory(Menu::class)->create();

        $admin = factory(User::class)->states('admin')->create();

        $this->assertEquals(1, Menu::count());

        $response = $this->actingAs($admin)->json('DELETE', '/api/v1/menus/' . $menu->id);

        $response->assertStatus(200);

        $this->assertEquals(0, Menu::count());
        $this->assertEquals(1, Menu::withTrashed()->count());
        $this->assertNotNull(Menu::withTrashed()->find($menu->id));
    }

    /** @test */
    public function should_get_404_when_attempt_to_delete_non_existing_menu()
    {
        factory(Menu::class, 10)->create();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->delete('/api/v1/menus/1000');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'Cannot delete non existing menu',
        ]);
        $this->assertEquals(10, Menu::count());
    }
}
