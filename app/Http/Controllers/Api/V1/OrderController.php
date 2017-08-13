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

        $meals = $cart->meals();

        $amount = $meals->reduce(function ($total, $item) {
            return $total + $item->menu->price;
        }, 0);

        $order = Order::forMeals($meals, $employee, $amount);

        return response()->json([]);
    }
}
