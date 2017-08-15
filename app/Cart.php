<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotEnoughMealsException;

class Cart extends Model
{
    protected $fillable = ['employee_id'];

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

    public static function of(Employee $employee)
    {
        $cart = static::where('employee_id', $employee->id)->first();

        if (is_null($cart)) {
            return static::create(['employee_id' => $employee->id]);
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

    public function addItem($menuId, $qty, $date)
    {
        if ($menuId instanceof Menu) {
            $menuId = $menuId->id;
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $foundItem = $this->cartItems()
            ->where('menu_id', $menuId)
            ->where('date', $date->format('Y-m-d'))
            ->first();

        if (!$foundItem) {
            $this->cartItems()->create([
                'cart_id' => $this->id,
                'menu_id' => $menuId,
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
            ->select('menus.*', \DB::raw('cart_items.id as cart_item_id'), 'cart_items.qty', 'cart_items.date', 'vendors.name as vendorName')
            ->get();
        // return \DB::table('carts')->join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
        //     ->join('menus', 'cart_items.menu_id', '=', 'menus.id')
        //     ->join('vendors', 'menus.vendor_id', '=', 'vendors.id')
        //     ->select('menus.*', \DB::raw('cart_items.id as cart_item_id'), 'cart_items.qty', 'cart_items.date', 'vendors.name as vendorName')
        //     ->get();
    }

    private function findMeals()
    {
        return $this->items()->map(function ($item) {
            $meals = Meal::where('menu_id', $item->id)
                ->where('date', Carbon::parse($item->date))
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

        return new Reservation($meals, $this->employee);
    }
}
