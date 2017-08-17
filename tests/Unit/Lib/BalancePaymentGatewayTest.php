<?php

namespace Tests\Unit\Lib;

use App\Balance;
use App\Employee;
use Tests\TestCase;
use App\Lib\BalancePaymentGateway;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BalancePaymentGatewayTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function running_a_hook_before_the_first_charge()
    {
        // @todo create fake implementation class
        $paymentGateway = new BalancePaymentGateway;
        $timesCallbackRan = 0;
        $employee = factory(Employee::class)->create();
        Balance::employeeTopUp($employee, 50000);

        $paymentGateway->beforeFirstCharge(function ($paymentGateway) use (&$timesCallbackRan, $employee) {
            $timesCallbackRan++;
            $paymentGateway->charge($employee, 2500);
            $this->assertEquals(2500, $paymentGateway->totalCharges());
        });

        $paymentGateway->charge($employee, 2500);
        $this->assertEquals(1, $timesCallbackRan);
        $this->assertEquals(5000, $paymentGateway->totalCharges());
    }
}
