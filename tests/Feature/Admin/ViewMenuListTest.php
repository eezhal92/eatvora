<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Menu;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewMenuListTest extends TestCase
{
    use DatabaseMigrations;

    private $vendor;

    private $admin;

    public function setUp()
    {
        parent::setUp();

        $this->admin = factory(User::class)->states('admin')->create();

        $this->createVendorAndMenus();
    }

    private function createVendorAndMenus()
    {
        $this->vendor = factory(Vendor::class)->create();

        factory(Menu::class, 20)->create([
            'vendor_id' => $this->vendor->id,
        ]);
    }

    /** @test */
    public function can_view_menu_list()
    {
        $response = $this->actingAs($this->admin)->get('/ap/menus');

        $response->assertStatus(200);
    }

    /** @test */
    public function can_retrieve_menus_by_vendor()
    {
        $response = $this->actingAs($this->admin)->json('GET', '/api/v1/menus?vendor_id=' . $this->vendor->id);

        $anotherVendor = factory(Vendor::class)->create();

        factory(Menu::class, 2)->create([
            'vendor_id' => $anotherVendor->id,
        ]);

        $response->assertJsonFragment(['vendor_id' => (string) $this->vendor->id]);

        $response->assertJsonMissing(['vendor_id' => (string) $anotherVendor->id]);
    }
}
