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

    /** @test */
    public function can_get_final_price()
    {
        config([
            'eatvora.commission_percentage' => 0.1,
            'eatvora.rupiah_per_point' => 500,
        ]);

        $menu = factory(Menu::class)->make([
            'price' => 20000,
        ]);

        $this->assertEquals(20000, $menu->price);
        $this->assertEquals(22000, $menu->final_price);

        config([
            'eatvora.commission_percentage' => 0.15,
            'eatvora.rupiah_per_point' => 500,
        ]);

        $menu = factory(Menu::class)->make([
            'price' => 20000,
        ]);

        $this->assertEquals(20000, $menu->price);
        $this->assertEquals(23000, $menu->final_price);
    }

    /** @test */
    public function can_get_point()
    {
        config([
            'eatvora.commission_percentage' => 0.1,
            'eatvora.rupiah_per_point' => 500,
        ]);

        $menu = factory(Menu::class)->make([
            'price' => 20000,
        ]);

        $this->assertEquals(22000, $menu->final_price);
        $this->assertEquals(44, $menu->point);

        config([
            'eatvora.commission_percentage' => 0.15,
            'eatvora.rupiah_per_point' => 500,
        ]);

        $menu = factory(Menu::class)->make([
            'price' => 20000,
        ]);

        $this->assertEquals(23000, $menu->final_price);
        $this->assertEquals(46, $menu->point);
    }

    /**
     * Provider for 10% commission and Rp. 500 / point
     * @return array
     */
    public function finalPriceAndPointFor10PercentCommissionAnd500RupiahPerPointProvider()
    {
        return [
            /** name, price, final price, point */
            ['Nasi Goreng', 20000, 22000, 44],
            ['Nasi Ayam', 18000, 20000, 40],
            ['Rendang', 13000, 14500, 29],
            ['Burger', 14200, 16000, 32],
            ['Sate', 12000, 13500, 27],
        ];
    }

    /**
     * Provider for 15% commission and Rp. 500 / point
     * @return array
     */
    public function finalPriceAndPointFor15PercentCommissionAnd500RupiahPerPointProvider()
    {
        return [
            /** name, price, final price, point */
            ['Nasi Goreng', 20000, 23000, 46],
            ['Nasi Ayam', 18000, 21000, 42],
            ['Rendang', 13000, 15000, 30],
            ['Burger', 14200, 16500, 33],
            ['Sate', 12000, 14000, 28],
        ];
    }

    /**
     * @test
     * @dataProvider finalPriceAndPointFor10PercentCommissionAnd500RupiahPerPointProvider
     */
    public function ten_percent_commission_and_500_rupiah_per_point($name, $price, $finalPrice, $point)
    {
        $meal = factory(Menu::class)->create(compact('name', 'price'));

        $this->assertEquals($finalPrice, $meal->final_price);
        $this->assertEquals($point, $meal->point);
    }

    /**
     * @test
     * @dataProvider finalPriceAndPointFor15PercentCommissionAnd500RupiahPerPointProvider
     */
    public function fifteen_percent_commission_and_500_rupiah_per_point($name, $price, $finalPrice, $point)
    {
        config([
            'eatvora.commission_percentage' => 0.15,
            'eatvora.rupiah_per_point' => 500,
        ]);

        $meal = factory(Menu::class)->create(compact('name', 'price'));

        $this->assertEquals($finalPrice, $meal->final_price);
        $this->assertEquals($point, $meal->point);
    }
}
