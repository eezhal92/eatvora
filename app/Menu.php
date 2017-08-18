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

    public function orders()
    {
        return Meal::with('menu')->join('orders', 'orders.id', '=', 'meals.order_id')
            ->join('employees', 'employees.id', '=', 'orders.employee_id')
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->join('offices', 'offices.id', '=', 'employees.office_id')
            ->join('companies', 'companies.id', '=', 'offices.company_id')
            ->where('meals.menu_id', $this->id)
            ->select('meals.menu_id', \DB::raw('users.name as customer_name'), \DB::raw('count(*) as qty'), \DB::raw('companies.name as company_name'), \DB::raw('offices.name as office_name'), \DB::raw('orders.delivery_address as delivery_address'))
            ->groupBy('meals.menu_id', 'customer_name', 'office_name', 'company_name', 'delivery_address')
            ->get();
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
        // @todo add test
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $meals = collect([]);

        foreach (range(1, $quantity) as $i) {
            $meals->push($this->meals()->create(['date' => $date->format('Y-m-d')]));
        }

        return $meals;
    }

    public function getFinalPriceAttribute()
    {
        // @todo add test
        $commissionPercentage = config('eatvora.commission_percentage');

        return $this->roundUpByPerPoint($this->price + ($this->price * $commissionPercentage));
    }

    public function formattedFinalPrice()
    {
        // @todo add test
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
