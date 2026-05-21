<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($data);
        return back()->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        $products = Product::latest()->get();
        return view('admin.products', compact('products', 'product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'  => 'required|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($data);
        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }
}
