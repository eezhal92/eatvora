<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public static function forMeals($meals, $employee, $charge)
    {
        $order = self::create([
            'user_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'vendor_bill' => $charge->vendorBill(),
            'revenue' => $charge->revenue(),
            'amount' => $charge->total(),
            'delivery_address' => $employee->office->address,
            'commission_percentage_per_meal' => config('eatvora.commission_percentage'),
        ]);

        $meals->each->claimFor($order);

        return $order;
    }
}
