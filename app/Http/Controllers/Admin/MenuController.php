<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MenuController extends Controller
{
    public function edit(Request $request, $id)
    {
        try {
            $menu = Menu::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:8',
            'price' => 'required|numeric|min:15000',
        ]);

        $menu = Menu::find($id);

        $menu->update($request->only([
            'name',
            'price',
            'description',
            'contents',
        ]));

        return redirect('/ap/menus/' . $id);
    }
}
