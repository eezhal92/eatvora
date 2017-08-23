<?php

namespace App;

use App\Vendor;
use Carbon\Carbon;
use App\Services\ScheduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes, MenuConsent;

    protected $guarded = [];

    protected $appends = ['final_price', 'nextweek_remaining_qty', 'nextweek_available_qty'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function orders($date)
    {
        return Meal::with('menu')->join('orders', 'orders.id', '=', 'meals.order_id')
            ->join('employees', 'employees.id', '=', 'orders.employee_id')
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->join('offices', 'offices.id', '=', 'employees.office_id')
            ->join('companies', 'companies.id', '=', 'offices.company_id')
            ->where('meals.menu_id', $this->id)
            ->where('meals.date', $date)
            ->select(
                'meals.menu_id',
                \DB::raw('users.name as customer_name'),
                \DB::raw('count(*) as qty'),
                \DB::raw('companies.name as company_name'),
                \DB::raw('offices.name as office_name'),
                \DB::raw('orders.delivery_address as delivery_address'),
                \DB::raw('meals.price as meal_price'),
                \DB::raw('meals.date as meal_date')
            )
            ->groupBy(
                'customer_name',
                'office_name',
                'company_name',
                'delivery_address',
                'meal_price',
                'meals.menu_id',
                'meal_date'
            )
            ->get();
    }

    public function vendorName()
    {
        return $this->vendor->name;
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
            $meals->push($this->meals()->create([
                'date' => $date->format('Y-m-d'),
                'price' => $this->price,
            ]));
        }

        return $meals;
    }

    public function getNextWeekRemainingQtyAttribute()
    {
        $range = app()->make(ScheduleService::class)->nextWeekDaysRange();

        return $this->meals()->whereBetween('date', $range)
            ->whereNull('order_id')
            ->count();
    }

    public function getNextWeekAvailableQtyAttribute()
    {
        $range = app()->make(ScheduleService::class)->nextWeekDaysRange();

        return $this->meals()->whereBetween('date', $range)->count();
    }
}
