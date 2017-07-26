<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewVendorListTest extends TestCase
{
    use DatabaseMigrations;

    private function createVendors()
    {
        $vendors = [
            'Susi Tei',
            'Dapur Lulu',
            'Mc Donald',
            'D Good Food',
            'Revans Cafe',
            'Vendor 6',
            'Vendor 7',
            'Vendor 8',
            'Vendor 9',
            'Vendor 10',
            'Vendor 11',
        ];

        foreach ($vendors as $vendor) {
            factory(Vendor::class)->create([
                'name' => $vendor,
            ]);
        }
    }

    /** @test */
    public function admin_can_view_list_of_vendors_with_default_limit_10()
    {
        $this->createVendors();

        $admin = factory(User::class)->states('admin')->create();
        $response = $this->actingAs($admin)->get('/ap/vendors');

        $response->assertSee('Susi Tei');
        $response->assertSee('Dapur Lulu');
        $response->assertSee('Mc Donald');
        $response->assertSee('Mc Donald');
        $response->assertSee('D Good Food');
        $response->assertSee('Revans Cafe');
        $response->assertSee('Vendor 6');
        $response->assertSee('Vendor 7');
        $response->assertSee('Vendor 8');
        $response->assertSee('Vendor 9');
        $response->assertSee('Vendor 10');
        $response->assertDontSee('Vendor 11');
        $response->assertDontSee('Vendor 12');
    }
}
