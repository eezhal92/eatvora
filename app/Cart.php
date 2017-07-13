<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['employee_id'];

    public static function of(Employee $employee)
    {
        return static::where('employee_id', $employee->id)->first();
    }

    public function addItem($menuId, $qty, $date)
    {
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

    public function items()
    {
        return static::join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
            ->join('menus', 'cart_items.menu_id', '=', 'menus.id')
            ->join('vendors', 'menus.vendor_id', '=', 'vendors.id')
            ->select('menus.*', 'cart_items.qty', 'cart_items.date', 'vendors.name as vendorName')
            ->get();
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'cart_items', 'cart_id', 'menu_id');
    }
}
