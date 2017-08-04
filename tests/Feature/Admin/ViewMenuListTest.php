<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewMenuListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_view_menu_list()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $menuA = factory(Menu::class)->create();
        $menuB = factory(Menu::class)->create();
        $menuC = factory(Menu::class)->create();
        $menuD = factory(Menu::class)->create();

        $response = $this->actingAs($admin)->get('/ap/menus');

        $response->assertStatus(200);

        // @todo : refactor this assertion
        $this->assertEquals($response->data('menus')->pluck('id')->toArray(), collect([
            $menuA,
            $menuB,
            $menuC,
            $menuD,
        ])->pluck('id')->toArray());
    }
}
