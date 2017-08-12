<?php

namespace Tests\Unit;

use App\Balance;
use App\Office;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BalanceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_top_up_for_employee()
    {
        $office = factory(Office::class)->create();
        $employee = factory(Employee::class)->create(['office_id' => $office->id]);

        Balance::employeeTopUp($employee, 25000);

        $balance = Balance::first();

        $this->assertEquals($employee->user_id, $balance->user_id);
        $this->assertEquals($balance->amount, 25000);
        $this->assertEquals(Balance::TOP_UP, $balance->type);
        $this->assertEquals(sprintf('Top up from company %s', $office->company->name), $balance->description);
    }
}
