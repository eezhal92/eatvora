<?php

namespace Tests\Feature\Admin;

use App\Menu;
use App\User;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
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
            'vendor' => factory(Vendor::class)->create()->id,
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
        Storage::fake('public');

        $admin = factory(User::class)->states('admin')->create();

        $menu = factory(Menu::class)->create([
            'name' => 'Roasted Beef',
            'price' => 100000,
            'description' => 'Roasted beef description',
            'contents' => 'French fries, roasted beef',
            'image_path' => '/images/menus/old-image.jpg',
        ]);

        $vendor = factory(Vendor::class)->create();

        $response = $this->actingAs($admin)->patch('/ap/menus/' . $menu->id, [
            'name' => 'New Roasted Beef',
            'price' => 150000,
            'description' => 'New roasted beef description',
            'contents' => 'New french fries, roasted beef',
            'image' => File::image('new-image.jpg', 480, 320),
            'vendor' => $vendor->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/' . $menu->id);

        $menu->refresh();

        $this->assertEquals('New Roasted Beef', $menu->name);
        $this->assertEquals(150000, $menu->price);
        $this->assertEquals('New roasted beef description', $menu->description);
        $this->assertEquals('New french fries, roasted beef', $menu->contents);
        $this->assertNotEquals('/images/menus/old-image.jpg', $menu->image_path);
        $this->assertEquals($vendor->id, $menu->vendor_id);
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
    public function vendor_is_required()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create();

        $menu = factory(Menu::class)->create([
            'vendor_id' => $vendor->id,
        ]);

        $response = $this->updateMenu($menu->id, [
            'vendor' => null,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}/edit");
        $response->assertSessionHasErrors('vendor');

        $menu->refresh();

        $this->assertEquals($vendor->id, $menu->vendor_id);
    }

    /** @test */
    public function vendor_must_be_positive_number()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create();

        $menu = factory(Menu::class)->create([
            'vendor_id' => $vendor->id,
        ]);

        $response = $this->updateMenu($menu->id, [
            'vendor' => 'not valid number',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}/edit");
        $response->assertSessionHasErrors('vendor');

        $menu->refresh();

        $this->assertEquals($vendor->id, $menu->vendor_id);

        $response = $this->updateMenu($menu->id, [
            'vendor' => -1,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}/edit");
        $response->assertSessionHasErrors('vendor');

        $menu->refresh();

        $this->assertEquals($vendor->id, $menu->vendor_id);
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

    /** @test */
    public function image_is_optional()
    {
        $menu = factory(Menu::class)->create([
            'image_path' => '/images/menus/old-image.jpg',
        ]);

        $response = $this->updateMenu($menu->id, [
            'images' => null,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/menus/{$menu->id}");

        $menu->refresh();

        $this->assertEquals('/images/menus/old-image.jpg', $menu->image_path);
    }
}
