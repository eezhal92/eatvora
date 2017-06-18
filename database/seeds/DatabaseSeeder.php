<?php

use App\Menu;
use App\Schedule;
use App\Services\ScheduleService;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private function createVendors($count = 1)
    {
        return factory(Vendor::class, $count)->create();
    }

    private function createMenuByVendor($vendorId, $count = 1)
    {
        return factory(Menu::class, $count)->create([
            'vendor_id' => $vendorId,
        ]);
    }

    private function createSchedule($menuId, $date)
    {
        return factory(Schedule::class)->create([
            'menu_id' => $menuId,
            'date' => $date,
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create(['email' => 'john@example.com']);
        // senin-jum'at lihat hari bsok
        $weekDays = (new ScheduleService())->nextWeekDayDates();

        $vendors = $this->createVendors(3);

        $vendors->each(function ($vendor) use ($weekDays) {
            $menus = $this->createMenuByVendor($vendor->id, 5);

            $weekDays->each(function ($day, $key) use ($menus) {
                $menu = $menus[$key];


                $this->createSchedule($menu->id, $day);
            });
        });
        // $this->call(UsersTableSeeder::class);
    }
}
