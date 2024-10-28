<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::paginate(5);
        return view('pages.product.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        return view('pages.product.create', compact('categories'));
    }

    public function store(Request $request) {
        $filename = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/products', $filename);
        $data = $request->all();

        $product = new \App\Models\Product;
        $product->name = $request->name;
        $product->price = (int) $request->price;
        $product->quantity = (int) $request->quantity;
        $product->category_id = $request->category_id;
        $product->image = $filename;
        $product->save();
        return redirect()->route('product.index');
    }

    public function edit($id) {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('pages.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        if ($request->image) {
            $filename = time() .'.'. $request->image->extension();
            $request->image->storeAs('public/products', $filename);
            $product->image = $filename;
        }
        $product->update($request->all());

        return redirect()->route('product.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully');
    }
}
