<?php

namespace App\Http\Controllers;

use App\Balance;
use Illuminate\Http\Request;
use App\Services\ScheduleService;

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
            ->take(5)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('balances'));
    }
}
