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
    public function guest_cannot_add_employee()
    {
        $this->withExceptionHandling();

        $employee = $this->createEmployee([
            'name' => 'Jason',
            'email' => 'jason@gmail.com',
        ]);

        $response = $this->json('PATCH', '/api/v1/employees/' . $employee->id, [
            'name' => 'Steph Curry',
            'email' => 'curry@gmail.com'
        ]);

        $response->assertStatus(401);

        $employee->refresh();

        $this->assertEquals('Jason', $employee->user->name);
        $this->assertEquals('jason@gmail.com', $employee->user->email);
    }

    /** @test */
    public function non_admin_user_cannot_add_employee()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create();

        $employee = $this->createEmployee([
            'name' => 'Jason',
            'email' => 'jason@gmail.com',
        ]);

        $response = $this->actingAs($user)->json('PATCH', '/api/v1/employees/' . $employee->id, [
            'name' => 'Steph Curry',
            'email' => 'curry@gmail.com'
        ]);

        $response->assertStatus(401);

        $employee->refresh();

        $this->assertEquals('Jason', $employee->user->name);
        $this->assertEquals('jason@gmail.com', $employee->user->email);
    }

    /** @test */
    public function admin_can_edit_existing_employee()
    {
        $employee = $this->createEmployee();

        $office = Office::with('company')->find($employee->office_id);

        $otherOffice = factory(Office::class)->create(['company_id' => $office->company->id]);

        $response = $this->updateEmployeeAsAdmin($employee->id, [
            'office_id' => $otherOffice->id,
            'name' => 'Steph Curry',
            'email' => 'stephcurry@gmail.com',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $employee->user_id,
            'name' => 'Steph Curry',
            'email' => 'stephcurry@gmail.com',
            'office_id' => $otherOffice->id,
        ]);

        $employee->refresh();

        $this->assertEquals('Steph Curry', $employee->user->name);
        $this->assertEquals('stephcurry@gmail.com', $employee->user->email);
        $this->assertEquals($otherOffice->id, $employee->office_id);
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

    /** @test */
    public function office_id_is_optional()
    {
        $this->withExceptionHandling();

        $employee = $this->createEmployee();
        $prevOfficeId = $employee->id;

        $response = $this->updateEmployeeAsAdmin($employee->id, [
            'name' => 'Steph Curry',
            'email' => 'stephcurry@gmail.com',
        ]);

        $response->assertStatus(200);

        $employee->refresh();

        $this->assertEquals('Steph Curry', $employee->user->name);
        $this->assertEquals('stephcurry@gmail.com', $employee->user->email);
        $this->assertEquals($prevOfficeId, $employee->office_id);
    }

    /** @test */
    public function cannot_update_to_another_company_office()
    {
        $this->withExceptionHandling();

        $employee = $this->createEmployee();
        $prevOfficeId = $employee->id;

        $anotherCompanyOffice = factory(Office::class)->create();

        $response = $this->updateEmployeeAsAdmin($employee->id, [
            'office_id' => $anotherCompanyOffice->id,
            'name' => 'Steph Curry',
            'email' => 'stephcurry@gmail.com',
        ]);

        $this->assertValidationError($response, 'office_id');

        $employee->refresh();

        $this->assertNotEquals('Steph Curry', $employee->user->name);
        $this->assertNotEquals('stephcurry@gmail.com', $employee->user->email);
        $this->assertEquals($prevOfficeId, $employee->office_id);
    }
}
