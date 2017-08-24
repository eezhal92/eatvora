<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\Meal;
use App\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MealController extends Controller
{
    private function decoratePaginatedResponse(LengthAwarePaginator $result)
    {
        $result = $result->toArray();

        $query = array_merge([
            'page' => $result['current_page'],
            'last_page' => $result['last_page'],
            'limit' => $result['per_page'],
        ], request()->all());

        return [
            'query' => $query,
            'items' => $result['data'],
            'total' => $result['total'],
        ];
    }

    public function index(Request $request)
    {
        $menus = Meal::join('menus', 'menus.id', '=', 'meals.menu_id')
            ->join('vendors', 'vendors.id', '=', 'menus.vendor_id')
            ->where('meals.date', Carbon::parse(request('date'))->format('Y-m-d'))
            ->groupBy('menus.id', 'meals.price')
            // select menu_id so Meal accessor can access menu_id field
            ->select('menus.*', 'meals.price', 'meals.menu_id', \DB::raw('vendors.name as vendor_name'))
            ->paginate($request->get('limit', 6));

        return response()->json($this->decoratePaginatedResponse($menus));
    }

    public function myMeals(Request $request)
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

        $user = auth()->user();

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
