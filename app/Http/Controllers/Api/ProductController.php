<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with(['transactionItems.transaction.creator'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string',
        ]);

        $product = Product::create($validated);
        ActivityLog::log('CREATE_PRODUCT', 'Created product ' . $product->name);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'sometimes|string',
            'name' => 'sometimes|string',
            'unit' => 'sometimes|string',
        ]);

        $product->update($validated);
        ActivityLog::log('UPDATE_PRODUCT', 'Updated product ' . $product->name);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();
        ActivityLog::log('DELETE_PRODUCT', 'Deleted product ' . $name);
        return response()->json(null, 204);
    }
}
