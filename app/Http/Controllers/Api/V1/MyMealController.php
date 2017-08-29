<?php

namespace App\Http\Controllers\Api\V1;

use App\Menu;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;

class MyMealController extends Controller
{
    public function index(Request $request)
    {
        $for = $request->get('for', 'this_week');

        $weekDays = app()->make(ScheduleService::class)
            ->nextWeekDayDates();

        if ($for === 'next_week') {
            $weekDays = $weekDays->map->format('Y-m-d');
        } else {
            $weekDays = $weekDays->map(function ($day) {
                return $day->subWeek()->format('Y-m-d');
            });
        }

        $meals = Menu::with('vendor')->join('meals', 'menus.id', '=', 'meals.menu_id')
            ->join('orders', 'orders.id', '=', 'meals.order_id')
            ->where('orders.user_id', auth()->user()->id) // @todo <-- cover in test
            ->whereBetween('meals.date', [$weekDays->first(), $weekDays->last()])
            ->select('menus.*', \DB::raw('count(*) as qty'), 'meals.date')
            ->groupBy('menus.id', 'meals.date')
            ->orderBy('meals.date')
            ->get();

        return response()->json($meals);
    }
}
