<?php

use App\Company;
use App\Employee;
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
        $user = factory(User::class)->create(['email' => 'john@mail.com', 'password' => bcrypt('password')]);
        $company = factory(Company::class)->create(['name' => 'Traveloka']);
        factory(Employee::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);

        // senin-jum'at lihat hari bsok
        $weekDays = (new ScheduleService())->nextWeekDayDates();

        $vendors = $this->createVendors(9);

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
