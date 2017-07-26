<?php

namespace App\Http\Controllers\Admin;

use App\Vendor;
use Illuminate\Http\Request;
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
}
