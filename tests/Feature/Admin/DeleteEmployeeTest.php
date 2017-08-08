<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteEmployeeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_cannot_delete_employee()
    {
        $this->withExceptionHandling();

        factory(Employee::class, 10)->create();

        $response = $this->json('DELETE', '/api/v1/employees/1000');

        $response->assertStatus(401);

        $this->assertEquals(10, Employee::count());
    }

    /** @test */
    public function non_admin_user_cannot_delete_employee()
    {
        $this->withExceptionHandling();

        factory(Employee::class, 10)->create();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('DELETE', '/api/v1/employees/1000');

        $response->assertStatus(401);

        $this->assertEquals(10, Employee::count());
    }

    /** @test */
    public function admin_can_delete_employee()
    {
        factory(Employee::class, 9)->create();
        $employee = factory(Employee::class)->create();

        $admin = factory(User::class)->states('admin')->create();

        $this->assertEquals(10, Employee::count());

        $response = $this->actingAs($admin)->delete('/api/v1/employees/' . $employee->id);

        $response->assertStatus(200);

        $this->assertNull(Employee::find($employee->id));
        $this->assertEquals(9, Employee::count());
    }

    /** @test */
    public function employee_should_be_soft_deleted()
    {
        factory(Employee::class, 9)->create();

        $employee = factory(Employee::class)->create();

        $admin = factory(User::class)->states('admin')->create();

        $this->assertEquals(10, Employee::count());

        $response = $this->actingAs($admin)->delete('/api/v1/employees/' . $employee->id);

        $response->assertStatus(200);

        $this->assertNotNull(Employee::withTrashed()->find($employee->id));
        $this->assertEquals(9, Employee::count());
        $this->assertEquals(10, Employee::withTrashed()->count());
    }

    /** @test */
    public function cannot_delete_company_admin()
    {
        factory(Employee::class, 9)->create();

        $employee = factory(Employee::class)->states('admin')->create();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->delete('/api/v1/employees/' . $employee->id);

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'message' => 'Cannot delete admin of company',
        ]);

        $this->assertNotNull(Employee::find($employee->id));
        $this->assertEquals(10, Employee::count());
    }

    /** @test */
    public function should_get_404_when_attempt_to_delete_non_existing_employee()
    {
        factory(Employee::class, 10)->create();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->delete('/api/v1/employees/1000');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'Cannot delete non existing employee',
        ]);
        $this->assertEquals(10, Employee::count());
    }
}
