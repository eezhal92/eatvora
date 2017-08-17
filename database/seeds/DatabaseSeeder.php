<?php

use App\Menu;
use App\User;
use App\Office;
use App\Vendor;
use App\Balance;
use App\Company;
use App\Employee;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Services\ScheduleService;

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
        $menu = Menu::find($menuId);

        $menu->scheduleMeals($date, 3);
    }

    private function setUpVendor()
    {
        // senin-jum'at lihat hari bsok
        $weekDays = (new ScheduleService())->nextWeekDayDates();

        $vendors = $this->createVendors(12);

        $vendors->each(function ($vendor) use ($weekDays) {
            $menus = $this->createMenuByVendor($vendor->id, 5);

            $weekDays->each(function ($day, $key) use ($menus) {
                $menu = $menus[$key];

                $this->createSchedule($menu->id, $day->format('Y-m-d'));
            });
        });
    }

    private function setUpCompany()
    {
        factory(User::class)->states('admin')->create(['email' => 'admin@mail.com', 'password' => bcrypt('password')]);

        $userA = factory(User::class)->create(['email' => 'john@mail.com', 'password' => bcrypt('password')]);
        $userB = factory(User::class)->create(['email' => 'jane@mail.com', 'password' => bcrypt('password')]);

        $company = factory(Company::class)->create(['name' => 'Traveloka']);

        foreach (range(1, 15) as $i) {
            $c = factory(Company::class)->create();
            $o = factory(Office::class)->states('main')->create(['company_id' => $c->id]);
            $u = factory(User::class)->create();
            $a = factory(Employee::class)->states('admin')->create([
                'user_id' => $u->id,
                'office_id' => $o->id,
            ]);
        }

        $office = factory(Office::class)->create([
            'company_id' => $company->id,
            'name' => 'Operation & Customer Care',
            'address' => 'Wisma 77 Tower 1, 7th floor Jl. S. Parman Kav. 77',
            'is_main' => true,
        ]);

        $officeB = factory(Office::class)->create([
            'company_id' => $company->id,
            'name' => 'Development HQ',
            'address' => 'Wisma 77 Tower 2, 21st floor Jl. S. Parman Kav. 77',
        ]);

        foreach (range(1, 10) as $number) {
            factory(Office::class)->create([
                'company_id' => $company->id,
            ]);
        }

        foreach (range(1, 25) as $number) {
            $u = factory(User::class)->create();
            factory(Employee::class)->create([
                'user_id' => $u->id,
                'office_id' => $office->id,
            ]);
        }

        $employeeA = factory(Employee::class)->create([
            'user_id' => $userA->id,
            'is_admin' => true,
            'office_id' => $office->id,
        ]);

        $employeeB = factory(Employee::class)->create([
            'user_id' => $userB->id,
            'office_id' => $officeB->id,
        ]);

        Balance::employeeTopUp($employeeA, 150000);
        Balance::employeeTopUp($employeeB, 150000);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setUpCompany();

        $this->setUpVendor();
    }
}
