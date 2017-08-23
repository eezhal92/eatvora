<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Balance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function balance($id)
    {
        // @todo add test
        $user = User::find($id);

        $balances = Balance::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.balances', compact('balances', 'user'));
    }
}
