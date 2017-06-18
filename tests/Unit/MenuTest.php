<?php

namespace Tests\Unit;

use App\Menu;
use App\Vendor;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_get_vendor_name()
    {
        $vendor = factory(Vendor::class)->make([
            'name' => 'Dapur Lulu',
        ]);

        $menu = factory(Menu::class)->make([
            'name' => 'Nasi Padang',
        ]);

        $menu->vendor()->associate($vendor);

        $this->assertEquals('Dapur Lulu', $menu->vendorName());
    }

    /** @test */
    public function can_get_formatted_price()
    {
        $menu = factory(Menu::class)->make([
            'price' => 40500,
        ]);

        $this->assertEquals('Rp. 40,500', $menu->formattedPrice());
    }
}
