<?php

namespace Tests\Feature\Admin;

use App\Menu;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditMenuTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides)
    {
        return array_merge([
            'name' => 'Juicy Menu',
            'price' => 20000,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor.',
            'contents' => 'Menu contents',
        ], $overrides);
    }

    /** @test */
    public function guest_cannot__view_edit_form()
    {
        $menu = factory(Menu::class)->create([
            'name' => 'Roasted Beef',
            'price' => 100000,
        ]);

        $response = $this->get("/ap/menus/{$menu->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function non_admin_user_cannot__view_edit_form()
    {
        $user = factory(User::class)->create();

        $menu = factory(Menu::class)->create([
            'name' => 'Roasted Beef',
            'price' => 100000,
        ]);

        $response = $this->actingAs($user)->get("/ap/menus/{$menu->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function eatvora_admin_can_view_edit_form()
    {
        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create([
            'name' => 'Roasted Beef',
            'price' => 100000,
        ]);

        $response = $this->actingAs($admin)->get("/ap/menus/{$menu->id}/edit");

        $response->assertStatus(200);
        $this->assertEquals($menu->id, $response->data('menu')->id);
        $this->assertEquals($menu->name, $response->data('menu')->name);
        $this->assertEquals($menu->price, $response->data('menu')->price);
    }

    /** @test */
    public function eatvora_admin_cannot_view_the_edit_form_of_non_existing_menu()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get('/ap/menus/1000/edit');

        $response->assertStatus(404);
    }

    /** @test */
    public function eatvora_admin_can_update_existing_menu()
    {
        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create([
            'name' => 'Roasted Beef',
            'price' => 100000,
            'description' => 'Roasted beef description',
            'contents' => 'French fries, roasted beef',
        ]);

        $response = $this->actingAs($admin)->patch('/ap/menus/' . $menu->id, [
            'name' => 'New Roasted Beef',
            'price' => 150000,
            'description' => 'New roasted beef description',
            'contents' => 'New french fries, roasted beef',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/' . $menu->id);

        $menu->refresh();

        $this->assertEquals($menu->name, 'New Roasted Beef');
        $this->assertEquals($menu->price, 150000);
        $this->assertEquals($menu->description, 'New roasted beef description');
        $this->assertEquals($menu->contents, 'New french fries, roasted beef');
    }

    private function updateMenu($menuId, $overrides)
    {
        $admin = factory(User::class)->states('admin')->create();

        return $this->actingAs($admin)
            ->from("/ap/menus/{$menuId}/edit")
            ->patch('/ap/menus/' . $menuId, $this->validParams($overrides));
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create([
            'name' => 'Roasted Beef',
        ]);

        $response = $this->updateMenu($menu->id, [
            'name' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}/edit");
        $response->assertSessionHasErrors('name');

        $menu->refresh();

        $this->assertEquals($menu->name, 'Roasted Beef');
    }

    /** @test */
    public function name_must_be_at_least_8()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create([
            'name' => 'Roasted Beef',
        ]);

        $response = $this->updateMenu($menu->id, [
            'name' => 'Roasted',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}/edit");
        $response->assertSessionHasErrors('name');

        $menu->fresh();

        $this->assertEquals($menu->name, 'Roasted Beef');
    }

    /** @test */
    public function price_is_required()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create([
            'price' => 100000,
        ]);

        $response = $this->updateMenu($menu->id, [
            'price' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}/edit");
        $response->assertSessionHasErrors('price');

        $menu->refresh();

        $this->assertEquals($menu->price, 100000);
    }

    /** @test */
    public function price_must_be_numeric()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create([
            'price' => 100000,
        ]);

        $response = $this->updateMenu($menu->id, [
            'price' => 'not a valid price',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}/edit");
        $response->assertSessionHasErrors('price');

        $menu->refresh();

        $this->assertEquals($menu->price, 100000);
    }

    /** @test */
    public function price_must_be_at_least_15000()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create([
            'price' => 100000,
        ]);

        $response = $this->updateMenu($menu->id, [
            'price' => 14999,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}/edit");
        $response->assertSessionHasErrors('price');

        $menu->refresh();

        $this->assertEquals($menu->price, 100000);
    }

    /** @test */
    public function description_is_optional()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create([
            'description' => 'Roasted beef description',
        ]);

        $response = $this->updateMenu($menu->id, [
            'description' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}");

        $menu->refresh();

        $this->assertEquals($menu->description, '');
    }

    /** @test */
    public function contents_is_optional()
    {
        $this->withExceptionHandling();

        $menu = factory(Menu::class)->create([
            'contents' => 'Roasted beef, french fries, tomato',
        ]);

        $response = $this->updateMenu($menu->id, [
            'contents' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}");

        $menu->refresh();

        $this->assertEquals($menu->contents, '');
    }
}
