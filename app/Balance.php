<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $guarded = [];

    const TOP_UP = 'top_up';

    const PAYMENT = 'payment';

    public static function employeeTopUp($employee, $amount)
    {
        $employee->load('office.company', 'user');

        static::create([
            'user_id' => $employee->user->id,
            'type' => static::TOP_UP,
            'amount' => $amount,
            'description' => sprintf('Top up from company %s', $employee->office->company->name),
        ]);
    }

    public static function payOrder($amount, $employee)
    {
        return static::create([
            'user_id' => $employee->user->id,
            'type' => static::PAYMENT,
            'amount' => -$amount,
        ]);
    }

    public function formattedAmount()
    {
        $formattedAmount = number_format((string) abs($this->amount));

        if ($this->amount < 0) {
            return sprintf('- Rp. %s', $formattedAmount);
        }

        return sprintf('+ Rp. %s', $formattedAmount);
    }
}
