<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
        ]);

        Category::create([
            'name' => $request->get('name'),
            'slug' => str_slug($request->get('name')),
        ]);

        return redirect('/ap/categories');
    }

    public function edit($id)
    {
        $category = Category::find($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
        ]);

        $category = Category::find($id);

        $category->update([
            'name' => request('name'),
            'slug' => str_slug(request('name')),
        ]);

        return redirect('/ap/categories');
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        $category->delete();

        return response()->json([]);
    }
}
