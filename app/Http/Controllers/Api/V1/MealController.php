<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\Meal;
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
        $q = Meal::join('menus', 'menus.id', '=', 'meals.menu_id')
            ->join('vendors', 'vendors.id', '=', 'menus.vendor_id')
            ->where('meals.date', Carbon::parse(request('date'))->format('Y-m-d'))
            ->groupBy('menus.id', 'meals.price')
            // select menu_id so Meal accessor can access menu_id field
            ->select('menus.*', 'meals.price', 'meals.menu_id', \DB::raw('vendors.name as vendor_name'));

        if ($request->get('category') && !in_array('all', $request->get('category'))) {
            // @todo add test
            $q->join('category_menu', 'category_menu.menu_id', '=', 'menus.id')
                ->whereIn('category_menu.category_id', $request->get('category'));
        }

        $meals = $q->paginate($request->get('limit', 6));
        $meals->each(function ($meal) {
            $meal->setHidden(['final_price', 'price']);
            $meal->menu->setHidden(['final_price', 'price']);
        });

        return response()->json($this->decoratePaginatedResponse($meals));
    }
}
