<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Company;
use App\Office;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewCompanyListTest extends TestCase
{
    use DatabaseMigrations;

    private function createCompanies()
    {
        $companies = collect([
            'Microsoft',
            'Google',
            'Apple',
            'Netflix',
            'Twitter',
            'Facebook',
            'LinkedIn',
            'Github',
            'Amazon',
            'Ali Baba',
            'Paypal',
            'Stripe',
        ]);

        $companies = $companies->map(function ($company) {
            $company = factory(Company::class)->create(['name' => $company]);

            factory(Office::class)->states('main')->create(['company_id' => $company->id]);

            return $company;
        });

        return $companies;
    }

    /** @test */
    public function admin_can_view_all_created_companies_with_default_limit_of_10()
    {
        $this->createCompanies();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get('/ap/companies');

        $response->assertStatus(200);
        $response->assertSee('Microsoft');
        $response->assertSee('Google');
        $response->assertSee('Apple');
        $response->assertSee('Netflix');
        $response->assertSee('Twitter');
        $response->assertSee('Facebook');
        $response->assertSee('LinkedIn');
        $response->assertSee('Github');
        $response->assertSee('Amazon');
        $response->assertSee('Ali Baba');
        $response->assertDontSee('Paypal');
        $response->assertDontSee('Stripe');
    }

    /** @test */
    public function admin_can_view_all_next_page_created_companies()
    {
        $this->createCompanies();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get('/ap/companies?page=2');

        $response->assertStatus(200);
        $response->assertDontSee('Microsoft');
        $response->assertDontSee('Google');
        $response->assertDontSee('Apple');
        $response->assertDontSee('Netflix');
        $response->assertDontSee('Twitter');
        $response->assertDontSee('Facebook');
        $response->assertDontSee('LinkedIn');
        $response->assertDontSee('Github');
        $response->assertDontSee('Amazon');
        $response->assertDontSee('Ali Baba');
        $response->assertSee('Paypal');
        $response->assertSee('Stripe');
    }
}
