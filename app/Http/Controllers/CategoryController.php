<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategoryController extends Controller
{
    public function index(Request $request) {
        // get category with pagination
        $categories = DB::table('categories')
        ->when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%'.$name.'%');
        })
        ->paginate(10);
        return view('pages.category.index', compact('categories'));
    }

    public function create() {
        return view('pages.category.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:100'
        ]);

        $category = Category::create($validated);
        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }

    public function show() {

    }

    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('pages.category.edit', compact('category'));
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|max:100',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully');
    }
}
