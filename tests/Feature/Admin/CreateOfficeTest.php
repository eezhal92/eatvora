<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateOfficeTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Company Office',
            'address' => 'Office Address',
            'phone' => '+62 21 985223',
            'email' => 'hello@company.com',
        ], $overrides);
    }

    /** @test */
    public function guest_cannot_see_add_office_form()
    {
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);

        $response = $this->get("/ap/companies/{$company->id}/offices/create");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function non_eatvora_admin_user_cannot_see_add_office_form()
    {
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get("/ap/companies/{$company->id}/offices/create");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function eatvora_admin_user_can_see_add_office_form()
    {
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);

        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->get("/ap/companies/{$company->id}/offices/create");

        $response->assertStatus(200);
    }

    /** @test */
    public function redirect_when_attempt_to_create_office_for_non_existing_company()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->get("/ap/companies/1000/offices/create");

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies');
        $response->assertSessionHas('message', 'Company was not found.');
    }

    /** @test */
    public function create_new_company()
    {
        $admin = factory(User::class)->states('admin')->create();
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);

        $response = $this->actingAs($admin)->post("/ap/companies/{$company->id}/offices", [
            'company_id' => $company->id,
            'name' => 'Another Company Office',
            'address' => 'Non-Exists St.',
            'phone' => '+62 451 483350',
            'email' => 'another_office@company.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/' . $company->id);

        $office = Office::first();

        $this->assertEquals($office->name, 'Another Company Office');
        $this->assertEquals($office->address, 'Non-Exists St.');
        $this->assertEquals($office->phone, '+62 451 483350');
        $this->assertEquals($office->email, 'another_office@company.com');
        $this->assertEquals($office->is_main, false);
        $this->assertEquals($office->company_id, $company->id);
    }

    /** @test */
    public function company_must_be_exists()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->post('/ap/companies/1000/offices', $this->validParams());

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Company was not found.');
        $response->assertRedirect('/ap/companies');

        $this->assertEquals(0, Office::count());
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();
        $company = factory(Company::class)->create();

        $response = $this->actingAs($admin)->post("/ap/companies/{$company->id}/offices", $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');

        $this->assertEquals(0, Office::count());
    }

    /** @test */
    public function name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();
        $company = factory(Company::class)->create();

        $response = $this->actingAs($admin)->post("/ap/companies/{$company->id}/offices", $this->validParams([
            'name' => 'Sa',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');

        $this->assertEquals(0, Office::count());
    }

    /** @test */
    public function address_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();
        $company = factory(Company::class)->create();

        $response = $this->actingAs($admin)->post("/ap/companies/{$company->id}/offices", $this->validParams([
            'address' => '',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('address');

        $this->assertEquals(0, Office::count());
    }

    /** @test */
    public function address_must_be_at_least_6()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();
        $company = factory(Company::class)->create();

        $response = $this->actingAs($admin)->post("/ap/companies/{$company->id}/offices", $this->validParams([
            'address' => 'Jogja',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('address');

        $this->assertEquals(0, Office::count());
    }

    /** @test */
    public function phone_is_optional()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();
        $company = factory(Company::class)->create();

        $response = $this->actingAs($admin)->post("/ap/companies/{$company->id}/offices", $this->validParams([
            'phone' => '',
        ]));

        $this->assertEquals(1, Office::count());
    }

    /** @test */
    public function email_is_optional()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();
        $company = factory(Company::class)->create();

        $response = $this->actingAs($admin)->post("/ap/companies/{$company->id}/offices", $this->validParams([
            'email' => '',
        ]));

        $this->assertEquals(1, Office::count());
    }
}
