<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateVendorTest extends TestCase
{
    use DatabaseMigrations;

    private function createVendor($overrides = [])
    {
        $admin = factory(User::class)->states('admin')->create();

        return $this->actingAs($admin)
            ->from('/ap/vendors/create')
            ->post('/ap/vendors', array_merge([
                'name' => 'Dapur Lulu',
                'address' => 'Jl. Veteran No. 15',
                'phone' => '085 225 575 999',
                'email' => 'dapurlulu@gmail.com',
                'capacity' => 200,
            ], $overrides));
    }

    /** @test */
    public function guest_cannot_see_create_vendor_form()
    {
        $response = $this->get('/ap/vendors/create');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function non_eatvora_admin_cannot_see_create_vendor_form()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/ap/vendors/create');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function eatvora_admin_can_see_create_vendor_form()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get('/ap/vendors/create');

        $response->assertStatus(200);
        $response->assertSee('Create New Vendor');
    }

    /** @test */
    public function can_create_valid_vendor()
    {
        $response = $this->createVendor();

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/1');

        $vendor = Vendor::first();

        $this->assertEquals(1, Vendor::count());
        $this->assertEquals('Dapur Lulu', $vendor->name);
        $this->assertEquals('Jl. Veteran No. 15', $vendor->address);
        $this->assertEquals('085 225 575 999', $vendor->phone);
        $this->assertEquals('dapurlulu@gmail.com', $vendor->email);
        $this->assertEquals(200, $vendor->capacity);
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->createVendor([
            'name' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/create');
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Vendor::count());
    }

    /** @test */
    public function name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $response = $this->createVendor([
            'name' => 'Da',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/create');
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Vendor::count());
    }

    /** @test */
    public function capacity_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->createVendor([
            'capacity' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/create');
        $response->assertSessionHasErrors('capacity');
        $this->assertEquals(0, Vendor::count());
    }

    /** @test */
    public function capacity_must_be_positive_number_with_minimum_10()
    {
        $this->withExceptionHandling();

        $response = $this->createVendor([
            'capacity' => 'asd',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/create');
        $response->assertSessionHasErrors('capacity');
        $this->assertEquals(0, Vendor::count());

        $response = $this->createVendor([
            'capacity' => -2,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/create');
        $response->assertSessionHasErrors('capacity');

        $response = $this->createVendor([
            'capacity' => 9,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/create');
        $response->assertSessionHasErrors('capacity');
    }

    /** @test */
    public function phone_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->createVendor([
            'phone' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/create');
        $response->assertSessionHasErrors('phone');
        $this->assertEquals(0, Vendor::count());
    }

    /** @test */
    public function phone_must_be_at_least_6()
    {
        $this->withExceptionHandling();

        $response = $this->createVendor([
            'phone' => '08522',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/create');
        $response->assertSessionHasErrors('phone');
        $this->assertEquals(0, Vendor::count());
    }

    /** @test */
    public function email_is_optional()
    {
        $this->withExceptionHandling();

        $response = $this->createVendor([
            'email' => '',
        ]);

        $vendor = Vendor::first();

        $response->assertStatus(302);
        $response->assertRedirect("/ap/vendors/{$vendor->id}");
        $this->assertEquals(1, Vendor::count());
    }

    /** @test */
    public function address_is_optional()
    {
        $this->withExceptionHandling();

        $response = $this->createVendor([
            'address' => '',
        ]);

        $vendor = Vendor::first();

        $response->assertStatus(302);
        $response->assertRedirect("/ap/vendors/{$vendor->id}");
        $this->assertEquals(1, Vendor::count());
    }
}
