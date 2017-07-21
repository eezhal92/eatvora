<?php

namespace Tests\Unit;

use App\Office;
use App\Company;
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
}
