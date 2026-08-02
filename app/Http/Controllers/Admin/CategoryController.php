<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);

        return view('admin.categories.index', compact('categories'));
    }


    public function create()
    {
        return view('admin.categories.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        Category::create([
            'name' => $request->name,
        ]);


        return redirect()
            ->route('admin.categories')
            ->with('success', 'دسته بندی ایجاد شد');
    }


    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }


    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        $category->update([
            'name' => $request->name,
        ]);


        return redirect()
            ->route('admin.categories')
            ->with('success', 'دسته بندی ویرایش شد');
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return back()
            ->with('success', 'دسته بندی حذف شد');
    }
}
