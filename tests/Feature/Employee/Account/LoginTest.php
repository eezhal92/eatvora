<?php

namespace Tests\Feature\Employee\Account;

use App\User;
use App\Company;
use Tests\TestCase;
use EmployeeFactory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function logging_in_successfully()
    {
        $company = factory(Company::class)->create();

        $employee = EmployeeFactory::createOne($company, [
            'email' => 'john@mail.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'john@mail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }

    /** @test */
    public function has_correct_session_after_logged_in()
    {
        $company = factory(Company::class)->create();

        $user = factory(User::class)->create(['email' => 'john@mail.com', 'password' => bcrypt('password')]);

        $employee = EmployeeFactory::createOne($company, $user);

        $response = $this->actingAs($user)->get('/home');

        $response->assertSessionHas('company_id', $company->id);
        $response->assertSessionHas('employee_id', $employee->id);
    }
}
