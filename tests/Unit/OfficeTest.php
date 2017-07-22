<?php

namespace Tests\Unit;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OfficeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function retrieve_admin_of_main_office()
    {
        $company = factory(Company::class)->create();
        $office = factory(Office::class)->states('main')->create(['company_id' => $company->id]);

        $user = factory(User::class)->create();
        $admin = factory(Employee::class)->states('admin')->create([
            'office_id' => $office->id,
            'user_id' => $user->id,
        ]);

        $this->assertEquals($admin->id, $office->admin()->id);
    }
}
