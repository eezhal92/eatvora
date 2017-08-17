<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\Cart;
use App\Menu;
use App\CartItem;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\IncorrectMealDateException;

class CartController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', Auth::user()->id)
            ->where('id', session('employee_id'))
            ->first();

        $cart = Cart::of($employee);

        if (!$cart) {
            return response()->json([]);
        }

        $items = $cart->items()->sortByDesc('date')->groupBy('date');

        return response()->json([
            'already_placed_order' => $cart->already_placed_order,
            'items' => $items,
        ]);
    }

    public function store()
    {
        //@ todo add validation when trying menu / meal that are not scheduled
        $employee = Employee::where('user_id', Auth::user()->id)
            ->where('id', session('employee_id'))
            ->first();

        $cart = Cart::of($employee);

        if ($cart->already_placed_order) {
            return response()->json(['message' => 'Cannot add item to the cart. Order for this cart has been placed.'], 422);
        }

        try {
            $cart->addItem(Menu::find(request('menuId')), request('qty'), Carbon::parse(request('date')));
        } catch (IncorrectMealDateException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($cart->items());
    }

    public function update()
    {
        $employee = Employee::where('user_id', Auth::user()->id)
            ->where('id', session('employee_id'))
            ->first();

        $cart = Cart::of($employee);

        $cart->updateItem(request('menu_id'), request('qty'), Carbon::parse(request('date')));

        return response()->json($cart->items());
    }

    public function remove()
    {
        $employee = Employee::where('user_id', Auth::user()->id)
            ->where('id', session('employee_id'))
            ->first();

        $cart = Cart::of($employee);

        $cart->removeItem(request('menu_id'), Carbon::parse(request('date')));

        return response()->json($cart->items());
    }
}
