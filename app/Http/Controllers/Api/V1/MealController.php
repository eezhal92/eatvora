<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\Http\Controllers\Controller;
use App\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
