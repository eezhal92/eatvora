<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditOfficeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_cannot_see_add_office_form()
    {
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);
        $office = factory(Office::class)->create(['company_id' => $company->id]);

        $companyId = $company->id;
        $officeId = $office->id;

        $response = $this->get("/ap/companies/{$companyId}/offices/{$officeId}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function non_eatvora_admin_user_cannot_see_add_office_form()
    {
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);
        $office = factory(Office::class)->create(['company_id' => $company->id]);

        $companyId = $company->id;
        $officeId = $office->id;

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get("/ap/companies/{$companyId}/offices/{$officeId}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function eatvora_admin_user_can_see_add_office_form()
    {
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);
        $office = factory(Office::class)->create(['company_id' => $company->id]);

        $companyId = $company->id;
        $officeId = $office->id;

        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->get("/ap/companies/{$companyId}/offices/{$officeId}/edit");

        $response->assertStatus(200);
    }

    /** @test */
    public function eatvora_admin_cannot_view_the_edit_form_when_company_is_not_exists()
    {
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);
        $office = factory(Office::class)->create(['company_id' => $company->id]);

        $officeId = $office->id;

        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->get("/ap/companies/1000/offices/{$officeId}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies');
    }

    /** @test */
    public function eatvora_admin_cannot_view_the_edit_form_when_office_is_not_exists()
    {
        $company = factory(Company::class)->create([
            'name' => 'A Company',
        ]);

        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->get("/ap/companies/{$company->id}/offices/1000/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies');
    }

    /** @test */
    public function eatvora_admin_cannot_view_the_edit_form_when_company_and_office_is_not_associated()
    {
        $companyA = factory(Company::class)->create();
        $officeBelongsToCompanyA = factory(Office::class)->create([
            'company_id' => $companyA->id,
            'name' => 'Old Office Name',
            'address' => 'Old Office Address',
            'phone' => '+62 21 999 888',
            'email' => 'old_office_email@mail.com',
        ]);
        $companyB = factory(Company::class)->create();

        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->get("/ap/companies/{$companyB->id}/offices/{$officeBelongsToCompanyA->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies');
    }

    /** @test */
    public function can_update_existing_office()
    {
        $company = factory(Company::class)->create();
        $office = factory(Office::class)->create([
            'company_id' => $company->id,
            'name' => 'Old Office Name',
            'address' => 'Old Office Address',
            'phone' => '+62 21 999 888',
            'email' => 'old_office_email@mail.com',
        ]);

        $admin = factory(User::class)->states('admin')->create();

        $companyId = $company->id;
        $officeId = $office->id;

        $response = $this->actingAs($admin)
            ->from("/ap/companies/{$companyId}/offices/{$officeId}/edit")
            ->patch("/ap/companies/{$companyId}/offices/{$officeId}", [
                'name' => 'New Office Name',
                'address' => 'New Office Address',
                'phone' => '+62 21 999 999',
                'email' => 'new_office_email@mail.com',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect("/ap/companies/{$companyId}");

        $office->refresh();

        $this->assertEquals('New Office Name', $office->name);
        $this->assertEquals('New Office Address', $office->address);
        $this->assertEquals('+62 21 999 999', $office->phone);
        $this->assertEquals('new_office_email@mail.com', $office->email);
        $this->assertFalse($office->is_main);
    }
}
