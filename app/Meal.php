<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $guarded = [];

    protected $dates = ['reserved_at'];

    public function scopeAvailable($query)
    {
        return $query->whereNull('order_id')->whereNull('reserved_at');
    }

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

    public function reserve()
    {
        $this->update(['reserved_at' => Carbon::now()]);
    }

    public function release()
    {
        $this->update(['reserved_at' => null]);
    }
}
