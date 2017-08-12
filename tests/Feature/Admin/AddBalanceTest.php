<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use App\CompanyPayment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @nextFeature
 *
 * Both company or user itself can top up.
 */
class AddBalanceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_admin_cannot_perform_request()
    {
        $this->withExceptionHandling();

        $response = $this->json('POST', '/api/v1/balances');

        $response->assertStatus(401);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('POST', '/api/v1/balances');

        $response->assertStatus(401);
    }

    /** @test */
    public function add_balance_for_particular_company()
    {
        $admin = factory(User::class)->states('admin')->create();

        $company = factory(Company::class)->create();

        $officeA = factory(Office::class)->create([
            'company_id' => $company->id,
        ]);

        $officeB = factory(Office::class)->create([
            'company_id' => $company->id,
        ]);

        factory(Employee::class, 20)->create([
            'office_id' => $officeA->id,
        ]);

        factory(Employee::class, 10)->create([
            'office_id' => $officeB->id,
        ]);

        factory(Employee::class, 20)->create();

        $note = 'This is for Aug 14, 2017 - Aug 19, 2017. Here is the link of transfer receipt http://lorempixel.com';

        $response = $this->actingAs($admin)->json('POST', '/api/v1/balances', [
            'amount_per_employee' => 125000,
            'company_id' => 1,
            'note' => $note,
        ]);

        $response->assertStatus(200);

        $this->assertEquals((20 + 10) * 125000, $company->activeEmployees()->sum->balance());

        $companyPayment = CompanyPayment::first();

        $this->assertEquals($company->id, $companyPayment->company_id);
        $this->assertEquals(30, $companyPayment->employee_count);
        $this->assertEquals(125000, $companyPayment->amount_per_employee);
        $this->assertEquals((20 + 10) * 125000, $companyPayment->total_amount);
        $this->assertEquals($note, $companyPayment->note);
    }

    private function assertValidationError($response, $field)
    {
        $response->assertStatus(422);
        $this->assertArrayHasKey($field, $response->decodeResponseJson());
    }

    /** @test */
    public function company_id_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->json('POST', '/api/v1/balances', [
            'amount_per_employee' => 25000,
            'note' => 'Payment note',
        ]);


        $response->assertStatus(422);
        $this->assertValidationError($response, 'company_id');
    }

    /** @test */
    public function amount_per_employee_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->json('POST', '/api/v1/balances', [
            'company_id' => 1,
            'note' => 'Payment note',
        ]);

        $response->assertStatus(422);
        $this->assertValidationError($response, 'amount_per_employee');
    }

    /** @test */
    public function amount_per_employee_must_be_numeric()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->json('POST', '/api/v1/balances', [
            'company_id' => 1,
            'amount_per_employee' => 'not-valid-number',
            'note' => 'Payment note',
        ]);

        $response->assertStatus(422);
        $this->assertValidationError($response, 'amount_per_employee');
    }

    /** @test */
    public function note_is_optional()
    {
        $admin = factory(User::class)->states('admin')->create();

        $employee = factory(Employee::class)->create();

        $employee->load('office.company');

        $response = $this->actingAs($admin)->json('POST', '/api/v1/balances', [
            'company_id' => $employee->office->company->id,
            'amount_per_employee' => 125000,
        ]);

        $response->assertStatus(200);
    }
}
