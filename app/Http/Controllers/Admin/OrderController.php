<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        // @todo add test
        $orders = Order::orderBy('created_at', 'desc')->paginate(20);

        $order = Order::join('meals', 'meals.order_id', '=', 'orders.id')
            ->whereBetween('meals.date', app()->make(ScheduleService::class)->nextWeekDaysRange())
            ->select('orders.*')
            ->groupBy('orders.id')
            ->get();
        $totalOrder = $order->sum('amount');
        $totalVendorBill = $order->sum('vendor_bill');
        $totalRevenue = $order->sum('revenue');

        return view('admin.orders.index', compact('orders', 'totalOrder', 'totalVendorBill', 'totalRevenue'));
    }

    public function show($id)
    {
        // @todo add test
        $order = Order::with('employee.user', 'employee.office.company', 'meals.menu')->find($id);

        $groupedMeals = $order->meals()
            ->join('menus', 'menus.id', '=', 'meals.menu_id')
            ->join('vendors', 'vendors.id', '=', 'menus.vendor_id')
            ->select(
                'menus.*',
                'meals.date',
                \DB::raw('vendors.name as vendor_name'),
                \DB::raw('count(*) as qty')
            )
            ->groupBy('meals.date', 'menus.id')
            ->get()
            ->groupBy('date');

        return view('admin.orders.show', compact('order', 'groupedMeals'));
    }
}
