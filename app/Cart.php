<?php

namespace App;

use Carbon\Carbon;
use App\Services\ScheduleService;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotEnoughMealsException;
use App\Exceptions\IncorrectMealDateException;

class Cart extends Model
{
    const NEXT_WEEK_ORDER_PLACED = 'placed';

    const NEXT_WEEK_ORDER_NOT_PLACED_YET = 'not_placed_yet';

    protected $guarded = [];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'cart_items', 'cart_id', 'menu_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getAlreadyPlacedOrderAttribute()
    {
        return !!$this->order_id;
    }

    /**
     * Create or retrieve employee cart for next week
     *
     * @param  Employee $employee
     * @return self
     */
    public static function of(Employee $employee)
    {
        $schedule = app()->make(ScheduleService::class);

        $days = $schedule->nextWeekDayDates();

        $cart = static::where('employee_id', $employee->id)
            ->where('start_date', $days->first()->format('Y-m-d'))
            ->where('end_date', $days->last()->format('Y-m-d'))
            ->first();

        if (is_null($cart)) {
            return static::create([
                'employee_id' => $employee->id,
                'start_date' => $days->first()->format('Y-m-d'),
                'end_date' => $days->last()->format('Y-m-d'),
            ]);
        }

        return $cart;
    }

    private function findItem($menuId, $date)
    {
        return $this->cartItems()
            ->where('menu_id', $menuId)
            ->where('date', $date->format('Y-m-d'))
            ->first();
    }

    public function addItem($menu, $qty, $date)
    {
        if (is_numeric($menu)) {
            $menu = Menu::find($menu)->first();
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $schedule = app()->make(ScheduleService::class);

        // dd(Meal::all()->toArray());
        // dd(Carbon::now());
        // dd($schedule->nextWeekDaysRange());
        // dd($menu->meals()->get()->toArray());
        $mealCount = $menu->meals()->whereBetween('date', $schedule->nextWeekDaysRange())->count();

        // dd($schedule->nextWeekDaysRange());

        if (!$mealCount) {
            throw new IncorrectMealDateException("{$menu->name} is not scheduled for next week.");
        }

        $foundItem = $this->cartItems()
            ->where('menu_id', $menu->id)
            ->where('date', $date->format('Y-m-d'))
            ->first();

        if (!$foundItem) {
            $this->cartItems()->create([
                'cart_id' => $this->id,
                'menu_id' => $menu->id,
                'qty' => $qty,
                'date' => $date->format('Y-m-d'),
            ]);

            return;
        }

        $foundItem->qty = $foundItem->qty + $qty;
        $foundItem->save();
    }

    public function updateItem($menuId, $qty, $date)
    {
        // @todo: cover unit test
        $item = $this->findItem($menuId, $date);

        if (!$item) {
            throw new \Exception('Menu item is not found');
        }

        $item->qty = $qty;
        $item->save();
    }

    public function removeItem($menuId, $date)
    {
        // @todo: cover unit test
        $item = $this->findItem($menuId, $date);

        if (!$item) {
            throw new \Exception('Menu item is not found');
        }

        $item->delete();
    }

    public function items()
    {
        return Menu::join('cart_items', 'cart_items.menu_id', '=', 'menus.id')
            ->join('vendors', 'menus.vendor_id', '=', 'vendors.id')
            ->join('carts', 'carts.id', '=', 'cart_items.cart_id')
            ->where('carts.start_date', $this->start_date)
            ->where('carts.end_date', $this->end_date)
            ->select('menus.*', \DB::raw('cart_items.id as cart_item_id'), 'cart_items.qty', 'cart_items.date', 'vendors.name as vendorName')
            ->get();
    }

    public function removeItems()
    {
        return CartItem::where('cart_id', $this->id)->delete();
    }

    private function findMeals()
    {
        return $this->items()->map(function ($item) {
            $meals = Meal::where('menu_id', $item->id)
                ->where('date', Carbon::parse($item->date)->format('Y-m-d'))
                ->available()
                ->take($item->qty)
                ->get();

            if ($meals->count() < $item->qty) {
                throw new NotEnoughMealsException("Maaf, Stok menu {$item->name} tinggal {$meals->count()}.");
            }

            return $meals;
        })->flatten();
    }

    public function reserveMeals()
    {
        $meals = $this->findMeals()->each(function ($meal) {
            $meal->reserve();
        });

        return new Reservation($this, $meals, $this->employee);
    }
}
