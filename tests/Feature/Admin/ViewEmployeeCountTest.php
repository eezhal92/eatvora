<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Company;
use App\Employee;
use Tests\TestCase;
use EmployeeFactory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewEmployeeCountTest extends TestCase
{
    use DatabaseMigrations;

    /** @testExample */
    public function can_view_employees_count()
    {
        $admin = factory(User::class)->states('admin')->create();

        $company = factory(Company::class)->create();

        $employees = EmployeeFactory::createEmployees($company, 20);

        $employees->take(3)->each->update(['active' => false]);

        $response = $this->actingAs($admin)->json('GET', '/api/v1/employees-count?company_id=' . $company->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'count' => 20,
        ]);
    }

    /** @testExample */
    public function can_view_count_of_active_employees()
    {
        $admin = factory(User::class)->states('admin')->create();

        $company = factory(Company::class)->create();

        $employees = EmployeeFactory::createEmployees($company, 20);

        $employees->take(3)->each->update(['active' => false]);

        $response = $this->actingAs($admin)->json('GET', '/api/v1/employees-count?company_id', [
            'company_id' => $company->id,
            'active' => 'true',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'count' => 17,
        ]);
    }
}
