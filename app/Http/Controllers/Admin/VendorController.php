<?php

namespace App\Http\Controllers\Admin;

use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
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
}
