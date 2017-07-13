<?php

namespace App\Http\Controllers\Api\V1;

use App\Cart;
use App\CartItem;
use App\Employee;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store()
    {
        $employee = Employee::where('user_id', Auth::user()->id)
            ->where('company_id', session('company_id'))
            ->first();

        // todo: make test and refactor
        $cart = Cart::of($employee);

        if (!$cart) {
            $cart = Cart::create([
                'employee_id' => $employee->id,
            ]);
        }

        $cart->addItem(request('menuId'), request('qty'), Carbon::parse(request('date')));

        $items = $cart->items();

        return response()->json($items)
            ->cookie('cart_id', $cart->id);
    }

    public function index()
    {
        $employee = Employee::where('user_id', Auth::user()->id)
            ->where('company_id', session('company_id'))
            ->first();

        $cart = Cart::of($employee);

        if (!$cart) {
            return response()->json([]);
        }

        $items = $cart->items()->sortByDesc('date')->groupBy('date');

        return response()->json($items);
    }
}
