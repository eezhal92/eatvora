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

    public static function forMeals($meals, $employee, $amount)
    {
        $order = self::create([
            'user_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'amount' => $amount,
        ]);

        $meals->each->claimFor($order);

        return $order;
    }

    public function vendorBill()
    {
        // @todo: add test
        return $this->meals()->with('menu')->get()->map(function ($meal) {
            return $meal->menu->price;
        })->sum();
    }

    public function revenue()
    {
        // @todo: add test
        return $this->amount - $this->vendorBill();
    }
}
