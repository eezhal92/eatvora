<?php

namespace Tests\Unit;

use App\Office;
use App\Company;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function retrieve_main_office()
    {
        $company = factory(Company::class)->create();

        $mainOffice = factory(Office::class)->create([
            'company_id' => $company->id,
            'is_main' => true,
            'name' => 'Main Office',
        ]);

        $anotherOffice = factory(Office::class)->create([
            'company_id' => $company->id,
            'is_main' => false,
            'name' => 'Another Office',
        ]);

        $retrievedOffice = $company->mainOffice();

        $this->assertEquals($mainOffice->id, $retrievedOffice->id);
        $this->assertNotEquals($anotherOffice->id, $retrievedOffice->id);
    }

    /** @test */
    public function retrieve_active_employees()
    {
        $company = factory(Company::class)->create();

        $mainOffice = factory(Office::class)->create([
            'company_id' => $company->id,
            'is_main' => true,
            'name' => 'Main Office',
        ]);

        $anotherOffice = factory(Office::class)->create([
            'company_id' => $company->id,
            'is_main' => false,
            'name' => 'Another Office',
        ]);

        $employeesMainOffice = factory(Employee::class, 10)->create([
            'office_id' => $mainOffice->id,
        ]);

        $employeesAnotherOffice = factory(Employee::class, 5)->create([
            'office_id' => $anotherOffice->id,
        ]);

        $employeesMainOffice->take(2)->each->update(['active' => false]);
        $employeesAnotherOffice->take(1)->each->update(['active' => false]);

        $this->assertEquals(12, $company->activeEmployees()->count());
    }
}
