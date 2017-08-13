<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $guarded = [];

    protected $dates = ['reserved_at'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function claimFor($order)
    {
        $this->update([
            'order_id' => $order->id,
        ]);
    }
}
