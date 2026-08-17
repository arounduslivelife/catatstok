<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $totalProducts = Product::count();
        $trxIn = Transaction::where('type', 'IN')->count();
        $trxOut = Transaction::where('type', 'OUT')->count();
        
        $recentActivities = ActivityLog::with('user')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get()
                            ->map(function($log) {
                                return [
                                    'id' => $log->id,
                                    'username' => optional($log->user)->username ?? 'User',
                                    'description' => $log->description,
                                    'time_ago' => $log->created_at->diffForHumans()
                                ];
                            });

        return response()->json([
            'workspace_name' => optional($user->workspace)->name ?? 'Workspace',
            'total_products' => $totalProducts,
            'trx_in' => $trxIn,
            'trx_out' => $trxOut,
            'recent_activities' => $recentActivities
        ]);
    }
}
