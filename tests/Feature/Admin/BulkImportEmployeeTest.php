<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use Tests\TestCase;
use App\Jobs\CreateEmployee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Todo
 * Assert input validation
 * Create unit test of dispatched job
 * Future feature: able to add without creating new user, just relate them into office target
 */
class BulkImportEmployeeTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function can_import_bulk_employee()
    {
        Bus::fake();

        $csvPath = base_path('tests/fixtures/employee.csv');

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->post('/api/v1/employees/bulk', [
            'file' => new UploadedFile($csvPath, 'employee.csv', 'text/csv'),
            'office_id' => factory(Office::class)->create()->id,
        ]);


        Bus::assertDispatched(CreateEmployee::class, function ($job) {
            return $job->name === 'Kyrie Irving' && $job->email === 'kyrie@gmail.com';
        });

        Bus::assertDispatched(CreateEmployee::class, function ($job) {
            return $job->name === 'Steph Curry' && $job->email === 'steph@gmail.com';
        });

        // Remaining Assertions:
        // Emails are sent
        // Users Created
        // When fail, notify / handling
    }
}
