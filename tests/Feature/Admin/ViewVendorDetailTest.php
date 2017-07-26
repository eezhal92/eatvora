<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewVendorDetailTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function eatvora_admin_can_view_vendor_detail_page()
    {
        $admin = factory(User::class)->states('admin')->create();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Dapur Lulu',
            'address' => 'Rasuna Said No. 33',
            'phone' => '0451 483350',
            'email' => 'dapur_lulu@gmail.com',
            'capacity' => 200,
        ]);

        $response = $this->actingAs($admin)->get("/ap/vendors/{$vendor->id}");

        $this->assertEquals($vendor->id, $response->data('vendor')->id);
        $this->assertEquals($vendor->name, $response->data('vendor')->name);
        $this->assertEquals($vendor->phone, $response->data('vendor')->phone);
        $this->assertEquals($vendor->email, $response->data('vendor')->email);
        $this->assertEquals($vendor->address, $response->data('vendor')->address);
    }

    /** @test */
    public function cannot_view_non_existing_vendor()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get("/ap/vendors/1000");

        $response->assertStatus(404);
    }
}
