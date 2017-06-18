<?php

namespace App\Http\Controllers\Api\V1;

use App\Cart;
use App\CartItem;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $menuId = $request->get('menuId');
        $date = $request->get('date');
        $qty = $request->get('qty');
        $employeeId = $request->get('employeeId');

        // @todo: should check whether current user has matching employee id
        // Auth::user()->employees()->where('id', $employeeId)->first()

        $cart = Cart::create([
            'employee_id' => $employeeId,
        ]);

        $cart->addItem($menuId, $qty, Carbon::parse($date));

        $items = $cart->items();

        return response()->json($items);
    }
}
