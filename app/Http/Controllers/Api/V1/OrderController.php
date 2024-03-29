<?php

namespace App\Http\Controllers\Api\V1;

use App\Cart;
use App\Meal;
use App\Order;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Lib\BalancePaymentGateway;
use App\Http\Controllers\Controller;
use App\Exceptions\NotEnoughMealsException;
use App\Exceptions\NotEnoughBalanceException;

class OrderController extends Controller
{
    public function store(BalancePaymentGateway $balanceService)
    {
        // @todo validate user whether has employee
        $employee = Employee::with('user')->where([
            'user_id' => auth()->user()->id,
            'id' => session('employee_id'),
        ])->first();

        $cart = Cart::of($employee);

        try {
            $reservation = $cart->reserveMeals();

            $order = $reservation->complete($balanceService);

            $cart->removeItems();
        } catch (NotEnoughBalanceException $e) {
            $reservation->cancel();

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        } catch (NotEnoughMealsException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([]);
    }
}
