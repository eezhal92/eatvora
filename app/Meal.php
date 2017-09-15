<?php

namespace App;

use Carbon\Carbon;
use App\Services\ScheduleService;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use MenuConsent;

    protected $guarded = [];

    protected $dates = ['reserved_at'];

    protected $appends = ['point', 'final_price', 'image_url', 'categories', 'nextweek_remaining_qty', 'nextweek_available_qty'];

    public function scopeAvailable($query)
    {
        return $query->whereNull('order_id')->whereNull('reserved_at');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function categories()
    {
        return $this->menu->categories();
    }

    public function getCategoriesAttribute()
    {
        return $this->categories()->get()->pluck('name');
    }

    public function getNextWeekRemainingQtyAttribute()
    {
        $range = app()->make(ScheduleService::class)->nextWeekDaysRange();

        return static::whereBetween('date', $range)
            ->where('menu_id', $this->menu_id)
            ->whereNull('order_id')
            ->count();
    }

    public function getNextWeekAvailableQtyAttribute()
    {
        $range = app()->make(ScheduleService::class)->nextWeekDaysRange();

        return static::whereBetween('date', $range)
            ->where('menu_id', $this->menu_id)
            ->count();
    }

    public function getImageUrlAttribute()
    {
        $path = $this->menu->image_path;

        if (!$path) {
            return asset('/images/menu-placeholder.png');
        }

        return \Storage::disk('public')->url($path);
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
