<?php

namespace App\Http\Controllers\Api\V1;

use App\Cart;
use App\Meal;
use App\Order;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function store()
    {
        // @todo validate user whether has employee
        $employee = Employee::with('user')->find(request('employee_id'));

        $cart = Cart::of($employee);

        $order = Order::create([
            'employee_id' => $employee->id,
            'user_id' => $employee->user->id,
        ]);

        $cart->items()->each(function ($menu) use ($order) {
            $meals = Meal::where('menu_id', $menu->id)->take($menu->qty)->get();

            $meals->each->update([
                'reserved_at' => Carbon::now(),
                'order_id' => $order->id,
            ]);
        });

        return response()->json([]);
    }
}
