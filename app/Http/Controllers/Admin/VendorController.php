<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::paginate($request->get('limit', 10));

        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'capacity' => 'required|numeric|min:10',
            'phone' => 'required|min:6',
        ]);

        $vendor = Vendor::create([
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'capacity' => $request->get('capacity'),
        ]);

        return redirect("/ap/vendors/{$vendor->id}");
    }

    public function show($id)
    {
        try {
            $vendor = Vendor::with('menus')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit($id)
    {
        $vendor = Vendor::find($id);

        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'phone' => 'required',
            'capacity' => 'required|numeric|min:10',
            'email' => 'sometimes|required|email',
        ]);

        $vendor = Vendor::find($id);

        $vendor->update($request->only([
            'name',
            'capacity',
            'phone',
            'email',
            'address',
        ]));

        return redirect("/ap/vendors/{$vendor->id}");
    }

    public function order(Request $request, $vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $range = app()->make(ScheduleService::class)->currentWeekDaysRange();

        if ($request->get('date_from') && $request->get('date_to')) {
            $range = [$request->get('date_from'), $request->get('date_to')];
        }

        $meals = Menu::with('vendor')
            ->join('vendors', 'vendors.id', '=', 'menus.vendor_id')
            ->join('meals', 'menus.id', '=', 'meals.menu_id')
            ->join('orders', 'orders.id', '=', 'meals.order_id')
            ->where('vendors.id', $vendorId)
            ->whereBetween('meals.date', $range)
            ->select('menus.*', \DB::raw('meals.price as price_when_ordered'), \DB::raw('count(*) as qty'), 'meals.date')
            ->groupBy('menus.id', 'meals.date', 'meals.price')
            ->get();

        $groupedMeals = $meals->groupBy('date');
        $mealsCount = $meals->count();

        $carbonRange = array_map(function ($date) {
            return Carbon::parse($date);
        }, $range);

        $nextLink = url(sprintf('/ap/vendors/%s/orders?date_from=%s&date_to=%s', $vendorId, $carbonRange[0]->copy()->addWeek()->format('Y-m-d'), $carbonRange[1]->copy()->addWeek()->format('Y-m-d')));
        $prevLink = url(sprintf('/ap/vendors/%s/orders?date_from=%s&date_to=%s', $vendorId, $carbonRange[0]->copy()->subWeek()->format('Y-m-d'), $carbonRange[1]->copy()->subWeek()->format('Y-m-d')));

        return view('admin.vendors.orders', compact('groupedMeals', 'mealsCount', 'range', 'vendor', 'nextLink', 'prevLink'));
    }
}
