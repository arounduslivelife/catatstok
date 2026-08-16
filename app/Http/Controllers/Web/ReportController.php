<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class ReportController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::with(['transactionItems.transaction.creator'])->get();
        $transactions = \App\Models\Transaction::with(['items.product', 'creator'])->latest()->get();
        
        return view('reports', compact('products', 'transactions'));
    }
}
