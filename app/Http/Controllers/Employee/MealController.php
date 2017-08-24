<?php

namespace App\Http\Controllers\Employee;

use App\Meal;
use App\Menu;
use App\Category;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function show($id)
    {
        try {
            $menu = Menu::with('vendor')->findOrFail($id);

            $nextWeekDays = app()->make(ScheduleService::class)
                ->nextWeekDayDates()
                ->map(function ($day) {
                    return $day->format('Y-m-d');
                });

            $mealCount = Meal::where('menu_id', $menu->id)
                ->whereBetween('date', [$nextWeekDays->first(), $nextWeekDays->last()])
                ->count();

            $renderAddToCartButton = !!$mealCount;
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        // return view('employee.meals.show', compact('menu', 'renderAddToCartButton'));
        return view('employee.meals.detail', compact('menu', 'renderAddToCartButton'));
    }
}
