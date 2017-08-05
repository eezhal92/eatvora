<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditEmployeeStatusTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_update_employee_active_status()
    {
        $admin = factory(User::class)->states('admin')->create();

        $employee = factory(Employee::class)->create([
            'active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->json('PATCH', sprintf('/api/v1/employees/%d/active', $employee->id), [
                'status' => false,
            ]);

        $response->assertStatus(200);

        $employee->refresh();

        $this->assertFalse($employee->active);

        $response = $this->actingAs($admin)
            ->json('PATCH', sprintf('/api/v1/employees/%d/active', $employee->id), [
                'status' => true,
            ]);

        $response->assertStatus(200);

        $employee->refresh();

        $this->assertTrue($employee->active);
    }

    /** @test */
    public function cannot_update_status_of_non_existing_employee()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->json('PATCH', '/api/v1/employees/1000/active', [
                'status' => false,
            ]);

        $response->assertStatus(404);
    }
}
