<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use App\Vendor;
use App\Category;
use App\Lib\NullFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('vendor')->paginate(20);

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $vendors = Vendor::all()->pluck('name', 'id');

        return view('admin.menus.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:8',
            'vendor' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:15000',
        ]);

        $menu = Menu::create([
            'vendor_id' => $request->get('vendor'),
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'description' => $request->get('description'),
            'contents' => $request->get('contents'),
            'image_path' => $request->file('image', new NullFile())->store('images/menus', 'public'),
        ]);


        return redirect('/ap/menus/' . $menu->id)
            ->with('success', sprintf('Menu %s successfully been created.', $menu->name));
    }

    public function show($id)
    {
        try {
            $menu = Menu::with('vendor')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect('/ap/menus')->with('error', 'Menu was not found.');
        }


        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Request $request, $id)
    {
        try {
            $menu = Menu::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        $vendors = Vendor::all()->pluck('name', 'id');
        $categories = Category::all()->pluck('name', 'id');

        return view('admin.menus.edit', compact('menu', 'vendors', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:8',
            'price' => 'required|numeric|min:15000',
            'vendor' => 'required|numeric|min:1',
        ]);

        $menu = Menu::find($id);

        $data = [
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'description' => $request->get('description'),
            'contents' => $request->get('contents'),
            'vendor_id' => $request->get('vendor'),
        ];

        $path = $request->file('image', new NullFile())->store('images/menus', 'public');

        if ($path) {
            $data['image_path'] = $path;
        }

        if ($categories = $request->get('categories')) {
            $menu->categories()->sync($categories);
        } else {
            $menu->categories()->detach();
        }

        $menu->update($data);

        return redirect('/ap/menus/' . $id);
    }
}
