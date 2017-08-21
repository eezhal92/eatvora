<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        // @todo add test
        $orders = Order::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // @todo add test
        $order = Order::with('employee.user', 'employee.office.company', 'meals.menu')->find($id);

        $groupedMeals = $order->meals()
            ->join('menus', 'menus.id', '=', 'meals.menu_id')
            ->select('menus.*', 'meals.date', \DB::raw('count(*) as qty'))
            ->groupBy('meals.date', 'menus.id')
            ->get()
            ->groupBy('date');

        return view('admin.orders.show', compact('order', 'groupedMeals'));
    }
}
