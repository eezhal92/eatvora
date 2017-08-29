<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('change-password');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->get('old_password'), auth()->user()->password)) {
            return redirect()->back()->with('error', 'Password lama tidak benar.');
        }

        $request->user()->fill([
            'password' => Hash::make($request->get('new_password'))
        ])->save();

        return redirect('/change-password')->with('success', 'Password Anda berhasil diperbarui');
    }
}
