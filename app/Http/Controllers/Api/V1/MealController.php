<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;


class MealController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date');
        $limit = $request->get('limit', 6);

        $menus = Menu::with('vendor')->join('schedules', 'schedules.menu_id', '=', 'menus.id')
            ->where('schedules.date', Carbon::parse($date))
            ->paginate($limit);

        $result = $this->decoratePaginatedResponse($menus);

        return response()->json($result);
    }

    public function myMeals(Request $request)
    {
        // @todo: add test
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

        $user = auth()->user();

        $meals = Menu::with('vendor')->join('meals', 'menus.id', '=', 'meals.menu_id')
            ->join('orders', 'orders.id', '=', 'meals.order_id')
            ->whereBetween('meals.date', [$weekDays->first(), $weekDays->last()])
            ->select('menus.*', \DB::raw('count(*) as qty'), \DB::raw("DATE_FORMAT(meals.date, '%Y-%m-%d') as date"))
            ->groupBy('menus.id', 'meals.date')
            ->get();

        return response()->json($meals);
    }
}
