<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditVendorTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function eatvora_admin_can_view_edit_form()
    {
        $admin = factory(User::class)->states('admin')->create();

        $vendor = factory(Vendor::class)->create([
            'name' => 'D Good Food',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'TB Simatupang',
            'email' => 'goodfood@gmail.com',
        ]);

        $response = $this->actingAs($admin)->get("/ap/vendors/{$vendor->id}/edit");

        $response->assertStatus(200);
        $this->assertEquals($vendor->id, $response->data('vendor')->id);
        $this->assertEquals($vendor->name, $response->data('vendor')->name);
        $this->assertEquals($vendor->capacity, $response->data('vendor')->capacity);
        $this->assertEquals($vendor->phone, $response->data('vendor')->phone);
        $this->assertEquals($vendor->address, $response->data('vendor')->address);
        $this->assertEquals($vendor->email, $response->data('vendor')->email);
    }

    private function updateVendor($vendorId, $fields = [])
    {
        $admin = factory(User::class)->states('admin')->create();

        return $this->actingAs($admin)
            ->from("/ap/vendors/{$vendorId}/edit")
            ->patch("/ap/vendors/{$vendorId}", array_merge([
                'name' => 'New name',
                'capacity' => 150,
                'phone' => '021 444 555',
                'address' => 'New address',
                'email' => 'new_email@gmail.com',
            ], $fields));
    }

    /** @test */
    public function eatvora_admin_can_update_existing_vendor()
    {
        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/' . $vendor->id);

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'New name');
        $this->assertEquals($vendor->capacity, 150);
        $this->assertEquals($vendor->phone, '021 444 555');
        $this->assertEquals($vendor->address, 'New address');
        $this->assertEquals($vendor->email, 'new_email@gmail.com');
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id, [
            'name' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/vendors/{$vendor->id}/edit");
        $response->assertSessionHasErrors('name');

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'Old name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '0451 483350');
        $this->assertEquals($vendor->address, 'Old address');
        $this->assertEquals($vendor->email, 'old_email@gmail.com');
    }

    /** @test */
    public function name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id, [
            'name' => 'Ol',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/vendors/{$vendor->id}/edit");
        $response->assertSessionHasErrors('name');

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'Old name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '0451 483350');
        $this->assertEquals($vendor->address, 'Old address');
        $this->assertEquals($vendor->email, 'old_email@gmail.com');
    }

    /** @test */
    public function phone_is_required()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id, [
            'phone' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/vendors/{$vendor->id}/edit");
        $response->assertSessionHasErrors('phone');

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'Old name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '0451 483350');
        $this->assertEquals($vendor->address, 'Old address');
        $this->assertEquals($vendor->email, 'old_email@gmail.com');
    }

    /** @test */
    public function capacity_is_required()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id, [
            'capacity' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/vendors/{$vendor->id}/edit");
        $response->assertSessionHasErrors('capacity');

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'Old name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '0451 483350');
        $this->assertEquals($vendor->address, 'Old address');
        $this->assertEquals($vendor->email, 'old_email@gmail.com');
    }

    /** @test */
    public function capacity_must_be_positive_number_with_minimum_10()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id, [
            'capacity' => 'asd',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/vendors/{$vendor->id}/edit");
        $response->assertSessionHasErrors('capacity');

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'Old name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '0451 483350');
        $this->assertEquals($vendor->address, 'Old address');
        $this->assertEquals($vendor->email, 'old_email@gmail.com');

        $response = $this->updateVendor($vendor->id, [
            'capacity' => '9',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/vendors/{$vendor->id}/edit");
        $response->assertSessionHasErrors('capacity');

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'Old name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '0451 483350');
        $this->assertEquals($vendor->address, 'Old address');
        $this->assertEquals($vendor->email, 'old_email@gmail.com');
    }

    public function address_is_optional()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id, [
            'address' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/' . $vendor->id);

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'New name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '021 444 555');
        $this->assertEquals($vendor->address, 'New address');
        $this->assertEquals($vendor->email, 'new_email@gmail.com');
    }

    public function email_is_optional()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id, [
            'email' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/' . $vendor->id);

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'New name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '0451 483350');
        $this->assertEquals($vendor->address, 'New address');
        $this->assertEquals($vendor->email, 'old_email@gmail.com');
    }

    /** @test */
    public function email_must_be_type_of_email()
    {
        $this->withExceptionHandling();

        $vendor = factory(Vendor::class)->create([
            'name' => 'Old name',
            'capacity' => 200,
            'phone' => '0451 483350',
            'address' => 'Old address',
            'email' => 'old_email@gmail.com',
        ]);

        $response = $this->updateVendor($vendor->id, [
            'email' => 'not-valid-email',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/vendors/' . $vendor->id . '/edit');
        $response->assertSessionHasErrors('email');

        $vendor->refresh();

        $this->assertEquals($vendor->name, 'Old name');
        $this->assertEquals($vendor->capacity, 200);
        $this->assertEquals($vendor->phone, '0451 483350');
        $this->assertEquals($vendor->address, 'Old address');
        $this->assertEquals($vendor->email, 'old_email@gmail.com');
    }
}
