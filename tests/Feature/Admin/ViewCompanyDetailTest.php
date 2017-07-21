<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use App\Office;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ViewCompanyDetailTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function guest_cannot_view_edit_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->get("/ap/companies/{$company->id}");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function non_admin_cannot_view_edit_company()
    {
        $user = factory(User::class)->create();

        $company = factory(Company::class)->create();

        $response = $this->actingAs($user)->get("/ap/companies/{$company->id}");

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_can_view_office_list()
    {
        $admin = factory(User::class)->states('admin')->create();

        $company = factory(Company::class)->create([
            'name' => 'Traveloka',
        ]);

        factory(Office::class)->create([
            'company_id' => $company->id,
            'name' => 'Development Office',
            'address' => 'Rasuna Said',
        ]);

        $response = $this->actingAs($admin)->get("/ap/companies/{$company->id}");

        $response->assertStatus(200);
        $this->assertTrue($response->data('company')->is($company));
        $response->assertSee('Traveloka');
        $response->assertSee('Development Office');
    }

    /** @test */
    function admin_see_a_404_when_attempting_to_view_the_company_detail_that_does_not_exist()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get("/ap/companies/1000");

        $response->assertStatus(404);
    }
}
