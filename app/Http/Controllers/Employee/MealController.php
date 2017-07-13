<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Menu;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index(Request $request)
    {

        $nextWeekDayDates = app()->make(ScheduleService::class)
            ->nextWeekDayDates()
            ->map(function ($day) {
                return $day->format('Y-m-d');
            });

        return view('employee.meals.index', compact('nextWeekDayDates'));
    }


    public function show($date, $menuId)
    {
        try {
            $menu = Menu::with('vendor')
                ->join('schedules', 'schedules.menu_id', '=', 'menus.id')
                ->where('schedules.date', Carbon::parse($date))
                ->where('menus.id', $menuId)
                ->firstOrFail();
        } catch (\Exception $e) {
            return 'Not found!';
        }

        return view('employee.meals.show', [
            'menu' => $menu,
        ]);
    }
}
