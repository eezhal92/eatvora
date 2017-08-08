<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use Tests\TestCase;
use App\Facades\RandomPassword;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeRegistrationEmail;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddEmployeeTest extends TestCase
{
    use DatabaseMigrations;

    private $response;

    public function setUp()
    {
        parent::setUp();

        Mail::fake();
    }

    private function assertResponseStatus($status)
    {
        $this->response->assertStatus($status);
    }

    private function decodeResponseJson()
    {
        return $this->response->decodeResponseJson();
    }

    private function addEmployeeAsAdmin($fields = [])
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->response = $this->actingAs($admin)->json('POST', '/api/v1/employees', $fields);

        return $this->response;
    }

    private function assertValidationError($field)
    {
        $this->assertResponseStatus(422);
        $this->assertArrayHasKey($field, $this->decodeResponseJson());
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'office_id' => factory(Office::class)->create()->id,
            'name' => '',
            'email' => 'jordan@mail.com',
        ], $overrides);
    }

    /** @test */
    public function guest_cannot_add_employee()
    {
        $this->withExceptionHandling();

        $response = $this->json('POST', '/api/v1/employees', $this->validParams());

        $response->assertStatus(401);

        $this->assertEquals(0, Employee::count());
    }

    /** @test */
    public function non_admin_user_cannot_add_employee()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('POST', '/api/v1/employees', $this->validParams());

        $response->assertStatus(401);

        $this->assertEquals(0, Employee::count());
    }

    /** @test */
    public function add_valid_employee()
    {
        RandomPassword::shouldReceive('generate')->andReturn('tmp-scrt');

        $admin = factory(User::class)->states('admin')->create();

        $company = factory(Company::class)->create();
        $office = factory(Office::class)->states('main')->create([
            'company_id' => $company->id,
        ]);

        $response = $this->addEmployeeAsAdmin([
            'office_id' => $office->id,
            'name' => 'Michael Jordan',
            'email' => 'jordan@mail.com',
        ]);

        $employee = Employee::with('user')->first();
        $employeeUser = $employee->user;

        // assert data is stored into db
        $this->assertEquals($office->id, $employee->office_id);
        $this->assertFalse($employee->is_admin);
        $this->assertEquals('Michael Jordan', $employee->user->name);
        $this->assertEquals('jordan@mail.com', $employee->user->email);

        // assert response
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'id' => $employee->id,
            'name' => 'Michael Jordan',
            'email' => 'jordan@mail.com',
        ]);

        // assert email to employee is sent with content email, password
        Mail::assertSent(EmployeeRegistrationEmail::class, function ($mail) use ($company, $employeeUser) {
            return $mail->hasTo('jordan@mail.com') // @fixme: Need to refactor
                && $mail->user->email == $employeeUser->email
                && $mail->company->id === $company->id
                && $mail->password === 'tmp-scrt';
        });
    }

    /** @test */
    public function cannot_add_employee_when_office_is_not_exists()
    {
        $admin = factory(User::class)->states('admin')->create();

        $company = factory(Company::class)->create();

        $response = $this->addEmployeeAsAdmin([
            'office_id' => 1000,
            'name' => 'Michael Jordan',
            'email' => 'jordan@mail.com',
        ]);

        $response->assertStatus(422);
        $this->assertValidationError('office_id');
        $this->assertEquals(0, Employee::count());
    }

    /** @test */
    public function name_is_required()
    {
        $this->withExceptionHandling();

        $office = factory(Office::class)->states('main')->create();

        $response = $this->addEmployeeAsAdmin([
            'office_id' => $office->id,
            'name' => '',
            'email' => 'jordan@mail.com',
        ]);

        $this->assertValidationError('name');
    }

    /** @test */
    public function name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $office = factory(Office::class)->states('main')->create();

        $response = $this->addEmployeeAsAdmin([
            'office_id' => $office->id,
            'name' => 'Dw',
            'email' => 'jordan@mail.com',
        ]);

        $this->assertValidationError('name');
    }

    /** @test */
    public function email_is_required()
    {
        $this->withExceptionHandling();

        $office = factory(Office::class)->states('main')->create();

        $response = $this->addEmployeeAsAdmin([
            'office_id' => $office->id,
            'name' => 'Jordan Michael',
            'email' => '',
        ]);

        $this->assertValidationError('email');
    }

    /** @test */
    public function email_must_be_a_valid_email()
    {
        $this->withExceptionHandling();

        $office = factory(Office::class)->states('main')->create();

        $response = $this->addEmployeeAsAdmin([
            'office_id' => $office->id,
            'name' => 'Jordan Michael',
            'email' => 'not-valid-email',
        ]);

        $this->assertValidationError('email');
    }

        /** @test */
    public function email_must_be_unique()
    {
        $this->withExceptionHandling();

        $office = factory(Office::class)->states('main')->create();

        $response = $this->addEmployeeAsAdmin([
            'office_id' => $office->id,
            'name' => 'Michael Jordan',
            'email' => 'michael@mail.com',
        ]);

        $response->assertStatus(201);

        $officeB = factory(Office::class)->states('main')->create();

        $this->addEmployeeAsAdmin([
            'office_id' => $officeB->id,
            'name' => 'Michael Jackson',
            'email' => 'michael@mail.com',
        ]);

        $this->assertValidationError('email');
    }
}
