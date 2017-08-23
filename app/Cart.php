<?php

namespace App;

use Carbon\Carbon;
use App\Services\ScheduleService;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotEnoughMealsException;
use App\Exceptions\CartItemNotFoundException;
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
        if (!$menu) {
            throw new CartItemNotFoundException("Cannot find mel item");
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $schedule = app()->make(ScheduleService::class);

        $mealCount = $menu->meals()->whereBetween('date', $schedule->nextWeekDaysRange())->count();

        if (!$mealCount) {
            throw new IncorrectMealDateException("{$menu->name} is not scheduled for next week.");
        }

        $foundItem = $this->findItem($menu->id, $date);

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
        $item = $this->findItem($menuId, $date);

        if (!$item) {
            throw new CartItemNotFoundException("Menu item with id {$menuId} and date {$date} is not found");
        }

        $item->qty = $qty;
        $item->save();
    }

    public function removeItem($menuId, $date)
    {
        $item = $this->findItem($menuId, $date);

        if (!$item) {
            throw new CartItemNotFoundException("Menu item with id {$menuId} and date {$date} is not found");
        }

        $item->delete();
    }

    public function items()
    {
        $itemsDate = CartItem::where('cart_id', $this->id)
            ->select('date')
            ->get()
            ->pluck('date')
            ->unique()
            ->toArray();

        return Menu::join('cart_items', 'cart_items.menu_id', '=', 'menus.id')
            ->join('meals', 'meals.menu_id', '=', 'menus.id')
            ->join('vendors', 'vendors.id', '=', 'menus.vendor_id')
            ->join('carts', 'carts.id', '=', 'cart_items.cart_id')
            ->where('carts.start_date', $this->start_date)
            ->where('carts.end_date', $this->end_date)
            ->whereIn('meals.date', $itemsDate)
            ->select(
                'menus.id',
                'menus.name',
                'meals.price',
                'cart_items.qty',
                'vendors.name as vendor_name',
                'meals.menu_id',
                'meals.date'
            )
            ->groupBy(
                'cart_items.qty',
                'meals.price',
                'meals.menu_id',
                'meals.date'
            )
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
                throw new NotEnoughMealsException("Maaf, Jumlah item {$item->name} yang tersedia tinggal {$meals->count()} pcs.");
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
