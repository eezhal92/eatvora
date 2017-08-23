<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        return view('profile', compact('user'));
    }

    public function store(Request $request)
    {
        // @todo add test
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => [
                'required',
                Rule::unique('users')->ignore(auth()->user()->id),
            ],
        ]);

        auth()->user()->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        return redirect('/profile')->with('success', 'Berhasil memperbarui informasi Anda.');
    }
}
