<?php

namespace App;

use App\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['final_price'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function vendorName()
    {
        return $this->vendor->name;
    }

    public function formattedPrice()
    {
        return 'Rp. ' . number_format($this->price);
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image_path;

        if (!$path) {
            return '';
        }

        return \Storage::disk('public')->url($path);
    }

    public function scheduleMeals($date, $quantity)
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        foreach (range(1, $quantity) as $i) {
            $this->meals()->create(['date' => $date->format('Y-m-d H:i:s')]);
        }

        return $this;
    }

    public function getFinalPriceAttribute()
    {
        $commissionPercentage = config('eatvora.commission_percentage');

        return $this->roundUpByPerPoint($this->price + ($this->price * $commissionPercentage));
    }

    public function formattedFinalPrice()
    {
        return 'Rp. ' . number_format($this->final_price);
    }

    public function getPointAttribute()
    {
        return $this->final_price / config('eatvora.rupiah_per_point');
    }


    private function roundUpByPerPoint($price)
    {
        if ($this->isMultipleOfRupiahPerPoint($price)) {
            return $price;
        }

        return $this->roundUpToNearestMultiply($price, config('eatvora.rupiah_per_point'));
    }

    private function roundUpToNearestMultiply($price, $multiply)
    {
        return ceil($price / $multiply) * $multiply;
    }

    private function isMultipleOfRupiahPerPoint($price)
    {
        return $price % config('eatvora.rupiah_per_point') === 0;
    }
}
