<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ActivityLog;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string'
        ]);

        $product = Product::create($validated);
        ActivityLog::log('CREATE_PRODUCT', 'Menambahkan produk ' . $product->name);
        
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('products_edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string'
        ]);

        $product->update($validated);
        ActivityLog::log('UPDATE_PRODUCT', 'Mengubah produk ' . $product->name);
        
        return redirect('/products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();
        ActivityLog::log('DELETE_PRODUCT', 'Menghapus produk ' . $name);
        
        return redirect('/products')->with('success', 'Produk berhasil dihapus!');
    }
}
