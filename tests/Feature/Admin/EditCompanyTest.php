<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditCompanyTest extends TestCase
{
    use DatabaseMigrations;

    private function createAdmin()
    {
        return factory(User::class)->states('admin')->create();
    }

    private function oldCompanyAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Company Name',
        ], $overrides);
    }

    private function oldMainOfficeAttributes($overrides = [])
    {
        return array_merge([
            'company_id' => null,
            'is_main' => true,
            'name' => 'Old Company Main Office Name',
            'address' => 'Old Company Main Office Address',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'New Company Name',
            'main_office_name' => 'New Company Main Office Name',
            'main_office_address' => 'New Company Main Office Address',
        ], $overrides);
    }

    /** @test */
    public function guest_cannot_view_edit_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->get("/ap/companies/{$company->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function non_admin_cannot_view_edit_company()
    {
        $user = factory(User::class)->create();

        $company = factory(Company::class)->create();

        $response = $this->actingAs($user)->get("/ap/companies/{$company->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_can_view_edit_company_form()
    {
        $admin = $this->createAdmin();

        $company = factory(Company::class)->create();
        $mainOffice = factory(Office::class)->create([
            'company_id' => $company->id,
            'is_main' => true,
        ]);

        $response = $this->actingAs($admin)->get("/ap/companies/{$company->id}/edit");

        $response->assertStatus(200);
        $this->assertTrue($response->data('company')->is($company));
    }

    /** @test */
    public function admin_can_edit_existing_company()
    {
        $admin = $this->createAdmin();

        $company = factory(Company::class)->create($this->oldCompanyAttributes());

        $office = factory(Office::class)->create($this->oldMainOfficeAttributes([
            'company_id' => $company->id,
        ]));

        factory(Office::class)->create([
            'company_id' => $company->id,
            'name' => 'Another Company Office',
            'address' => 'Another Company Office Address',
            'is_main' => false,
        ]);

        $response = $this->actingAs($admin)->patch("/ap/companies/{$company->id}", [
            'name' => 'New Company Name',
            'main_office_name' => 'New Company Office Name',
            'main_office_address' => 'New Company Office Address',
        ]);

        $response->assertRedirect("/ap/companies/{$company->id}");

        $company->refresh();
        $office->refresh();

        $this->assertEquals('New Company Name', $company->name);
        $this->assertEquals($company->id, $office->id);
        $this->assertEquals('New Company Office Name', $office->name);
        $this->assertEquals('New Company Office Address', $office->address);
        $this->assertTrue($office->is_main);
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $admin = $this->createAdmin();

        $company = factory(Company::class)->create($this->oldCompanyAttributes([
            'name' => 'Microsoft',
        ]));

        $response = $this->actingAs($admin)->patch("/ap/companies/{$company->id}", $this->validParams([
            'name' => '',
        ]));

        $response->assertSessionHasErrors('name');

        $company->refresh();

        $this->assertEquals('Microsoft', $company->name);
    }

    /** @test */
    public function name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $admin = $this->createAdmin();

        $company = factory(Company::class)->create($this->oldCompanyAttributes([
            'name' => 'Cool Office',
        ]));

        $response = $this->actingAs($admin)->patch("/ap/companies/{$company->id}", $this->validParams([
            'name' => 'as',
        ]));

        $response->assertSessionHasErrors('name');

        $company->refresh();

        $this->assertEquals('Cool Office', $company->name);
    }

    /** @test */
    public function main_office_name_is_required()
    {
        $this->withExceptionHandling();

        $admin = $this->createAdmin();

        $company = factory(Company::class)->create($this->oldCompanyAttributes());
        $office = factory(Office::class)->create($this->oldMainOfficeAttributes([
            'name' => 'Old Office Name',
            'company_id' => $company->id,
        ]));

        $response = $this->actingAs($admin)->patch("/ap/companies/{$company->id}", $this->validParams([
            'main_office_name' => '',
        ]));

        $response->assertSessionHasErrors('main_office_name');

        $company->refresh();

        $this->assertEquals('Old Office Name', $office->name);
    }

    /** @test */
    public function main_office_name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $admin = $this->createAdmin();

        $company = factory(Company::class)->create($this->oldCompanyAttributes());
        $office = factory(Office::class)->create($this->oldMainOfficeAttributes([
            'name' => 'Old Office Name',
            'company_id' => $company->id,
        ]));

        $response = $this->actingAs($admin)->patch("/ap/companies/{$company->id}", $this->validParams([
            'main_office_name' => 'so',
        ]));

        $response->assertSessionHasErrors('main_office_name');

        $company->refresh();

        $this->assertEquals('Old Office Name', $office->name);
    }

    /** @test */
    public function main_office_address_is_required()
    {
        $this->withExceptionHandling();

        $admin = $this->createAdmin();

        $company = factory(Company::class)->create($this->oldCompanyAttributes());
        $office = factory(Office::class)->create($this->oldMainOfficeAttributes([
            'address' => 'Old Office Address',
            'company_id' => $company->id,
        ]));

        $response = $this->actingAs($admin)->patch("/ap/companies/{$company->id}", $this->validParams([
            'main_office_address' => '',
        ]));

        $response->assertSessionHasErrors('main_office_address');

        $company->refresh();

        $this->assertEquals('Old Office Address', $office->address);
    }

    /** @test */
    public function main_office_address_must_be_at_least_6()
    {
        $this->withExceptionHandling();

        $admin = $this->createAdmin();

        $company = factory(Company::class)->create($this->oldCompanyAttributes());
        $office = factory(Office::class)->create($this->oldMainOfficeAttributes([
            'address' => 'Old Office Address',
            'company_id' => $company->id,
        ]));

        $response = $this->actingAs($admin)->patch("/ap/companies/{$company->id}", $this->validParams([
            'main_office_address' => 'Manha',
        ]));

        $response->assertSessionHasErrors('main_office_address');

        $company->refresh();

        $this->assertEquals('Old Office Address', $office->address);
    }
}
