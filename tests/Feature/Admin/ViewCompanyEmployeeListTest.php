<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use Tests\TestCase;
use PHPUnit\Framework\Assert;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewCompanyEmployeeListTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Collection::macro('assertEquals', function ($items) {
            Assert::assertEquals(count($this), count($items));

            $this->zip($items)->each(function ($pair) {
                list($a, $b) = $pair;
                Assert::assertTrue($a->is($b));
            });
        });
    }
    /** @test */
    public function admin_can_view_a_list_of_employee_of_particular_company()
    {
        $company = factory(Company::class)->create();

        $officeA = factory(Office::class)->states('main')->create(['company_id' => $company->id]);
        $officeB = factory(Office::class)->create(['company_id' => $company->id]);

        $employeesOfficeA = factory(Employee::class, 5)->create(['office_id' => $officeA->id]);
        $employeesOfficeB = factory(Employee::class, 5)->create(['office_id' => $officeB->id]);

        $otherUser = factory(User::class)->create([
            'name' => 'Matt Hilda',
        ]);
        $otherEmployee = factory(Employee::class)->create();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get("/ap/companies/{$company->id}/employees");

        $this->assertEquals(
            $response->data('employees')->pluck('id')->toArray(),
            array_merge($employeesOfficeA->pluck('id')->toArray(), $employeesOfficeB->pluck('id')->toArray())
        );
    }

    /** @test */
    public function cannot_view_non_existing_company_employee_list()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->get('/ap/companies/1000/employees');

        $response->assertStatus(404);
    }

    /** @test */
    public function can_search_employee_by_name()
    {
        $admin = factory(User::class)->states('admin')->create([
            'name' => 'Firmino',
            'email' => 'firmino@gmail.com',
        ]);

        $company = factory(Company::class)->create();
        $office = factory(Office::class)->states('main')->create(['company_id' => $company->id]);

        $user = factory(User::class)->create([
            'name' => 'John Doe',
        ]);

        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
            'office_id' => $office->id,
        ]);

        $anotherUser = factory(User::class)->create([
            'name' => 'Bill Joe',
            'email' => 'billy@gmail.com',
        ]);
        factory(Employee::class, 10)->create([
            'office_id' => $office->id,
            'user_id' => $anotherUser->id,
        ]);

        $response = $this->actingAs($admin)->get(
            sprintf('/ap/companies/%d/employees?query=john', $company->id)
        );

        $employeesFromResponse = $response->data('employees');

        $this->assertEquals(1, $employeesFromResponse->count());
        $employeesFromResponse->pluck('id')->assertContains($employee->id);
    }
}
