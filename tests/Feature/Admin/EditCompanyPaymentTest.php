<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use App\CompanyPayment;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditCompanyPaymentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_update_payment_note()
    {
        $admin = factory(User::class)->states('admin')->create();

        $payment = factory(CompanyPayment::class)->create([
            'note' => 'Old note',
        ]);

        $response = $this->actingAs($admin)->json('PATCH', "/api/v1/payments/{$payment->id}", [
            'note' => 'Updated note',
        ]);

        $payment->refresh();

        $this->assertEquals('Updated note', $payment->note);
    }

    /** @test */
    public function can_update_with_empty_note()
    {
        $admin = factory(User::class)->states('admin')->create();

        $payment = factory(CompanyPayment::class)->create([
            'note' => 'Old note',
        ]);

        $response = $this->actingAs($admin)->json('PATCH', "/api/v1/payments/{$payment->id}", [
            'note' => '',
        ]);

        $payment->refresh();

        $this->assertEquals('', $payment->note);
    }

    /** @test */
    public function only_able_to_update_note_field()
    {
        $admin = factory(User::class)->states('admin')->create();

        $payment = factory(CompanyPayment::class)->create([
            'company_id' => 1,
            'note' => 'Old note',
            'total_amount' => 200000,
            'amount_per_employee' => 100000,
            'employee_count' => 2,
        ]);

        $response = $this->actingAs($admin)->json('PATCH', "/api/v1/payments/{$payment->id}", [
            'company_id' => 2,
            'note' => 'Updated note',
            'total_amount' => 999999,
            'amount_per_employee' => 11111,
            'employee_count' => 3,
        ]);

        $payment->refresh();

        $this->assertEquals(1, $payment->company_id);
        $this->assertEquals('Updated note', $payment->note);
        $this->assertEquals(200000, $payment->total_amount);
        $this->assertEquals(100000, $payment->amount_per_employee);
        $this->assertEquals(2, $payment->employee_count);
    }
}
