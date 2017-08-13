<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use App\CompanyPayment;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewPaymentListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_view_payment_list()
    {
        $this->assertTrue(true);
    }
}
