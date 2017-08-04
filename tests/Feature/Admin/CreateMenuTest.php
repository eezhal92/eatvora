<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Menu;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateMenuTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_and_non_admin_cannot_view_create_form()
    {
        $response = $this->get('/ap/menus/create');

        $response->assertRedirect('/');

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/ap/menus/create');

        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_can_view_create_form()
    {
        $admin = factory(User::class)->states('admin')->create();

        $vendors = factory(Vendor::class, 10)->create();

        $response = $this->actingAs($admin)->get('/ap/menus/create');

        $response->assertStatus(200);
        $this->assertEquals(
            $vendors->pluck('name', 'id')->toArray(),
            $response->data('vendors')->toArray()
        );

    }

    /** @test */
    public function can_create_valid_menu()
    {
        Storage::fake('public');

        $admin = factory(User::class)->states('admin')->create();

        $vendor = factory(Vendor::class)->create(['name' => 'D Good Food']);

        $response = $this->actingAs($admin)->post('/ap/menus', [
            'name' => 'Nasi Goreng Sapi',
            'price' => 30000,
            'vendor' => $vendor->id,
            'description' => 'A very delicious meal to eat',
            'contents' => 'Rice, Meat Dice, Vegetables',
            'image' => File::image('menu-image.jpg', 480, 320)
        ]);

        $menu = Menu::first();

        $response->assertRedirect('/ap/menus/' . $menu->id);
        $response->assertSessionHas('success', 'Menu Nasi Goreng Sapi successfully been created.');

        $this->assertEquals('Nasi Goreng Sapi', $menu->name);
        $this->assertEquals(30000, $menu->price);
        $this->assertEquals($vendor->id, $menu->vendor_id);
        $this->assertEquals('A very delicious meal to eat', $menu->description);
        $this->assertEquals('Rice, Meat Dice, Vegetables', $menu->contents);
        $this->assertNotNull($menu->image_path);

        Storage::disk('public')->assertExists($menu->image_path);
    }

    private function createMenu($overrides = [])
    {
        $admin = factory(User::class)->states('admin')->create();

        return $this->actingAs($admin)
            ->from('/ap/menus/create')
            ->post('/ap/menus', array_merge([
                'name' => 'Valid Menu',
                'price' => 20000,
                'vendor' => factory(Vendor::class)->create()->id,
                'description' => 'A valid menu description',
                'contents' => 'Valid content 1, valid content 2',
            ], $overrides));
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->createMenu([
            'name' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/create');
        $response->assertSessionHasErrors('name');

        $this->assertEquals(0, Menu::count());
    }

    /** @test */
    public function name_must_be_at_least_8()
    {
        $this->withExceptionHandling();


        $response = $this->createMenu([
            'name' => 'Roasted',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/create');
        $response->assertSessionHasErrors('name');

        $this->assertEquals(0, Menu::count());
    }

    /** @test */
    public function vendor_is_required()
    {
        $this->withExceptionHandling();


        $response = $this->createMenu([
            'vendor' => null,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/create');
        $response->assertSessionHasErrors('vendor');

        $this->assertEquals(0, Menu::count());
    }

    /** @test */
    public function vendor_is_must_be_positive_integer()
    {
        $this->withExceptionHandling();


        $response = $this->createMenu([
            'vendor' => -1,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/create');
        $response->assertSessionHasErrors('vendor');

        $this->assertEquals(0, Menu::count());

        $response = $this->createMenu([
            'vendor' => 'sdfsd',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/create');
        $response->assertSessionHasErrors('vendor');

        $this->assertEquals(0, Menu::count());
    }

    /** @test */
    public function price_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->createMenu([
            'price' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/create');
        $response->assertSessionHasErrors('price');

        $this->assertEquals(0, Menu::count());
    }

    /** @test */
    public function price_must_be_numeric()
    {
        $this->withExceptionHandling();

        $response = $this->createMenu([
            'price' => 'not a valid price',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/create');
        $response->assertSessionHasErrors('price');

        $this->assertEquals(0, Menu::count());
    }

    /** @test */
    public function price_must_be_at_least_15000()
    {
        $this->withExceptionHandling();

        $response = $this->createMenu([
            'price' => 14999,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/create');
        $response->assertSessionHasErrors('price');

        $this->assertEquals(0, Menu::count());
    }

    /** @test */
    public function description_is_optional()
    {
        $this->withExceptionHandling();

        $response = $this->createMenu([
            'description' => '',
        ]);

        $this->assertEquals(1, Menu::count());

        $menu = Menu::first();

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/' . $menu->id);

    }

    /** @test */
    public function contents_is_optional()
    {
        $response = $this->createMenu([
            'contents' => '',
        ]);

        $this->assertEquals(1, Menu::count());

        $menu = Menu::first();

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/' . $menu->id);
    }

    /** @test */
    public function image_is_optional()
    {
        $response = $this->createMenu([
            'images' => null,
        ]);

        $this->assertEquals(1, Menu::count());

        $menu = Menu::first();

        $response->assertStatus(302);
        $response->assertRedirect('/ap/menus/' . $menu->id);
    }
}
