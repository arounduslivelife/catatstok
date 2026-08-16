<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workspace;

class SuperAdminController extends Controller
{
    public function index()
    {
        $workspaces = Workspace::with('creator')
            ->withCount('products')
            ->latest()
            ->get();
            
        return view('superadmin.dashboard', ['workspaces' => $workspaces, 'hideNav' => true]);
    }

    public function toggleStatus(Workspace $workspace)
    {
        $workspace->status = !$workspace->status;
        $workspace->save();
        
        $msg = $workspace->status ? 'Workspace diaktifkan.' : 'Workspace dinonaktifkan (Suspend).';
        return back()->with('success', $msg);
    }

    public function setSubscription(Request $request, Workspace $workspace)
    {
        $request->validate([
            'end_date' => 'required|date'
        ]);

        $workspace->subscription_ends_at = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        $workspace->status = true; // Auto activate
        $workspace->is_trial = false; // Jika di set tanggal resmi, bukan trial lagi
        $workspace->save();

        return back()->with('success', 'Masa aktif langganan diatur hingga ' . $workspace->subscription_ends_at->format('d M Y') . '.');
    }

    public function startTrial(Workspace $workspace)
    {
        $workspace->is_trial = true;
        // Masa aktif tidak ditambah +14 hari otomatis, 100% diatur oleh Set Tanggal
        $workspace->save();

        return back()->with('success', 'Status diubah menjadi Trial untuk ' . $workspace->name . '. Masa aktif mengikuti tanggal yang di-set.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6'
        ]);

        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return back()->with('success', 'Password Superadmin berhasil diubah!');
    }
}
