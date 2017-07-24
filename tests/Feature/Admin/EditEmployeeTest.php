<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditEmployeeTest extends TestCase
{
    use DatabaseMigrations;

    private function assertValidationError($response, $field)
    {
        $response->assertStatus(422);
        $this->assertArrayHasKey($field, $response->decodeResponseJson());
    }

    private function updateEmployeeAsAdmin($employeeId, $fields = [])
    {
        $admin = factory(User::class)->states('admin')->create();

        return $this->actingAs($admin)->json('PATCH', "/api/v1/employees/{$employeeId}", $fields);
    }

    private function createEmployee($fields = [])
    {
        $fields = array_merge([
            'name' => 'Kyrie Irving',
            'email' => 'kyrie.11@gmail.com',
        ], $fields);

        $user = factory(User::class)->create($fields);
        $company = factory(Company::class)->create();
        $office = factory(Office::class)->create([
            'company_id' => $company->id,
        ]);

        return factory(Employee::class)->create([
            'user_id' => $user->id,
            'office_id' => $office->id,
        ]);
    }

    /** @test */
    public function admin_can_edit_existing_employee()
    {
        $employee = $this->createEmployee();

        $response = $this->updateEmployeeAsAdmin($employee->id, [
            'office_id' => $employee->office_id,
            'name' => 'Steph Curry',
            'email' => 'stephcurry@gmail.com',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $employee->user_id,
            'name' => 'Steph Curry',
            'email' => 'stephcurry@gmail.com',
        ]);
    }

    /** @test */
    public function can_update_with_email_remains_same()
    {
        $employee = $this->createEmployee([
            'name' => 'Kyrie',
            'email' => 'awesome@email.com',
        ]);

        $response = $this->updateEmployeeAsAdmin($employee->id, [
            'office_id' => $employee->office_id,
            'name' => 'Steph Curry',
            'email' => 'awesome@gmail.com',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $employee->user_id,
            'name' => 'Steph Curry',
            'email' => 'awesome@gmail.com',
        ]);
    }

    /** @test */
    public function cannot_update_non_existing_employee()
    {
        $office = factory(Office::class)->create();

        $response = $this->updateEmployeeAsAdmin(1000, [
            'office_id' => $office->id,
            'name' => 'King James',
            'email' => 'king23@gmail.com',
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $employee = $this->createEmployee();

        $response = $this->updateEmployeeAsAdmin($employee->id, [
            'office_id' => $employee->office_id,
            'name' => '',
            'email' => 'stephcurry@gmail.com',
        ]);

        $this->assertValidationError($response, 'name');
    }

    /** @test */
    public function name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $employee = $this->createEmployee();

        $response = $this->updateEmployeeAsAdmin($employee->id, [
            'office_id' => $employee->office_id,
            'name' => 'aa',
            'email' => 'stephcurry@gmail.com',
        ]);

        $this->assertValidationError($response, 'name');
    }

    /** @test */
    public function email_is_required()
    {
        $this->withExceptionHandling();

        $employee = $this->createEmployee();

        $response = $this->updateEmployeeAsAdmin($employee->id, [
            'office_id' => $employee->office_id,
            'name' => 'Steph Curry',
            'email' => '',
        ]);

        $this->assertValidationError($response, 'email');
    }
}
