<?php

namespace App\Http\Controllers\Employee;

use App\Balance;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $balances = Balance::where('user_id', auth()->user()->id)
            ->take(10)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('balances'));
    }
}
