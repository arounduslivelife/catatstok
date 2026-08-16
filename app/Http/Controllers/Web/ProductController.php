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
}
