<?php

namespace Tests\Feature;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HasCorrectCompanySessionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function company_session_is_set_when_only_have_one_company()
    {
        $company = factory(Company::class)->create();

        $office = factory(Office::class)->create([
            'company_id' => $company->id,
        ]);

        $user = factory(User::class)->create([
            'email' => 'johndoe@mail.com',
            'password' => bcrypt('password'),
        ]);

        $employee = factory(Employee::class)->create([
            'office_id' => $office->id,
            'user_id' => $user->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'johndoe@mail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);

        // @todo: Assert correct session
    }
}
