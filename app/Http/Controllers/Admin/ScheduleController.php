<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index(Request $request)
    {
        // @todo add test
        $dateRange = $this->scheduleService->nextWeekDaysRange();

        if ($request->get('date_from') && $request->get('date_to')) {
            $dateRange = [$request->get('date_from'), $request->get('date_to')];
        }

        $meals = Menu::with('vendor')->join('meals', 'meals.menu_id', '=', 'menus.id')
            ->whereBetween('meals.date', $dateRange)
            ->select('menus.*', \DB::raw('meals.price as meal_price'), 'meals.date', \DB::raw('count(*) as qty'))
            ->groupBy('menus.id', 'meals.date', 'meal_price')
            ->get();
        $mealCount = $meals->count();
        $mealGroups = $meals->groupBy('date');

        $range = array_map(function ($date) {
            return Carbon::parse($date);
        }, $dateRange);

        $nextLink = url(sprintf('/ap/schedules?date_from=%s&date_to=%s', $range[0]->copy()->addWeek()->format('Y-m-d'), $range[1]->copy()->addWeek()->format('Y-m-d')));
        $prevLink = url(sprintf('/ap/schedules?date_from=%s&date_to=%s', $range[0]->copy()->subWeek()->format('Y-m-d'), $range[1]->copy()->subWeek()->format('Y-m-d')));

        return view('admin.schedules.index', compact('mealCount', 'mealGroups', 'dateRange', 'nextLink', 'prevLink'));
    }

    public function create()
    {
        $vendors = Vendor::all();

        return view('admin.schedules.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'menu_id' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
            'qty' => 'required|numeric',
        ]);

        $nextMonday = $this->scheduleService->nextWeekDayDates()->first();

        if (Carbon::parse($request->get('date'))->lt($nextMonday)) {
            return redirect('/ap/schedules')->with('error', 'Cannot create meal schedule for less than nextMonday');
        }

        try {
            $menu = Menu::findOrFail($request->get('menu_id'));
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        $meals = $menu->scheduleMeals($request->get('date'), $request->get('qty'));

        return redirect('/ap/schedules');
    }
}
