<?php

namespace Tests\Unit;

use App\User;
use App\Balance;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function retrieve_user_balance()
    {
        $user = factory(User::class)->create();
        $employee = factory(Employee::class)->create(['user_id' => $user]);

        Balance::create([
            'user_id' => $user->id,
            'amount' => 50000,
            'type' => Balance::TOP_UP,
        ]);

        Balance::create([
            'user_id' => $user->id,
            'amount' => 100000,
            'type' => Balance::TOP_UP,
        ]);

        Balance::create([
            'user_id' => $user->id,
            'amount' => -25000,
            'type' => Balance::PAYMENT,
            'description' => 'Payment for meal order at Aug 14, 2017 - Aug 19, 2017',
        ]);

        $this->assertEquals(125000, $user->balance());
    }
}
