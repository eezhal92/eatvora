<?php

namespace Tests\Unit\Lib;

use App\Lib\Cost;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CostTest extends TestCase
{
    /** @test */
    function can_retrieve_vendor_bill()
    {
        $menus = collect([
            new MenuStub(),
            new MenuStub(),
            new MenuStub(),
        ]);
        $cost = new Cost($menus);
        $this->assertEquals(30000, $cost->vendorBill());
    }

    /** @test */
    function can_retrieve_total_cost()
    {
        $menus = collect([
            new MenuStub(),
            new MenuStub(),
            new MenuStub(),
        ]);
        $cost = new Cost($menus);
        $this->assertEquals(33000, $cost->total());
    }

    /** @test */
    function can_retrieve_revenue()
    {
        $menus = collect([
            new MenuStub(),
            new MenuStub(),
            new MenuStub(),
        ]);
        $cost = new Cost($menus);
        $this->assertEquals(3000, $cost->revenue());
    }
}

class MenuStub
{
    public $final_price = 11000;

    public $price = 10000;
}
