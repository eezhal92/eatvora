<?php

namespace Tests\Feature;

use App\Company;
use App\Employee;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class HasCorrectCompanySession extends TestCase
{
    use DatabaseMigrations;

    public function company_session_is_set_when_only_have_one_company()
    {
        $company = factory(Company::class)->create();
        
        $user = factory(User::class)->create([
            'email' => 'johndoe@mail.com',
            'password' => bcrypt('password'),
        ]);
        
        $employee = factory(Employee::class)->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'johndoe@mail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302)
            ->assertSessionHas('company_id', $company->id);
    }
}
