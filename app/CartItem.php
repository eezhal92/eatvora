<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'menu_id', 'date', 'qty'];

    public static function of(Cart $cart)
    {
        return static::where('cart_id', $cart->id)->get();
    }
}
